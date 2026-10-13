@extends('layouts.app')

@section('title', 'Dashboard Pengajar')

@push('styles')
<style>
    .td-wrap {
        max-width: 1280px;
        margin: 0 auto;
    }

    /* Header & Welcome Banner */
    .td-welcome {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        padding: 24px 28px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .td-welcome-text h1 {
        font: 800 clamp(20px, 2vw, 24px) "Plus Jakarta Sans", sans-serif;
        color: var(--text);
        letter-spacing: -.5px;
        margin: 0 0 4px;
    }

    .td-welcome-text p {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    .td-welcome-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* 4 Grid Statistik Cepat */
    .td-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .td-stat-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 20px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        transition: transform .18s ease, border-color .18s ease;
    }

    .td-stat-card:hover {
        transform: translateY(-2px);
        border-color: var(--primary);
    }

    .td-stat-ico {
        display: grid;
        place-items: center;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        flex-shrink: 0;
    }

    .td-stat-ico.ico-primary {
        background: var(--primary-soft);
        color: var(--primary);
    }

    .td-stat-ico.ico-mint {
        background: var(--mint);
        color: var(--mint-text);
    }

    .td-stat-ico.ico-gold {
        background: var(--accent-gold-soft);
        color: var(--accent-gold-text);
    }

    .td-stat-ico.ico-terra {
        background: var(--accent-terra-soft);
        color: var(--accent-terra-text);
    }

    .td-stat-content strong {
        display: block;
        font: 800 20px/1.2 "Plus Jakarta Sans", sans-serif;
        color: var(--text);
        letter-spacing: -.4px;
    }

    .td-stat-content span {
        display: block;
        font-size: 11.5px;
        font-weight: 600;
        color: var(--muted);
        margin-top: 3px;
    }

    /* Layout 2 Kolom Utama */
    .td-main-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 22px;
        align-items: start;
    }

    /* Filter Tabs Agenda */
    .td-filter-bar {
        display: flex;
        gap: 6px;
        background: var(--surface-2);
        padding: 4px;
        border-radius: 10px;
    }

    .td-filter-btn {
        border: 0;
        background: transparent;
        color: var(--muted);
        padding: 6px 12px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all .15s ease;
    }

    .td-filter-btn.active {
        background: var(--surface);
        color: var(--primary);
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
    }

    /* Grid Kartu Agenda Mengajar */
    .td-agenda-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
        margin-top: 16px;
    }

    .td-agenda-card {
        padding: 18px 20px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border-left: 4px solid var(--primary);
        transition: transform .18s ease, border-color .18s ease;
    }

    .td-agenda-card:hover {
        transform: translateY(-2px);
    }

    .td-agenda-card.status-completed {
        border-left-color: var(--mint-text);
        opacity: .88;
    }

    .td-agenda-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .td-agenda-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--text);
        margin: 0 0 4px;
    }

    .td-agenda-time {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--muted);
        font-weight: 600;
    }

    .td-agenda-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 12.5px;
        color: var(--muted);
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .td-seat-bar-wrap {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .td-seat-bar {
        width: 60px;
        height: 6px;
        background: var(--surface-2);
        border-radius: 99px;
        overflow: hidden;
        display: inline-block;
    }

    .td-seat-bar i {
        display: block;
        height: 100%;
        background: var(--primary);
        border-radius: 99px;
    }

    /* Chip Siswa */
    .td-students {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-bottom: 14px;
        padding-top: 10px;
        border-top: 1px dashed var(--line);
    }

    .td-chip {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 99px;
        background: var(--surface-2);
        color: var(--text);
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Tombol Aksi Agenda */
    .td-agenda-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        padding-top: 10px;
        border-top: 1px solid var(--line);
        flex-wrap: wrap;
    }

    /* Recent Activity Sidebar List */
    .td-activity-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
        margin-top: 14px;
    }

    .td-activity-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        position: relative;
    }

    .td-activity-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 17px;
        top: 36px;
        bottom: -14px;
        width: 2px;
        background: var(--line);
    }

    .td-activity-icon {
        display: grid;
        place-items: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        flex-shrink: 0;
        z-index: 1;
    }

    .td-activity-content {
        flex: 1;
    }

    .td-activity-content strong {
        display: block;
        font-size: 13px;
        color: var(--text);
        margin-bottom: 2px;
    }

    .td-activity-content p {
        font-size: 12px;
        color: var(--muted);
        margin: 0 0 4px;
        line-height: 1.4;
    }

    .td-activity-content small {
        font-size: 11px;
        color: var(--muted);
        font-weight: 600;
    }

    /* Dialog Modals */
    dialog.td-dialog {
        border: 1px solid var(--line);
        border-radius: 16px;
        background: var(--surface);
        color: var(--text);
        padding: 0;
        box-shadow: 0 20px 60px rgba(0,0,0,.25);
        max-width: 460px;
        width: 90%;
        margin: auto;
    }

    dialog.td-dialog::backdrop {
        background: rgba(11, 22, 20, .6);
        backdrop-filter: blur(3px);
    }

    .td-modal-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--line);
    }

    .td-modal-head h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 800;
        color: var(--text);
    }

    .td-modal-close {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: var(--muted);
    }

    .td-modal-body {
        padding: 20px;
    }

    .td-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 16px;
        padding-top: 14px;
        border-top: 1px solid var(--line);
    }

    /* Responsive */
    @media (max-width: 990px) {
        .td-stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .td-main-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 560px) {
        .td-stats-grid {
            grid-template-columns: 1fr;
        }
        .td-welcome {
            flex-direction: column;
            align-items: flex-start;
        }
        .td-agenda-head {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
@php
    $tutor = Auth::user();

    // 1. Data jadwal milik tutor (dari controller atau fallback langsung khusus tutor login)
    if (!isset($schedules)) {
        try {
            $schedules = \App\Models\Schedule::with(['subject', 'room', 'timeSlot', 'educationLevel', 'program', 'activeBookings.student'])
                ->where('tutor_id', $tutor->id)
                ->orderBy('date', 'desc')
                ->orderBy('time_slot_id', 'asc')
                ->get();
        } catch (\Throwable $e) {
            $schedules = collect();
        }
    }

    $displaySchedules = $schedules;

    // 2. Aktivitas terbaru riil milik tutor ini
    if (!isset($recentBookings)) {
        try {
            $recentBookings = \App\Models\Booking::with(['student', 'schedule.subject', 'schedule.timeSlot', 'schedule.room'])
                ->whereHas('schedule', function ($q) use ($tutor) {
                    $q->where('tutor_id', $tutor->id);
                })
                ->latest()
                ->take(6)
                ->get();
        } catch (\Throwable $e) {
            $recentBookings = collect();
        }
    }

    // 3. Daftar murid untuk modal direct assignment
    if (!isset($studentsList)) {
        try {
            $studentsList = \App\Models\User::role('student')->orderBy('name')->get();
        } catch (\Throwable $e) {
            $studentsList = collect();
        }
    }

    // Status map
    $statusMap = [
        'completed' => ['Selesai', 'badge-tutor'],
        'cancelled' => ['Batal', 'btn-danger'],
        'scheduled' => ['Aktif', 'badge-student'],
    ];

    // Hitung statistik ringkas khusus jadwal tutor yang login
    $todaySchedulesCount = $displaySchedules->filter(function($s) {
        return \Carbon\Carbon::parse($s->date)->isToday() && $s->status === 'scheduled';
    })->count();

    $totalStudentsCount = $displaySchedules->sum('booked_count');
    $completedCount = $displaySchedules->where('status', 'completed')->count();
@endphp

<div class="td-wrap">
    {{-- 1. Header & Welcome Banner --}}
    <div class="td-welcome">
        <div class="td-welcome-text">
            <h1>Selamat Datang, {{ $tutor->name }}!</h1>
            <p>Pantau agenda mengajar, aktivitas murid, dan sesi bimbingan belajar Anda di sini.</p>
        </div>
        <div class="td-welcome-actions">
            <a href="{{ route('tutor.schedules.index') }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:2px;"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg>
                Matriks Ruang Bimbel
            </a>
            <a href="{{ route('tutor.schedules.index', ['open_modal' => 1]) }}" class="btn btn-primary">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Buka Slot Baru
            </a>
        </div>
    </div>

    {{-- 2. Grid Statistik Cepat --}}
    <section class="td-stats-grid">
        <div class="td-stat-card">
            <div class="td-stat-ico ico-primary">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div class="td-stat-content">
                <strong>{{ $todaySchedulesCount }} Sesi</strong>
                <span>Jadwal Aktif Hari Ini</span>
            </div>
        </div>

        <div class="td-stat-card">
            <div class="td-stat-ico ico-mint">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="td-stat-content">
                <strong>{{ $totalStudentsCount }} Murid</strong>
                <span>Total Murid Terbimbing</span>
            </div>
        </div>

        <div class="td-stat-card">
            <div class="td-stat-ico ico-gold">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div class="td-stat-content">
                <strong>{{ $completedCount }} Sesi</strong>
                <span>Sesi Telah Selesai</span>
            </div>
        </div>

        <div class="td-stat-card">
            <div class="td-stat-ico ico-terra">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/></svg>
            </div>
            <div class="td-stat-content">
                <strong>{{ $displaySchedules->count() }} Slot</strong>
                <span>Total Agenda Mengajar</span>
            </div>
        </div>
    </section>

    {{-- 3. Layout Utama: Agenda Mengajar (Kiri) & Recent Activity (Kanan) --}}
    <div class="td-main-grid">
        {{-- KOLOM KIRI: AGENDA MENGAJAR SAYA (DIPINDAHKAN KE SINI) --}}
        <section class="card" style="padding:22px;margin:0;">
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:8px;">
                <div>
                    <h2 style="font-size:16.5px;font-weight:800;color:var(--text);margin:0;">Agenda Mengajar Saya</h2>
                    <p style="font-size:12.5px;color:var(--muted);margin:2px 0 0;">Daftar sesi bimbingan, ruang, kapasitas, dan murid yang terdaftar.</p>
                </div>
                
                {{-- Filter Tabs --}}
                <div class="td-filter-bar">
                    <button type="button" class="td-filter-btn active" data-filter="all">Semua</button>
                    <button type="button" class="td-filter-btn" data-filter="today">Hari Ini</button>
                    <button type="button" class="td-filter-btn" data-filter="scheduled">Aktif</button>
                    <button type="button" class="td-filter-btn" data-filter="completed">Selesai</button>
                </div>
            </div>

            <div class="td-agenda-list" id="agenda-container">
                @forelse($displaySchedules as $s)
                    @php
                        $dateObj = \Carbon\Carbon::parse($s->date);
                        $isToday = $dateObj->isToday();
                        $dateTag = $isToday ? 'Hari Ini' : $dateObj->isoFormat('D MMM Y');
                        [$stLabel, $stClass] = $statusMap[$s->status] ?? ['Aktif', 'badge-student'];
                        $fill = $s->quota_max > 0 ? min(100, round(($s->booked_count / $s->quota_max) * 100)) : 0;
                        $filterTag = ($isToday ? 'today ' : '') . $s->status;
                    @endphp
                    <article class="td-agenda-card status-{{ $s->status }}" data-tags="{{ $filterTag }}">
                        <div class="td-agenda-head">
                            <div>
                                <h3 class="td-agenda-title">{{ $s->subject?->name ?? 'Mata Pelajaran' }}</h3>
                                <div class="td-agenda-time">
                                    <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span>{{ $dateTag }} • {{ $s->timeSlot?->name ?? '-' }}</span>
                                </div>
                            </div>
                            <div style="display:flex;gap:6px;align-items:center;">
                                <span class="badge" style="background:var(--surface-2);color:var(--muted);font-size:11px;">{{ $s->program?->name ?? 'Reguler' }}</span>
                                <span class="badge {{ $stClass }}">{{ $stLabel }}</span>
                            </div>
                        </div>

                        <div class="td-agenda-meta">
                            <span style="display:inline-flex;align-items:center;gap:5px;">
                                 <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <strong>{{ $s->room?->name ?? 'Rumah Siswa' }}</strong>
                            </span>
                            <span class="td-seat-bar-wrap">
                                <span class="td-seat-bar"><i style="width: {{ $fill }}%"></i></span>
                                <strong>{{ $s->booked_count }}/{{ $s->quota_max }}</strong> kursi
                            </span>
                        </div>

                        {{-- Chip Daftar Murid --}}
                        <div class="td-students">
                            @forelse($s->activeBookings as $b)
                                <span class="td-chip">
                                    <svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    {{ data_get($b, 'student.name') }}
                                </span>
                            @empty
                                <span style="color:var(--muted);font-size:12px;font-style:italic;">Belum ada murid terdaftar di slot ini.</span>
                            @endforelse
                        </div>

                        {{-- Aksi Cepat --}}
                        @if($s->status === 'scheduled')
                            <div class="td-agenda-actions">
                                <button type="button" class="btn btn-success" style="padding:7px 12px;font-size:12px;"
                                    data-open="dlg-complete"
                                    data-action="{{ route('tutor.schedules.complete', $s->id) }}"
                                    data-sub="{{ $s->subject?->name ?? 'Sesi' }}, {{ $dateTag }}">
                                    ✓ Tandai Selesai
                                </button>

                                @if(! (is_callable([$s, 'isFull']) ? $s->isFull() : ($s->booked_count >= $s->quota_max)))
                                    <button type="button" class="btn btn-secondary" style="padding:7px 12px;font-size:12px;"
                                        data-open="dlg-assign"
                                        data-action="{{ route('tutor.schedules.assignStudent', $s->id) }}"
                                        data-sub="{{ $s->subject?->name ?? 'Sesi' }}, {{ $dateTag }}">
                                        + Murid
                                    </button>
                                @endif

                                @if($s->booked_count === 0)
                                    <button type="button" class="btn btn-danger" style="margin-left:auto;padding:7px 12px;font-size:12px;"
                                        data-open="dlg-cancel"
                                        data-action="{{ route('tutor.schedules.cancel', $s->id) }}"
                                        data-sub="{{ $s->subject?->name ?? 'Sesi' }}, {{ $dateTag }}">
                                        Batalkan
                                    </button>
                                @endif
                            </div>
                        @else
                            <div style="font-size:11.5px;color:var(--muted);font-weight:600;">
                                ✓ Sesi bimbingan telah selesai dilaksanakan.
                            </div>
                        @endif
                    </article>
                @empty
                    <div style="text-align:center;padding:40px 16px;color:var(--muted);">
                        <div style="width:48px;height:48px;border-radius:12px;background:var(--surface-2);color:var(--muted);display:grid;place-items:center;margin:0 auto 12px;">
                            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </div>
                        <strong style="color:var(--text);font-size:15px;display:block;">Belum ada jadwal mengajar</strong>
                        <p style="margin:4px 0 16px;font-size:13px;">Buka slot baru atau pilih tanggal di matriks ruangan.</p>
                        <a href="{{ route('tutor.schedules.index') }}" class="btn btn-primary">
                            + Buka Slot Mengajar
                        </a>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- KOLOM KANAN: RECENT ACTIVITY & INFO PENGAJAR --}}
        <div style="display:flex;flex-direction:column;gap:20px;">
            {{-- 1. Recent Activity --}}
            <section class="card" style="padding:22px;margin:0;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div class="td-stat-ico ico-terra" style="width:28px;height:28px;border-radius:8px;">
                            <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        </div>
                        <h2 style="font-size:15px;font-weight:800;color:var(--text);margin:0;">Aktivitas Terbaru</h2>
                    </div>
                    <span class="badge badge-tutor" style="font-size:10.5px;">Live</span>
                </div>

                <div class="td-activity-list">
                    @forelse($recentBookings as $rb)
                        <div class="td-activity-item">
                            <div class="td-activity-icon" style="background:var(--mint);color:var(--mint-text);">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
                            </div>
                            <div class="td-activity-content">
                                <strong>{{ $rb->student?->name ?? 'Murid' }}</strong>
                                <p>Mendaftar sesi {{ $rb->schedule?->subject?->name ?? 'Bimbingan' }} ({{ $rb->schedule?->timeSlot?->name ?? '-' }}).</p>
                                <small>{{ $rb->created_at ? $rb->created_at->diffForHumans() : '-' }}</small>
                            </div>
                        </div>
                    @empty
                        <div style="padding:16px 8px;text-align:center;color:var(--muted);font-size:12.5px;">
                            Belum ada aktivitas pendaftaran murid terbaru pada jadwal Anda.
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- 2. Panduan & Pintasan --}}
            <section class="card" style="padding:20px;margin:0;background:var(--surface-2);">
                <h3 style="font-size:13.5px;font-weight:700;color:var(--text);margin:0 0 8px;">Tips Pengajar</h3>
                <p style="font-size:12px;color:var(--muted);line-height:1.5;margin:0 0 12px;">
                    Pastikan menandai sesi sebagai <strong>Selesai</strong> setelah pembelajaran usai agar riwayat absensi siswa dan catatan materi tercatat secara akurat di sistem.
                </p>
                <a href="{{ route('tutor.schedules.index') }}" style="font-size:12px;font-weight:700;color:var(--primary);text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                    Buka Matriks Ruang Bimbel →
                </a>
            </section>
        </div>
    </div>
</div>

{{-- DIALOG MODALS UNTUK AKSI AGENDA --}}
{{-- 1. Tandai Selesai --}}
<dialog id="dlg-complete" class="td-dialog">
    <div class="td-modal-head">
        <h3>Tandai Sesi Selesai</h3>
        <button type="button" class="td-modal-close" data-close>&times;</button>
    </div>
    <form method="POST" class="td-modal-body">
        @csrf
        <p data-sub style="color:var(--muted);font-size:12.5px;margin:0 0 12px;font-weight:600;"></p>
        <div class="form-group">
            <label for="td-notes" style="font-size:13px;font-weight:700;display:block;margin-bottom:6px;">Catatan Pembelajaran (Opsional)</label>
            <input type="text" id="td-notes" name="notes" class="form-control" placeholder="Contoh: Bab Eksponen & Logaritma selesai dibahas">
        </div>
        <div class="td-modal-footer">
            <button type="button" class="btn btn-secondary" data-close>Tutup</button>
            <button type="submit" class="btn btn-success">Simpan Selesai</button>
        </div>
    </form>
</dialog>

{{-- 2. Tambah Murid --}}
<dialog id="dlg-assign" class="td-dialog">
    <div class="td-modal-head">
        <h3>Tambah Murid ke Sesi</h3>
        <button type="button" class="td-modal-close" data-close>&times;</button>
    </div>
    <form method="POST" class="td-modal-body">
        @csrf
        <p data-sub style="color:var(--muted);font-size:12.5px;margin:0 0 12px;font-weight:600;"></p>
        <div class="form-group">
            <label for="td-student" style="font-size:13px;font-weight:700;display:block;margin-bottom:6px;">Pilih Siswa</label>
            <select id="td-student" name="student_id" class="form-control" required>
                <option value="">Pilih murid...</option>
                @foreach ($studentsList as $std)
                    <option value="{{ $std->id }}">{{ $std->name }} ({{ $std->school_origin ?? 'Siswa' }})</option>
                @endforeach
            </select>
        </div>
        <div class="td-modal-footer">
            <button type="button" class="btn btn-secondary" data-close>Tutup</button>
            <button type="submit" class="btn btn-primary">Daftarkan Murid</button>
        </div>
    </form>
</dialog>

{{-- 3. Batalkan Slot --}}
<dialog id="dlg-cancel" class="td-dialog">
    <div class="td-modal-head">
        <h3 style="color:var(--pink-text);">Batalkan Slot Mengajar?</h3>
        <button type="button" class="td-modal-close" data-close>&times;</button>
    </div>
    <form method="POST" class="td-modal-body">
        @csrf
        @method('DELETE')
        <p data-sub style="color:var(--muted);font-size:12.5px;margin:0 0 10px;font-weight:600;"></p>
        <p style="font-size:13px;color:var(--text);line-height:1.5;margin:0 0 14px;">
            Apakah Anda yakin ingin membatalkan slot ini? Slot ruangan akan kembali berstatus <strong>Kosong</strong> di matriks ruangan.
        </p>
        <div class="td-modal-footer">
            <button type="button" class="btn btn-secondary" data-close>Batal</button>
            <button type="submit" class="btn btn-danger">Ya, Batalkan Jadwal</button>
        </div>
    </form>
</dialog>

@endsection

@push('scripts')
<script>
    // Tab Filter Interaktif
    document.querySelectorAll('.td-filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.td-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.dataset.filter;
            document.querySelectorAll('#agenda-container .td-agenda-card').forEach(card => {
                if (filter === 'all') {
                    card.style.display = 'block';
                } else if (filter === 'today') {
                    card.style.display = card.dataset.tags.includes('today') ? 'block' : 'none';
                } else if (filter === 'scheduled') {
                    card.style.display = card.dataset.tags.includes('scheduled') ? 'block' : 'none';
                } else if (filter === 'completed') {
                    card.style.display = card.dataset.tags.includes('completed') ? 'block' : 'none';
                }
            });
        });
    });

    // Modal Action Open/Close Handler
    document.querySelectorAll('[data-open]').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.open;
            const dlg = document.getElementById(targetId);
            if (!dlg) return;

            const form = dlg.querySelector('form');
            if (form && this.dataset.action) {
                form.action = this.dataset.action;
            }

            const sub = dlg.querySelector('[data-sub]');
            if (sub && this.dataset.sub) {
                sub.textContent = this.dataset.sub;
            }

            dlg.showModal();
        });
    });

    document.querySelectorAll('[data-close]').forEach(btn => {
        btn.addEventListener('click', function() {
            const dlg = this.closest('dialog');
            if (dlg) dlg.close();
        });
    });

    // Close when clicking outside modal backdrop
    document.querySelectorAll('dialog.td-dialog').forEach(dlg => {
        dlg.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const isInDialog = (rect.top <= e.clientY && e.clientY <= rect.top + rect.height
                && rect.left <= e.clientX && e.clientX <= rect.left + rect.width);
            if (!isInDialog) {
                this.close();
            }
        });
    });
</script>
@endpush
