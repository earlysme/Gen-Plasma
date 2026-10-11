<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\TimeSlot;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard Utama Admin & Matriks Okupansi Ruangan.
     */
    public function index()
    {
        $today = Carbon::today();

        $totalTutors = User::role('tutor')->count();
        $totalStudents = User::role('student')->count();
        $schedulesTodayCount = Schedule::where('date', $today)->count();
        $bookingsTodayCount = Booking::whereHas('schedule', function ($q) use ($today) {
            $q->where('date', $today)->where('status', '!=', 'cancelled');
        })->where('status', 'confirmed')->count();

        // 8 Ruangan Fisik Bimbel & 4 Sesi Waktu
        $rooms = Room::where('is_physical', true)->orderBy('code')->get();
        $timeSlots = TimeSlot::where('is_active', true)->orderBy('session_number')->get();

        // Ambil jadwal hari ini untuk matriks okupansi
        $schedulesToday = Schedule::with(['tutor', 'subject'])
            ->where('date', $today)
            ->where('status', '!=', 'cancelled')
            ->get();

        // Susun matriks [room_id][time_slot_id] => Schedule
        $occupancyMatrix = [];
        foreach ($schedulesToday as $sch) {
            $occupancyMatrix[$sch->room_id][$sch->time_slot_id] = $sch;
        }

        // Daftar jadwal terbaru
        $recentSchedules = Schedule::with(['tutor', 'subject', 'room', 'timeSlot'])
            ->orderBy('date', 'desc')
            ->orderBy('time_slot_id', 'asc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalTutors',
            'totalStudents',
            'schedulesTodayCount',
            'bookingsTodayCount',
            'rooms',
            'timeSlots',
            'occupancyMatrix',
            'recentSchedules',
            'today'
        ));
    }
}
