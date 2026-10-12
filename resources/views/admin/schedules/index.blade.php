@extends('layouts.app')

@section('title', 'Kelola Jadwal')

@section('content')
<div class="page-header">
    <div>
        <h1>Kelola Jadwal Operasional</h1>
        <p>Pantau seluruh slot bimbingan belajar, atur alokasi pengajar, dan masukkan murid.</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="document.getElementById('dlg-create-sched').showModal()">+ Buka slot baru</button>
</div>

{{-- Filter Toolbar --}}
<div class="card" style="padding:16px 20px;margin-bottom:18px;">
    <form method="GET" action="{{ route('admin.schedules.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">
        <div style="flex:1;min-width:140px;">
            <label style="font-size:12px;font-weight:700;color:var(--muted);display:block;margin-bottom:4px;">Tanggal</label>
            <input type="date" name="date" value="{{ request('date') }}" class="form-control" style="padding:8px 10px;font-size:13px;">
        </div>

        <div style="flex:1;min-width:140px;">
            <label style="font-size:12px;font-weight:700;color:var(--muted);display:block;margin-bottom:4px;">Pengajar</label>
            <select name="tutor_id" class="form-control" style="padding:8px 10px;font-size:13px;">
                <option value="">Semua Pengajar</option>
                @foreach($tutors as $tut)
                    <option value="{{ $tut->id }}" {{ request('tutor_id') == $tut->id ? 'selected' : '' }}>{{ $tut->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="flex:1;min-width:130px;">
            <label style="font-size:12px;font-weight:700;color:var(--muted);display:block;margin-bottom:4px;">Ruangan</label>
            <select name="room_id" class="form-control" style="padding:8px 10px;font-size:13px;">
                <option value="">Semua Ruang</option>
                @foreach($rooms as $rm)
                    <option value="{{ $rm->id }}" {{ request('room_id') == $rm->id ? 'selected' : '' }}>{{ $rm->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="flex:1;min-width:130px;">
            <label style="font-size:12px;font-weight:700;color:var(--muted);display:block;margin-bottom:4px;">Program</label>
            <select name="program_id" class="form-control" style="padding:8px 10px;font-size:13px;">
                <option value="">Semua Program</option>
                @foreach($programs as $pr)
                    <option value="{{ $pr->id }}" {{ request('program_id') == $pr->id ? 'selected' : '' }}>{{ $pr->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="flex:1;min-width:120px;">
            <label style="font-size:12px;font-weight:700;color:var(--muted);display:block;margin-bottom:4px;">Status</label>
            <select name="status" class="form-control" style="padding:8px 10px;font-size:13px;">
                <option value="">Semua Status</option>
                <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Aktif</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Batal</option>
            </select>
        </div>

        <div style="display:flex;gap:6px;">
            <button type="submit" class="btn btn-primary" style="padding:8px 14px;font-size:13px;">Filter</button>
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary" style="padding:8px 12px;font-size:13px;">Reset</a>
        </div>
    </form>
</div>

{{-- Tabel Jadwal --}}
<div class="card" style="padding:0;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="padding:14px 20px;">Hari &amp; Tanggal</th>
                    <th style="padding:14px 20px;">Sesi Waktu</th>
                    <th style="padding:14px 20px;">Mata Pelajaran</th>
                    <th style="padding:14px 20px;">Pengajar</th>
                    <th style="padding:14px 20px;">Ruang &amp; Program</th>
                    <th style="padding:14px 20px;text-align:center;">Keterisian Kursi</th>
                    <th style="padding:14px 20px;">Status</th>
                    <th style="padding:14px 20px;text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $schedules = $schedules ?? $allSchedules ?? collect();
                @endphp
                @forelse($schedules as $s)
                    @php
                        $isFull = $s->isFull();
                        $dateObj = \Carbon\Carbon::parse($s->date);
                    @endphp
                    <tr>
                        <td style="padding:14px 20px;">
                            <strong style="color:var(--text);font-size:13px;">{{ $dateObj->isoFormat('dddd, D MMM Y') }}</strong>
                        </td>
                        <td style="padding:14px 20px;">
                            <strong>{{ $s->timeSlot->name }}</strong>
                            <small style="display:block;color:var(--muted);font-size:12px;">{{ substr($s->timeSlot->start_time, 0, 5) }} &ndash; {{ substr($s->timeSlot->end_time, 0, 5) }}</small>
                        </td>
                        <td style="padding:14px 20px;">
                            <strong style="color:var(--primary);font-size:14px;">{{ $s->subject->name }}</strong>
                            <small style="display:block;color:var(--muted);font-size:12px;">{{ $s->educationLevel->name ?? '-' }}</small>
                        </td>
                        <td style="padding:14px 20px;">
                            <strong style="color:var(--text);">{{ $s->tutor->name }}</strong>
                        </td>
                        <td style="padding:14px 20px;">
                            <span class="badge" style="background:var(--surface-2);color:var(--text);">{{ $s->room->name }}</span>
                            <span class="badge" style="background:var(--blue-soft);color:var(--blue-text);margin-left:4px;">{{ $s->program->name }}</span>
                        </td>
                        <td style="padding:14px 20px;text-align:center;">
                            <strong style="color:{{ $isFull ? 'var(--pink-text)' : 'var(--mint-text)' }};">
                                {{ $s->booked_count }} / {{ $s->quota_max }}
                            </strong>
                            <small style="display:block;color:var(--muted);font-size:11px;">kursi</small>
                        </td>
                        <td style="padding:14px 20px;">
                            @if($s->status === 'scheduled')
                                <span class="badge" style="background:var(--blue-soft);color:var(--blue-text);">Aktif</span>
                            @elseif($s->status === 'completed')
                                <span class="badge" style="background:var(--mint);color:var(--mint-text);">Selesai</span>
                            @else
                                <span class="badge" style="background:var(--pink-soft);color:var(--pink-text);">Batal</span>
                            @endif
                        </td>
                        <td style="padding:14px 20px;text-align:right;">
                            <div style="display:inline-flex;gap:6px;align-items:center;justify-content:flex-end;">
                                @if($s->status === 'scheduled' && !$isFull)
                                    <button type="button" class="btn-icon btn-icon-secondary" title="Tambah Murid ke Sesi" aria-label="Tambah Murid"
                                        onclick="openAssign({{ $s->id }}, '{{ addslashes($s->subject->name) }}', '{{ $dateObj->isoFormat('D MMM') }}')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                                    </button>
                                @endif

                                @if($s->status === 'scheduled')
                                    <button type="button" class="btn-icon btn-icon-success" title="Tandai Selesai" aria-label="Tandai Selesai"
                                        onclick="openStatus({{ $s->id }}, 'completed')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    </button>

                                    <form action="{{ route('admin.schedules.destroy', $s->id) }}" method="POST" style="margin:0;"
                                        onsubmit="return confirm('Batalkan sesi jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-danger" title="Batalkan Sesi" aria-label="Batalkan Sesi">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="4.93" x2="19.07" y1="4.93" y2="19.07"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:36px;color:var(--muted);">
                            Tidak ada jadwal yang sesuai dengan filter pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($schedules) && method_exists($schedules, 'hasPages') && $schedules->hasPages())
        <div style="padding:16px 20px;border-top:1px solid var(--line);">
            {{ $schedules->links() }}
        </div>
    @endif
</div>

{{-- Dialog Buka Slot Jadwal Baru --}}
<dialog id="dlg-create-sched" class="card" style="width:min(92vw,560px);padding:24px;border-radius:18px;margin:auto;">
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
                    @foreach($timeSlots as $ts)
                        <option value="{{ $ts->id }}">{{ $ts->name }} ({{ substr($ts->start_time, 0, 5) }} - {{ substr($ts->end_time, 0, 5) }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2" style="gap:12px;">
            <div class="form-group">
                <label>Pengajar (Tutor)</label>
                <select name="tutor_id" class="form-control" required>
                    <option value="">Pilih Tutor</option>
                    @foreach($tutors as $tut)
                        <option value="{{ $tut->id }}">{{ $tut->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Mata Pelajaran</label>
                <select name="subject_id" class="form-control" required>
                    <option value="">Pilih Mapel</option>
                    @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2" style="gap:12px;">
            <div class="form-group">
                <label>Target Jenjang</label>
                <select name="education_level_id" class="form-control" required>
                    @foreach($educationLevels as $el)
                        <option value="{{ $el->id }}">{{ $el->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Program Belajar</label>
                <select name="program_id" id="sched-program" class="form-control" required>
                    @foreach($programs as $pr)
                        <option value="{{ $pr->id }}" data-private="{{ $pr->is_private ? '1' : '0' }}">{{ $pr->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2" style="gap:12px;">
            <div class="form-group">
                <label>Ruangan</label>
                <select name="room_id" id="sched-room" class="form-control" required>
                    @foreach($rooms as $rm)
                        <option value="{{ $rm->id }}" data-cap="{{ $rm->capacity }}">{{ $rm->name }} ({{ $rm->capacity }} kursi)</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Kapasitas Kuota Kursi</label>
                <input type="number" name="quota_max" id="sched-quota" class="form-control" min="1" max="50" value="8">
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:20px;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('dlg-create-sched').close()">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan slot</button>
        </div>
    </form>
</dialog>

{{-- Dialog Tambah Murid Langsung --}}
<dialog id="dlg-assign-student" class="card" style="width:min(90vw,440px);padding:24px;border-radius:18px;margin:auto;">
    <form id="form-assign-student" method="POST">
        @csrf
        <h3 style="font:800 18px 'Plus Jakarta Sans';margin-bottom:6px;">Masukkan Murid ke Jadwal</h3>
        <p id="assign-sub-info" style="color:var(--muted);font-size:13px;margin-bottom:16px;"></p>

        <div class="form-group">
            <label for="assign-student-id">Pilih Siswa</label>
            <select name="student_id" id="assign-student-id" class="form-control" required>
                <option value="">Pilih murid...</option>
                @foreach($students as $std)
                    <option value="{{ $std->id }}">{{ $std->name }} ({{ $std->school_origin ?? 'Siswa' }})</option>
                @endforeach
            </select>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:20px;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('dlg-assign-student').close()">Batal</button>
            <button type="submit" class="btn btn-primary">Daftarkan siswa</button>
        </div>
    </form>
</dialog>

{{-- Dialog Status Selesai / Catatan --}}
<dialog id="dlg-status" class="card" style="width:min(90vw,440px);padding:24px;border-radius:18px;margin:auto;">
    <form id="form-status" method="POST">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" id="status-val" value="completed">
        <h3 style="font:800 18px 'Plus Jakarta Sans';margin-bottom:16px;">Tandai Sesi Selesai</h3>
        
        <div class="form-group">
            <label for="status-notes">Catatan Pembelajaran (Opsional)</label>
            <input type="text" id="status-notes" name="notes" class="form-control" placeholder="Materi yang telah dibahas">
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:20px;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('dlg-status').close()">Batal</button>
            <button type="submit" class="btn btn-success">Simpan sesi selesai</button>
        </div>
    </form>
</dialog>

<script>
function openAssign(schedId, subject, date) {
    const form = document.getElementById('form-assign-student');
    form.action = '/admin/schedules/' + schedId + '/assign-student';
    document.getElementById('assign-sub-info').textContent = subject + ' (' + date + ')';
    document.getElementById('dlg-assign-student').showModal();
}

function openStatus(schedId, st) {
    const form = document.getElementById('form-status');
    form.action = '/admin/schedules/' + schedId + '/status';
    document.getElementById('status-val').value = st;
    document.getElementById('dlg-status').showModal();
}

// Auto adjust quota input when room / program changes
document.getElementById('sched-room')?.addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const cap = opt.getAttribute('data-cap');
    const progOpt = document.getElementById('sched-program').options[document.getElementById('sched-program').selectedIndex];
    if (progOpt && progOpt.getAttribute('data-private') === '1') {
        document.getElementById('sched-quota').value = 1;
    } else if (cap) {
        document.getElementById('sched-quota').value = cap;
    }
});
</script>
@endsection
