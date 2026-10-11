<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Student\BookingController as StudentBookingController;
use App\Http\Controllers\Tutor\ScheduleController as TutorScheduleController;
use Illuminate\Support\Facades\Route;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Autentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Terproteksi
Route::middleware(['auth'])->group(function () {

    // 1. AREA ADMIN
    Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Kelola Program
        Route::resource('programs', \App\Http\Controllers\Admin\ProgramController::class)->except(['create', 'show', 'edit']);

        // Kelola Ruangan
        Route::resource('rooms', \App\Http\Controllers\Admin\RoomController::class)->except(['create', 'show', 'edit']);

        // Kelola Jadwal
        Route::get('/schedules', [\App\Http\Controllers\Admin\ScheduleController::class, 'index'])->name('schedules.index');
        Route::post('/schedules', [\App\Http\Controllers\Admin\ScheduleController::class, 'store'])->name('schedules.store');
        Route::post('/schedules/{schedule}/assign-student', [\App\Http\Controllers\Admin\ScheduleController::class, 'assignStudent'])->name('schedules.assignStudent');
        Route::patch('/schedules/{schedule}/status', [\App\Http\Controllers\Admin\ScheduleController::class, 'updateStatus'])->name('schedules.updateStatus');
        Route::delete('/schedules/{schedule}', [\App\Http\Controllers\Admin\ScheduleController::class, 'destroy'])->name('schedules.destroy');

        // Kelola Mata Pelajaran
        Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class)->except(['create', 'show', 'edit']);

        // Kelola Pengguna (Siswa & Tutor)
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['create', 'show', 'edit']);
    });

    // 2. AREA PENGAJAR / TUTOR
    Route::prefix('tutor')->name('tutor.')->middleware(['role:tutor'])->group(function () {
        Route::get('/dashboard', [TutorScheduleController::class, 'index'])->name('dashboard');
        Route::get('/schedules/create', [TutorScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/schedules', [TutorScheduleController::class, 'store'])->name('schedules.store');
        Route::post('/schedules/{schedule}/complete', [TutorScheduleController::class, 'complete'])->name('schedules.complete');
        Route::post('/schedules/{schedule}/assign-student', [TutorScheduleController::class, 'assignStudent'])->name('schedules.assignStudent');
        Route::delete('/schedules/{schedule}/cancel', [TutorScheduleController::class, 'cancelSlot'])->name('schedules.cancel');
    });

    // 3. AREA MURID / SISWA
    Route::prefix('student')->name('student.')->middleware(['role:student'])->group(function () {
        Route::get('/dashboard', [StudentBookingController::class, 'index'])->name('dashboard');
        Route::post('/bookings', [StudentBookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/my', [StudentBookingController::class, 'myBookings'])->name('bookings.my');
        Route::post('/bookings/{booking}/cancel', [StudentBookingController::class, 'cancel'])->name('bookings.cancel');
    });

});
