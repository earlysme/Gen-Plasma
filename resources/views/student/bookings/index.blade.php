@extends('layouts.app')

@section('title', 'Katalog Booking Jadwal')

@section('content')
<div class="page-header">
    <div>
        <h1>Katalog Jadwal Belajar</h1>
        <p>Pilih sesi belajar yang tersedia dan pesan kursi Anda.</p>
    </div>
    <div>
        <span class="badge" style="background: {{ $weeklyBookingCount >= $maxWeekly ? '#fee2e2' : '#dbeafe' }}; color: {{ $weeklyBookingCount >= $maxWeekly ? '#991b1b' : '#1e40af' }}; font-size: 13px; padding: 6px 12px;">
            Jadwal Minggu Ini: <strong>{{ $weeklyBookingCount }} / {{ $maxWeekly }} Terbooking</strong>
        </span>
    </div>
</div>

@if($weeklyBookingCount >= $maxWeekly)
    <div class="alert" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; margin-bottom: 16px;">
        <strong>Batas Kuota Tercapai!</strong> Anda sudah memenuhi kuota maksimal {{ $maxWeekly }} jadwal pada minggu ini. Lihat di <a href="{{ route('student.bookings.my') }}" style="color: #b45309; font-weight: bold;">Jadwal Saya</a>.
    </div>
@endif

{{-- FILTER TABS --}}
<div style="display: flex; gap: 8px; margin-bottom: 16px; background: #f1f5f9; padding: 4px; border-radius: 8px; width: fit-content;">
    <button type="button" onclick="switchStudentTab('all')" id="btn-std-all"
            class="btn btn-primary">
        Semua ({{ $schedules->count() }})
    </button>
    <button type="button" onclick="switchStudentTab('reguler')" id="btn-std-reguler"
            class="btn btn-secondary">
        Kelas Reguler ({{ $regularSchedules->count() }})
    </button>
    <button type="button" onclick="switchStudentTab('special')" id="btn-std-special"
            class="btn btn-secondary">
        Privat &amp; Konsultasi ({{ $specialSchedules->count() }})
    </button>
</div>

<div class="card">
    @if($schedules->isEmpty())
        <div style="text-align: center; padding: 40px 20px; color: #64748b;">
            <p>Belum ada slot jadwal yang dibuka saat ini.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 10px 12px; border: 1px solid #e2e8f0; text-align: left;">Tanggal &amp; Hari</th>
                        <th style="padding: 10px 12px; border: 1px solid #e2e8f0; text-align: left;">Sesi &amp; Jam</th>
                        <th style="padding: 10px 12px; border: 1px solid #e2e8f0; text-align: left;">Mata Pelajaran</th>
                        <th style="padding: 10px 12px; border: 1px solid #e2e8f0; text-align: left;">Pengajar (Tutor)</th>
                        <th style="padding: 10px 12px; border: 1px solid #e2e8f0; text-align: left;">Program &amp; Ruangan</th>
                        <th style="padding: 10px 12px; border: 1px solid #e2e8f0; text-align: center;">Sisa Kursi</th>
                        <th style="padding: 10px 12px; border: 1px solid #e2e8f0; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="student-schedules-tbody">
                    @foreach($schedules as $s)
                        @php
                            $isReguler = $s->program->code === 'reguler';
                            $rowCat = $isReguler ? 'row-reguler' : 'row-special';
                        @endphp
                        <tr class="student-schedule-row {{ $rowCat }}">
                            <td style="padding: 10px 12px; border: 1px solid #e2e8f0;">
                                <strong style="color: #0f172a;">{{ \Carbon\Carbon::parse($s->date)->isoFormat('dddd, D MMM Y') }}</strong>
                            </td>
                            <td style="padding: 10px 12px; border: 1px solid #e2e8f0;">
                                <strong style="color: #334155;">{{ $s->timeSlot->name }}</strong><br>
                                <small style="color: #64748b;">{{ substr($s->timeSlot->start_time, 0, 5) }} &ndash; {{ substr($s->timeSlot->end_time, 0, 5) }}</small>
                            </td>
                            <td style="padding: 10px 12px; border: 1px solid #e2e8f0;">
                                <strong style="font-size: 14px; color: #1e293b;">{{ $s->subject->name }}</strong>
                            </td>
                            <td style="padding: 10px 12px; border: 1px solid #e2e8f0; font-weight: 600; color: #334155;">
                                {{ $s->tutor->name }}
                            </td>
                            <td style="padding: 10px 12px; border: 1px solid #e2e8f0;">
                                @if($s->program->code === 'reguler')
                                    <span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: 700;">Reguler</span>
                                @elseif($s->program->code === 'consult')
                                    <span class="badge" style="background: #e0e7ff; color: #4338ca; font-weight: 700;">Konsultasi</span>
                                @elseif($s->program->code === 'private_home')
                                    <span class="badge" style="background: #fef3c7; color: #b45309; font-weight: 700;">Privat Rumah</span>
                                @else
                                    <span class="badge" style="background: #ede9fe; color: #6d28d9; font-weight: 700;">Privat Bimbel</span>
                                @endif
                                <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                                    {{ $s->room->name }}
                                </div>
                            </td>
                            <td style="padding: 10px 12px; border: 1px solid #e2e8f0; text-align: center;">
                                <strong style="color: #059669; font-size: 13px;">{{ $s->remaining_seats }} kursi lagi</strong>
                                <small style="display: block; color: #64748b;">dari kuota {{ $s->quota_max }}</small>
                            </td>
                            <td style="padding: 10px 12px; border: 1px solid #e2e8f0; text-align: center;">
                                <form action="{{ route('student.bookings.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="schedule_id" value="{{ $s->id }}">
                                    <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 13px;" {{ $weeklyBookingCount >= $maxWeekly ? 'disabled' : '' }}>
                                        Pesan Kursi
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script>
function switchStudentTab(cat) {
    const rows = document.querySelectorAll('.student-schedule-row');
    const btnAll = document.getElementById('btn-std-all');
    const btnReguler = document.getElementById('btn-std-reguler');
    const btnSpecial = document.getElementById('btn-std-special');

    [btnAll, btnReguler, btnSpecial].forEach(b => {
        b.style.background = 'transparent';
        b.style.color = '#64748b';
    });

    if (cat === 'all') {
        btnAll.style.background = '#2563eb';
        btnAll.style.color = '#ffffff';
        rows.forEach(r => r.style.display = '');
    } else if (cat === 'reguler') {
        btnReguler.style.background = '#2563eb';
        btnReguler.style.color = '#ffffff';
        rows.forEach(r => {
            r.style.display = r.classList.contains('row-reguler') ? '' : 'none';
        });
    } else if (cat === 'special') {
        btnSpecial.style.background = '#2563eb';
        btnSpecial.style.color = '#ffffff';
        rows.forEach(r => {
            r.style.display = r.classList.contains('row-special') ? '' : 'none';
        });
    }
}
</script>
@endsection
