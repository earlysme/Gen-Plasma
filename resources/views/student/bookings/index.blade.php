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

{{-- Filter Tanggal --}}
<div class="card" style="padding: 14px 18px; margin-bottom: 16px;">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        {{-- Quick Date Chips --}}
        <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
            <span style="font-size: 12px; font-weight: 700; color: #64748b; margin-right: 4px;">Pilih Hari:</span>
            
            @php
                $todayStr = now()->format('Y-m-d');
                $tomorrowStr = now()->addDay()->format('Y-m-d');
                $dayAfterStr = now()->addDays(2)->format('Y-m-d');
                $selectedDateStr = $filterDate ? $filterDate->format('Y-m-d') : null;
            @endphp

            <a href="{{ route('student.dashboard') }}"
               class="btn"
               style="padding: 5px 12px; font-size: 12px; border-radius: 20px; text-decoration: none; font-weight: 600; {{ is_null($selectedDateStr) ? 'background: #2563eb; color: #fff;' : 'background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;' }}">
                Semua Jadwal
            </a>

            <a href="{{ route('student.dashboard', ['date' => $todayStr]) }}"
               class="btn"
               style="padding: 5px 12px; font-size: 12px; border-radius: 20px; text-decoration: none; font-weight: 600; {{ $selectedDateStr === $todayStr ? 'background: #2563eb; color: #fff;' : 'background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;' }}">
                Hari Ini ({{ now()->isoFormat('D MMM') }})
            </a>

            <a href="{{ route('student.dashboard', ['date' => $tomorrowStr]) }}"
               class="btn"
               style="padding: 5px 12px; font-size: 12px; border-radius: 20px; text-decoration: none; font-weight: 600; {{ $selectedDateStr === $tomorrowStr ? 'background: #2563eb; color: #fff;' : 'background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;' }}">
                Besok ({{ now()->addDay()->isoFormat('D MMM') }})
            </a>

            <a href="{{ route('student.dashboard', ['date' => $dayAfterStr]) }}"
               class="btn"
               style="padding: 5px 12px; font-size: 12px; border-radius: 20px; text-decoration: none; font-weight: 600; {{ $selectedDateStr === $dayAfterStr ? 'background: #2563eb; color: #fff;' : 'background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;' }}">
                Lusa ({{ now()->addDays(2)->isoFormat('D MMM') }})
            </a>
        </div>

        {{-- Custom Date Picker Form --}}
        <form method="GET" action="{{ route('student.dashboard') }}" style="display: flex; align-items: center; gap: 8px;">
            <div style="position: relative; display: flex; align-items: center;">
                <input type="date" name="date" id="filter-date-input"
                       value="{{ $selectedDateStr ?? '' }}"
                       min="{{ now()->format('Y-m-d') }}"
                       onchange="this.form.submit()"
                       class="form-control"
                       style="padding: 5px 10px; font-size: 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; cursor: pointer; color: #1e293b; font-weight: 500;">
            </div>
            @if($filterDate)
                <a href="{{ route('student.dashboard') }}"
                   title="Reset Filter Tanggal"
                   class="btn"
                   style="padding: 5px 10px; font-size: 12px; background: #fee2e2; color: #991b1b; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    ✕ Reset
                </a>
            @endif
        </form>
    </div>

    @if($filterDate)
        <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #e2e8f0; display: flex; align-items: center; justify-content: space-between; font-size: 12px;">
            <span style="color: #4338ca; font-weight: 600;">
                📅 Menampilkan jadwal untuk: <strong>{{ $filterDate->isoFormat('dddd, D MMMM Y') }}</strong>
            </span>
            <span style="color: #64748b;">
                Ditemukan <strong>{{ $schedules->count() }}</strong> sesi belajar
            </span>
        </div>
    @endif
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
        <div style="text-align: center; padding: 40px 20px; color: #64748b;">
            <p style="font-size: 15px; font-weight: 500; margin-bottom: 6px;">Belum ada slot jadwal yang dibuka untuk tanggal ini.</p>
            <p style="font-size: 13px; color: #94a3b8;">Silakan pilih tanggal lain atau klik <a href="{{ route('student.dashboard') }}" style="color: #2563eb; font-weight: 600;">Semua Jadwal</a>.</p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 10px 14px; border: 1px solid #e2e8f0; text-align: left;">Sesi &amp; Jam</th>
                        <th style="padding: 10px 14px; border: 1px solid #e2e8f0; text-align: left;">Mata Pelajaran</th>
                        <th style="padding: 10px 14px; border: 1px solid #e2e8f0; text-align: left;">Pengajar (Tutor)</th>
                        <th style="padding: 10px 14px; border: 1px solid #e2e8f0; text-align: left;">Program &amp; Ruangan</th>
                        <th style="padding: 10px 14px; border: 1px solid #e2e8f0; text-align: center;">Sisa Kursi</th>
                        <th style="padding: 10px 14px; border: 1px solid #e2e8f0; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="student-schedules-tbody">
                    @php $lastDate = null; @endphp
                    @foreach($schedules as $s)
                        @php
                            $isReguler = $s->program->code === 'reguler';
                            $rowCat = $isReguler ? 'row-reguler' : 'row-special';
                            $scheduleDate = \Carbon\Carbon::parse($s->date);
                        @endphp

                        {{-- Header Pemisah Hari jika menampilkan Semua Jadwal (tanpa filter tanggal tunggal) --}}
                        @if(! $filterDate && $lastDate !== $s->date)
                            @php $lastDate = $s->date; @endphp
                            <tr class="date-divider-row" style="background: #f1f5f9;">
                                <td colspan="6" style="padding: 8px 14px; border: 1px solid #e2e8f0; font-weight: 700; color: #334155; font-size: 13px;">
                                    📅 {{ $scheduleDate->isoFormat('dddd, D MMMM Y') }}
                                    @if($scheduleDate->isToday())
                                        <span class="badge" style="background: #dcfce7; color: #15803d; margin-left: 6px; font-size: 11px; padding: 2px 8px;">Hari Ini</span>
                                    @elseif($scheduleDate->isTomorrow())
                                        <span class="badge" style="background: #dbeafe; color: #1e40af; margin-left: 6px; font-size: 11px; padding: 2px 8px;">Besok</span>
                                    @endif
                                </td>
                            </tr>
                        @endif

                        <tr class="student-schedule-row {{ $rowCat }}">
                            <td style="padding: 12px 14px; border: 1px solid #e2e8f0;">
                                <strong style="color: #334155; font-size: 13px;">{{ $s->timeSlot->name }}</strong><br>
                                <small style="color: #64748b;">{{ substr($s->timeSlot->start_time, 0, 5) }} &ndash; {{ substr($s->timeSlot->end_time, 0, 5) }}</small>
                            </td>
                            <td style="padding: 12px 14px; border: 1px solid #e2e8f0;">
                                <strong style="font-size: 14px; color: #1e293b;">{{ $s->subject->name }}</strong>
                            </td>
                            <td style="padding: 12px 14px; border: 1px solid #e2e8f0; font-weight: 600; color: #334155;">
                                {{ $s->tutor->name }}
                            </td>
                            <td style="padding: 12px 14px; border: 1px solid #e2e8f0;">
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
                            <td style="padding: 12px 14px; border: 1px solid #e2e8f0; text-align: center;">
                                <strong style="color: #059669; font-size: 13px;">{{ $s->remaining_seats }} kursi lagi</strong>
                                <small style="display: block; color: #64748b;">dari kuota {{ $s->quota_max }}</small>
                            </td>
                            <td style="padding: 12px 14px; border: 1px solid #e2e8f0; text-align: center;">
                                <form action="{{ route('student.bookings.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="schedule_id" value="{{ $s->id }}">
                                    <button type="submit" class="btn btn-primary" style="padding: 7px 14px; font-size: 13px;" {{ $weeklyBookingCount >= $maxWeekly ? 'disabled' : '' }}>
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
    $subjectTutorMap = $subjects->mapWithKeys(function ($s) {
        return [$s->id => [
            'id'     => $s->id,
            'tutors' => $s->tutors->map(function ($t) {
                            return ['id' => $t->id, 'name' => $t->name];
                        })->values(),
        ]];
    });
@endphp

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

    // Perbarui visibilitas pemisah hari (jangan tampilkan pemisah hari jika tidak ada jadwal di bawahnya)
    document.querySelectorAll('.date-divider-row').forEach(div => {
        let next = div.nextElementSibling;
        let hasVisible = false;
        while (next && !next.classList.contains('date-divider-row')) {
            if (next.classList.contains('student-schedule-row') && next.style.display !== 'none') {
                hasVisible = true;
                break;
            }
            next = next.nextElementSibling;
        }
        div.style.display = hasVisible ? '' : 'none';
    });
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
