@extends('layouts.app')

@section('title', 'Kelola Mata Pelajaran')

@section('content')
<div class="page-header">
    <div>
        <h1>Kelola Mata Pelajaran</h1>
        <p>Atur daftar mata pelajaran dan pemetaan tim pengajar (tutor) yang mengampu.</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="document.getElementById('dlg-create-subject').showModal()">+ Tambah mapel</button>
</div>

<div class="card" style="padding:0;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="padding:14px 20px;">Kode</th>
                    <th style="padding:14px 20px;">Mata Pelajaran</th>
                    <th style="padding:14px 20px;">Tim Pengajar (Tutor)</th>
                    <th style="padding:14px 20px;text-align:center;">Total Jadwal</th>
                    <th style="padding:14px 20px;text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $sub)
                    <tr>
                        <td style="padding:14px 20px;">
                            <code style="font-weight:700;color:var(--primary);background:var(--primary-soft);padding:3px 8px;border-radius:6px;">{{ $sub->code }}</code>
                        </td>
                        <td style="padding:14px 20px;">
                            <strong style="font-size:14px;color:var(--text);">{{ $sub->name }}</strong>
                        </td>
                        <td style="padding:14px 20px;">
                            <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                @forelse($sub->tutors as $tut)
                                    <span class="badge" style="background:var(--surface-2);color:var(--text);">{{ $tut->name }}</span>
                                @empty
                                    <span style="color:var(--muted);font-size:12px;">Belum ada tutor ditugaskan</span>
                                @endforelse
                            </div>
                        </td>
                        <td style="padding:14px 20px;text-align:center;font-weight:700;">
                            {{ $sub->schedules_count }} sesi
                        </td>
                        <td style="padding:14px 20px;text-align:right;">
                            <div style="display:inline-flex;gap:6px;align-items:center;justify-content:flex-end;">
                                <button type="button" class="btn-icon btn-icon-edit" title="Edit Mata Pelajaran" aria-label="Edit Mata Pelajaran"
                                    onclick="editSubject({{ json_encode($sub) }}, {{ json_encode($sub->tutors->pluck('id')) }})">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </button>
                                <form action="{{ route('admin.subjects.destroy', $sub->id) }}" method="POST" style="margin:0;"
                                    onsubmit="return confirm('Hapus mata pelajaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-danger" title="Hapus Mata Pelajaran" aria-label="Hapus Mata Pelajaran">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:32px;color:var(--muted);">
                            Belum ada mata pelajaran terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Dialog Tambah Mapel --}}
<dialog id="dlg-create-subject" class="card" style="width:min(90vw,520px);padding:24px;border-radius:18px;margin:auto;">
    <form action="{{ route('admin.subjects.store') }}" method="POST">
        @csrf
        <h3 style="font:800 18px 'Plus Jakarta Sans';margin-bottom:16px;">Tambah Mata Pelajaran</h3>
        
        <div class="form-group">
            <label for="create-sub-code">Kode Mapel</label>
            <input type="text" id="create-sub-code" name="code" class="form-control" placeholder="Contoh: BIO" required>
        </div>

        <div class="form-group">
            <label for="create-sub-name">Nama Mata Pelajaran</label>
            <input type="text" id="create-sub-name" name="name" class="form-control" placeholder="Contoh: Biologi" required>
        </div>

        <div class="form-group">
            <label>Pilih Tutor Pengampu</label>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;max-height:160px;overflow-y:auto;padding:8px;border:1px solid var(--line);border-radius:10px;">
                @foreach($tutors as $tut)
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
                        <input type="checkbox" name="tutor_ids[]" value="{{ $tut->id }}">
                        <span>{{ $tut->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:20px;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('dlg-create-subject').close()">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan mapel</button>
        </div>
    </form>
</dialog>

{{-- Dialog Edit Mapel --}}
<dialog id="dlg-edit-subject" class="card" style="width:min(90vw,520px);padding:24px;border-radius:18px;margin:auto;">
    <form id="form-edit-subject" method="POST">
        @csrf
        @method('PUT')
        <h3 style="font:800 18px 'Plus Jakarta Sans';margin-bottom:16px;">Edit Mata Pelajaran</h3>
        
        <div class="form-group">
            <label for="edit-sub-code">Kode Mapel</label>
            <input type="text" id="edit-sub-code" name="code" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="edit-sub-name">Nama Mata Pelajaran</label>
            <input type="text" id="edit-sub-name" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Pilih Tutor Pengampu</label>
            <div id="edit-tutors-box" style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;max-height:160px;overflow-y:auto;padding:8px;border:1px solid var(--line);border-radius:10px;">
                @foreach($tutors as $tut)
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
                        <input type="checkbox" name="tutor_ids[]" value="{{ $tut->id }}" class="edit-tut-check" data-id="{{ $tut->id }}">
                        <span>{{ $tut->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:20px;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('dlg-edit-subject').close()">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan perubahan</button>
        </div>
    </form>
</dialog>

<script>
function editSubject(sub, assignedIds) {
    const form = document.getElementById('form-edit-subject');
    form.action = '/admin/subjects/' + sub.id;
    document.getElementById('edit-sub-code').value = sub.code;
    document.getElementById('edit-sub-name').value = sub.name;

    document.querySelectorAll('.edit-tut-check').forEach(chk => {
        const id = parseInt(chk.getAttribute('data-id'));
        chk.checked = assignedIds.includes(id);
    });

    document.getElementById('dlg-edit-subject').showModal();
}
</script>
@endsection
