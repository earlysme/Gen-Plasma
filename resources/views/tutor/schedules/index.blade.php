@extends('layouts.app')

@section('title', 'Jadwal Mengajar')

@push('styles')
    <style>
        .jd {
            max-width: 1120px;
            margin: 0 auto;
        }

        /* Kartu & judul */
        .jd-card {
            margin-bottom: 16px;
            padding: 18px 20px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .jd-card-head {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 8px 16px;
            margin-bottom: 14px;
        }

        .jd-title {
            font: 700 16px/1.3 "Plus Jakarta Sans", sans-serif;
            letter-spacing: -.2px;
        }

        .jd-muted {
            color: var(--muted);
            font-size: 13px;
        }

        /* Pilih tanggal */
        .jd-days {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
        }

        .jd-day {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            padding: 10px 4px;
            color: var(--muted);
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 12px;
            transition: background .15s, border-color .15s;
        }

        .jd-day:hover {
            background: var(--surface-2);
        }

        .jd-day small {
            font-size: 12px;
            font-weight: 600;
        }

        .jd-day b {
            color: var(--text);
            font: 800 20px/1.2 "Plus Jakarta Sans", sans-serif;
        }

        .jd-day.is-on {
            color: #fff;
            background: var(--primary);
            border-color: var(--primary);
        }

        .jd-day.is-on b {
            color: #fff;
        }

        .jd-day.is-on:hover {
            background: var(--primary-dark);
        }

        .jd-day:focus-visible,
        .jd-free:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        .jd-datepick {
            position: relative;
        }

        .jd-datepick input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        /* Legenda */
        .jd-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 6px 16px;
            color: var(--muted);
            font-size: 13px;
        }

        .jd-legend span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .jd-legend b {
            color: var(--text);
        }

        .jd-legend i {
            width: 12px;
            height: 12px;
            border: 1px solid var(--line);
            border-radius: 4px;
        }

        .jd-legend .free {
            background: color-mix(in srgb, var(--mint) 45%, var(--surface));
            border-color: var(--mint-text);
        }

        .jd-legend .mine {
            background: var(--primary-soft);
            border-color: var(--primary);
        }

        .jd-legend .other {
            background: var(--surface-2);
        }

        /* Grid ruangan */
        .jd-scroll {
            overflow-x: auto;
            border: 1px solid var(--line);
            border-radius: 12px;
        }

        .jd-scroll .jd-table {
            min-width: 720px;
            table-layout: fixed;
        }

        .jd-scroll .jd-table th,
        .jd-scroll .jd-table td {
            padding: 0;
            text-align: center;
            border: 0;
            border-right: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
        }

        .jd-scroll .jd-table th:last-child,
        .jd-scroll .jd-table td:last-child {
            border-right: 0;
        }

        .jd-scroll .jd-table tr:last-child td {
            border-bottom: 0;
        }

        .jd-scroll .jd-table th {
            padding: 10px 6px;
            color: var(--text);
            background: var(--surface-2);
            font-weight: 700;
        }

        .jd-scroll .jd-table th small {
            display: block;
            color: var(--muted);
            font-size: 12px;
            font-weight: 400;
        }

        .jd-scroll .jd-table .jd-sess {
            position: sticky;
            left: 0;
            z-index: 1;
            width: 124px;
            padding: 10px 12px;
            text-align: left;
            background: var(--surface-2);
        }

        .jd-scroll .jd-table th.jd-sess {
            z-index: 2;
        }

        .jd-sess strong {
            display: block;
            font-size: 13px;
        }

        .jd-sess small {
            color: var(--muted);
            font-size: 12px;
            font-weight: 400;
        }

        .jd-cell {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            min-height: 64px;
            padding: 8px 6px;
        }

        .jd-cell strong,
        .jd-cell span {
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .jd-cell strong {
            font-size: 13px;
            font-weight: 700;
        }

        .jd-cell span {
            font-size: 12px;
        }

        .jd-free {
            color: var(--mint-text);
            background: color-mix(in srgb, var(--mint) 45%, var(--surface));
            font: 600 22px/1 "DM Sans", sans-serif;
            transition: background .15s;
        }

        .jd-free:hover {
            background: var(--mint);
        }

        .jd-free:focus-visible {
            outline-offset: -3px;
        }

        .jd-mine {
            color: var(--primary);
            background: var(--primary-soft);
        }

        .jd-mine strong {
            font-weight: 800;
        }

        .jd-other {
            color: var(--text);
            background: var(--surface-2);
        }

        .jd-other span {
            color: var(--muted);
        }

        /* Status badge */
        .jd-st-active {
            color: var(--blue-text);
            background: var(--blue-soft);
        }

        .jd-st-done {
            color: var(--mint-text);
            background: var(--mint);
        }

        .jd-st-cancel {
            color: var(--pink-text);
            background: var(--pink-soft);
        }

        /* Privat & konsultasi */
        .jd-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .jd-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 6px 16px;
            padding: 12px 14px;
            border: 1px solid var(--line);
            border-radius: 12px;
        }

        .jd-row.is-mine {
            background: var(--primary-soft);
            border-left: 3px solid var(--primary);
        }

        .jd-row-main {
            display: flex;
            flex-direction: column;
        }

        .jd-row-main strong {
            font-size: 14px;
        }

        .jd-row-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px 14px;
            color: var(--muted);
            font-size: 13px;
        }

        /* Agenda */
        .jd-item {
            padding: 14px 16px;
            border: 1px solid var(--line);
            border-radius: 12px;
        }

        .jd-item-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .jd-item h3 {
            font: 700 15px/1.3 "Plus Jakarta Sans", sans-serif;
        }

        .jd-when {
            margin-top: 2px;
            color: var(--primary);
            font-size: 13px;
            font-weight: 600;
        }

        .jd-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 4px 18px;
            margin-top: 10px;
            color: var(--muted);
            font-size: 13px;
        }

        .jd-seat {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .jd-bar {
            width: 56px;
            height: 6px;
            overflow: hidden;
            background: var(--surface-2);
            border-radius: 99px;
        }

        .jd-bar i {
            display: block;
            height: 100%;
            background: var(--primary);
            border-radius: 99px;
        }

        .jd-students {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 10px;
        }

        .jd-chip {
            padding: 3px 10px;
            background: var(--surface-2);
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
        }

        .jd-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid var(--line);
        }

        .jd-actions form {
            display: inline-flex;
        }

        .jd-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        .jd-empty {
            padding: 28px 16px;
            color: var(--muted);
            text-align: center;
        }

        .jd-empty strong {
            display: block;
            margin-bottom: 4px;
            color: var(--text);
            font-size: 15px;
        }

        .jd-empty .btn {
            margin-top: 14px;
        }

        /* Dialog (modal / bottom sheet di HP) */
        dialog.jd-dialog {
            width: min(92vw, 420px);
            padding: 0;
            color: var(--text);
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: 0 24px 60px rgba(0, 0, 0, .25);
        }

        dialog.jd-dialog::backdrop {
            background: rgba(20, 17, 47, .5);
        }

        .jd-dialog-body {
            display: block;
            padding: 20px;
        }

        .jd-dialog-body h3 {
            font: 700 17px/1.3 "Plus Jakarta Sans", sans-serif;
        }

        .jd-dialog-body>p {
            margin: 4px 0 16px;
            color: var(--muted);
        }

        .jd-dialog-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 16px;
        }

        @media (max-width: 560px) {
            .jd-card {
                padding: 16px;
            }

            dialog.jd-dialog {
                width: 100%;
                max-width: none;
                margin: auto 0 0;
                border-radius: var(--radius) var(--radius) 0 0;
            }
        }
    </style>
@endpush

@section('content')
    @php
        // Idealnya $students dikirim dari controller. Ini fallback: 1 query saja, dan hanya jika memang ada jadwal aktif.
        $students =
            $students ??
            ($schedules->contains('status', 'scheduled')
                ? \App\Models\User::role('student')->orderBy('name')->get()
                : collect());

        $statusMap = [
            'completed' => ['Selesai', 'jd-st-done'],
            'cancelled' => ['Batal', 'jd-st-cancel'],
        ];
    @endphp

    <div class="jd">
        <div class="page-header">
            <div>
                <h1>Jadwal Mengajar</h1>
                <p>Atur sesi, ruang, dan murid bimbingan Anda.</p>
            </div>
            <a href="{{ route('tutor.schedules.create', ['date' => $checkDate]) }}" class="btn btn-primary">+ Buka slot</a>
        </div>

        {{-- 1. PILIH TANGGAL --}}
        <section class="jd-card">
            <div class="jd-card-head">
                <h2 class="jd-title">{{ \Carbon\Carbon::parse($checkDate)->isoFormat('dddd, D MMMM Y') }}</h2>
                <div class="jd-datepick">
                    <button type="button" id="jd-date-btn" class="btn btn-secondary jd-sm"
                        data-url="{{ route('tutor.dashboard') }}">Pilih tanggal</button>
                    <input type="date" id="jd-date" value="{{ $checkDate }}" tabindex="-1" aria-hidden="true">
                </div>
            </div>

            <div class="jd-days">
                @foreach ($dateList as $d)
                    <a href="{{ route('tutor.dashboard', ['check_date' => $d['date']]) }}"
                        class="jd-day {{ $d['is_selected'] ? 'is-on' : '' }}"
                        @if ($d['is_selected']) aria-current="date" @endif>
                        <small>{{ $d['day_short'] }}</small>
                        <b>{{ $d['day_num'] }}</b>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- 2. GRID RUANGAN --}}
        <section class="jd-card">
            <div class="jd-card-head">
                <div>
                    <h2 class="jd-title">Ruang bimbel</h2>
                    <div class="jd-muted">{{ count($allTimeSlots) }} sesi, {{ count($physicalRooms) }} ruang</div>
                </div>
                <div class="jd-legend">
                    <span><i class="free"></i>Kosong <b>{{ $stats['empty'] }}</b></span>
                    <span><i class="mine"></i>Jadwal saya <b>{{ $stats['mine'] }}</b></span>
                    <span><i class="other"></i>Pengajar lain <b>{{ $stats['others'] }}</b></span>
                </div>
            </div>

            <div class="jd-scroll">
                <table class="jd-table">
                    <thead>
                        <tr>
                            <th class="jd-sess" scope="col">Sesi</th>
                            @foreach ($physicalRooms as $rm)
                                <th scope="col">
                                    {{ $rm->name }}
                                    <small>{{ $rm->capacity }} kursi</small>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allTimeSlots as $ts)
                            <tr>
                                <th class="jd-sess" scope="row">
                                    <strong>{{ $ts->name }}</strong>
                                    <small>{{ substr($ts->start_time, 0, 5) }}&ndash;{{ substr($ts->end_time, 0, 5) }}</small>
                                </th>
                                @foreach ($physicalRooms as $rm)
                                    @php
                                        $cell = $physicalGrid[$ts->id][$rm->id] ?? null;
                                        $isMine = $cell && $cell->tutor_id === Auth::id();
                                    @endphp
                                    <td>
                                        @if (!$cell)
                                            <a class="jd-cell jd-free"
                                                href="{{ route('tutor.schedules.create', ['date' => $checkDate, 'time_slot_id' => $ts->id, 'room_id' => $rm->id]) }}"
                                                aria-label="Buka slot {{ $rm->name }}, {{ $ts->name }}">+</a>
                                        @else
                                            <div class="jd-cell {{ $isMine ? 'jd-mine' : 'jd-other' }}">
                                                <strong>{{ $isMine ? 'Saya' : \Illuminate\Support\Str::words($cell->tutor->name, 1, '') }}</strong>
                                                <span>{{ $cell->subject->name }}</span>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        {{-- 3. PRIVAT & KONSULTASI --}}
        @if ($dayPrivateConsultSchedules->isNotEmpty())
            <section class="jd-card">
                <div class="jd-card-head">
                    <h2 class="jd-title">Privat &amp; konsultasi</h2>
                    <span class="badge jd-st-active">{{ $dayPrivateConsultSchedules->count() }} sesi</span>
                </div>
                <div class="jd-list">
                    @foreach ($dayPrivateConsultSchedules as $pcs)
                        <div class="jd-row {{ $pcs->tutor_id === Auth::id() ? 'is-mine' : '' }}">
                            <div class="jd-row-main">
                                <strong>{{ $pcs->subject->name }}</strong>
                                <span class="jd-muted">{{ $pcs->tutor->name }}</span>
                            </div>
                            <div class="jd-row-meta">
                                <span>{{ $pcs->timeSlot->name }}</span>
                                <span>{{ $pcs->room->name }}</span>
                                <span class="badge jd-st-active">{{ $pcs->program->name }}</span>
                                <span>{{ $pcs->booked_count }}/{{ $pcs->quota_max }} siswa</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- 4. AGENDA MENGAJAR SAYA --}}
        <section class="jd-card">
            <div class="jd-card-head">
                <h2 class="jd-title">Agenda mengajar saya ({{ $schedules->count() }})</h2>
            </div>

            @if ($schedules->isEmpty())
                <div class="jd-empty">
                    <strong>Belum ada jadwal mengajar</strong>
                    Klik tanda + di grid ruangan, atau buka slot baru.
                    <div>
                        <a href="{{ route('tutor.schedules.create', ['date' => $checkDate]) }}" class="btn btn-primary">+
                            Buka slot</a>
                    </div>
                </div>
            @else
                <div class="jd-list">
                    @foreach ($schedules as $s)
                        @php
                            $date = \Carbon\Carbon::parse($s->date);
                            $dateTag = $date->isToday() ? 'Hari ini' : $date->isoFormat('D MMM');
                            [$stLabel, $stClass] = $statusMap[$s->status] ?? ['Aktif', 'jd-st-active'];
                            $fill = $s->quota_max > 0 ? min(100, round(($s->booked_count / $s->quota_max) * 100)) : 0;
                        @endphp
                        <article class="jd-item">
                            <div class="jd-item-top">
                                <div>
                                    <h3>{{ $s->subject->name }}</h3>
                                    <div class="jd-when">{{ $dateTag }}, {{ $s->timeSlot->name }}</div>
                                </div>
                                <span class="badge {{ $stClass }}">{{ $stLabel }}</span>
                            </div>

                            <div class="jd-meta">
                                <span>{{ $s->room->name }}</span>
                                <span>{{ $s->program->name }}</span>
                                <span class="jd-seat">
                                    <span class="jd-bar"><i style="width: {{ $fill }}%"></i></span>
                                    {{ $s->booked_count }}/{{ $s->quota_max }} kursi
                                </span>
                            </div>

                            <div class="jd-students">
                                @forelse($s->activeBookings as $b)
                                    <span class="jd-chip">{{ data_get($b, 'student.name') }}</span>
                                @empty
                                    <span class="jd-muted">Belum ada murid.</span>
                                @endforelse
                            </div>

                            @if ($s->status === 'scheduled')
                                <div class="jd-actions">
                                    <button type="button" class="btn btn-success jd-sm" data-open="dlg-complete"
                                        data-action="{{ route('tutor.schedules.complete', $s->id) }}"
                                        data-sub="{{ $s->subject->name }}, {{ $dateTag }}">
                                        Tandai selesai
                                    </button>

                                    @if (!$s->isFull())
                                        <button type="button" class="btn btn-secondary jd-sm" data-open="dlg-assign"
                                            data-action="{{ route('tutor.schedules.assignStudent', $s->id) }}"
                                            data-sub="{{ $s->subject->name }}, {{ $dateTag }}">
                                            Tambah murid
                                        </button>
                                    @endif

                                    @if ($s->booked_count === 0)
                                        <form action="{{ route('tutor.schedules.cancel', $s->id) }}" method="POST"
                                            onsubmit="return confirm('Batalkan slot ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger jd-sm">Batalkan slot</button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    {{-- Dialog dipakai bersama oleh semua item agenda --}}
    <dialog id="dlg-complete" class="jd-dialog">
        <form method="POST" class="jd-dialog-body">
            @csrf
            <h3>Tandai sesi selesai</h3>
            <p data-sub></p>
            <div class="form-group" style="margin-bottom:0">
                <label for="jd-notes">Catatan materi</label>
                <input type="text" id="jd-notes" name="notes" class="form-control" placeholder="Materi yang dibahas">
            </div>
            <div class="jd-dialog-actions">
                <button type="button" class="btn btn-secondary" data-close>Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </dialog>

    <dialog id="dlg-assign" class="jd-dialog">
        <form method="POST" class="jd-dialog-body">
            @csrf
            <h3>Tambah murid</h3>
            <p data-sub></p>
            <div class="form-group" style="margin-bottom:0">
                <label for="jd-student">Murid</label>
                <select id="jd-student" name="student_id" class="form-control" required>
                    <option value="">Pilih murid</option>
                    @foreach ($students as $std)
                        <option value="{{ $std->id }}">{{ $std->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="jd-dialog-actions">
                <button type="button" class="btn btn-secondary" data-close>Tutup</button>
                <button type="submit" class="btn btn-primary">Tambah murid</button>
            </div>
        </form>
    </dialog>
@endsection

@push('scripts')
    <script>
        // Pilih tanggal
        (function() {
            const btn = document.getElementById('jd-date-btn');
            const input = document.getElementById('jd-date');
            if (!btn || !input) return;
            btn.addEventListener('click', () => (input.showPicker ? input.showPicker() : input.click()));
            input.addEventListener('change', () => {
                if (input.value) window.location.href = btn.dataset.url + '?check_date=' + encodeURIComponent(
                    input.value);
            });
        })();

        // Dialog agenda
        document.querySelectorAll('[data-open]').forEach(btn => {
            btn.addEventListener('click', () => {
                const dlg = document.getElementById(btn.dataset.open);
                dlg.querySelector('form').action = btn.dataset.action;
                dlg.querySelector('[data-sub]').textContent = btn.dataset.sub;
                dlg.showModal();
            });
        });
        document.querySelectorAll('dialog.jd-dialog').forEach(dlg => {
            dlg.addEventListener('click', e => {
                if (e.target === dlg) dlg.close();
            });
            dlg.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', () => dlg.close()));
        });
    </script>
@endpush
