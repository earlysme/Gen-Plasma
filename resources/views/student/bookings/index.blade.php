@extends('layouts.app')

@section('title', 'Katalog Booking Jadwal')

@section('content')
<div class="page-header">
    <div>
        <h1>Katalog Jadwal Belajar</h1>
        <p>Pilih sesi belajar yang tersedia dan pesan kursi Anda.</p>
    </div>
    <div>
        <span class="badge" style="background: {{ $weeklyBookingCount >= $maxWeekly ? 'var(--pink-soft)' : 'var(--blue-soft)' }}; color: {{ $weeklyBookingCount >= $maxWeekly ? 'var(--pink-text)' : 'var(--blue-text)' }}; font-size: 13px; padding: 6px 14px; border: 1px solid var(--line);">
            Jadwal Minggu Ini: <strong>{{ $weeklyBookingCount }} / {{ $maxWeekly }} Terbooking</strong>
        </span>
    </div>
</div>

@if($weeklyBookingCount >= $maxWeekly)
    <div class="alert alert-error" style="margin-bottom: 16px;">
        <strong>Batas Kuota Tercapai!</strong> Anda sudah memenuhi kuota maksimal {{ $maxWeekly }} jadwal pada minggu ini. Lihat di <a href="{{ route('student.bookings.my') }}" style="color: inherit; text-decoration: underline; font-weight: bold;">Jadwal Saya</a>.
    </div>
@endif

{{-- FILTER TABS + TOMBOL KONSULTASI --}}
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
    <div style="display: flex; gap: 6px; background: var(--surface-2); padding: 4px; border-radius: 12px; border: 1px solid var(--line); width: fit-content;">
        <button type="button" onclick="switchStudentTab('all')" id="btn-std-all"
                class="tab-pill active">
            Semua ({{ $schedules->count() }})
        </button>
        <button type="button" onclick="switchStudentTab('reguler')" id="btn-std-reguler"
                class="tab-pill">
            Kelas Reguler ({{ $regularSchedules->count() }})
        </button>
        <button type="button" onclick="switchStudentTab('special')" id="btn-std-special"
                class="tab-pill">
            Privat &amp; Konsultasi ({{ $specialSchedules->count() }})
        </button>
    </div>

    <button type="button" onclick="openConsultModal()"
            class="btn-consult"
            {{ $weeklyBookingCount >= $maxWeekly ? 'disabled' : '' }}>
        + Buat Konsultasi
    </button>
</div>

<div class="card">
    @if($schedules->isEmpty())
        <div style="text-align: center; padding: 40px 20px; color: var(--muted);">
            <p>Belum ada slot jadwal yang dibuka saat ini.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal &amp; Hari</th>
                        <th>Sesi &amp; Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Pengajar (Tutor)</th>
                        <th>Program &amp; Ruangan</th>
                        <th style="text-align: center;">Sisa Kursi</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="student-schedules-tbody">
                    @foreach($schedules as $s)
                        @php
                            $isReguler = $s->program->code === 'reguler';
                            $rowCat = $isReguler ? 'row-reguler' : 'row-special';
                        @endphp
                        <tr class="student-schedule-row {{ $rowCat }}">
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($s->date)->isoFormat('dddd, D MMM Y') }}</strong>
                            </td>
                            <td>
                                <strong>{{ $s->timeSlot->name }}</strong><br>
                                <small style="color: var(--muted);">{{ substr($s->timeSlot->start_time, 0, 5) }} &ndash; {{ substr($s->timeSlot->end_time, 0, 5) }}</small>
                            </td>
                            <td>
                                <strong style="font-size: 14px;">{{ $s->subject->name }}</strong>
                            </td>
                            <td style="font-weight: 600;">
                                {{ $s->tutor->name }}
                            </td>
                            <td>
                                @if($s->program->code === 'reguler')
                                    <span class="badge" style="background: var(--blue-soft); color: var(--blue-text); font-weight: 700;">Reguler</span>
                                @elseif($s->program->code === 'consult')
                                    <span class="badge" style="background: var(--accent-gold-soft); color: var(--accent-gold-text); font-weight: 700;">Konsultasi</span>
                                @elseif($s->program->code === 'private_home')
                                    <span class="badge" style="background: var(--accent-terra-soft); color: var(--accent-terra-text); font-weight: 700;">Privat Rumah</span>
                                @else
                                    <span class="badge" style="background: var(--primary-soft); color: var(--primary); font-weight: 700;">Privat Bimbel</span>
                                @endif
                                <div style="font-size: 11px; color: var(--muted); margin-top: 3px;">
                                    {{ $s->room->name }}
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <strong style="color: var(--mint-text); font-size: 13px;">{{ $s->remaining_seats }} kursi lagi</strong>
                                <small style="display: block; color: var(--muted);">dari kuota {{ $s->quota_max }}</small>
                            </td>
                            <td style="text-align: center;">
                                <form action="{{ route('student.bookings.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="schedule_id" value="{{ $s->id }}">
                                    <button type="submit" class="btn-icon btn-icon-primary" title="Pesan Kursi" aria-label="Pesan Kursi" {{ $weeklyBookingCount >= $maxWeekly ? 'disabled' : '' }} style="margin:auto;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
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

{{-- MODAL BUAT JADWAL KONSULTASI --}}
<div id="consult-modal-overlay"
     onclick="closeConsultModal(event)"
     style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 9000; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: var(--surface); border: 1px solid var(--line); border-radius: 16px; width: 100%; max-width: 560px; box-shadow: var(--shadow); overflow: hidden;"
         onclick="event.stopPropagation()">

        {{-- Header Modal --}}
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-bottom: 1px solid var(--line); background: var(--surface-2);">
            <div>
                <h2 style="font-size: 16px; font-weight: 700; color: var(--text); margin: 0;">Buat Jadwal Konsultasi</h2>
                <p style="font-size: 12px; color: var(--muted); margin: 3px 0 0;">Sesi 1-on-1 bersama tutor. Pembatalan berlaku minimal H-1.</p>
            </div>
            <button onclick="closeConsultModal()" type="button"
                    style="background: none; border: none; font-size: 22px; color: var(--muted); cursor: pointer; line-height: 1; padding: 0 4px;">&times;</button>
        </div>

        {{-- Body Modal --}}
        <div style="padding: 24px;">
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
                            <label for="consult_date" style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 5px;">
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
                            <label for="consult_time_slot" style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 5px;">
                                Sesi / Jam <span style="color: var(--pink-text);">*</span>
                            </label>
                            <select id="consult_time_slot" name="time_slot_id" class="form-control" required>
                                <option value="">-- Pilih Sesi --</option>
                                @foreach($timeSlots as $ts)
                                    <option value="{{ $ts->id }}" {{ old('time_slot_id') == $ts->id ? 'selected' : '' }}>
                                        {{ $ts->name }} ({{ substr($ts->start_time, 0, 5) }} – {{ substr($ts->end_time, 0, 5) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('time_slot_id')<p style="color: var(--pink-text); font-size: 11px; margin: 3px 0 0;">{{ $message }}</p>@enderror
                        </div>

                        {{-- Mata Pelajaran --}}
                        <div>
                            <label for="consult_subject" style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 5px;">
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
                            <label for="consult_tutor" style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 5px;">
                                Pengajar / Tutor <span style="color: var(--pink-text);">*</span>
                            </label>
                            <select id="consult_tutor" name="tutor_id" class="form-control" required>
                                <option value="">-- Pilih mata pelajaran dulu --</option>
                            </select>
                            @error('tutor_id')<p style="color: var(--pink-text); font-size: 11px; margin: 3px 0 0;">{{ $message }}</p>@enderror
                        </div>

                        {{-- Catatan --}}
                        <div style="grid-column: 1 / -1;">
                            <label for="consult_notes" style="display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 5px;">
                                Catatan / Topik
                            </label>
                            <textarea id="consult_notes" name="notes" rows="3" maxlength="500" class="form-control"
                                      placeholder="Topik atau materi yang ingin dikonsultasikan..."
                                      style="resize: vertical;">{{ old('notes') }}</textarea>
                            @error('notes')<p style="color: var(--pink-text); font-size: 11px; margin: 3px 0 0;">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px; border-top: 1px solid var(--line); padding-top: 16px;">
                        <small style="color: var(--muted); font-size: 11px;">Pembatalan hanya bisa dilakukan <strong>minimal H-1</strong>.</small>
                        <div style="display: flex; gap: 8px;">
                            <button type="button" onclick="closeConsultModal()"
                                    class="btn btn-secondary" style="padding: 8px 16px; font-size: 13px;">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary"
                                    style="padding: 8px 20px; font-size: 13px;">
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
    $subjectTutorsData = $subjects->map(function ($s) {
        return [
            'id' => $s->id,
            'tutors' => $s->tutors->map(fn ($t) => ['id' => $t->id, 'name' => $t->name])->values(),
        ];
    })->keyBy('id');
@endphp

<script>
const subjectTutors = @json($subjectTutorsData);

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
        b.style.background = '';
        b.style.color = '';
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
@endsection
