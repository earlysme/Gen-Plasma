<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    /**
     * Tampilkan Halaman Statistik & Laporan Mingguan.
     */
    public function index(Request $request)
    {
        $selectedDate = $request->filled('week')
            ? Carbon::parse($request->week)
            : Carbon::today();

        $startOfWeek = $selectedDate->copy()->startOfWeek(); // Senin
        $endOfWeek = $selectedDate->copy()->endOfWeek();   // Minggu

        $prevWeek = $startOfWeek->copy()->subWeek()->toDateString();
        $nextWeek = $startOfWeek->copy()->addWeek()->toDateString();
        $currentWeek = Carbon::today()->startOfWeek()->toDateString();
        $isCurrentWeek = $startOfWeek->isSameDay(Carbon::today()->startOfWeek());

        // Ambil jadwal dalam rentang minggu terpilih
        $schedules = Schedule::with(['tutor', 'subject', 'program', 'room', 'timeSlot'])
            ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->get();

        $nonCancelled = $schedules->where('status', '!=', 'cancelled');

        // 1. Metrik Utama
        $totalSchedules = $schedules->count();
        $activeSchedules = $schedules->where('status', 'scheduled')->count();
        $completedSchedules = $schedules->where('status', 'completed')->count();
        $cancelledSchedules = $schedules->where('status', 'cancelled')->count();

        $totalSeatsAvailable = $nonCancelled->sum('quota_max');
        $totalSeatsBooked = $nonCancelled->sum('booked_count');
        $seatOccupancyRate = $totalSeatsAvailable > 0
            ? round(($totalSeatsBooked / $totalSeatsAvailable) * 100, 1)
            : 0;

        // Utilisasi Ruangan Fisik
        $physicalRooms = Room::where('is_physical', true)->where('is_active', true)->get();
        $physicalRoomCount = $physicalRooms->count();
        $timeSlotCount = TimeSlot::where('is_active', true)->count();
        $totalPotentialSlots = $physicalRoomCount * $timeSlotCount * 7;

        $usedPhysicalSlots = $nonCancelled->filter(function ($s) {
            return $s->room && $s->room->is_physical;
        })->count();

        $roomUtilizationRate = $totalPotentialSlots > 0
            ? round(($usedPhysicalSlots / $totalPotentialSlots) * 100, 1)
            : 0;

        // 2. Tren Harian (Senin s/d Minggu)
        $dailyStats = [];
        $maxDailySchedules = 1;
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $daySchedules = $schedules->filter(fn ($s) => Carbon::parse($s->date)->isSameDay($day));
            $dayNonCancelled = $daySchedules->where('status', '!=', 'cancelled');
            $count = $daySchedules->count();
            if ($count > $maxDailySchedules) {
                $maxDailySchedules = $count;
            }

            $dailyStats[] = [
                'date' => $day->toDateString(),
                'day_name' => $day->isoFormat('dddd'),
                'day_short' => $day->isoFormat('ddd'),
                'date_formatted' => $day->isoFormat('D MMM'),
                'is_today' => $day->isToday(),
                'total_schedules' => $count,
                'active_schedules' => $daySchedules->where('status', 'scheduled')->count(),
                'completed_schedules' => $daySchedules->where('status', 'completed')->count(),
                'cancelled_schedules' => $daySchedules->where('status', 'cancelled')->count(),
                'seats_booked' => $dayNonCancelled->sum('booked_count'),
                'seats_quota' => $dayNonCancelled->sum('quota_max'),
                'rate' => $dayNonCancelled->sum('quota_max') > 0
                    ? round(($dayNonCancelled->sum('booked_count') / $dayNonCancelled->sum('quota_max')) * 100, 1)
                    : 0,
            ];
        }

        // 3. Distribusi Program
        $programs = Program::all();
        $programStats = $programs->map(function ($prog) use ($nonCancelled, $totalSchedules) {
            $progSchedules = $nonCancelled->where('program_id', $prog->id);
            $count = $progSchedules->count();
            $booked = $progSchedules->sum('booked_count');
            $share = $totalSchedules > 0 ? round(($count / $totalSchedules) * 100, 1) : 0;

            return [
                'program' => $prog,
                'count' => $count,
                'booked' => $booked,
                'share' => $share,
            ];
        })->sortByDesc('count')->values();

        // 4. Top Tutor Paling Aktif
        $topTutors = $nonCancelled
            ->groupBy('tutor_id')
            ->map(function ($tutorSchedules) {
                $tutor = $tutorSchedules->first()->tutor;

                return [
                    'tutor' => $tutor,
                    'schedule_count' => $tutorSchedules->count(),
                    'students_taught' => $tutorSchedules->sum('booked_count'),
                    'completed_count' => $tutorSchedules->where('status', 'completed')->count(),
                ];
            })
            ->filter(fn ($t) => ! is_null($t['tutor']))
            ->sortByDesc('schedule_count')
            ->take(6)
            ->values();

        // 5. Top Mata Pelajaran
        $topSubjects = $nonCancelled
            ->groupBy('subject_id')
            ->map(function ($subjectSchedules) {
                $subject = $subjectSchedules->first()->subject;

                return [
                    'subject' => $subject,
                    'count' => $subjectSchedules->count(),
                    'booked' => $subjectSchedules->sum('booked_count'),
                ];
            })
            ->filter(fn ($s) => ! is_null($s['subject']))
            ->sortByDesc('count')
            ->take(6)
            ->values();

        // 6. Okupansi per Ruangan
        $roomStats = $physicalRooms->map(function ($room) use ($nonCancelled) {
            $roomSchedules = $nonCancelled->where('room_id', $room->id);

            return [
                'room' => $room,
                'sessions_count' => $roomSchedules->count(),
                'students_count' => $roomSchedules->sum('booked_count'),
            ];
        })->sortByDesc('sessions_count')->values();

        return view('admin.statistics.index', compact(
            'startOfWeek',
            'endOfWeek',
            'prevWeek',
            'nextWeek',
            'currentWeek',
            'isCurrentWeek',
            'totalSchedules',
            'activeSchedules',
            'completedSchedules',
            'cancelledSchedules',
            'totalSeatsAvailable',
            'totalSeatsBooked',
            'seatOccupancyRate',
            'physicalRoomCount',
            'usedPhysicalSlots',
            'roomUtilizationRate',
            'dailyStats',
            'maxDailySchedules',
            'programStats',
            'topTutors',
            'topSubjects',
            'roomStats'
        ));
    }
}
