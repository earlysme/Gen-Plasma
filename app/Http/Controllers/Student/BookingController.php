<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Katalog Jadwal Bioskop: Siswa melihat slot jadwal aktif yang masih tersedia.
     */
    public function index()
    {
        /** @var \App\Models\User $student */
        $student = Auth::user();

        // Ambil jadwal aktif mulai hari ini yang belum penuh
        $schedules = Schedule::with(['subject', 'room', 'timeSlot', 'tutor', 'program'])
            ->where('date', '>=', Carbon::today())
            ->where('status', 'scheduled')
            ->whereColumn('booked_count', '<', 'quota_max')
            ->orderBy('date', 'asc')
            ->orderBy('time_slot_id', 'asc')
            ->get();

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

        return view('student.bookings.index', compact('schedules', 'regularSchedules', 'specialSchedules', 'weeklyBookingCount', 'maxWeekly'));
    }

    /**
     * Proses Booking Kursi dengan Validasi Kuota 2x/Minggu & Anti-Double Booking.
     */
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => ['required', 'exists:schedules,id'],
        ]);

        /** @var \App\Models\User $student */
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
            'booking_code' => 'BKG-' . strtoupper(Str::random(6)),
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
     * Halaman Jadwal Saya: Menampilkan riwayat dan tiket booking siswa.
     */
    public function myBookings()
    {
        /** @var \App\Models\User $student */
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

        // VALIDASI H-1: Selisih tanggal jadwal dengan hari ini wajib >= 1 hari (bukan di hari H)
        $sessionStartsAt = Carbon::parse($booking->schedule->date.' '.$booking->schedule->timeSlot->start_time);
        if (Carbon::now()->diffInHours($sessionStartsAt, false) < 24) {
            return back()->with('error', 'Pembatalan gagal! Pembatalan jadwal hanya dapat dilakukan maksimal H-1 (24 jam sebelum tanggal sesi dimulai).');
        }

        // Update status pembatalan
        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => Carbon::now(),
            'cancel_reason' => 'Dibatalkan oleh siswa sesuai kebijakan H-1',
        ]);

        // Kembalikan kuota kursi slot
        if ($booking->schedule->booked_count > 0) {
            $booking->schedule->decrement('booked_count');
        }

        return back()->with('success', 'Jadwal berhasil dibatalkan. Kuota mingguan Anda telah dikembalikan!');
    }
}
