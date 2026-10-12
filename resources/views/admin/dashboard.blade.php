@extends('layouts.app')

@section('title', 'Dashboard Ringkasan')

@push('styles')
<style>
    /* Navigator Periode Modern & Minimalis */
    .period-navigator {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 12px;
        padding: 4px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
    }

    .period-nav-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid transparent;
        background: transparent;
        color: var(--muted);
        text-decoration: none;
        transition: all .15s ease;
    }

    .period-nav-btn:hover {
        background: var(--surface-2);
        color: var(--text);
        border-color: var(--line);
    }

    .period-nav-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0 12px;
        font: 700 13px/1 'Plus Jakarta Sans', sans-serif;
        color: var(--text);
        letter-spacing: -0.2px;
    }

    .period-nav-label svg {
        color: var(--muted);
        flex-shrink: 0;
    }

    .period-nav-today-badge {
        font-size: 10.5px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        background: var(--primary-soft);
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .period-nav-quick-today {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        color: var(--text);
        background: var(--surface-2);
        border: 1px solid var(--line);
        text-decoration: none;
        transition: all .15s ease;
        margin-left: 4px;
    }

    .period-nav-quick-today:hover {
        background: var(--primary);
        color: var(--primary-contrast);
        border-color: var(--primary);
    }

    /* Selector Hari dalam Minggu */
    .day-selector-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
        margin-bottom: 20px;
    }

    .day-selector-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 10px 6px;
        border-radius: 12px;
        text-decoration: none;
        border: 1px solid var(--line);
        background: var(--surface);
        color: var(--text);
        transition: all .15s ease;
    }

    .day-selector-card:hover {
        background: var(--surface-2);
        border-color: color-mix(in srgb, var(--primary) 40%, transparent);
    }

    .day-selector-card.is-active {
        background: var(--primary);
        border-color: var(--primary);
        color: var(--primary-contrast);
        box-shadow: 0 4px 14px color-mix(in srgb, var(--primary) 25%, transparent);
    }

    .day-selector-card.is-active .day-card-name {
        opacity: 0.85;
        color: var(--primary-contrast);
    }

    .day-selector-card.is-active .day-card-badge {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
    }

    .day-card-name {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--muted);
    }

    .day-card-num {
        font: 800 14px 'Plus Jakarta Sans', sans-serif;
        margin: 2px 0 3px;
    }

    .day-card-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 1px 7px;
        border-radius: 6px;
        background: var(--surface-2);
        color: var(--muted);
        transition: all .15s ease;
    }

    /* Matriks Okupansi Grid Konsisten */
    .matrix-table {
        table-layout: fixed;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 12.5px;
    }

    .matrix-th-room {
        width: 150px;
        min-width: 150px;
        max-width: 150px;
        padding: 10px 14px;
        text-align: left;
        color: var(--muted);
        font-weight: 700;
        background: var(--surface-2);
        border-bottom: 1px solid var(--line);
        box-sizing: border-box;
    }

    .matrix-th-slot {
        min-width: 135px;
        padding: 10px 8px;
        text-align: center;
        border-left: 1px solid var(--line);
        border-bottom: 1px solid var(--line);
        background: var(--surface-2);
        color: var(--text);
        box-sizing: border-box;
    }

    .matrix-row {
        height: 84px;
    }

    .matrix-cell-room {
        width: 150px;
        min-width: 150px;
        max-width: 150px;
        height: 84px;
        padding: 8px 14px;
        background: var(--surface);
        border-bottom: 1px solid var(--line);
        vertical-align: middle;
        box-sizing: border-box;
    }

    .matrix-cell-slot {
        height: 84px;
        min-width: 135px;
        padding: 6px;
        text-align: center;
        vertical-align: middle;
        border-left: 1px solid var(--line);
        border-bottom: 1px solid var(--line);
        box-sizing: border-box;
    }

    .matrix-card {
        height: 72px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 6px 8px;
        border-radius: 8px;
        border: 1px solid #fca5a5;
        background: #ffffff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        text-align: left;
        overflow: hidden;
    }

    .matrix-card-title {
        font-size: 11.5px;
        font-weight: 800;
        color: #991b1b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.2;
    }

    .matrix-card-tutor {
        font-size: 10.5px;
        color: #7f1d1d;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.2;
        margin-top: 2px;
    }

    .matrix-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        font-size: 10px;
    }

    .matrix-empty-slot {
        height: 72px;
        box-sizing: border-box;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px dashed rgba(34, 197, 94, 0.35);
        background: rgba(34, 197, 94, 0.04);
        color: #166534;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.2px;
        transition: all .15s ease;
    }

    .matrix-empty-slot:hover {
        background: rgba(34, 197, 94, 0.08);
        border-color: rgba(34, 197, 94, 0.6);
    }
</style>
@endpush

@section('content')
<div class="page-header" style="flex-wrap: wrap; gap: 16px;">
    <div>
        <h1>Dashboard Ringkasan Mingguan</h1>
        <p>Pantau metrik operasional per minggu dan matriks okupansi ruangan harian.</p>
    </div>

    {{-- Navigasi Periode Minggu Bersih & Modern (Tanpa Emote) --}}
    <div class="period-navigator">
        <a href="{{ route('admin.dashboard', ['date' => $prevWeekDate]) }}" 
           class="period-nav-btn" 
           title="Minggu Sebelumnya"
           aria-label="Minggu Sebelumnya">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </a>

        <div class="period-nav-label">
            <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span>{{ $startOfWeek->isoFormat('D MMM') }} &ndash; {{ $endOfWeek->isoFormat('D MMM Y') }}</span>
            @if($isCurrentWeek)
                <span class="period-nav-today-badge">Minggu Ini</span>
            @endif
        </div>

        <a href="{{ route('admin.dashboard', ['date' => $nextWeekDate]) }}" 
           class="period-nav-btn" 
           title="Minggu Berikutnya"
           aria-label="Minggu Berikutnya">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </a>

        @if(! $selectedDate->isToday())
            <a href="{{ route('admin.dashboard', ['date' => date('Y-m-d')]) }}" 
               class="period-nav-quick-today">
                Hari Ini
            </a>
        @endif
    </div>
</div>

<!-- 1. KARTU RINGKASAN METRIK MINGGUAN -->
<div class="stats-grid" style="margin-bottom: 24px;">
    {{-- Jadwal Minggu Ini --}}
    <div class="card" style="margin: 0;">
        <span style="font-size: 12px; color: var(--muted); text-transform: uppercase; font-weight: 700;">Jadwal Minggu Ini</span>
        <h3 style="font: 800 28px 'Plus Jakarta Sans'; margin: 4px 0 2px; color: var(--text);">{{ $schedulesWeeklyCount }}</h3>
        <small style="color: var(--muted); font-size: 12px;">Slot sesi bimbingan</small>
    </div>

    {{-- Booking Kursi Minggu Ini --}}
    <div class="card" style="margin: 0;">
        <span style="font-size: 12px; color: var(--muted); text-transform: uppercase; font-weight: 700;">Booking Siswa Minggu Ini</span>
        <h3 style="font: 800 28px 'Plus Jakarta Sans'; margin: 4px 0 2px; color: var(--text);">{{ $bookingsWeeklyCount }}</h3>
        <small style="color: var(--muted); font-size: 12px;">dari total {{ $totalSeatsWeekly }} kuota kursi</small>
    </div>

    {{-- Okupansi Kursi Mingguan --}}
    <div class="card" style="margin: 0;">
        <span style="font-size: 12px; color: var(--muted); text-transform: uppercase; font-weight: 700;">Okupansi Kursi</span>
        <h3 style="font: 800 28px 'Plus Jakarta Sans'; margin: 4px 0 2px; color: {{ $occupancyWeeklyRate >= 70 ? 'var(--mint-text)' : 'var(--primary)' }};">
            {{ $occupancyWeeklyRate }}%
        </h3>
        <div style="width: 100%; height: 5px; background: var(--surface-2); border-radius: 99px; overflow: hidden; margin-top: 6px;">
            <div style="width: {{ min(100, $occupancyWeeklyRate) }}%; height: 100%; background: var(--mint-text); border-radius: 99px;"></div>
        </div>
    </div>

    {{-- Total Pengajar & Murid --}}
    <div class="card" style="margin: 0;">
        <span style="font-size: 12px; color: var(--muted); text-transform: uppercase; font-weight: 700;">Basis Pengguna</span>
        <div style="display: flex; gap: 16px; align-items: baseline; margin-top: 4px;">
            <div>
                <h3 style="font: 800 24px 'Plus Jakarta Sans'; margin: 0; color: var(--text);">{{ $totalTutors }}</h3>
                <small style="color: var(--muted); font-size: 11px;">Tutor</small>
            </div>
            <span style="color: var(--line); font-size: 20px;">|</span>
            <div>
                <h3 style="font: 800 24px 'Plus Jakarta Sans'; margin: 0; color: var(--text);">{{ $totalStudents }}</h3>
                <small style="color: var(--muted); font-size: 11px;">Siswa</small>
            </div>
        </div>
    </div>
</div>

<!-- 2. MATRIKS OKUPANSI RUANGAN DENGAN FILTER TANGGAL -->
<div class="card" style="padding: 22px; margin-bottom: 24px;">
    {{-- Header Section & Filter Tanggal Harian --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; flex-wrap: wrap; gap: 14px;">
        <div>
            <h3 style="font: 800 18px 'Plus Jakarta Sans'; margin: 0; color: var(--text);">
                Matriks Okupansi 8 Ruangan
            </h3>
            <p style="margin: 3px 0 0; font-size: 13px; color: var(--muted);">
                Jadwal pemakaian ruangan untuk: <strong style="color: var(--primary);">{{ $selectedDate->isoFormat('dddd, D MMMM Y') }}</strong>
                @if($selectedDate->isToday())
                    <span class="badge" style="background: var(--mint); color: var(--mint-text); margin-left: 4px; font-size: 10px;">Hari Ini</span>
                @endif
            </p>
        </div>

        {{-- Form Pemilih Tanggal Bebas --}}
        <form action="{{ route('admin.dashboard') }}" method="GET" style="display: flex; gap: 6px; align-items: center;">
            <input type="date" name="date" value="{{ $selectedDate->toDateString() }}" 
                   class="form-control" style="padding: 6px 10px; font-size: 13px; width: auto;">
            <button type="submit" class="btn btn-secondary" style="padding: 6px 12px; font-size: 13px;">
                Lihat Tanggal
            </button>
        </form>
    </div>

    {{-- FILTER TANGGAL: PILIH HARI DALAM MINGGU INI (Senin - Minggu) --}}
    <div class="day-selector-grid">
        @foreach($daysInWeek as $d)
            <a href="{{ route('admin.dashboard', ['date' => $d['date']]) }}"
               class="day-selector-card {{ $d['is_selected'] ? 'is-active' : '' }}">
                <span class="day-card-name">
                    {{ $d['day_short'] }}
                </span>
                <strong class="day-card-num">
                    {{ $d['day_num'] }}
                </strong>
                <span class="day-card-badge">
                    {{ $d['schedules_count'] }} sesi
                </span>
            </a>
        @endforeach
    </div>

    {{-- Keterangan Legenda Bersih --}}
    <div style="display: flex; justify-content: flex-end; align-items: center; gap: 16px; margin-bottom: 12px; font-size: 12px;">
        <span style="display: flex; align-items: center; gap: 6px;">
            <span style="display: inline-block; width: 10px; height: 10px; background: #22c55e; border-radius: 50%;"></span>
            <span style="color: var(--muted);">Ruangan Kosong</span>
        </span>
        <span style="display: flex; align-items: center; gap: 6px;">
            <span style="display: inline-block; width: 10px; height: 10px; background: #ef4444; border-radius: 50%;"></span>
            <span style="color: var(--muted);">Ruangan Terpakai</span>
        </span>
    </div>

    {{-- Tabel Matriks Okupansi (Ruangan x Sesi) --}}
    <div style="overflow-x: auto; border: 1px solid var(--line); border-radius: 12px;">
        <table class="matrix-table" style="min-width: {{ 150 + count($timeSlots) * 135 }}px;">
            <colgroup>
                <col style="width: 150px;">
                @foreach($timeSlots as $slot)
                    <col style="width: calc((100% - 150px) / {{ count($timeSlots) }}); min-width: 135px;">
                @endforeach
            </colgroup>
            <thead>
                <tr>
                    <th class="matrix-th-room">
                        Ruang &amp; Kapasitas
                    </th>
                    @foreach($timeSlots as $slot)
                        <th class="matrix-th-slot">
                            <div style="font-weight: 700; font-size: 12px;">{{ $slot->name }}</div>
                            <small style="font-weight: 500; color: var(--muted); font-size: 10.5px; display: block; margin-top: 1px;">
                                {{ substr($slot->start_time, 0, 5) }} &ndash; {{ substr($slot->end_time, 0, 5) }}
                            </small>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($rooms as $room)
                    <tr class="matrix-row">
                        <td class="matrix-cell-room">
                            <strong style="color: var(--text); font-size: 13px; display: block; line-height: 1.2;">{{ $room->code }}</strong>
                            <small style="display: block; color: var(--muted); font-size: 11px; line-height: 1.3; margin-top: 1px;">{{ $room->name }}</small>
                            <span class="badge" style="background: var(--surface-2); color: var(--muted); font-size: 10px; margin-top: 4px; padding: 1px 6px; display: inline-block;">
                                Maks {{ $room->capacity }} org
                            </span>
                        </td>
                        @foreach($timeSlots as $slot)
                            @php
                                $sched = $occupancyMatrix[$room->id][$slot->id] ?? null;
                            @endphp
                            <td class="matrix-cell-slot" style="background: {{ $sched ? '#fee2e2' : '#f0fdf4' }};">
                                @if($sched)
                                    <div class="matrix-card">
                                        <div>
                                            <div class="matrix-card-title" title="{{ $sched->subject->name }}">
                                                {{ $sched->subject->name }}
                                            </div>
                                            <div class="matrix-card-tutor" title="{{ $sched->tutor->name }}">
                                                Tutor: {{ $sched->tutor->name }}
                                            </div>
                                        </div>
                                        <div class="matrix-card-footer">
                                            <span style="background: #e0e7ff; color: #3730a3; padding: 1px 5px; border-radius: 4px; font-weight: 700; font-size: 9.5px;">
                                                {{ $sched->program->name }}
                                            </span>
                                            <span style="color: #64748b; font-weight: 700; font-size: 10px;">
                                                {{ $sched->booked_count }}/{{ $sched->quota_max }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="matrix-empty-slot">
                                        Kosong
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- 3. DAFTAR SESI JADWAL PADA TANGGAL TERPILIH -->
<div class="card" style="padding: 22px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
        <div>
            <h3 style="font: 800 16px 'Plus Jakarta Sans'; margin: 0; color: var(--text);">
                Daftar Sesi Jadwal ({{ $selectedDate->isoFormat('D MMMM Y') }})
            </h3>
            <small style="color: var(--muted);">Daftar seluruh bimbingan yang terjadwal pada tanggal terpilih</small>
        </div>
        <a href="{{ route('admin.schedules.index', ['date' => $selectedDate->toDateString()]) }}" 
           class="btn btn-secondary" style="font-size: 12px; padding: 6px 12px;">
            Buka Kelola Jadwal &rsaquo;
        </a>
    </div>

    @if($schedulesList->isEmpty())
        <div style="text-align: center; padding: 32px 16px; color: var(--muted);">
            <p style="font-size: 14px; font-weight: 600; margin: 0 0 4px;">Belum ada sesi jadwal yang terdaftar pada tanggal ini.</p>
            <p style="font-size: 12px; margin: 0;">Silakan pilih tanggal lain di atas atau buat slot baru di menu Kelola Jadwal.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; font-size: 13px;">
                <thead>
                    <tr>
                        <th>Sesi &amp; Waktu</th>
                        <th>Mata Pelajaran</th>
                        <th>Pengajar</th>
                        <th>Program &amp; Ruangan</th>
                        <th style="text-align: center;">Keterisian Kursi</th>
                        <th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedulesList as $s)
                        <tr>
                            <td>
                                <strong>{{ $s->timeSlot->name }}</strong>
                                <small style="display: block; color: var(--muted); font-size: 11px;">
                                    {{ substr($s->timeSlot->start_time, 0, 5) }} &ndash; {{ substr($s->timeSlot->end_time, 0, 5) }}
                                </small>
                            </td>
                            <td>
                                <strong style="color: var(--primary);">{{ $s->subject->name }}</strong>
                            </td>
                            <td style="font-weight: 600;">
                                {{ $s->tutor->name }}
                            </td>
                            <td>
                                <span class="badge" style="background: var(--surface-2); color: var(--text);">{{ $s->room->name }}</span>
                                <span class="badge" style="background: #e0e7ff; color: #4338ca; margin-left: 4px;">{{ $s->program->name }}</span>
                            </td>
                            <td style="text-align: center;">
                                <strong>{{ $s->booked_count }}</strong> / {{ $s->quota_max }} kursi
                            </td>
                            <td style="text-align: center;">
                                @if($s->status === 'completed')
                                    <span class="badge" style="background: var(--mint); color: var(--mint-text);">Selesai</span>
                                @elseif($s->status === 'cancelled')
                                    <span class="badge" style="background: var(--pink-soft); color: var(--pink-text);">Batal</span>
                                @else
                                    <span class="badge" style="background: var(--blue-soft); color: var(--blue-text);">Aktif</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
