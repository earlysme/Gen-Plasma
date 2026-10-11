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

{{-- FORM BUAT JADWAL KONSULTASI --}}
<div class="card" style="margin-top: 24px; border: 1.5px solid #c7d2fe;">
    <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; padding-bottom: 4px;"
         onclick="toggleConsultForm()">
        <div>
            <h2 style="font-size: 16px; color: #4338ca; margin: 0;">📅 Buat Jadwal Konsultasi</h2>
            <p style="font-size: 12px; color: #6366f1; margin: 4px 0 0;">
                Jadwal 1-on-1 bersama tutor pilihan Anda. Pembatalan berlaku H-1.
            </p>
        </div>
        <span id="consult-toggle-icon" style="font-size: 20px; color: #4338ca; font-weight: bold; transition: transform 0.2s;">▼</span>
    </div>

    <div id="consult-form-body" style="display: none; margin-top: 16px; border-top: 1px solid #e0e7ff; padding-top: 16px;">
        @if($weeklyBookingCount >= $maxWeekly)
            <div style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 16px; font-size: 13px;">
                <strong>Kuota mingguan sudah penuh.</strong> Anda tidak dapat membuat jadwal konsultasi baru pada minggu ini.
            </div>
        @else
            <form action="{{ route('student.consultations.store') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    {{-- Tanggal --}}
                    <div>
                        <label for="consult_date" style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">
                            Tanggal Konsultasi <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="date" id="consult_date" name="date"
                               min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                               value="{{ old('date') }}"
                               style="width: 100%; padding: 9px 12px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: 14px; background: #f9fafb; box-sizing: border-box;"
                               required>
                        @error('date')<p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
                    </div>

                    {{-- Sesi Waktu --}}
                    <div>
                        <label for="consult_time_slot" style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">
                            Sesi / Jam <span style="color: #ef4444;">*</span>
                        </label>
                        <select id="consult_time_slot" name="time_slot_id"
                                style="width: 100%; padding: 9px 12px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: 14px; background: #f9fafb; box-sizing: border-box;"
                                required>
                            <option value="">-- Pilih Sesi --</option>
                            @foreach($timeSlots as $ts)
                                <option value="{{ $ts->id }}" {{ old('time_slot_id') == $ts->id ? 'selected' : '' }}>
                                    {{ $ts->name }} ({{ substr($ts->start_time, 0, 5) }} – {{ substr($ts->end_time, 0, 5) }})
                                </option>
                            @endforeach
                        </select>
                        @error('time_slot_id')<p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
                    </div>

                    {{-- Mata Pelajaran --}}
                    <div>
                        <label for="consult_subject" style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">
                            Mata Pelajaran <span style="color: #ef4444;">*</span>
                        </label>
                        <select id="consult_subject" name="subject_id"
                                onchange="filterTutors(this.value)"
                                style="width: 100%; padding: 9px 12px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: 14px; background: #f9fafb; box-sizing: border-box;"
                                required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($subjects as $subj)
                                <option value="{{ $subj->id }}" {{ old('subject_id') == $subj->id ? 'selected' : '' }}>
                                    {{ $subj->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')<p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
                    </div>

                    {{-- Pengajar --}}
                    <div>
                        <label for="consult_tutor" style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">
                            Pengajar / Tutor <span style="color: #ef4444;">*</span>
                        </label>
                        <select id="consult_tutor" name="tutor_id"
                                style="width: 100%; padding: 9px 12px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: 14px; background: #f9fafb; box-sizing: border-box;"
                                required>
                            <option value="">-- Pilih Mata Pelajaran dulu --</option>
                        </select>
                        @error('tutor_id')<p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
                    </div>

                    {{-- Catatan --}}
                    <div style="grid-column: 1 / -1;">
                        <label for="consult_notes" style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px;">
                            Catatan / Topik yang Ingin Dikonsultasikan
                        </label>
                        <textarea id="consult_notes" name="notes" rows="3" maxlength="500"
                                  placeholder="Contoh: Ingin membahas soal integral tentu bab 4..."
                                  style="width: 100%; padding: 9px 12px; border: 1.5px solid #d1d5db; border-radius: 8px; font-size: 14px; background: #f9fafb; resize: vertical; box-sizing: border-box;">{{ old('notes') }}</textarea>
                        @error('notes')<p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div style="margin-top: 16px; display: flex; align-items: center; gap: 12px;">
                    <button type="submit" class="btn btn-primary" style="background: #4f46e5; padding: 9px 22px;">
                        📌 Ajukan Konsultasi
                    </button>
                    <small style="color: #6b7280;">
                        ⚠️ Pembatalan hanya bisa dilakukan <strong>minimal H-1</strong> (24 jam sebelum sesi).
                    </small>
                </div>
            </form>
        @endif
    </div>
</div>

{{-- Data tutor per mapel untuk JS filter --}}
<script>
const subjectTutors = @json($subjects->map(function($s) {
    return [
        'id' => $s->id,
        'tutors' => $s->tutors->map(fn($t) => ['id' => $t->id, 'name' => $t->name])->values()
    ];
})->keyBy('id'));

function filterTutors(subjectId) {
    const select = document.getElementById('consult_tutor');
    select.innerHTML = '<option value="">-- Pilih Tutor --</option>';

    if (!subjectId || !subjectTutors[subjectId]) return;

    const tutors = subjectTutors[subjectId].tutors;
    if (tutors.length === 0) {
        select.innerHTML = '<option value="">Tidak ada tutor tersedia</option>';
        return;
    }
    tutors.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t.id;
        opt.textContent = t.name;
        select.appendChild(opt);
    });
}

function toggleConsultForm() {
    const body = document.getElementById('consult-form-body');
    const icon = document.getElementById('consult-toggle-icon');
    const isHidden = body.style.display === 'none';
    body.style.display = isHidden ? 'block' : 'none';
    icon.style.transform = isHidden ? 'rotate(180deg)' : '';
}

// Auto-expand form jika ada validation error pada field konsultasi
@if($errors->has('date') || $errors->has('time_slot_id') || $errors->has('subject_id') || $errors->has('tutor_id') || $errors->has('notes'))
    document.addEventListener('DOMContentLoaded', () => toggleConsultForm());
    @if(old('subject_id'))
        document.addEventListener('DOMContentLoaded', () => filterTutors('{{ old('subject_id') }}'));
    @endif
@endif
</script>

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
