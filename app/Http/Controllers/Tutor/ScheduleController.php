<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\EducationLevel;
use App\Models\Program;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\TimeSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ScheduleController extends Controller
{
    /**
     * Dashboard & Daftar Jadwal Pengajar.
     */
    public function index(Request $request)
    {
        $tutorId = Auth::id();

        // Semua jadwal mengajar milik tutor yang login
        $schedules = Schedule::with(['subject', 'room', 'timeSlot', 'educationLevel', 'program', 'activeBookings.student'])
            ->where('tutor_id', $tutorId)
            ->orderBy('date', 'desc')
            ->orderBy('time_slot_id', 'asc')
            ->get();

        // Pisahkan agenda tutor: Reguler vs Privat/Konsultasi
        $regularSchedules = $schedules->filter(function ($s) {
            return $s->program && $s->program->code === 'reguler';
        });

        $specialSchedules = $schedules->filter(function ($s) {
            return $s->program && in_array($s->program->code, ['private_bimbel', 'private_home', 'consult']);
        });

        // Tanggal yang diperiksa (default: hari ini)
        $checkDate = $request->get('check_date', Carbon::today()->toDateString());

        // Semua sesi aktif
        $allTimeSlots = TimeSlot::where('is_active', true)->orderBy('session_number')->get();

        // 1. GRID 1: RUANGAN FISIK BIMBEL (R1 - R8) KHUSUS KELAS REGULER / BIMBEL
        $physicalRooms = Room::where('is_active', true)
            ->where('is_physical', true)
            ->orderBy('code')
            ->get();

        $dayPhysicalSchedules = Schedule::with(['tutor', 'subject', 'room', 'timeSlot', 'program'])
            ->where('date', $checkDate)
            ->where('status', '!=', 'cancelled')
            ->whereHas('room', function ($q) {
                $q->where('is_physical', true);
            })
            ->get();

        // Bangun lookup ruangan fisik: [time_slot_id][room_id] => Schedule|null
        $physicalGrid = [];
        $mineSlots = 0;
        $otherSlots = 0;
        foreach ($allTimeSlots as $ts) {
            foreach ($physicalRooms as $rm) {
                $physicalGrid[$ts->id][$rm->id] = null;
            }
        }
        foreach ($dayPhysicalSchedules as $ds) {
            if (array_key_exists($ds->room_id, $physicalGrid[$ds->time_slot_id] ?? [])) {
                $physicalGrid[$ds->time_slot_id][$ds->room_id] = $ds;
                if ((int) $ds->tutor_id === (int) $tutorId) {
                    $mineSlots++;
                } else {
                    $otherSlots++;
                }
            }
        }

        $totalPhysicalSlots = count($allTimeSlots) * count($physicalRooms);
        $emptySlots = max(0, $totalPhysicalSlots - ($mineSlots + $otherSlots));

        $stats = [
            'total' => $totalPhysicalSlots,
            'empty' => $emptySlots,
            'mine' => $mineSlots,
            'others' => $otherSlots,
        ];

        // 5 Hari horizontal selector di sekitar $checkDate
        $parsedCheckDate = Carbon::parse($checkDate);
        $dateList = [];
        for ($i = -2; $i <= 2; $i++) {
            $dt = $parsedCheckDate->copy()->addDays($i);
            $dateList[] = [
                'date' => $dt->toDateString(),
                'day_short' => strtoupper($dt->isoFormat('ddd')),
                'day_num' => $dt->format('d'),
                'is_selected' => $dt->toDateString() === $checkDate,
            ];
        }

        // 2. GRID 2: JADWAL PRIVAT & KONSULTASI (REQUEST / DITENTUKAN OLEH MURID)
        // Menampilkan sesi privat (bimbel/rumah) dan konsultasi pada tanggal tersebut
        $dayPrivateConsultSchedules = Schedule::with(['tutor', 'subject', 'room', 'timeSlot', 'program', 'activeBookings.student', 'educationLevel'])
            ->where('date', $checkDate)
            ->where('status', '!=', 'cancelled')
            ->whereHas('program', function ($q) {
                $q->whereIn('code', ['private_bimbel', 'private_home', 'consult']);
            })
            ->orderBy('time_slot_id')
            ->get();

        return view('tutor.schedules.index', compact(
            'schedules',
            'regularSchedules',
            'specialSchedules',
            'checkDate',
            'allTimeSlots',
            'physicalRooms',
            'physicalGrid',
            'dayPrivateConsultSchedules',
            'stats',
            'dateList'
        ));
    }

    /**
     * Form Buka Slot Mengajar Baru.
     */
    public function create(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Ambil hanya mapel yang diajar oleh tutor yang sedang login
        $subjects = $user->taughtSubjects()->get();
        if ($subjects->isEmpty()) {
            $subjects = Subject::all(); // fallback jika seeder belum dimapping
        }

        // Jika hanya 1 mapel -> auto-select (tidak perlu dropdown)
        // Jika 2+ mapel -> tampilkan pilihan dropdown
        $autoSubject = $subjects->count() === 1 ? $subjects->first() : null;

        $educationLevels = EducationLevel::all();
        $programs = Program::all();
        $timeSlots = TimeSlot::where('is_active', true)->orderBy('session_number')->get();
        $rooms = Room::where('is_active', true)->get();

        $prefillDate = $request->get('date', date('Y-m-d'));
        $prefillTimeSlotId = $request->get('time_slot_id');
        $prefillRoomId = $request->get('room_id');

        return view('tutor.schedules.create', compact(
            'subjects',
            'autoSubject',
            'educationLevels',
            'programs',
            'timeSlots',
            'rooms',
            'prefillDate',
            'prefillTimeSlotId',
            'prefillRoomId'
        ));
    }

    /**
     * Simpan Slot Jadwal Baru dengan Validasi Anti-Bentrok Ganda.
     */
    public function store(Request $request)
    {
        $tutorId = Auth::id();

        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time_slot_id' => ['required', 'exists:time_slots,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'education_level_id' => ['required', 'exists:education_levels,id'],
            'program_id' => ['required', 'exists:programs,id'],
            'room_id' => ['required', 'exists:rooms,id'],
            'quota_max' => ['nullable', 'integer', 'min:1'],
        ]);

        $date = $validated['date'];
        $timeSlotId = $validated['time_slot_id'];
        $roomId = $validated['room_id'];
        $program = Program::findOrFail($validated['program_id']);
        $room = Room::findOrFail($roomId);

        // 1. VALIDASI ANTI-BENTROK TUTOR:
        $tutorCollision = Schedule::where('tutor_id', $tutorId)
            ->where('date', $date)
            ->where('time_slot_id', $timeSlotId)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($tutorCollision) {
            return back()->withInput()->with('error', 'Bentrok! Anda sudah memiliki jadwal mengajar lain pada tanggal dan sesi waktu yang sama.');
        }

        // 2. VALIDASI ANTI-BENTROK RUANGAN FISIK:
        if ($room->is_physical) {
            $roomCollision = Schedule::where('room_id', $roomId)
                ->where('date', $date)
                ->where('time_slot_id', $timeSlotId)
                ->where('status', '!=', 'cancelled')
                ->exists();

            if ($roomCollision) {
                return back()->withInput()->with('error', "Bentrok! Ruangan '{$room->name}' sudah digunakan oleh pengajar lain pada sesi dan tanggal tersebut.");
            }
        }

        // 3. PENENTUAN KUOTA KURSI:
        if ($program->is_private) {
            $quotaMax = 1; // Program privat otomatis mengunci kuota 1
        } else {
            $quotaMax = ! empty($validated['quota_max'])
                ? min((int) $validated['quota_max'], $room->capacity)
                : $room->capacity;
        }

        // Simpan Slot Jadwal
        Schedule::create([
            'tutor_id' => $tutorId,
            'subject_id' => $validated['subject_id'],
            'education_level_id' => $validated['education_level_id'],
            'program_id' => $program->id,
            'room_id' => $room->id,
            'time_slot_id' => $timeSlotId,
            'date' => $date,
            'quota_max' => $quotaMax,
            'booked_count' => 0,
            'status' => 'scheduled',
        ]);

        return redirect()->route('tutor.dashboard')->with('success', 'Slot jadwal berhasil dibuka!');
    }

    /**
     * Tandai Sesi Selesai dan Simpan Catatan Materi.
     */
    public function complete(Request $request, Schedule $schedule)
    {
        if ($schedule->tutor_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $schedule->update([
            'status' => 'completed',
            'notes' => $request->input('notes'),
        ]);

        return back()->with('success', 'Sesi berhasil ditandai selesai beserta catatan materi!');
    }

    /**
     * Direct Assignment: Pengajar memasukkan murid langsung ke slot jadwalnya.
     */
    public function assignStudent(Request $request, Schedule $schedule)
    {
        if ($schedule->tutor_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'student_id' => ['required', 'exists:users,id'],
        ]);

        $studentId = $request->student_id;
        $student = User::findOrFail($studentId);

        if (! $student->hasRole('student')) {
            return back()->with('error', 'Pengguna yang dipilih bukan seorang siswa.');
        }

        if ($schedule->isFull()) {
            return back()->with('error', 'Kapasitas kursi pada slot ini sudah penuh!');
        }

        // Cek apakah siswa sudah terdaftar di slot ini
        $alreadyBooked = $schedule->bookings()
            ->where('student_id', $studentId)
            ->where('status', 'confirmed')
            ->exists();

        if ($alreadyBooked) {
            return back()->with('error', 'Siswa ini sudah terdaftar di slot jadwal tersebut.');
        }

        // Buat Tiket Booking
        Booking::create([
            'booking_code' => 'BKG-'.strtoupper(Str::random(6)),
            'schedule_id' => $schedule->id,
            'student_id' => $studentId,
            'booked_by_user_id' => Auth::id(),
            'booking_type' => 'tutor_assigned',
            'status' => 'confirmed',
        ]);

        $schedule->increment('booked_count');

        return back()->with('success', "Siswa '{$student->name}' berhasil ditambahkan ke jadwal!");
    }

    /**
     * Tutor membatalkan slot jadwal miliknya sendiri.
     * HANYA bisa dilakukan jika belum ada siswa yang booking slot tersebut.
     */
    public function cancelSlot(Schedule $schedule)
    {
        if ($schedule->tutor_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        if ($schedule->status !== 'scheduled') {
            return back()->with('error', 'Slot ini tidak dapat dibatalkan karena statusnya bukan "Terjadwal".');
        }

        // Cek apakah ada booking aktif dari siswa manapun
        $hasActiveBookings = $schedule->bookings()
            ->where('status', 'confirmed')
            ->exists();

        if ($hasActiveBookings) {
            return back()->with('error', 'Tidak dapat membatalkan slot! Ada siswa yang sudah memesan kursi pada jadwal ini. Hubungi Admin untuk pembatalan paksa.');
        }

        $schedule->update(['status' => 'cancelled']);

        return back()->with('success', 'Slot jadwal berhasil dibatalkan.');
    }
}
