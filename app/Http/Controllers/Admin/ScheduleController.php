<?php

namespace App\Http\Controllers\Admin;

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
     * Tampilkan grid jadwal operasional (layout kotak per sesi & tanggal).
     */
    public function index(Request $request)
    {
        // Rentang tanggal: filter tanggal tunggal ATAU 7 hari mulai hari ini
        if ($request->filled('date')) {
            $startDate = Carbon::parse($request->date)->startOfDay();
            $endDate   = $startDate->copy()->endOfDay();
        } else {
            $startDate = Carbon::today();
            $endDate   = Carbon::today()->addDays(6)->endOfDay();
        }

        $query = Schedule::with(['tutor', 'subject', 'room', 'timeSlot', 'educationLevel', 'program', 'activeBookings.student'])
            ->whereBetween('date', [$startDate, $endDate]);

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        if ($request->filled('tutor_id')) {
            $query->where('tutor_id', $request->tutor_id);
        }

        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $allSchedules = $query->orderBy('date')->orderBy('time_slot_id')->get();

        // Bangun struktur grid: $grid[time_slot_id][Y-m-d] = Collection<Schedule>
        $timeSlots = TimeSlot::where('is_active', true)->orderBy('session_number')->get();

        $dates = collect();
        for ($d = $startDate->copy(); $d->lte($endDate); $d->addDay()) {
            $dates->push($d->copy());
        }

        $grid = [];
        foreach ($timeSlots as $slot) {
            $grid[$slot->id] = [];
            foreach ($dates as $day) {
                $grid[$slot->id][$day->format('Y-m-d')] = collect();
            }
        }

        foreach ($allSchedules as $s) {
            $dateKey = Carbon::parse($s->date)->format('Y-m-d');
            $slotId  = $s->time_slot_id;
            if (isset($grid[$slotId][$dateKey])) {
                $grid[$slotId][$dateKey]->push($s);
            }
        }

        $rooms           = Room::where('is_active', true)->orderBy('code')->get();
        $tutors          = User::role('tutor')->orderBy('name')->get();
        $programs        = Program::all();
        $subjects        = Subject::orderBy('name')->get();
        $educationLevels = EducationLevel::all();
        $students        = User::role('student')->orderBy('name')->get();

        return view('admin.schedules.index', compact(
            'grid',
            'dates',
            'timeSlots',
            'rooms',
            'tutors',
            'programs',
            'subjects',
            'educationLevels',
            'students',
            'startDate',
            'endDate',
            'allSchedules'
        ));
    }

    /**
     * Buka slot jadwal baru oleh Admin.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tutor_id' => ['required', 'exists:users,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'education_level_id' => ['required', 'exists:education_levels,id'],
            'program_id' => ['required', 'exists:programs,id'],
            'room_id' => ['required', 'exists:rooms,id'],
            'time_slot_id' => ['required', 'exists:time_slots,id'],
            'date' => ['required', 'date'],
            'quota_max' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $room = Room::findOrFail($validated['room_id']);
        $program = Program::findOrFail($validated['program_id']);

        // 1. Validasi Anti-Bentrok Ruangan Fisik
        if ($room->is_physical) {
            $roomConflict = Schedule::where('date', $validated['date'])
                ->where('time_slot_id', $validated['time_slot_id'])
                ->where('room_id', $room->id)
                ->where('status', '!=', 'cancelled')
                ->exists();

            if ($roomConflict) {
                return back()->withInput()->with('error', "Ruangan {$room->name} sudah digunakan pada tanggal dan sesi waktu tersebut.");
            }
        }

        // 2. Validasi Anti-Bentrok Tutor
        $tutorConflict = Schedule::where('date', $validated['date'])
            ->where('time_slot_id', $validated['time_slot_id'])
            ->where('tutor_id', $validated['tutor_id'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($tutorConflict) {
            $tutor = User::find($validated['tutor_id']);
            return back()->withInput()->with('error', "Pengajar {$tutor->name} sudah memiliki jadwal lain pada sesi tersebut.");
        }

        // Tentukan kuota kursi
        $quota = $program->is_private ? 1 : ($validated['quota_max'] ?? $room->capacity);

        Schedule::create([
            'tutor_id' => $validated['tutor_id'],
            'subject_id' => $validated['subject_id'],
            'education_level_id' => $validated['education_level_id'],
            'program_id' => $validated['program_id'],
            'room_id' => $room->id,
            'time_slot_id' => $validated['time_slot_id'],
            'date' => $validated['date'],
            'quota_max' => $quota,
            'booked_count' => 0,
            'status' => 'scheduled',
        ]);

        return redirect()->route('admin.schedules.index', ['date' => $validated['date']])
            ->with('success', 'Jadwal berhasil dibuat.');
    }

    /**
     * Tambah murid ke jadwal secara langsung (Direct Assign oleh Admin).
     */
    public function assignStudent(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
        ]);

        if ($schedule->isFull()) {
            return back()->with('error', 'Kapasitas kursi jadwal ini sudah penuh.');
        }

        $existing = Booking::where('schedule_id', $schedule->id)
            ->where('student_id', $validated['student_id'])
            ->where('status', 'confirmed')
            ->exists();

        if ($existing) {
            return back()->with('error', 'Murid tersebut sudah terdaftar di jadwal ini.');
        }

        Booking::create([
            'booking_code' => 'BK-' . strtoupper(Str::random(8)),
            'schedule_id' => $schedule->id,
            'student_id' => $validated['student_id'],
            'booked_by_user_id' => Auth::id(),
            'booking_type' => 'tutor_assigned',
            'status' => 'confirmed',
            'booked_at' => now(),
        ]);

        $schedule->increment('booked_count');

        return back()->with('success', 'Murid berhasil didaftarkan ke jadwal.');
    }

    /**
     * Tandai status jadwal (completed / scheduled).
     */
    public function updateStatus(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:scheduled,completed,cancelled'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $schedule->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $schedule->notes,
        ]);

        return back()->with('success', 'Status jadwal berhasil diperbarui.');
    }

    /**
     * Batalkan slot jadwal.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->update(['status' => 'cancelled']);
        $schedule->bookings()->where('status', 'confirmed')->update(['status' => 'cancelled']);

        return back()->with('success', 'Jadwal berhasil dibatalkan.');
    }
}
