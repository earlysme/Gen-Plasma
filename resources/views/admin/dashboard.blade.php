@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
    .matrix-wrapper {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid var(--line);
        background: var(--surface);
    }
    .matrix-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 640px;
    }
    .matrix-table th {
        background: var(--surface-2);
        color: var(--text);
        padding: 12px 14px;
        font-size: 13px;
        font-weight: 700;
        border-bottom: 1px solid var(--line);
        border-right: 1px solid var(--line);
        text-align: center;
    }
    .matrix-table th:first-child {
        text-align: left;
        width: 160px;
    }
    .matrix-table th:last-child {
        border-right: none;
    }
    .matrix-table td {
        padding: 12px 10px;
        border-bottom: 1px solid var(--line);
        border-right: 1px solid var(--line);
        vertical-align: middle;
        font-size: 13px;
        transition: background-color .15s ease;
    }
    .matrix-table td:last-child {
        border-right: none;
    }
    .matrix-table tr:last-child td {
        border-bottom: none;
    }
    .matrix-room-cell {
        background: var(--surface);
        color: var(--text);
        padding: 12px 14px !important;
    }
    .matrix-cell-empty {
        background: color-mix(in srgb, var(--mint) 12%, var(--surface));
        text-align: center;
    }
    .matrix-cell-empty:hover {
        background: color-mix(in srgb, var(--mint) 22%, var(--surface));
    }
    .matrix-cell-booked {
        background: var(--accent-terra-soft);
        border-left: 3px solid var(--accent-terra);
        text-align: center;
    }
    .matrix-badge-empty {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 600;
        color: var(--mint-text);
        background: color-mix(in srgb, var(--mint) 40%, transparent);
    }
    .matrix-booked-subject {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--text);
        line-height: 1.3;
    }
    .matrix-booked-tutor {
        font-size: 11.5px;
        font-weight: 600;
        color: var(--accent-terra-text);
        margin-top: 2px;
    }
    .matrix-booked-quota {
        font-size: 11px;
        color: var(--muted);
        margin-top: 3px;
        font-weight: 500;
    }
    .legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--text);
        font-weight: 600;
    }
    .legend-box {
        width: 14px;
        height: 14px;
        border-radius: 4px;
        display: inline-block;
    }
    .legend-box-empty {
        background: color-mix(in srgb, var(--mint) 40%, transparent);
        border: 1.5px solid var(--mint-text);
    }
    .legend-box-booked {
        background: var(--accent-terra-soft);
        border: 1.5px solid var(--accent-terra);
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Dashboard Ringkasan</h1>
        <p>Pantau statistik harian, utilisasi ruangan, dan operasional jadwal.</p>
    </div>
</div>

<!-- Kartu Ringkasan Metrik -->
<div class="stats-grid" style="margin-bottom:24px;">
    <div class="card" style="margin:0;">
        <span style="font-size:12px;color:var(--muted);text-transform:uppercase;font-weight:700;">Total Pengajar</span>
        <h3 style="font:800 28px 'Plus Jakarta Sans';margin-top:4px;">{{ $totalTutors }}</h3>
    </div>
    <div class="card" style="margin:0;">
        <span style="font-size:12px;color:var(--muted);text-transform:uppercase;font-weight:700;">Total Siswa</span>
        <h3 style="font:800 28px 'Plus Jakarta Sans';margin-top:4px;">{{ $totalStudents }}</h3>
    </div>
    <div class="card" style="margin:0;">
        <span style="font-size:12px;color:var(--muted);text-transform:uppercase;font-weight:700;">Jadwal Hari Ini</span>
        <h3 style="font:800 28px 'Plus Jakarta Sans';margin-top:4px;">{{ $schedulesTodayCount }}</h3>
    </div>
    <div class="card" style="margin:0;">
        <span style="font-size:12px;color:var(--muted);text-transform:uppercase;font-weight:700;">Booking Kursi Hari Ini</span>
        <h3 style="font:800 28px 'Plus Jakarta Sans';margin-top:4px;">{{ $bookingsTodayCount }}</h3>
    </div>
</div>

<!-- Matriks Okupansi 8 Ruangan Hari Ini -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
        <div>
            <h3 style="font-size: 16px; color: var(--text); margin-bottom: 2px;">Matriks Okupansi 8 Ruangan Hari Ini</h3>
            <small style="color: var(--muted);">Tanggal: {{ $today->isoFormat('dddd, D MMMM Y') }}</small>
        </div>
        <div style="display: flex; align-items: center; gap: 16px;">
            <span class="legend-item">
                <span class="legend-box legend-box-empty"></span>
                Kosong
            </span>
            <span class="legend-item">
                <span class="legend-box legend-box-booked"></span>
                Terpakai
            </span>
        </div>
    </div>

    <div class="matrix-wrapper">
        <table class="matrix-table">
            <thead>
                <tr>
                    <th>Ruangan / Kapasitas</th>
                    @foreach($timeSlots as $slot)
                        <th>
                            {{ $slot->name }} <br>
                            <small style="font-weight: normal; color: var(--muted);">{{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}</small>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($rooms as $room)
                    <tr>
                        <td class="matrix-room-cell">
                            <strong>{{ $room->code }}</strong> ({{ $room->name }}) <br>
                            <small style="color: var(--muted);">Maks: {{ $room->capacity }} orang</small>
                        </td>
                        @foreach($timeSlots as $slot)
                            @php
                                $booked = $occupancyMatrix[$room->id][$slot->id] ?? null;
                            @endphp
                            <td class="{{ $booked ? 'matrix-cell-booked' : 'matrix-cell-empty' }}">
                                @if($booked)
                                    <div class="matrix-booked-subject">
                                        {{ $booked->subject->name }}
                                    </div>
                                    <div class="matrix-booked-tutor">
                                        Tutor: {{ $booked->tutor->name }}
                                    </div>
                                    <div class="matrix-booked-quota">
                                        {{ $booked->booked_count }}/{{ $booked->quota_max }} Kursi
                                    </div>
                                @else
                                    <span class="matrix-badge-empty">Kosong</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Jadwal Terbaru Keseluruhan -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
        <h3 style="font-size: 16px; margin: 0; color: var(--text);">Daftar Jadwal Terbaru</h3>
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">Lihat Semua</a>
    </div>
    @if($recentSchedules->isEmpty())
        <p style="color: var(--muted); font-size: 14px;">Belum ada jadwal yang terdaftar.</p>
    @else
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Sesi</th>
                        <th>Mapel</th>
                        <th>Tutor</th>
                        <th>Ruang</th>
                        <th>Terisi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSchedules as $rs)
                        <tr>
                            <td style="color: var(--text);">{{ \Carbon\Carbon::parse($rs->date)->format('d M Y') }}</td>
                            <td style="color: var(--muted);">{{ $rs->timeSlot->name }}</td>
                            <td><strong style="color: var(--text);">{{ $rs->subject->name }}</strong></td>
                            <td style="color: var(--text);">{{ $rs->tutor->name }}</td>
                            <td><span style="display: inline-block; padding: 2px 8px; border-radius: 6px; background: var(--surface-2); color: var(--text); font-size: 12px; font-weight: 600;">{{ $rs->room->name }}</span></td>
                            <td style="color: var(--muted);">{{ $rs->booked_count }} / {{ $rs->quota_max }}</td>
                            <td>
                                @if($rs->status === 'completed')
                                    <span class="badge" style="background: var(--mint); color: var(--mint-text);">
                                        Selesai
                                    </span>
                                @elseif($rs->status === 'cancelled')
                                    <span class="badge" style="background: var(--pink-soft); color: var(--pink-text);">
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="badge" style="background: var(--primary-soft); color: var(--primary);">
                                        Aktif
                                    </span>
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
