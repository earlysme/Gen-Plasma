@extends('layouts.app')

@section('title', 'Statistik & Laporan Mingguan')

@push('styles')
<style>
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
</style>
@endpush

@section('content')
<div class="page-header" style="flex-wrap: wrap; gap: 16px;">
    <div>
        <h1>Statistik &amp; Laporan Mingguan</h1>
        <p>Analisis komprehensif performa jadwal, tren booking murid, dan utilisasi ruangan.</p>
    </div>

    {{-- Navigasi Periode Minggu --}}
    <div class="period-navigator">
        <a href="{{ route('admin.statistics.index', ['week' => $prevWeek]) }}" 
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

        <a href="{{ route('admin.statistics.index', ['week' => $nextWeek]) }}" 
           class="period-nav-btn" 
           title="Minggu Berikutnya"
           aria-label="Minggu Berikutnya">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </a>

        @if(! $isCurrentWeek)
            <a href="{{ route('admin.statistics.index', ['week' => $currentWeek]) }}" 
               class="period-nav-quick-today">
                Ke Minggu Ini
            </a>
        @endif
    </div>
</div>

{{-- 1. KARTU KPI UTAMA MINGGUAN --}}
<div class="stats-grid" style="margin-bottom: 24px;">
    {{-- Total Jadwal Sesi --}}
    <div class="card" style="margin: 0; position: relative; overflow: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <span style="font-size: 12px; color: var(--muted); text-transform: uppercase; font-weight: 700;">Total Slot Sesi</span>
                <h3 style="font: 800 30px 'Plus Jakarta Sans'; margin: 4px 0 6px; color: var(--text);">{{ $totalSchedules }}</h3>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 10px; background: var(--blue-soft); color: var(--blue-text); display: grid; place-items: center;">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
        </div>
        <div style="display: flex; gap: 8px; font-size: 11.5px; font-weight: 600; flex-wrap: wrap;">
            <span style="color: var(--primary);">{{ $activeSchedules }} aktif</span> &bull;
            <span style="color: var(--mint-text);">{{ $completedSchedules }} selesai</span> &bull;
            <span style="color: var(--pink-text);">{{ $cancelledSchedules }} batal</span>
        </div>
    </div>

    {{-- Total Booking Kursi --}}
    <div class="card" style="margin: 0; position: relative; overflow: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <span style="font-size: 12px; color: var(--muted); text-transform: uppercase; font-weight: 700;">Siswa Terdaftar (Kursi)</span>
                <h3 style="font: 800 30px 'Plus Jakarta Sans'; margin: 4px 0 6px; color: var(--text);">{{ $totalSeatsBooked }}</h3>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 10px; background: var(--primary-soft); color: var(--primary); display: grid; place-items: center;">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
        </div>
        <div style="font-size: 12px; color: var(--muted);">
            dari total kuota <strong>{{ $totalSeatsAvailable }}</strong> kursi tersedia
        </div>
    </div>

    {{-- Rasio Keterisian Kursi (Occupancy Rate) --}}
    <div class="card" style="margin: 0;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <span style="font-size: 12px; color: var(--muted); text-transform: uppercase; font-weight: 700;">Okupansi Kursi</span>
                <h3 style="font: 800 30px 'Plus Jakarta Sans'; margin: 4px 0 6px; color: {{ $seatOccupancyRate >= 70 ? 'var(--mint-text)' : 'var(--primary)' }};">
                    {{ $seatOccupancyRate }}%
                </h3>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 10px; background: var(--mint); color: var(--mint-text); display: grid; place-items: center;">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
            </div>
        </div>
        <div style="width: 100%; height: 6px; background: var(--surface-2); border-radius: 99px; overflow: hidden; margin-top: 4px;">
            <div style="width: {{ min(100, $seatOccupancyRate) }}%; height: 100%; background: var(--mint-text); border-radius: 99px;"></div>
        </div>
    </div>

    {{-- Utilisasi Ruangan Fisik --}}
    <div class="card" style="margin: 0;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <span style="font-size: 12px; color: var(--muted); text-transform: uppercase; font-weight: 700;">Utilisasi Ruangan Fisik</span>
                <h3 style="font: 800 30px 'Plus Jakarta Sans'; margin: 4px 0 6px; color: var(--text);">
                    {{ $roomUtilizationRate }}%
                </h3>
            </div>
            <div style="width: 38px; height: 38px; border-radius: 10px; background: var(--peach); color: var(--peach-text); display: grid; place-items: center;">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </div>
        </div>
        <div style="font-size: 12px; color: var(--muted);">
            <strong>{{ $usedPhysicalSlots }}</strong> slot terpakai dari {{ $physicalRoomCount }} ruang fisik
        </div>
    </div>
</div>

{{-- 2. TREN AKTIVITAS HARIAN (SENIN - MINGGU) --}}
<div class="card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
        <div>
            <h3 style="font: 800 17px 'Plus Jakarta Sans'; margin: 0; color: var(--text);">Grafik &amp; Tren Aktivitas Harian</h3>
            <small style="color: var(--muted);">Perbandingan jumlah sesi dan keterisian kursi setiap hari (Senin s/d Minggu)</small>
        </div>
    </div>

    {{-- Visual Bar Chart CSS Sederhana & Ringan --}}
    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 12px; padding: 20px 0 10px; border-bottom: 1px solid var(--line); align-items: flex-end; min-height: 180px;">
        @foreach($dailyStats as $d)
            @php
                $barHeightPct = $maxDailySchedules > 0 ? max(10, round(($d['total_schedules'] / $maxDailySchedules) * 100)) : 10;
            @endphp
            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                <span style="font-size: 12px; font-weight: 800; color: var(--text);">
                    {{ $d['total_schedules'] }} <small style="font-size: 10px; color: var(--muted);">sesi</small>
                </span>
                
                <div style="width: 100%; max-width: 48px; height: 110px; background: var(--surface-2); border-radius: 8px; display: flex; align-items: flex-end; padding: 3px; position: relative;">
                    <div style="width: 100%; height: {{ $barHeightPct }}%; background: {{ $d['is_today'] ? 'var(--primary)' : 'color-mix(in srgb, var(--primary) 70%, #fff)' }}; border-radius: 6px; transition: height .3s ease;"
                         title="{{ $d['day_name'] }}: {{ $d['total_schedules'] }} sesi ({{ $d['seats_booked'] }} murid)">
                    </div>
                </div>

                <div style="text-align: center;">
                    <strong style="font-size: 13px; display: block; color: {{ $d['is_today'] ? 'var(--primary)' : 'var(--text)' }};">
                        {{ $d['day_short'] }}
                    </strong>
                    <small style="font-size: 11px; color: var(--muted);">{{ $d['date_formatted'] }}</small>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Tabel Rincian Harian --}}
    <div style="overflow-x: auto; margin-top: 16px;">
        <table style="width: 100%; font-size: 13px;">
            <thead>
                <tr>
                    <th>Hari &amp; Tanggal</th>
                    <th style="text-align: center;">Total Sesi</th>
                    <th style="text-align: center;">Status Sesi</th>
                    <th style="text-align: center;">Keterisian Kursi</th>
                    <th style="text-align: right;">Tingkat Okupansi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dailyStats as $d)
                    <tr style="{{ $d['is_today'] ? 'background: var(--surface-2);' : '' }}">
                        <td>
                            <strong>{{ $d['day_name'] }}</strong>, {{ $d['date_formatted'] }}
                            @if($d['is_today'])
                                <span class="badge" style="background: var(--primary-soft); color: var(--primary); margin-left: 6px; font-size: 10px;">Hari Ini</span>
                            @endif
                        </td>
                        <td style="text-align: center; font-weight: 700;">
                            {{ $d['total_schedules'] }}
                        </td>
                        <td style="text-align: center;">
                            <span style="color: var(--primary); font-weight: 600;">{{ $d['active_schedules'] }} aktif</span> &bull;
                            <span style="color: var(--mint-text); font-weight: 600;">{{ $d['completed_schedules'] }} selesai</span> &bull;
                            <span style="color: var(--pink-text); font-weight: 600;">{{ $d['cancelled_schedules'] }} batal</span>
                        </td>
                        <td style="text-align: center;">
                            <strong>{{ $d['seats_booked'] }}</strong> / {{ $d['seats_quota'] }} kursi
                        </td>
                        <td style="text-align: right; font-weight: 700; color: {{ $d['rate'] >= 70 ? 'var(--mint-text)' : 'var(--text)' }};">
                            {{ $d['rate'] }}%
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- 3. DISTRIBUSI PROGRAM & OKUPANSI RUANGAN --}}
<div class="grid grid-cols-2" style="gap: 20px; margin-bottom: 24px;">
    {{-- Distribusi Program --}}
    <div class="card" style="margin: 0;">
        <h3 style="font: 800 17px 'Plus Jakarta Sans'; margin-bottom: 4px; color: var(--text);">Distribusi Program Belajar</h3>
        <small style="color: var(--muted); display: block; margin-bottom: 16px;">Proporsi sesi dan siswa berdasarkan kategori program</small>

        @if($programStats->isEmpty() || $totalSchedules === 0)
            <p style="color: var(--muted); text-align: center; padding: 24px 0;">Belum ada data jadwal untuk minggu ini.</p>
        @else
            <div style="display: flex; flex-direction: column; gap: 14px;">
                @foreach($programStats as $ps)
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px;">
                            <strong style="color: var(--text);">{{ $ps['program']->name }}</strong>
                            <span style="color: var(--muted);">
                                <strong>{{ $ps['count'] }}</strong> sesi ({{ $ps['booked'] }} murid) &bull; <strong>{{ $ps['share'] }}%</strong>
                            </span>
                        </div>
                        <div style="width: 100%; height: 8px; background: var(--surface-2); border-radius: 99px; overflow: hidden;">
                            <div style="width: {{ $ps['share'] }}%; height: 100%; background: var(--primary); border-radius: 99px;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Okupansi per Ruangan Fisik --}}
    <div class="card" style="margin: 0;">
        <h3 style="font: 800 17px 'Plus Jakarta Sans'; margin-bottom: 4px; color: var(--text);">Okupansi Ruangan Fisik</h3>
        <small style="color: var(--muted); display: block; margin-bottom: 16px;">Total sesi dan keterisian pada 8 ruangan fisik bimbel</small>

        <div style="overflow-x: auto;">
            <table style="width: 100%; font-size: 13px;">
                <thead>
                    <tr>
                        <th>Ruangan</th>
                        <th style="text-align: center;">Kapasitas</th>
                        <th style="text-align: center;">Sesi Dipakai</th>
                        <th style="text-align: right;">Total Siswa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roomStats as $rs)
                        <tr>
                            <td>
                                <strong>{{ $rs['room']->code }}</strong> &ndash; {{ $rs['room']->name }}
                            </td>
                            <td style="text-align: center; color: var(--muted);">
                                {{ $rs['room']->capacity }} kursi
                            </td>
                            <td style="text-align: center; font-weight: 700; color: {{ $rs['sessions_count'] > 0 ? 'var(--primary)' : 'var(--muted)' }};">
                                {{ $rs['sessions_count'] }} sesi
                            </td>
                            <td style="text-align: right; font-weight: 600;">
                                {{ $rs['students_count'] }} siswa
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 16px; color: var(--muted);">Belum ada data ruangan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 4. TOP TUTOR & TOP MATA PELAJARAN --}}
<div class="grid grid-cols-2" style="gap: 20px;">
    {{-- Top Tutor Paling Aktif --}}
    <div class="card" style="margin: 0;">
        <h3 style="font: 800 17px 'Plus Jakarta Sans'; margin-bottom: 4px; color: var(--text);">Top Tutor Paling Aktif</h3>
        <small style="color: var(--muted); display: block; margin-bottom: 16px;">Tutor dengan jumlah sesi bimbingan terbanyak minggu ini</small>

        @if($topTutors->isEmpty())
            <p style="color: var(--muted); text-align: center; padding: 24px 0;">Belum ada aktivitas tutor pada minggu ini.</p>
        @else
            <table style="width: 100%; font-size: 13px;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pengajar (Tutor)</th>
                        <th style="text-align: center;">Sesi</th>
                        <th style="text-align: right;">Murid Diajar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topTutors as $idx => $t)
                        <tr>
                            <td style="width: 24px; color: var(--muted); font-weight: 700;">{{ $idx + 1 }}</td>
                            <td>
                                <strong style="color: var(--text);">{{ $t['tutor']->name }}</strong>
                                <small style="display: block; color: var(--muted); font-size: 11px;">{{ $t['completed_count'] }} sesi telah selesai</small>
                            </td>
                            <td style="text-align: center; font-weight: 700; color: var(--primary);">
                                {{ $t['schedule_count'] }} sesi
                            </td>
                            <td style="text-align: right; font-weight: 700; color: var(--mint-text);">
                                {{ $t['students_taught'] }} murid
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Top Mata Pelajaran --}}
    <div class="card" style="margin: 0;">
        <h3 style="font: 800 17px 'Plus Jakarta Sans'; margin-bottom: 4px; color: var(--text);">Mata Pelajaran Paling Diminati</h3>
        <small style="color: var(--muted); display: block; margin-bottom: 16px;">Mata pelajaran dengan frekuensi sesi dan peserta terbanyak</small>

        @if($topSubjects->isEmpty())
            <p style="color: var(--muted); text-align: center; padding: 24px 0;">Belum ada sesi mapel pada minggu ini.</p>
        @else
            <table style="width: 100%; font-size: 13px;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mata Pelajaran</th>
                        <th style="text-align: center;">Jumlah Sesi</th>
                        <th style="text-align: right;">Total Peserta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topSubjects as $idx => $s)
                        <tr>
                            <td style="width: 24px; color: var(--muted); font-weight: 700;">{{ $idx + 1 }}</td>
                            <td>
                                <strong style="color: var(--text);">{{ $s['subject']->name }}</strong>
                                <small style="display: block; color: var(--muted); font-size: 11px;">Kode: {{ $s['subject']->code }}</small>
                            </td>
                            <td style="text-align: center; font-weight: 700; color: var(--primary);">
                                {{ $s['count'] }} sesi
                            </td>
                            <td style="text-align: right; font-weight: 700; color: var(--mint-text);">
                                {{ $s['booked'] }} murid
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
