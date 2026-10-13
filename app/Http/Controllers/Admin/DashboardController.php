<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\TimeSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard Ringkasan Mingguan & Matriks Okupansi Ruangan Berdasarkan Tanggal.
     */
    public function index(Request $request)
    {
        // 1. Tentukan tanggal terpilih (default: hari ini)
        $selectedDate = $request->filled('date')
            ? Carbon::parse($request->date)
            : Carbon::today();

        // 2. Tentukan periode minggu berjalan dari tanggal terpilih
        $startOfWeek = $selectedDate->copy()->startOfWeek(); // Senin
        $endOfWeek = $selectedDate->copy()->endOfWeek();   // Minggu
        $prevWeekDate = $startOfWeek->copy()->subWeek()->toDateString();
        $nextWeekDate = $startOfWeek->copy()->addWeek()->toDateString();
        $isCurrentWeek = $startOfWeek->isSameDay(Carbon::today()->startOfWeek());

        // 3. Statistik Pengguna
        $totalTutors = User::role('tutor')->count();
        $totalStudents = User::role('student')->count();

        // 4. Jadwal & Metrik Mingguan (Senin - Minggu)
        $weeklySchedules = Schedule::with(['tutor', 'subject', 'program', 'room', 'timeSlot'])
            ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->get();

        $schedulesWeeklyCount = $weeklySchedules->count();
        $nonCancelledWeekly = $weeklySchedules->where('status', '!=', 'cancelled');
        $bookingsWeeklyCount = $nonCancelledWeekly->sum('booked_count');
        $totalSeatsWeekly = $nonCancelledWeekly->sum('quota_max');
        $occupancyWeeklyRate = $totalSeatsWeekly > 0
            ? round(($bookingsWeeklyCount / $totalSeatsWeekly) * 100, 1)
            : 0;

        // 5. Susun daftar 7 hari dalam minggu untuk tombol filter tanggal
        $daysInWeek = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $dayCount = $weeklySchedules->filter(fn ($s) => Carbon::parse($s->date)->isSameDay($day))->count();
            $daysInWeek[] = [
                'date' => $day->toDateString(),
                'day_short' => $day->isoFormat('ddd'),
                'day_name' => $day->isoFormat('dddd'),
                'day_num' => $day->isoFormat('D MMM'),
                'is_selected' => $day->isSameDay($selectedDate),
                'is_today' => $day->isToday(),
                'schedules_count' => $dayCount,
            ];
        }

        // 6. Matriks Okupansi Ruangan Fisik untuk Tanggal Terpilih
        $rooms = Room::where('is_physical', true)->where('is_active', true)->orderBy('code')->get();
        $timeSlots = TimeSlot::forDate($selectedDate);

        $schedulesOnDate = Schedule::with(['tutor', 'subject', 'program', 'room', 'timeSlot'])
            ->where('date', $selectedDate->toDateString())
            ->where('status', '!=', 'cancelled')
            ->get();

        // Matriks [room_id][time_slot_id] => Schedule
        $occupancyMatrix = [];
        foreach ($schedulesOnDate as $sch) {
            $occupancyMatrix[$sch->room_id][$sch->time_slot_id] = $sch;
        }

        // 7. Jadwal pada tanggal terpilih
        $schedulesList = Schedule::with(['tutor', 'subject', 'room', 'timeSlot', 'program'])
            ->where('date', $selectedDate->toDateString())
            ->orderBy('time_slot_id', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'selectedDate',
            'startOfWeek',
            'endOfWeek',
            'prevWeekDate',
            'nextWeekDate',
            'isCurrentWeek',
            'totalTutors',
            'totalStudents',
            'schedulesWeeklyCount',
            'bookingsWeeklyCount',
            'totalSeatsWeekly',
            'occupancyWeeklyRate',
            'daysInWeek',
            'rooms',
            'timeSlots',
            'occupancyMatrix',
            'schedulesList'
        ));
    }
}
