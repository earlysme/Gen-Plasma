@extends('layouts.app')

@section('title', 'Kelola Jadwal')

@section('content')
    <div class="page-header">
        <div>
            <h1>Kelola Jadwal Operasional</h1>
            <p>Daftar jadwal bimbingan belajar. Klik 'Lihat Detail' untuk melihat siswa terdaftar dan aksi jadwal.</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('dlg-create-sched').showModal()">+ Buka
            slot baru</button>
    </div>

    {{-- Filter Toolbar --}}
    <div class="card" style="padding:14px 18px;margin-bottom:18px;">
        <form method="GET" action="{{ route('admin.schedules.index') }}"
            style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;">
            <div style="flex:1;min-width:130px;">
                <label style="font-size:11px;font-weight:700;color:var(--muted);display:block;margin-bottom:4px;">Tanggal (1
                    hari)</label>
                <input type="date" name="date" value="{{ request('date') }}" class="form-control"
                    style="padding:7px 10px;font-size:13px;">
            </div>

            <div style="flex:1;min-width:140px;">
                <label
                    style="font-size:11px;font-weight:700;color:var(--muted);display:block;margin-bottom:4px;">Pengajar</label>
                <select name="tutor_id" class="form-control" style="padding:7px 10px;font-size:13px;">
                    <option value="">Semua Pengajar</option>
                    @foreach ($tutors as $tut)
                        <option value="{{ $tut->id }}" {{ request('tutor_id') == $tut->id ? 'selected' : '' }}>
                            {{ $tut->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="flex:1;min-width:120px;">
                <label
                    style="font-size:11px;font-weight:700;color:var(--muted);display:block;margin-bottom:4px;">Ruangan</label>
                <select name="room_id" class="form-control" style="padding:7px 10px;font-size:13px;">
                    <option value="">Semua Ruang</option>
                    @foreach ($rooms as $rm)
                        <option value="{{ $rm->id }}" {{ request('room_id') == $rm->id ? 'selected' : '' }}>
                            {{ $rm->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="flex:1;min-width:120px;">
                <label
                    style="font-size:11px;font-weight:700;color:var(--muted);display:block;margin-bottom:4px;">Program</label>
                <select name="program_id" class="form-control" style="padding:7px 10px;font-size:13px;">
                    <option value="">Semua Program</option>
                    @foreach ($programs as $pr)
                        <option value="{{ $pr->id }}" {{ request('program_id') == $pr->id ? 'selected' : '' }}>
                            {{ $pr->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="flex:1;min-width:110px;">
                <label
                    style="font-size:11px;font-weight:700;color:var(--muted);display:block;margin-bottom:4px;">Status</label>
                <select name="status" class="form-control" style="padding:7px 10px;font-size:13px;">
                    <option value="">Semua</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Aktif</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>

            <div style="display:flex;gap:6px;">
                <button type="submit" class="btn btn-primary" style="padding:7px 14px;font-size:13px;">Filter</button>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary"
                    style="padding:7px 12px;font-size:13px;">Reset</a>
            </div>
        </form>
    </div>

    {{-- TABEL DAFTAR JADWAL (Pas Layar) --}}
    <div class="card" style="padding:0;overflow:hidden;border:1px solid var(--line);">
        <div
            style="padding:14px 18px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;background:var(--surface-2);">
            <div style="font-size:13px;font-weight:700;color:var(--text);">
                Menampilkan Jadwal: <span style="color:var(--primary);">{{ $startDate->isoFormat('D MMM Y') }}</span>
                @if (!$startDate->isSameDay($endDate))
                    – <span style="color:var(--primary);">{{ $endDate->isoFormat('D MMM Y') }}</span>
                @endif
            </div>
            <div style="font-size:12px;color:var(--muted);">
                Total: <strong>{{ $allSchedules->count() }}</strong> slot jadwal
            </div>
        </div>

        @if ($allSchedules->isEmpty())
            <div style="text-align:center;padding:48px 20px;color:var(--muted);">
                <p style="font-size:15px;font-weight:600;margin-bottom:6px;">Tidak ada jadwal bimbingan pada filter ini.</p>
                <p style="font-size:13px;">Silakan sesuaikan tanggal/filter di atas atau klik tombol <strong>+ Buka slot
                        baru</strong>.</p>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:13px;">
                    <thead>
                        <tr style="background:var(--surface-2);border-bottom:1px solid var(--line);text-align:left;">
                            <th style="padding:12px 16px;color:var(--muted);font-weight:700;">Waktu &amp; Tanggal</th>
                            <th style="padding:12px 16px;color:var(--muted);font-weight:700;">Mata Pelajaran</th>
                            <th style="padding:12px 16px;color:var(--muted);font-weight:700;">Pengajar</th>
                            <th style="padding:12px 16px;color:var(--muted);font-weight:700;">Program &amp; Ruangan</th>
                            <th style="padding:12px 16px;color:var(--muted);font-weight:700;text-align:center;">Kursi</th>
                            <th style="padding:12px 16px;color:var(--muted);font-weight:700;text-align:center;">Status</th>
                            <th style="padding:12px 16px;color:var(--muted);font-weight:700;text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allSchedules as $s)
                            @php
                                $isFull = $s->isFull();
                                $pct = $s->quota_max > 0 ? $s->booked_count / $s->quota_max : 0;
                                $isCompleted = $s->status === 'completed';
                                $isCancelled = $s->status === 'cancelled';
                            @endphp
                            <tr style="border-bottom:1px solid var(--line);transition:background .15s;"
                                onmouseenter="this.style.background='var(--surface-2)'"
                                onmouseleave="this.style.background='transparent'">
                                <td style="padding:12px 16px;">
                                    <div style="font-weight:700;color:var(--text);">
                                        {{ \Carbon\Carbon::parse($s->date)->isoFormat('dddd, D MMM Y') }}
                                    </div>
                                    <div style="font-size:12px;color:var(--muted);margin-top:2px;">
                                        <strong>{{ $s->timeSlot->name }}</strong>
                                        ({{ substr($s->timeSlot->start_time, 0, 5) }} &ndash;
                                        {{ substr($s->timeSlot->end_time, 0, 5) }})
                                    </div>
                                </td>
                                <td style="padding:12px 16px;">
                                    <div style="font-weight:700;color:var(--text);font-size:14px;">{{ $s->subject->name }}
                                    </div>
                                    <div style="font-size:11px;color:var(--muted);">
                                        {{ $s->educationLevel->name ?? 'Semua Jenjang' }}</div>
                                </td>
                                <td style="padding:12px 16px;font-weight:600;color:var(--text);">
                                    {{ $s->tutor->name }}
                                </td>
                                <td style="padding:12px 16px;">
                                    <span class="badge"
                                        style="background:#e0e7ff;color:#4338ca;font-weight:700;font-size:11px;">
                                        {{ $s->program->name }}
                                    </span>
                                    <div style="font-size:11px;color:var(--muted);margin-top:3px;">
                                        {{ $s->room->name }}
                                    </div>
                                </td>
                                <td style="padding:12px 16px;text-align:center;">
                                    @if ($isFull)
                                        <span class="badge" style="background:#fee2e2;color:#991b1b;font-weight:700;">
                                            {{ $s->booked_count }} / {{ $s->quota_max }} Penuh
                                        </span>
                                    @elseif($pct >= 0.7)
                                        <span class="badge" style="background:#fef9c3;color:#854d0e;font-weight:700;">
                                            {{ $s->booked_count }} / {{ $s->quota_max }} Kursi
                                        </span>
                                    @else
                                        <span class="badge" style="background:#dcfce7;color:#166534;font-weight:700;">
                                            {{ $s->booked_count }} / {{ $s->quota_max }} Kursi
                                        </span>
                                    @endif
                                </td>
                                <td style="padding:12px 16px;text-align:center;">
                                    @if ($s->status === 'scheduled')
                                        <span class="badge"
                                            style="background:#dbeafe;color:#1e40af;font-weight:700;">Aktif</span>
                                    @elseif($s->status === 'completed')
                                        <span class="badge"
                                            style="background:#dcfce7;color:#166534;font-weight:700;">Selesai</span>
                                    @else
                                        <span class="badge"
                                            style="background:#fee2e2;color:#991b1b;font-weight:700;">Batal</span>
                                    @endif
                                </td>
                                <td style="padding:12px 16px;text-align:center;">
                                    <button type="button" class="btn btn-secondary"
                                        style="padding:6px 14px;font-size:12px;font-weight:600;"
                                        onclick="openDetail({{ $s->id }})">
                                        Lihat Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- MODAL DETAIL JADWAL (Pop-up di Tengah Layar) --}}
    <dialog id="dlg-detail-schedule" class="card"
        style="width:min(92vw,520px);padding:24px;border-radius:18px;margin:auto;box-shadow:0 20px 40px rgba(0,0,0,0.25);">
        <div
            style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:18px;border-bottom:1px solid var(--line);padding-bottom:14px;">
            <div>
                <h3 id="modal-sched-subject" style="font:800 20px 'Plus Jakarta Sans';margin:0;color:var(--text);">Detail
                    Jadwal</h3>
                <p id="modal-sched-level" style="font-size:12px;color:var(--muted);margin:3px 0 0;"></p>
            </div>
            <button onclick="closeDetail()" type="button"
                style="background:none;border:none;font-size:24px;color:var(--muted);cursor:pointer;line-height:1;padding:0 4px;">&times;</button>
        </div>

        <div id="detail-content">
            <p style="color:var(--muted);text-align:center;padding:20px 0;">Memuat...</p>
        </div>
    </dialog>

    @php
        $schedulesMap = $allSchedules->mapWithKeys(function ($s) {
            return [
                $s->id => [
                    'id' => $s->id,
                    'subject' => $s->subject->name,
                    'tutor' => $s->tutor->name,
                    'room' => $s->room->name,
                    'program' => $s->program->name,
                    'date' => \Carbon\Carbon::parse($s->date)->isoFormat('dddd, D MMMM Y'),
                    'slot' =>
                        $s->timeSlot->name .
                        ' (' .
                        substr($s->timeSlot->start_time, 0, 5) .
                        ' – ' .
                        substr($s->timeSlot->end_time, 0, 5) .
                        ')',
                    'level' => $s->educationLevel->name ?? '-',
                    'booked' => $s->booked_count,
                    'quota' => $s->quota_max,
                    'status' => $s->status,
                    'is_full' => $s->isFull(),
                    'students' => $s->activeBookings->map(fn($b) => $b->student->name)->values(),
                    'url_assign' => route('admin.schedules.assignStudent', $s->id),
                    'url_status' => route('admin.schedules.updateStatus', $s->id),
                    'url_destroy' => route('admin.schedules.destroy', $s->id),
                ],
            ];
        });
    @endphp

    <script>
        const schedulesData = @json($schedulesMap);

        function openDetail(id) {
            const s = schedulesData[id];
            if (!s) {
                return;
            }

            document.getElementById('modal-sched-subject').textContent = s.subject;
            document.getElementById('modal-sched-level').textContent = s.level + ' • ' + s.program;

            const statusLabel = s.status === 'scheduled' ?
                '<span style="background:#dbeafe;color:#1e40af;padding:2px 8px;border-radius:4px;font-size:12px;font-weight:700;">Aktif</span>' :
                s.status === 'completed' ?
                '<span style="background:#dcfce7;color:#166534;padding:2px 8px;border-radius:4px;font-size:12px;font-weight:700;">Selesai</span>' :
                '<span style="background:#fee2e2;color:#991b1b;padding:2px 8px;border-radius:4px;font-size:12px;font-weight:700;">Batal</span>';

            const studentList = s.students.length ?
                s.students.map((n, i) =>
                    `<li style="font-size:13px;padding:6px 8px;border-bottom:1px solid var(--line);display:flex;align-items:center;gap:8px;"><span style="color:var(--muted);font-weight:700;font-size:11px;">${i+1}.</span> <strong style="color:var(--text);">${n}</strong></li>`
                    ).join('') :
                '<li style="font-size:13px;color:var(--muted);padding:8px 0;">Belum ada murid terdaftar pada slot ini.</li>';

            let actions = '';
            if (s.status === 'scheduled') {
                let assignBtn = '';
                if (!s.is_full) {
                    assignBtn = `<button type="button" class="btn btn-secondary" style="flex:1;font-size:13px;"
                onclick="openAssign(${s.id}, '${s.subject}', '${s.date}')">+ Masukkan Murid</button>`;
                }
                actions = `
            <div style="display:flex;gap:8px;margin-top:16px;flex-wrap:wrap;">
                ${assignBtn}
                <button type="button" class="btn btn-success" style="flex:1;font-size:13px;"
                    onclick="openStatus(${s.id}, 'completed')">Tandai Selesai</button>
            </div>
            <form action="${s.url_destroy}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan jadwal ini?')" style="margin-top:8px;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-danger" style="width:100%;font-size:13px;">Batalkan Jadwal</button>
            </form>
        `;
            }

            document.getElementById('detail-content').innerHTML = `
        <table style="width:100%;font-size:13px;border-collapse:collapse;margin-bottom:16px;">
            <tr style="border-bottom:1px solid var(--line);"><td style="padding:6px 0;color:var(--muted);width:100px;">Pengajar</td><td style="padding:6px 0;font-weight:700;color:var(--text);">${s.tutor}</td></tr>
            <tr style="border-bottom:1px solid var(--line);"><td style="padding:6px 0;color:var(--muted);">Hari &amp; Tanggal</td><td style="padding:6px 0;color:var(--text);">${s.date}</td></tr>
            <tr style="border-bottom:1px solid var(--line);"><td style="padding:6px 0;color:var(--muted);">Sesi Waktu</td><td style="padding:6px 0;font-weight:600;color:var(--text);">${s.slot}</td></tr>
            <tr style="border-bottom:1px solid var(--line);"><td style="padding:6px 0;color:var(--muted);">Ruangan</td><td style="padding:6px 0;color:var(--text);">${s.room}</td></tr>
            <tr style="border-bottom:1px solid var(--line);"><td style="padding:6px 0;color:var(--muted);">Kapasitas</td><td style="padding:6px 0;font-weight:700;color:var(--text);">${s.booked} dari ${s.quota} kursi ${s.is_full ? '<span style="color:#ef4444;">(Penuh)</span>':''}</td></tr>
            <tr><td style="padding:6px 0;color:var(--muted);">Status</td><td style="padding:6px 0;">${statusLabel}</td></tr>
        </table>
        
        <div style="background:var(--surface-2);padding:12px;border-radius:10px;margin-bottom:16px;">
            <div style="font-size:12px;font-weight:800;color:var(--muted);margin-bottom:6px;text-transform:uppercase;">
                Murid Terdaftar (${s.students.length})
            </div>
            <ul style="list-style:none;padding:0;margin:0;">${studentList}</ul>
        </div>
        
        ${actions}
    `;

            document.getElementById('dlg-detail-schedule').showModal();
        }

        function closeDetail() {
            document.getElementById('dlg-detail-schedule').close();
        }

        function openAssign(schedId, subject, date) {
            closeDetail();
            const form = document.getElementById('form-assign-student');
            form.action = '/admin/schedules/' + schedId + '/assign-student';
            document.getElementById('assign-sub-info').textContent = subject + ' (' + date + ')';
            document.getElementById('dlg-assign-student').showModal();
        }

        function openStatus(schedId, st) {
            closeDetail();
            const form = document.getElementById('form-status');
            form.action = '/admin/schedules/' + schedId + '/status';
            document.getElementById('status-val').value = st;
            document.getElementById('dlg-status').showModal();
        }

        // Hover effect pada sel jadwal
        document.querySelectorAll('.sched-card').forEach(el => {
            el.addEventListener('mouseenter', () => el.style.boxShadow = '0 4px 12px rgba(0,0,0,0.12)');
            el.addEventListener('mouseleave', () => el.style.boxShadow = '');
        });

        // Auto adjust quota input when room / program changes
        document.getElementById('sched-room')?.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            const cap = opt.getAttribute('data-cap');
            const progEl = document.getElementById('sched-program');
            const progOpt = progEl.options[progEl.selectedIndex];
            if (progOpt && progOpt.getAttribute('data-private') === '1') {
                document.getElementById('sched-quota').value = 1;
            } else if (cap) {
                document.getElementById('sched-quota').value = cap;
            }
        });
    </script>

    <style>
        .sched-card:hover {
            transform: translateY(-1px);
        }
    </style>

    {{-- Dialog Buka Slot Jadwal Baru --}}
    <dialog id="dlg-create-sched" class="card"
        style="width:min(92vw,560px);padding:24px;border-radius:18px;margin:auto;">
        <form action="{{ route('admin.schedules.store') }}" method="POST">
            @csrf
            <h3 style="font:800 18px 'Plus Jakarta Sans';margin-bottom:16px;">Buka Slot Jadwal Baru</h3>

            <div class="grid grid-cols-2" style="gap:12px;">
                <div class="form-group">
                    <label>Tanggal Pelaksanaan</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Sesi Waktu</label>
                    <select name="time_slot_id" class="form-control" required>
                        @foreach ($timeSlots as $ts)
                            <option value="{{ $ts->id }}">{{ $ts->name }} ({{ substr($ts->start_time, 0, 5) }}
                                - {{ substr($ts->end_time, 0, 5) }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2" style="gap:12px;">
                <div class="form-group">
                    <label>Pengajar (Tutor)</label>
                    <select name="tutor_id" class="form-control" required>
                        <option value="">Pilih Tutor</option>
                        @foreach ($tutors as $tut)
                            <option value="{{ $tut->id }}">{{ $tut->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Mata Pelajaran</label>
                    <select name="subject_id" class="form-control" required>
                        <option value="">Pilih Mapel</option>
                        @foreach ($subjects as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2" style="gap:12px;">
                <div class="form-group">
                    <label>Target Jenjang</label>
                    <select name="education_level_id" class="form-control" required>
                        @foreach ($educationLevels as $el)
                            <option value="{{ $el->id }}">{{ $el->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Program Belajar</label>
                    <select name="program_id" id="sched-program" class="form-control" required>
                        @foreach ($programs as $pr)
                            <option value="{{ $pr->id }}" data-private="{{ $pr->is_private ? '1' : '0' }}">
                                {{ $pr->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2" style="gap:12px;">
                <div class="form-group">
                    <label>Ruangan</label>
                    <select name="room_id" id="sched-room" class="form-control" required>
                        @foreach ($rooms as $rm)
                            <option value="{{ $rm->id }}" data-cap="{{ $rm->capacity }}">{{ $rm->name }}
                                ({{ $rm->capacity }} kursi)</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Kapasitas Kuota Kursi</label>
                    <input type="number" name="quota_max" id="sched-quota" class="form-control" min="1"
                        max="50" value="8">
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:20px;">
                <button type="button" class="btn btn-secondary"
                    onclick="document.getElementById('dlg-create-sched').close()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan slot</button>
            </div>
        </form>
    </dialog>

    {{-- Dialog Tambah Murid Langsung --}}
    <dialog id="dlg-assign-student" class="card"
        style="width:min(90vw,440px);padding:24px;border-radius:18px;margin:auto;">
        <form id="form-assign-student" method="POST">
            @csrf
            <h3 style="font:800 18px 'Plus Jakarta Sans';margin-bottom:6px;">Masukkan Murid ke Jadwal</h3>
            <p id="assign-sub-info" style="color:var(--muted);font-size:13px;margin-bottom:16px;"></p>

            <div class="form-group">
                <label for="assign-student-id">Pilih Siswa</label>
                <select name="student_id" id="assign-student-id" class="form-control" required>
                    <option value="">Pilih murid...</option>
                    @foreach ($students as $std)
                        <option value="{{ $std->id }}">{{ $std->name }} ({{ $std->school_origin ?? 'Siswa' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:20px;">
                <button type="button" class="btn btn-secondary"
                    onclick="document.getElementById('dlg-assign-student').close()">Batal</button>
                <button type="submit" class="btn btn-primary">Daftarkan siswa</button>
            </div>
        </form>
    </dialog>

    {{-- Dialog Status Selesai --}}
    <dialog id="dlg-status" class="card" style="width:min(90vw,440px);padding:24px;border-radius:18px;margin:auto;">
        <form id="form-status" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" id="status-val" value="completed">
            <h3 style="font:800 18px 'Plus Jakarta Sans';margin-bottom:16px;">Tandai Sesi Selesai</h3>

            <div class="form-group">
                <label for="status-notes">Catatan Pembelajaran (Opsional)</label>
                <input type="text" id="status-notes" name="notes" class="form-control"
                    placeholder="Materi yang telah dibahas">
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:20px;">
                <button type="button" class="btn btn-secondary"
                    onclick="document.getElementById('dlg-status').close()">Batal</button>
                <button type="submit" class="btn btn-success">Simpan sesi selesai</button>
            </div>
        </form>
    </dialog>
@endsection
