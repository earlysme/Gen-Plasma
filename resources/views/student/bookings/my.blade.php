@extends('layouts.app')

@section('title', 'Jadwal Belajar Saya')

@section('content')
<div class="page-header">
    <div>
        <h1>Jadwal Belajar Saya</h1>
        <p>Kelola sesi belajar yang sudah Anda pesan.</p>
    </div>
    <a href="{{ route('student.dashboard') }}" class="btn btn-primary">＋ Cari Jadwal Lain</a>
</div>

<div class="card">
    @if($bookings->isEmpty())
        <div style="text-align: center; padding: 40px 20px; color: #64748b;">
            <p style="font-size: 16px; margin-bottom: 12px;">Anda belum memiliki jadwal belajar.</p>
            <a href="{{ route('student.dashboard') }}" class="btn btn-primary">Lihat Katalog Jadwal</a>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Tanggal & Hari</th>
                    <th>Sesi & Jam</th>
                    <th>Mata Pelajaran</th>
                    <th>Pengajar (Tutor)</th>
                    <th>Ruangan</th>
                    <th>Status</th>
                    <th>Aksi Pembatalan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $b)
                    @php
                        $scheduleDate = \Carbon\Carbon::parse($b->schedule->date)->startOfDay();
                        $today = \Carbon\Carbon::today();
                        $canCancel = $today->diffInDays($scheduleDate, false) >= 1 && $b->status === 'confirmed';
                    @endphp
                    <tr>
                        <td>
                            <code>{{ $b->booking_code }}</code>
                            <small style="display: block; color: #64748b;">{{ $b->booking_type === 'tutor_assigned' ? 'Diinput Tutor' : 'Pesan Sendiri' }}</small>
                        </td>
                        <td>
                            <strong>{{ $scheduleDate->isoFormat('dddd, D MMM Y') }}</strong>
                        </td>
                        <td>
                            {{ $b->schedule->timeSlot->name }} <br>
                            <small style="color: #64748b;">{{ substr($b->schedule->timeSlot->start_time, 0, 5) }} - {{ substr($b->schedule->timeSlot->end_time, 0, 5) }}</small>
                        </td>
                        <td><strong>{{ $b->schedule->subject->name }}</strong></td>
                        <td>{{ $b->schedule->tutor->name }}</td>
                        <td>{{ $b->schedule->room->name }}</td>
                        <td>
                            @if($b->status === 'confirmed')
                                @if($b->schedule->status === 'completed')
                                    <span class="badge" style="background: #e2e8f0; color: #334155;">Sesi Selesai</span>
                                @else
                                    <span class="badge badge-student">Terkonfirmasi</span>
                                @endif
                            @else
                                <span class="badge badge-admin">Dibatalkan</span>
                            @endif
                        </td>
                        <td>
                            @if($b->status === 'confirmed' && $b->schedule->status !== 'completed')
                                @if($canCancel)
                                    <form action="{{ route('student.bookings.cancel', $b->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan jadwal ini? Kursi akan dilepas untuk siswa lain.');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" style="padding: 4px 10px; font-size: 12px;">
                                            Batalkan (H-1)
                                        </button>
                                    </form>
                                @else
                                    <span style="font-size: 11px; color: #94a3b8; font-weight: bold;">
                                        Terkunci (&lt; 24 Jam)
                                    </span>
                                @endif
                            @else
                                <span style="font-size: 12px; color: #94a3b8;">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
