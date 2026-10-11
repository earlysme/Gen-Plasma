@extends('layouts.app')

@section('title', 'Dashboard Admin')

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
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <div>
            <h3 style="font-size: 16px;">Matriks Okupansi 8 Ruangan Hari Ini</h3>
            <small style="color: #64748b;">Tanggal: {{ $today->isoFormat('dddd, D MMMM Y') }}</small>
        </div>
        <div style="font-size: 12px; display: flex; gap: 12px;">
            <span><span style="display: inline-block; width: 12px; height: 12px; background: #dcfce7; border: 1px solid #86efac; border-radius: 2px;"></span> Kosong</span>
            <span><span style="display: inline-block; width: 12px; height: 12px; background: #fee2e2; border: 1px solid #fca5a5; border-radius: 2px;"></span> Terpakai</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 140px;">Ruangan / Kapasitas</th>
                @foreach($timeSlots as $slot)
                    <th style="text-align: center;">
                        {{ $slot->name }} <br>
                        <small style="font-weight: normal; color: #64748b;">{{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}</small>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rooms as $room)
                <tr>
                    <td>
                        <strong>{{ $room->code }}</strong> ({{ $room->name }}) <br>
                        <small style="color: #64748b;">Maks: {{ $room->capacity }} orang</small>
                    </td>
                    @foreach($timeSlots as $slot)
                        @php
                            $booked = $occupancyMatrix[$room->id][$slot->id] ?? null;
                        @endphp
                        <td style="text-align: center; vertical-align: middle; background: {{ $booked ? '#fee2e2' : '#f0fdf4' }};">
                            @if($booked)
                                <div style="font-size: 12px; font-weight: bold; color: #991b1b;">
                                    {{ $booked->subject->name }}
                                </div>
                                <div style="font-size: 11px; color: #7f1d1d;">
                                    Tutor: {{ $booked->tutor->name }}
                                </div>
                                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                    {{ $booked->booked_count }}/{{ $booked->quota_max }} Kursi
                                </div>
                            @else
                                <span style="font-size: 12px; color: #166534;">Kosong</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Jadwal Terbaru Keseluruhan -->
<div class="card">
    <h3 style="font-size: 16px; margin-bottom: 12px;">Daftar Jadwal Terbaru</h3>
    @if($recentSchedules->isEmpty())
        <p style="color: #64748b; font-size: 14px;">Belum ada jadwal yang terdaftar.</p>
    @else
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
                        <td>{{ \Carbon\Carbon::parse($rs->date)->format('d M Y') }}</td>
                        <td>{{ $rs->timeSlot->name }}</td>
                        <td><strong>{{ $rs->subject->name }}</strong></td>
                        <td>{{ $rs->tutor->name }}</td>
                        <td>{{ $rs->room->name }}</td>
                        <td>{{ $rs->booked_count }} / {{ $rs->quota_max }}</td>
                        <td>
                            <span class="badge" style="background: {{ $rs->status === 'completed' ? '#dcfce7' : '#e0f2fe' }}; color: {{ $rs->status === 'completed' ? '#166534' : '#0369a1' }};">
                                {{ ucfirst($rs->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
