<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Program;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\TimeSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Katalog Jadwal: Siswa melihat slot jadwal aktif yang masih tersedia.
     */
    public function index(Request $request)
    {
        /** @var User $student */
        $student = Auth::user();

        $query = Schedule::with(['subject', 'room', 'timeSlot', 'tutor', 'program'])
            ->where('status', 'scheduled')
            ->whereColumn('booked_count', '<', 'quota_max');

        if ($request->filled('date')) {
            $filterDate = Carbon::parse($request->date);
            $query->whereDate('date', $filterDate);
        } else {
            $filterDate = null;
            $query->where('date', '>=', Carbon::today())
                ->where('date', '<=', Carbon::today()->addDays(13));
        }

        $schedules = $query->orderBy('date', 'asc')->orderBy('time_slot_id', 'asc')->get();

        // Hitung total booking aktif siswa dalam minggu berjalan (Senin - Minggu)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $weeklyBookingCount = Booking::where('student_id', $student->id)
            ->where('status', 'confirmed')
            ->whereHas('schedule', function ($q) use ($startOfWeek, $endOfWeek) {
                $q->whereBetween('date', [$startOfWeek, $endOfWeek])
                    ->where('status', '!=', 'cancelled');
            })
            ->count();

        // Pisahkan jadwal Reguler vs Privat & Konsultasi
        $regularSchedules = $schedules->filter(function ($s) {
            return $s->program && $s->program->code === 'reguler';
        });

        $specialSchedules = $schedules->filter(function ($s) {
            return $s->program && in_array($s->program->code, ['private_bimbel', 'private_home', 'consult']);
        });

        $maxWeekly = $student->weekly_frequency ?? 2;

        // Data pendukung untuk Siswa membuat jadwal Konsultasi mandiri
        $subjects = Subject::with('tutors')->orderBy('name')->get();
        $timeSlots = TimeSlot::where('is_active', true)->orderBy('start_time')->get();

        return view('student.bookings.index', compact(
            'schedules',
            'regularSchedules',
            'specialSchedules',
            'weeklyBookingCount',
            'maxWeekly',
            'subjects',
            'timeSlots',
            'filterDate'
        ));
    }

    /**
     * Proses Booking Kursi dengan Validasi Kuota 2x/Minggu & Anti-Double Booking.
     */
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => ['required', 'exists:schedules,id'],
        ]);

        /** @var User $student */
        $student = Auth::user();
        $schedule = Schedule::findOrFail($request->schedule_id);

        if ($schedule->status !== 'scheduled' || Carbon::parse($schedule->date)->isPast()) {
            return back()->with('error', 'Jadwal ini sudah tidak tersedia untuk dibooking.');
        }

        // 1. CEK KAPASITAS PENUH:
        if ($schedule->isFull()) {
            return back()->with('error', 'Maaf, kuota kursi pada slot jadwal ini sudah penuh!');
        }

        // 2. CEK DOUBLE BOOKING (Siswa sudah booking slot yang sama):
        $alreadyBooked = $schedule->bookings()
            ->where('student_id', $student->id)
            ->where('status', 'confirmed')
            ->exists();

        if ($alreadyBooked) {
            return back()->with('error', 'Anda sudah memesan kursi pada jadwal ini.');
        }

        // 3. VALIDASI KUOTA MINGGUAN (Maksimal 2x Jadwal per Minggu):
        $scheduleDate = Carbon::parse($schedule->date);
        $startOfWeek = $scheduleDate->copy()->startOfWeek();
        $endOfWeek = $scheduleDate->copy()->endOfWeek();

        $weeklyBookingCount = Booking::where('student_id', $student->id)
            ->where('status', 'confirmed')
            ->whereHas('schedule', function ($q) use ($startOfWeek, $endOfWeek) {
                $q->whereBetween('date', [$startOfWeek, $endOfWeek])
                    ->where('status', '!=', 'cancelled');
            })
            ->count();

        $maxWeekly = $student->weekly_frequency ?? 2;

        if ($weeklyBookingCount >= $maxWeekly) {
            return back()->with('error', "Batas kuota tercapai! Anda sudah mengambil {$weeklyBookingCount} dari batas maksimal {$maxWeekly} jadwal pada minggu tersebut.");
        }

        // 4. BUAT BOOKING KURSI:
        DB::transaction(function () use ($schedule, $student) {
            $lockedSchedule = Schedule::lockForUpdate()->findOrFail($schedule->id);
            if ($lockedSchedule->isFull()) {
                abort(409, 'Kuota kursi pada slot jadwal ini sudah penuh.');
            }
            Booking::create([
                'booking_code' => 'BKG-'.strtoupper(Str::random(6)),
                'schedule_id' => $schedule->id,
                'student_id' => $student->id,
                'booked_by_user_id' => $student->id,
                'booking_type' => 'self_service',
                'status' => 'confirmed',
            ]);
            $lockedSchedule->increment('booked_count');
        });

        return redirect()->route('student.bookings.my')->with('success', 'Kursi berhasil dibooking!');
    }

    /**
     * Siswa membuat jadwal konsultasi mandiri dengan alokasi ruangan otomatis & validasi anti-bentrok.
     */
    public function storeConsultation(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date', 'after:today'],
            'time_slot_id' => ['required', 'exists:time_slots,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'tutor_id' => ['required', 'exists:users,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        /** @var User $student */
        $student = Auth::user();
        $scheduleDate = Carbon::parse($request->date);

        // 0. Validasi Sesi Khusus Sabtu (Sesi 7 s/d 9 hanya hari Sabtu)
        $timeSlot = TimeSlot::findOrFail($request->time_slot_id);
        if ($timeSlot->is_saturday_only && ! $scheduleDate->isSaturday()) {
            return back()->withInput()->with('error', "Sesi {$timeSlot->name} hanya tersedia untuk kegiatan konsultasi pada hari Sabtu.");
        }

        // 1. Cek Kuota Mingguan Siswa
        $startOfWeek = $scheduleDate->copy()->startOfWeek();
        $endOfWeek = $scheduleDate->copy()->endOfWeek();

        $weeklyBookingCount = Booking::where('student_id', $student->id)
            ->where('status', 'confirmed')
            ->whereHas('schedule', function ($q) use ($startOfWeek, $endOfWeek) {
                $q->whereBetween('date', [$startOfWeek, $endOfWeek])
                    ->where('status', '!=', 'cancelled');
            })
            ->count();

        $maxWeekly = $student->weekly_frequency ?? 2;
        if ($weeklyBookingCount >= $maxWeekly) {
            return back()->with('error', "Batas kuota tercapai! Anda sudah mengambil {$weeklyBookingCount} dari batas maksimal {$maxWeekly} jadwal pada minggu tersebut.");
        }

        // 2. Cek apakah tutor mengajar mapel ini
        $tutor = User::role('tutor')->findOrFail($request->tutor_id);
        $teachesSubject = $tutor->taughtSubjects()->where('subjects.id', $request->subject_id)->exists();
        if (! $teachesSubject) {
            return back()->with('error', "Pengajar {$tutor->name} tidak mengampu mata pelajaran yang dipilih.");
        }

        // 3. Validasi Anti-Bentrok Jadwal Tutor
        $tutorCollision = Schedule::where('tutor_id', $tutor->id)
            ->where('date', $request->date)
            ->where('time_slot_id', $request->time_slot_id)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($tutorCollision) {
            return back()->with('error', "Bentrok! Pengajar {$tutor->name} sudah memiliki jadwal lain pada sesi dan tanggal tersebut. Silakan pilih sesi atau hari lain.");
        }

        // 4. Alokasi Ruangan Fisik yang Masih Tersedia (Prioritas kapasitas kecil: R6, R1, R2, dll)
        $busyRoomIds = Schedule::where('date', $request->date)
            ->where('time_slot_id', $request->time_slot_id)
            ->where('status', '!=', 'cancelled')
            ->pluck('room_id')
            ->toArray();

        $availableRoom = Room::where('is_physical', true)
            ->where('is_active', true)
            ->whereNotIn('id', $busyRoomIds)
            ->orderBy('capacity', 'asc')
            ->first();

        if (! $availableRoom) {
            return back()->with('error', 'Semua ruangan bimbingan penuh pada tanggal dan sesi waktu tersebut. Silakan pilih sesi atau hari lain.');
        }

        // 5. Ambil Program Konsultasi
        $program = Program::where('code', 'consult')->first();
        if (! $program) {
            $program = Program::firstOrCreate(
                ['code' => 'consult'],
                ['name' => 'Konsultasi', 'is_private' => false, 'requires_room' => true]
            );
        }

        // 6. Buat Jadwal & Booking Konsultasi dalam Transaksi Database
        DB::transaction(function () use ($student, $tutor, $request, $availableRoom, $program) {
            $schedule = Schedule::create([
                'tutor_id' => $tutor->id,
                'subject_id' => $request->subject_id,
                'education_level_id' => $student->education_level_id ?? 2,
                'program_id' => $program->id,
                'room_id' => $availableRoom->id,
                'time_slot_id' => $request->time_slot_id,
                'date' => $request->date,
                'quota_max' => 1,
                'booked_count' => 1,
                'status' => 'scheduled',
                'notes' => $request->notes ?? 'Jadwal Konsultasi (Dibuat Mandiri oleh Siswa)',
            ]);

            Booking::create([
                'booking_code' => 'KNS-'.strtoupper(Str::random(6)),
                'schedule_id' => $schedule->id,
                'student_id' => $student->id,
                'booked_by_user_id' => $student->id,
                'booking_type' => 'self_service',
                'status' => 'confirmed',
                'booked_at' => now(),
            ]);
        });

        return redirect()->route('student.bookings.my')
            ->with('success', "Jadwal konsultasi berhasil dibuat! Ruangan {$availableRoom->name} bersama {$tutor->name} telah dipesan.");
    }

    /**
     * Halaman Jadwal Saya: Menampilkan riwayat dan tiket booking siswa.
     */
    public function myBookings()
    {
        /** @var User $student */
        $student = Auth::user();

        $bookings = Booking::with(['schedule.subject', 'schedule.room', 'schedule.timeSlot', 'schedule.tutor', 'schedule.program'])
            ->where('student_id', $student->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('student.bookings.my', compact('bookings'));
    }

    /**
     * Batalkan Booking dengan Validasi Ketat Cut-Off H-1.
     */
    public function cancel(Booking $booking)
    {
        if ($booking->student_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Jadwal ini sudah dibatalkan sebelumnya.');
        }

        // VALIDASI H-1: Selisih waktu mulai sesi dengan sekarang wajib >= 24 jam (minimal H-1)
        $sessionStartsAt = Carbon::parse($booking->schedule->date->format('Y-m-d').' '.$booking->schedule->timeSlot->start_time);
        if (Carbon::now()->diffInHours($sessionStartsAt, false) < 24) {
            return back()->with('error', 'Pembatalan gagal! Pembatalan jadwal hanya dapat dilakukan maksimal H-1 (minimal 24 jam sebelum tanggal sesi dimulai).');
        }

        // Update status pembatalan booking
        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => Carbon::now(),
            'cancel_reason' => 'Dibatalkan oleh siswa sesuai kebijakan H-1',
        ]);

        // Kembalikan kuota kursi slot
        if ($booking->schedule->booked_count > 0) {
            $booking->schedule->decrement('booked_count');
        }

        // Jika ini sesi konsultasi yang dibuat mandiri oleh siswa, otomatis batalkan juga slot jadwalnya agar ruangan dan tutor kembali terbuka
        if ($booking->schedule->program && $booking->schedule->program->code === 'consult') {
            $booking->schedule->update(['status' => 'cancelled']);
        }

        return back()->with('success', 'Jadwal berhasil dibatalkan. Kuota mingguan Anda telah dikembalikan!');
    }
}
