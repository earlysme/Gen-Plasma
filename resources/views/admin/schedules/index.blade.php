@extends('layouts.app')

@section('title', 'Kelola Jadwal')

@push('styles')
<style>
    /* Styling Rework Kelola Jadwal Operasional */
    .schedule-toolbar-card {
        padding: 16px 20px;
        margin-bottom: 20px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
    }

    .filter-field {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .filter-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--muted);
    }

    .filter-control {
        padding: 8px 12px;
        font-size: 13px;
        border-radius: 8px;
        border: 1px solid var(--line);
        background: var(--surface);
        color: var(--text);
        outline: none;
        transition: all .15s ease;
    }

    .filter-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 15%, transparent);
    }

    .btn-filter-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 700;
        color: var(--muted);
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 8px;
        text-decoration: none;
        transition: all .15s ease;
    }

    .btn-filter-reset:hover {
        background: var(--surface-2);
        color: var(--text);
        border-color: var(--line);
    }

    .schedule-table-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .table-info-banner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 20px;
        border-bottom: 1px solid var(--line);
        background: var(--surface);
        flex-wrap: wrap;
        gap: 12px;
    }

    .table-date-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13.5px;
        font-weight: 800;
        color: var(--text);
    }

    .table-count-badge {
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 99px;
        background: var(--surface-2);
        color: var(--muted);
        border: 1px solid var(--line);
    }

    .sched-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        text-align: left;
    }

    .sched-table thead tr {
        background: color-mix(in srgb, var(--surface) 94%, var(--text) 6%);
        border-bottom: 1px solid var(--line);
    }

    .sched-table th {
        padding: 12px 18px;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--muted);
        white-space: nowrap;
    }

    .sched-table tbody tr {
        border-bottom: 1px solid var(--line);
        transition: background .15s ease;
    }

    .sched-table tbody tr:hover {
        background: color-mix(in srgb, var(--surface) 96%, var(--primary) 4%);
    }

    .sched-table td {
        padding: 14px 18px;
        vertical-align: middle;
    }

    /* Avatar Tutor Mini */
    .tutor-avatar-mini {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: var(--primary-soft);
        color: var(--primary);
        font-size: 11px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
        border: 1px solid var(--line);
    }

    .tutor-avatar-mini img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Program Chip */
    .chip-program {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        background: color-mix(in srgb, var(--primary) 10%, transparent);
        color: var(--primary);
        border: 1px solid color-mix(in srgb, var(--primary) 20%, transparent);
    }

    /* Kursi Pill */
    .pill-seat {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 99px;
        font-size: 11.5px;
        font-weight: 700;
        white-space: nowrap;
    }

    .pill-seat-available {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .pill-seat-medium {
        background: #fffbeb;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .pill-seat-full {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* Status Pill */
    .pill-status {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 99px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .pill-status-scheduled {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .pill-status-completed {
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .pill-status-cancelled {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    /* Action Button */
    .btn-table-detail {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 700;
        color: var(--text);
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        cursor: pointer;
        transition: all .15s ease;
    }

    .btn-table-detail:hover {
        background: var(--primary);
        color: var(--primary-contrast);
        border-color: var(--primary);
        box-shadow: 0 3px 8px color-mix(in srgb, var(--primary) 25%, transparent);
    }

    /* Dark Mode Adjustments */
    html[data-theme="dark"] .pill-seat-available {
        background: rgba(16, 185, 129, 0.15);
        color: #6ee7b7;
        border-color: rgba(16, 185, 129, 0.3);
    }

    html[data-theme="dark"] .pill-seat-medium {
        background: rgba(245, 158, 11, 0.15);
        color: #fcd34d;
        border-color: rgba(245, 158, 11, 0.3);
    }

    html[data-theme="dark"] .pill-seat-full {
        background: rgba(239, 68, 68, 0.15);
        color: #fca5a5;
        border-color: rgba(239, 68, 68, 0.3);
    }

    html[data-theme="dark"] .pill-status-scheduled {
        background: rgba(59, 130, 246, 0.15);
        color: #93c5fd;
        border-color: rgba(59, 130, 246, 0.3);
    }

    html[data-theme="dark"] .pill-status-completed {
        background: rgba(148, 163, 184, 0.15);
        color: #cbd5e1;
        border-color: rgba(148, 163, 184, 0.3);
    }

    html[data-theme="dark"] .pill-status-cancelled {
        background: rgba(239, 68, 68, 0.15);
        color: #fca5a5;
        border-color: rgba(239, 68, 68, 0.3);
    }
</style>
@endpush

@section('content')
    <div class="page-header" style="flex-wrap: wrap; gap: 16px;">
        <div>
            <h1>Kelola Jadwal Operasional</h1>
            <p>Daftar jadwal bimbingan belajar. Klik 'Lihat Detail' untuk melihat siswa terdaftar dan aksi jadwal.</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('dlg-create-sched').showModal()">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Buka Slot Baru
        </button>
    </div>

    {{-- Filter Toolbar Rework --}}
    <div class="schedule-toolbar-card">
        <form method="GET" action="{{ route('admin.schedules.index') }}"
            style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">
            
            <div class="filter-field" style="flex:1;min-width:140px;">
                <label class="filter-label">Tanggal (1 Hari)</label>
                <input type="date" name="date" value="{{ request('date') }}" class="filter-control">
            </div>

            <div class="filter-field" style="flex:1;min-width:140px;">
                <label class="filter-label">Pengajar</label>
                <select name="tutor_id" class="filter-control">
                    <option value="">Semua Pengajar</option>
                    @foreach ($tutors as $tut)
                        <option value="{{ $tut->id }}" {{ request('tutor_id') == $tut->id ? 'selected' : '' }}>
                            {{ $tut->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-field" style="flex:1;min-width:130px;">
                <label class="filter-label">Ruangan</label>
                <select name="room_id" class="filter-control">
                    <option value="">Semua Ruang</option>
                    @foreach ($rooms as $rm)
                        <option value="{{ $rm->id }}" {{ request('room_id') == $rm->id ? 'selected' : '' }}>
                            {{ $rm->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-field" style="flex:1;min-width:130px;">
                <label class="filter-label">Program</label>
                <select name="program_id" class="filter-control">
                    <option value="">Semua Program</option>
                    @foreach ($programs as $pr)
                        <option value="{{ $pr->id }}" {{ request('program_id') == $pr->id ? 'selected' : '' }}>
                            {{ $pr->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-field" style="flex:1;min-width:110px;">
                <label class="filter-label">Status</label>
                <select name="status" class="filter-control">
                    <option value="">Semua</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Aktif</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>

            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary" style="padding:8px 16px;">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('admin.schedules.index') }}" class="btn-filter-reset">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- TABEL DAFTAR JADWAL REWORK --}}
    <div class="schedule-table-card">
        <div class="table-info-banner">
            <div class="table-date-badge">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--muted);">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span>Menampilkan Jadwal:</span>
                <span style="color:var(--primary);">{{ $startDate->isoFormat('D MMM Y') }}</span>
                @if (!$startDate->isSameDay($endDate))
                    <span>&ndash;</span>
                    <span style="color:var(--primary);">{{ $endDate->isoFormat('D MMM Y') }}</span>
                @endif
            </div>
            <div class="table-count-badge">
                Total: <strong style="color:var(--text);">{{ $allSchedules->count() }}</strong> slot jadwal
            </div>
        </div>

        @if ($allSchedules->isEmpty())
            <div style="text-align:center;padding:56px 20px;color:var(--muted);">
                <div style="width:48px;height:48px;border-radius:12px;background:var(--surface-2);border:1px solid var(--line);display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;color:var(--muted);">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
                <p style="font-size:15px;font-weight:700;color:var(--text);margin-bottom:4px;">Tidak ada jadwal bimbingan pada filter ini.</p>
                <p style="font-size:13px;max-width:380px;margin:0 auto;">Silakan sesuaikan filter tanggal di atas atau buka slot bimbingan baru.</p>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="sched-table">
                    <thead>
                        <tr>
                            <th>Waktu &amp; Tanggal</th>
                            <th>Mata Pelajaran</th>
                            <th>Pengajar</th>
                            <th>Program &amp; Ruangan</th>
                            <th style="text-align:center;">Kursi</th>
                            <th style="text-align:center;">Status</th>
                            <th style="text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allSchedules as $s)
                            @php
                                $isFull = $s->isFull();
                                $pct = $s->quota_max > 0 ? $s->booked_count / $s->quota_max : 0;
                                $isCompleted = $s->status === 'completed';
                                $isCancelled = $s->status === 'cancelled';
                                
                                // Inisial Tutor
                                $words = explode(' ', trim($s->tutor->name));
                                $tutorInit = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                            @endphp
                            <tr>
                                <td>
                                    <div style="font-weight:700;color:var(--text);font-size:13.5px;">
                                        {{ \Carbon\Carbon::parse($s->date)->isoFormat('dddd, D MMM Y') }}
                                    </div>
                                    <div style="font-size:12px;color:var(--muted);margin-top:2px;">
                                        <strong style="color:var(--text);">{{ $s->timeSlot->name }}</strong>
                                        <span>({{ substr($s->timeSlot->start_time, 0, 5) }} &ndash; {{ substr($s->timeSlot->end_time, 0, 5) }})</span>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight:800;color:var(--text);font-size:14px;">
                                        {{ $s->subject->name }}
                                    </div>
                                    <div style="font-size:11.5px;color:var(--muted);margin-top:1px;">
                                        {{ $s->educationLevel->name ?? 'Semua Jenjang' }}
                                    </div>
                                </td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <span class="tutor-avatar-mini">
                                            @if($s->tutor->profile_photo_url)
                                                <img src="{{ $s->tutor->profile_photo_url }}" alt="{{ $s->tutor->name }}">
                                            @else
                                                {{ $tutorInit }}
                                            @endif
                                        </span>
                                        <span style="font-weight:700;color:var(--text);">{{ $s->tutor->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="chip-program">
                                        {{ $s->program->name }}
                                    </span>
                                    <div style="font-size:11.5px;color:var(--muted);margin-top:3px;">
                                        {{ $s->room->name }}
                                    </div>
                                </td>
                                <td style="text-align:center;">
                                    @if ($isFull)
                                        <span class="pill-seat pill-seat-full">
                                            {{ $s->booked_count }} / {{ $s->quota_max }} Penuh
                                        </span>
                                    @elseif($pct >= 0.7)
                                        <span class="pill-seat pill-seat-medium">
                                            {{ $s->booked_count }} / {{ $s->quota_max }} Kursi
                                        </span>
                                    @else
                                        <span class="pill-seat pill-seat-available">
                                            {{ $s->booked_count }} / {{ $s->quota_max }} Kursi
                                        </span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    @if ($s->status === 'scheduled')
                                        <span class="pill-status pill-status-scheduled">Aktif</span>
                                    @elseif($s->status === 'completed')
                                        <span class="pill-status pill-status-completed">Selesai</span>
                                    @else
                                        <span class="pill-status pill-status-cancelled">Batal</span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    <button type="button" class="btn-table-detail" onclick="openDetail({{ $s->id }})">
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

    {{-- MODAL DETAIL JADWAL --}}
    <dialog id="dlg-detail-schedule" class="card"
        style="width:min(92vw,520px);padding:24px;border-radius:18px;margin:auto;box-shadow:0 20px 40px rgba(0,0,0,0.25);">
        <div
            style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:18px;border-bottom:1px solid var(--line);padding-bottom:14px;">
            <div>
                <h3 id="modal-sched-subject" style="font:800 20px 'Plus Jakarta Sans';margin:0;color:var(--text);">Detail Jadwal</h3>
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
                '<span class="pill-status pill-status-scheduled">Aktif</span>' :
                s.status === 'completed' ?
                '<span class="pill-status pill-status-completed">Selesai</span>' :
                '<span class="pill-status pill-status-cancelled">Batal</span>';

            const studentList = s.students.length ?
                s.students.map((n, i) =>
                    `<li style="font-size:13px;padding:8px 10px;border-bottom:1px solid var(--line);display:flex;align-items:center;gap:8px;"><span style="color:var(--muted);font-weight:700;font-size:11px;">${i+1}.</span> <strong style="color:var(--text);">${n}</strong></li>`
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
            <tr style="border-bottom:1px solid var(--line);"><td style="padding:8px 0;color:var(--muted);width:110px;">Pengajar</td><td style="padding:8px 0;font-weight:700;color:var(--text);">${s.tutor}</td></tr>
            <tr style="border-bottom:1px solid var(--line);"><td style="padding:8px 0;color:var(--muted);">Hari &amp; Tanggal</td><td style="padding:8px 0;color:var(--text);">${s.date}</td></tr>
            <tr style="border-bottom:1px solid var(--line);"><td style="padding:8px 0;color:var(--muted);">Sesi Waktu</td><td style="padding:8px 0;font-weight:600;color:var(--text);">${s.slot}</td></tr>
            <tr style="border-bottom:1px solid var(--line);"><td style="padding:8px 0;color:var(--muted);">Ruangan</td><td style="padding:8px 0;color:var(--text);">${s.room}</td></tr>
            <tr style="border-bottom:1px solid var(--line);"><td style="padding:8px 0;color:var(--muted);">Kapasitas</td><td style="padding:8px 0;font-weight:700;color:var(--text);">${s.booked} dari ${s.quota} kursi ${s.is_full ? '<span style="color:#ef4444;">(Penuh)</span>':''}</td></tr>
            <tr><td style="padding:8px 0;color:var(--muted);">Status</td><td style="padding:8px 0;">${statusLabel}</td></tr>
        </table>
        
        <div style="background:var(--surface-2);padding:12px;border-radius:10px;margin-bottom:16px;border:1px solid var(--line);">
            <div style="font-size:11.5px;font-weight:800;color:var(--muted);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px;">
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
