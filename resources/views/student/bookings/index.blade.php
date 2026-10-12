@extends('layouts.app')

@section('title', 'Katalog Jadwal Belajar')

@push('styles')
<style>
    .sb-wrap {
        max-width: 1260px;
        margin: 0 auto;
    }

    /* Page Header */
    .sb-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .sb-header h1 {
        font: 800 clamp(20px, 2vw, 25px) "Plus Jakarta Sans", sans-serif;
        color: var(--text);
        letter-spacing: -.5px;
        margin: 0 0 4px;
    }

    .sb-header p {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    /* Quota Badge */
    .sb-quota-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 99px;
        font-size: 12.5px;
        font-weight: 700;
        border: 1px solid var(--line);
        background: var(--surface);
        box-shadow: var(--shadow);
    }

    .sb-quota-pill.quota-ok {
        color: var(--mint-text);
        background: var(--mint);
        border-color: color-mix(in srgb, var(--mint-text) 20%, transparent);
    }

    .sb-quota-pill.quota-full {
        color: var(--pink-text);
        background: var(--pink-soft);
        border-color: color-mix(in srgb, var(--pink-text) 25%, transparent);
    }

    /* Top Date Filter Box */
    .sb-filter-card {
        padding: 16px 20px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        margin-bottom: 18px;
    }

    .sb-filter-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }

    .sb-chips-group {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .sb-chips-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-right: 4px;
    }

    /* Pill Buttons for Dates */
    .sb-chip-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 99px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all .16s ease;
        border: 1px solid var(--line);
        background: var(--surface-2);
        color: var(--muted);
        cursor: pointer;
    }

    .sb-chip-btn:hover {
        color: var(--text);
        border-color: var(--primary);
        background: color-mix(in srgb, var(--primary) 8%, var(--surface));
    }

    .sb-chip-btn.active {
        background: var(--primary);
        color: var(--primary-contrast);
        border-color: var(--primary);
        box-shadow: 0 2px 8px color-mix(in srgb, var(--primary) 28%, transparent);
    }

    /* Custom Date Picker Trigger (No dd/mm/yyyy) */
    .sb-date-picker-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .sb-custom-date-trigger {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 14px;
        border-radius: 99px;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid var(--line);
        background: var(--surface-2);
        color: var(--text);
        cursor: pointer;
        transition: all .16s ease;
    }

    .sb-custom-date-trigger:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .sb-custom-date-trigger.has-value {
        background: var(--primary-soft);
        color: var(--primary);
        border-color: color-mix(in srgb, var(--primary) 30%, transparent);
    }

    .sb-hidden-date-input {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        pointer-events: auto;
    }

    .sb-reset-btn {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        border-radius: 99px;
        font-size: 11.5px;
        font-weight: 700;
        color: var(--pink-text);
        background: var(--pink-soft);
        border: 1px solid color-mix(in srgb, var(--pink-text) 25%, transparent);
        text-decoration: none;
        transition: all .15s ease;
    }

    .sb-reset-btn:hover {
        filter: brightness(.95);
    }

    /* Top Active Date Banner */
    .sb-date-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 18px;
        background: var(--surface-2);
        border: 1px solid var(--line);
        border-radius: 12px;
        margin-top: 14px;
        flex-wrap: wrap;
    }

    .sb-date-banner-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sb-date-banner-icon {
        display: grid;
        place-items: center;
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: var(--primary-soft);
        color: var(--primary);
        flex-shrink: 0;
    }

    .sb-date-banner strong {
        display: block;
        font-size: 13.5px;
        color: var(--text);
    }

    .sb-date-banner span {
        font-size: 12px;
        color: var(--muted);
    }

    /* Toolbar Filter Kategori (All / Reguler / Privat) + Tombol Konsultasi */
    .sb-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .sb-cat-tabs {
        display: flex;
        gap: 6px;
        background: var(--surface-2);
        padding: 4px;
        border-radius: 12px;
        border: 1px solid var(--line);
    }

    .sb-cat-tab {
        border: 0;
        background: transparent;
        color: var(--muted);
        padding: 7px 14px;
        border-radius: 9px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all .16s ease;
    }

    .sb-cat-tab:hover {
        color: var(--text);
    }

    .sb-cat-tab.active {
        background: var(--surface);
        color: var(--primary);
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
    }

    /* Tabel Jadwal Utama */
    .sb-table-card {
        padding: 0;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .sb-table {
        width: 100%;
        border-collapse: collapse;
    }

    .sb-table th {
        background: var(--surface-2);
        color: var(--muted);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: 14px 16px;
        border-bottom: 1px solid var(--line);
        text-align: left;
    }

    .sb-table td {
        padding: 16px;
        border-bottom: 1px solid var(--line);
        font-size: 13px;
        color: var(--text);
        vertical-align: middle;
        background: var(--surface);
        transition: background .12s ease;
    }

    .sb-table tr:hover td {
        background: color-mix(in srgb, var(--surface-2) 65%, var(--surface));
    }

    .sb-table tr:last-child td {
        border-bottom: 0;
    }

    /* Format Sesi & Tanggal */
    .sb-time-box {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .sb-date-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 6px;
        background: var(--surface-2);
        color: var(--primary);
        width: fit-content;
        margin-bottom: 2px;
    }

    .sb-time-title {
        font-size: 13.5px;
        font-weight: 800;
        color: var(--text);
    }

    .sb-time-range {
        font-size: 11.5px;
        color: var(--muted);
        font-weight: 600;
    }

    /* Mapel & Tutor */
    .sb-subj-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 2px;
    }

    .sb-tutor-name {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        color: var(--text);
    }

    .sb-tutor-avatar {
        display: grid;
        place-items: center;
        width: 26px;
        height: 26px;
        border-radius: 8px;
        background: var(--primary-soft);
        color: var(--primary);
        font-size: 11px;
        font-weight: 800;
        flex-shrink: 0;
    }

    /* Program & Ruangan */
    .sb-room-label {
        font-size: 11.5px;
        color: var(--muted);
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Kuota Sisa Kursi */
    .sb-seat-info {
        text-align: center;
    }

    .sb-seat-count {
        font-size: 13px;
        font-weight: 800;
        color: var(--mint-text);
        display: block;
    }

    .sb-seat-sub {
        font-size: 11px;
        color: var(--muted);
        display: block;
        margin-top: 1px;
    }

    .sb-seat-bar {
        width: 60px;
        height: 5px;
        background: var(--surface-2);
        border-radius: 99px;
        margin: 5px auto 0;
        overflow: hidden;
    }

    .sb-seat-bar i {
        display: block;
        height: 100%;
        background: var(--primary);
        border-radius: 99px;
    }

    /* Mobile Card Stack (HP <= 680px) */
    .sb-mobile-stack {
        display: none;
    }

    @media (max-width: 680px) {
        .sb-desktop-table {
            display: none !important;
        }

        .sb-mobile-stack {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 14px;
        }

        .sb-m-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 16px;
            box-shadow: var(--shadow);
        }

        .sb-m-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 10px;
        }

        .sb-m-meta-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-size: 12.5px;
            color: var(--muted);
            margin-bottom: 14px;
        }

        .sb-m-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sb-m-actions {
            padding-top: 12px;
            border-top: 1px dashed var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
    }
</style>
@endpush

@section('content')
@php
    $student = Auth::user();
    $todayStr = now()->format('Y-m-d');
    $tomorrowStr = now()->addDay()->format('Y-m-d');
    $dayAfterStr = now()->addDays(2)->format('Y-m-d');
    $selectedDateStr = $filterDate ? $filterDate->format('Y-m-d') : null;
@endphp

<div class="sb-wrap">
    {{-- 1. Page Header --}}
    <div class="sb-header">
        <div>
            <h1>Katalog Jadwal Belajar</h1>
            <p>Pilih sesi bimbingan belajar yang tersedia dan amankan kursi Anda.</p>
        </div>
        <div>
            @if($weeklyBookingCount >= $maxWeekly)
                <div class="sb-quota-pill quota-full">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>Kuota Minggu Ini: <strong>{{ $weeklyBookingCount }} / {{ $maxWeekly }} Penuh</strong></span>
                </div>
            @else
                <div class="sb-quota-pill quota-ok">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>Jadwal Minggu Ini: <strong>{{ $weeklyBookingCount }} / {{ $maxWeekly }} Terbooking</strong></span>
                </div>
            @endif
        </div>
    </div>

    {{-- Alert jika kuota mingguan sudah habis --}}
    @if($weeklyBookingCount >= $maxWeekly)
        <div class="alert alert-error" style="margin-bottom: 18px; display:flex; align-items:center; gap:10px;">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <div>
                <strong>Batas Kuota Tercapai!</strong> Anda sudah memenuhi kuota maksimal {{ $maxWeekly }} jadwal pada minggu ini. Anda dapat meninjau jadwal aktif Anda di <a href="{{ route('student.bookings.my') }}" style="color: inherit; text-decoration: underline; font-weight: bold;">Jadwal Belajar Saya</a>.
            </div>
        </div>
    @endif

    {{-- 2. Filter Tanggal Minimalis (Pill Hari + Custom Date Trigger Elegan) --}}
    <section class="sb-filter-card">
        <div class="sb-filter-row">
            {{-- Quick Date Chips --}}
            <div class="sb-chips-group">
                <span class="sb-chips-label">Pilih Hari:</span>

                {{-- Semua Jadwal --}}
                <a href="{{ route('student.dashboard') }}"
                   class="sb-chip-btn {{ is_null($selectedDateStr) ? 'active' : '' }}">
                    Semua Jadwal
                </a>

                {{-- Hari Ini --}}
                <a href="{{ route('student.dashboard', ['date' => $todayStr]) }}"
                   class="sb-chip-btn {{ $selectedDateStr === $todayStr ? 'active' : '' }}">
                    Hari Ini ({{ now()->isoFormat('D MMM') }})
                </a>

                {{-- Besok --}}
                <a href="{{ route('student.dashboard', ['date' => $tomorrowStr]) }}"
                   class="sb-chip-btn {{ $selectedDateStr === $tomorrowStr ? 'active' : '' }}">
                    Besok ({{ now()->addDay()->isoFormat('D MMM') }})
                </a>

                {{-- Lusa --}}
                <a href="{{ route('student.dashboard', ['date' => $dayAfterStr]) }}"
                   class="sb-chip-btn {{ $selectedDateStr === $dayAfterStr ? 'active' : '' }}">
                    Lusa ({{ now()->addDays(2)->isoFormat('D MMM') }})
                </a>
            </div>

            {{-- Custom Date Picker Trigger (Pengganti dd/mm/yyyy bawaan browser) --}}
            <div class="sb-date-picker-wrap">
                <form id="form-custom-date" method="GET" action="{{ route('student.dashboard') }}" style="margin:0;">
                    <div class="sb-custom-date-trigger {{ $filterDate ? 'has-value' : '' }}">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <span>
                            @if($filterDate)
                                {{ $filterDate->isoFormat('D MMMM Y') }}
                            @else
                                Pilih Tanggal Lain
                            @endif
                        </span>
                        <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" style="opacity:.6">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                        {{-- Hidden Native Picker that triggers transparently on user click --}}
                        <input type="date" name="date" class="sb-hidden-date-input"
                               value="{{ $selectedDateStr ?? '' }}"
                               min="{{ now()->format('Y-m-d') }}"
                               onchange="this.form.submit()"
                               aria-label="Pilih tanggal khusus">
                    </div>
                </form>

                @if($filterDate)
                    <a href="{{ route('student.dashboard') }}" class="sb-reset-btn" title="Kembali ke semua jadwal">
                        <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </div>

        {{-- Banner Tanggal Aktif (Memindahkan info tanggal yang tadinya di dalam tabel ke atas) --}}
        <div class="sb-date-banner">
            <div class="sb-date-banner-left">
                <div class="sb-date-banner-icon">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div>
                    <strong>
                        @if($filterDate)
                            Jadwal untuk: {{ $filterDate->isoFormat('dddd, D MMMM Y') }}
                        @else
                            Semua Jadwal Tersedia (2 Minggu ke Depan)
                        @endif
                    </strong>
                    <span>Ditemukan <strong>{{ $schedules->count() }}</strong> sesi bimbingan aktif</span>
                </div>
            </div>

            @if($filterDate)
                <div>
                    @if($filterDate->isToday())
                        <span class="badge badge-tutor">Hari Ini</span>
                    @elseif($filterDate->isTomorrow())
                        <span class="badge badge-student">Besok</span>
                    @else
                        <span class="badge" style="background:var(--surface);color:var(--text);border:1px solid var(--line);">Terfilter</span>
                    @endif
                </div>
            @endif
        </div>
    </section>

    {{-- 3. Toolbar Kategori & Tombol Konsultasi --}}
    <div class="sb-toolbar">
        <div class="sb-cat-tabs">
            <button type="button" onclick="switchStudentTab('all')" id="btn-std-all" class="sb-cat-tab active">
                Semua ({{ $schedules->count() }})
            </button>
            <button type="button" onclick="switchStudentTab('reguler')" id="btn-std-reguler" class="sb-cat-tab">
                Kelas Reguler ({{ $regularSchedules->count() }})
            </button>
            <button type="button" onclick="switchStudentTab('special')" id="btn-std-special" class="sb-cat-tab">
                Privat &amp; Konsultasi ({{ $specialSchedules->count() }})
            </button>
        </div>

        <button type="button" onclick="openConsultModal()" class="btn-consult" {{ $weeklyBookingCount >= $maxWeekly ? 'disabled' : '' }}>
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Buat Jadwal Konsultasi
        </button>
    </div>

    {{-- 4. Daftar Jadwal Belajar (Desktop Table + Mobile Stack) --}}
    <div class="sb-table-card">
        @if($schedules->isEmpty())
            <div style="text-align: center; padding: 48px 20px; color: var(--muted);">
                <div style="width:48px;height:48px;border-radius:12px;background:var(--surface-2);color:var(--muted);display:grid;place-items:center;margin:0 auto 12px;">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <strong style="font-size: 15px; color: var(--text); display:block; margin-bottom: 4px;">Tidak Ada Slot Jadwal Tersedia</strong>
                <p style="font-size: 13px; margin: 0 0 16px;">Belum ada sesi bimbingan yang dibuka untuk filter tanggal ini.</p>
                <a href="{{ route('student.dashboard') }}" class="btn btn-secondary" style="font-size: 12.5px;">
                    Lihat Semua Jadwal
                </a>
            </div>
        @else
            {{-- DESKTOP TABLE VIEW --}}
            <div class="sb-desktop-table" style="overflow-x: auto;">
                <table class="sb-table">
                    <thead>
                        <tr>
                            <th style="width: 22%;">Sesi &amp; Tanggal</th>
                            <th style="width: 24%;">Mata Pelajaran</th>
                            <th style="width: 20%;">Pengajar (Tutor)</th>
                            <th style="width: 16%;">Program &amp; Ruangan</th>
                            <th style="width: 10%; text-align: center;">Sisa Kursi</th>
                            <th style="width: 8%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="student-schedules-tbody">
                        @foreach($schedules as $s)
                            @php
                                $isReguler = $s->program->code === 'reguler';
                                $rowCat = $isReguler ? 'row-reguler' : 'row-special';
                                $scheduleDate = \Carbon\Carbon::parse($s->date);
                                $fillPercent = $s->quota_max > 0 ? min(100, round(($s->booked_count / $s->quota_max) * 100)) : 0;
                                $tutorInitial = mb_substr($s->tutor->name, 0, 1);
                            @endphp
                            <tr class="student-schedule-row {{ $rowCat }}">
                                {{-- Kolom 1: Sesi & Tanggal --}}
                                <td>
                                    <div class="sb-time-box">
                                        <span class="sb-date-tag">
                                            <svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                            {{ $scheduleDate->isoFormat('D MMM Y') }}
                                        </span>
                                        <strong class="sb-time-title">{{ $s->timeSlot->name }}</strong>
                                        <small class="sb-time-range">{{ substr($s->timeSlot->start_time, 0, 5) }} &ndash; {{ substr($s->timeSlot->end_time, 0, 5) }} WIB</small>
                                    </div>
                                </td>

                                {{-- Kolom 2: Mata Pelajaran --}}
                                <td>
                                    <div class="sb-subj-title">{{ $s->subject->name }}</div>
                                    <small style="color:var(--muted);font-size:11.5px;">{{ $s->educationLevel->name ?? 'Semua Jenjang' }}</small>
                                </td>

                                {{-- Kolom 3: Pengajar / Tutor --}}
                                <td>
                                    <div class="sb-tutor-name">
                                        <div class="sb-tutor-avatar">{{ $tutorInitial }}</div>
                                        <span>{{ $s->tutor->name }}</span>
                                    </div>
                                </td>

                                {{-- Kolom 4: Program & Ruangan --}}
                                <td>
                                    @if($s->program->code === 'reguler')
                                        <span class="badge" style="background:var(--mint);color:var(--mint-text);font-weight:700;">Reguler</span>
                                    @elseif($s->program->code === 'consult')
                                        <span class="badge" style="background:var(--accent-gold-soft);color:var(--accent-gold-text);font-weight:700;">Konsultasi</span>
                                    @elseif($s->program->code === 'private_home')
                                        <span class="badge" style="background:var(--accent-terra-soft);color:var(--accent-terra-text);font-weight:700;">Privat Rumah</span>
                                    @else
                                        <span class="badge" style="background:var(--primary-soft);color:var(--primary);font-weight:700;">Privat Bimbel</span>
                                    @endif
                                    <div class="sb-room-label">
                                        <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        {{ $s->room->name }}
                                    </div>
                                </td>

                                {{-- Kolom 5: Sisa Kursi --}}
                                <td>
                                    <div class="sb-seat-info">
                                        <strong class="sb-seat-count">{{ $s->remaining_seats }} kursi lagi</strong>
                                        <small class="sb-seat-sub">dari kuota {{ $s->quota_max }}</small>
                                        <div class="sb-seat-bar">
                                            <i style="width: {{ $fillPercent }}%"></i>
                                        </div>
                                    </div>
                                </td>

                                {{-- Kolom 6: Aksi Pesan Kursi --}}
                                <td style="text-align: center;">
                                    <form action="{{ route('student.bookings.store') }}" method="POST" style="margin:0;">
                                        @csrf
                                        <input type="hidden" name="schedule_id" value="{{ $s->id }}">
                                        <button type="submit" class="btn btn-primary" style="padding: 8px 14px; font-size: 12.5px; white-space: nowrap;" {{ $weeklyBookingCount >= $maxWeekly ? 'disabled' : '' }}>
                                            Pesan Kursi
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- MOBILE CARD STACK VIEW (Layar HP <= 680px) --}}
            <div class="sb-mobile-stack">
                @foreach($schedules as $s)
                    @php
                        $isReguler = $s->program->code === 'reguler';
                        $rowCat = $isReguler ? 'row-reguler' : 'row-special';
                        $scheduleDate = \Carbon\Carbon::parse($s->date);
                        $tutorInitial = mb_substr($s->tutor->name, 0, 1);
                    @endphp
                    <div class="sb-m-card student-schedule-row {{ $rowCat }}">
                        <div class="sb-m-head">
                            <div>
                                <span class="sb-date-tag">
                                    {{ $scheduleDate->isoFormat('D MMM Y') }}
                                </span>
                                <h3 style="font-size:15px;font-weight:800;color:var(--text);margin:4px 0 0;">
                                    {{ $s->subject->name }}
                                </h3>
                            </div>
                            @if($s->program->code === 'reguler')
                                <span class="badge" style="background:var(--mint);color:var(--mint-text);font-weight:700;">Reguler</span>
                            @else
                                <span class="badge" style="background:var(--accent-gold-soft);color:var(--accent-gold-text);font-weight:700;">{{ $s->program->name }}</span>
                            @endif
                        </div>

                        <div class="sb-m-meta-list">
                            <div class="sb-m-meta-item">
                                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span>{{ $s->timeSlot->name }} ({{ substr($s->timeSlot->start_time, 0, 5) }} &ndash; {{ substr($s->timeSlot->end_time, 0, 5) }} WIB)</span>
                            </div>
                            <div class="sb-m-meta-item">
                                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                <span>Tutor: <strong>{{ $s->tutor->name }}</strong></span>
                            </div>
                            <div class="sb-m-meta-item">
                                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span>Ruangan: <strong>{{ $s->room->name }}</strong></span>
                            </div>
                        </div>

                        <div class="sb-m-actions">
                            <div>
                                <strong style="color:var(--mint-text);font-size:12.5px;">{{ $s->remaining_seats }} kursi lagi</strong>
                                <small style="display:block;color:var(--muted);font-size:11px;">Kuota: {{ $s->quota_max }}</small>
                            </div>
                            <form action="{{ route('student.bookings.store') }}" method="POST" style="margin:0;">
                                @csrf
                                <input type="hidden" name="schedule_id" value="{{ $s->id }}">
                                <button type="submit" class="btn btn-primary" style="padding:8px 16px;font-size:12px;" {{ $weeklyBookingCount >= $maxWeekly ? 'disabled' : '' }}>
                                    Pesan Kursi
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- MODAL BUAT JADWAL KONSULTASI MANDIRI --}}
<div id="consult-modal-overlay"
     onclick="closeConsultModal(event)"
     style="display: none; position: fixed; inset: 0; background: rgba(11,22,20,0.65); backdrop-filter: blur(4px); z-index: 9000; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: var(--surface); border: 1px solid var(--line); border-radius: 18px; width: 100%; max-width: 540px; box-shadow: 0 24px 60px rgba(0,0,0,0.3); overflow: hidden;"
         onclick="event.stopPropagation()">

        {{-- Header Modal --}}
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-bottom: 1px solid var(--line); background: var(--surface-2);">
            <div>
                <h2 style="font-size: 16px; font-weight: 800; color: var(--text); margin: 0;">Buat Jadwal Konsultasi</h2>
                <p style="font-size: 12px; color: var(--muted); margin: 3px 0 0;">Sesi privat 1-on-1 dengan tutor. Pembatalan berlaku minimal H-1.</p>
            </div>
            <button onclick="closeConsultModal()" type="button"
                    style="background: none; border: none; font-size: 22px; color: var(--muted); cursor: pointer; line-height: 1; padding: 0 4px;" aria-label="Tutup modal">&times;</button>
        </div>

        {{-- Body Modal --}}
        <div style="padding: 22px 24px;">
            @if($weeklyBookingCount >= $maxWeekly)
                <div class="alert alert-error" style="border-radius: 10px; font-size: 13px;">
                    <strong>Kuota mingguan sudah penuh.</strong> Anda tidak dapat membuat jadwal konsultasi baru pada minggu ini.
                </div>
            @else
                <form action="{{ route('student.consultations.store') }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">

                        {{-- Tanggal --}}
                        <div>
                            <label for="consult_date" style="display: block; font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 6px;">
                                Tanggal <span style="color: var(--pink-text);">*</span>
                            </label>
                            <input type="date" id="consult_date" name="date" class="form-control"
                                   min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                                   value="{{ old('date') }}"
                                   required>
                            @error('date')<p style="color: var(--pink-text); font-size: 11px; margin: 3px 0 0;">{{ $message }}</p>@enderror
                        </div>

                        {{-- Sesi --}}
                        <div>
                            <label for="consult_time_slot" style="display: block; font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 6px;">
                                Sesi / Jam <span style="color: var(--pink-text);">*</span>
                            </label>
                            <select id="consult_time_slot" name="time_slot_id" class="form-control" required>
                                <option value="">-- Pilih Sesi --</option>
                                @foreach($timeSlots as $ts)
                                    <option value="{{ $ts->id }}" {{ old('time_slot_id') == $ts->id ? 'selected' : '' }}>
                                        {{ $ts->name }} ({{ substr($ts->start_time, 0, 5) }} &ndash; {{ substr($ts->end_time, 0, 5) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('time_slot_id')<p style="color: var(--pink-text); font-size: 11px; margin: 3px 0 0;">{{ $message }}</p>@enderror
                        </div>

                        {{-- Mata Pelajaran --}}
                        <div>
                            <label for="consult_subject" style="display: block; font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 6px;">
                                Mata Pelajaran <span style="color: var(--pink-text);">*</span>
                            </label>
                            <select id="consult_subject" name="subject_id" class="form-control"
                                    onchange="filterTutors(this.value)" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($subjects as $subj)
                                    <option value="{{ $subj->id }}" {{ old('subject_id') == $subj->id ? 'selected' : '' }}>
                                        {{ $subj->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')<p style="color: var(--pink-text); font-size: 11px; margin: 3px 0 0;">{{ $message }}</p>@enderror
                        </div>

                        {{-- Pengajar --}}
                        <div>
                            <label for="consult_tutor" style="display: block; font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 6px;">
                                Pengajar / Tutor <span style="color: var(--pink-text);">*</span>
                            </label>
                            <select id="consult_tutor" name="tutor_id" class="form-control" required>
                                <option value="">-- Pilih mapel dulu --</option>
                            </select>
                            @error('tutor_id')<p style="color: var(--pink-text); font-size: 11px; margin: 3px 0 0;">{{ $message }}</p>@enderror
                        </div>

                        {{-- Catatan Topik --}}
                        <div style="grid-column: 1 / -1;">
                            <label for="consult_notes" style="display: block; font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 6px;">
                                Catatan / Materi Bimbingan
                            </label>
                            <textarea id="consult_notes" name="notes" rows="3" maxlength="500" class="form-control"
                                      placeholder="Contoh: Pembahasan soal latihan Bab 3 Kalkulus / Trigonometri..."
                                      style="resize: vertical;">{{ old('notes') }}</textarea>
                            @error('notes')<p style="color: var(--pink-text); font-size: 11px; margin: 3px 0 0;">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px; border-top: 1px solid var(--line); padding-top: 16px; flex-wrap: wrap; gap: 10px;">
                        <small style="color: var(--muted); font-size: 11.5px;">Pembatalan jadwal hanya bisa dilakukan <strong>minimal H-1</strong>.</small>
                        <div style="display: flex; gap: 8px;">
                            <button type="button" onclick="closeConsultModal()" class="btn btn-secondary" style="padding: 8px 16px; font-size: 13px;">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary" style="padding: 8px 20px; font-size: 13px;">
                                Ajukan Konsultasi
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

@php
    $subjectTutorMap = $subjects->mapWithKeys(function ($s) {
        return [$s->id => [
            'id'     => $s->id,
            'tutors' => $s->tutors->map(function ($t) {
                            return ['id' => $t->id, 'name' => $t->name];
                        })->values(),
        ]];
    });
@endphp

@push('scripts')
<script>
const subjectTutors = @json($subjectTutorMap);

function filterTutors(subjectId) {
    const select = document.getElementById('consult_tutor');
    select.innerHTML = '<option value="">-- Pilih Tutor --</option>';
    if (! subjectId || ! subjectTutors[subjectId]) { return; }
    const tutors = subjectTutors[subjectId].tutors;
    if (tutors.length === 0) {
        select.innerHTML = '<option value="">Tidak ada tutor untuk mapel ini</option>';
        return;
    }
    tutors.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t.id;
        opt.textContent = t.name;
        select.appendChild(opt);
    });
}

function openConsultModal() {
    const overlay = document.getElementById('consult-modal-overlay');
    overlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeConsultModal(event) {
    if (event && event.target !== document.getElementById('consult-modal-overlay') && event.type !== 'click') { return; }
    const overlay = document.getElementById('consult-modal-overlay');
    overlay.style.display = 'none';
    document.body.style.overflow = '';
}

function switchStudentTab(cat) {
    const rows = document.querySelectorAll('.student-schedule-row');
    const btnAll = document.getElementById('btn-std-all');
    const btnReguler = document.getElementById('btn-std-reguler');
    const btnSpecial = document.getElementById('btn-std-special');

    [btnAll, btnReguler, btnSpecial].forEach(b => {
        b.classList.remove('active');
    });

    if (cat === 'all') {
        btnAll.classList.add('active');
        rows.forEach(r => r.style.display = '');
    } else if (cat === 'reguler') {
        btnReguler.classList.add('active');
        rows.forEach(r => {
            r.style.display = r.classList.contains('row-reguler') ? '' : 'none';
        });
    } else if (cat === 'special') {
        btnSpecial.classList.add('active');
        rows.forEach(r => {
            r.style.display = r.classList.contains('row-special') ? '' : 'none';
        });
    }
}

// Buka modal otomatis jika ada validation error dari form konsultasi
@if($errors->has('date') || $errors->has('time_slot_id') || $errors->has('subject_id') || $errors->has('tutor_id') || $errors->has('notes'))
    document.addEventListener('DOMContentLoaded', () => openConsultModal());
    @if(old('subject_id'))
        document.addEventListener('DOMContentLoaded', () => filterTutors('{{ old('subject_id') }}'));
    @endif
@endif
</script>
@endpush
@endsection
