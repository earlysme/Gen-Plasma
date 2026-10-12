@extends('layouts.app')

@section('title', 'Kelola Program')

@section('content')
<div class="page-header">
    <div>
        <h1>Kelola Program Belajar</h1>
        <p>Atur tipe program bimbingan, jenis kelas privat, dan alokasi fasilitas.</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="document.getElementById('dlg-create-program').showModal()">+ Tambah program</button>
</div>

<div class="card" style="padding:0;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="padding:14px 20px;">Kode</th>
                    <th style="padding:14px 20px;">Nama Program</th>
                    <th style="padding:14px 20px;">Karakteristik</th>
                    <th style="padding:14px 20px;">Lokasi / Ruang</th>
                    <th style="padding:14px 20px;text-align:center;">Total Jadwal</th>
                    <th style="padding:14px 20px;text-align:center;">Total Siswa</th>
                    <th style="padding:14px 20px;text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($programs as $p)
                    <tr>
                        <td style="padding:14px 20px;">
                            <code style="font-weight:700;color:var(--primary);background:var(--primary-soft);padding:3px 8px;border-radius:6px;">{{ $p->code }}</code>
                        </td>
                        <td style="padding:14px 20px;">
                            <strong style="font-size:14px;color:var(--text);">{{ $p->name }}</strong>
                        </td>
                        <td style="padding:14px 20px;">
                            @if($p->is_private)
                                <span class="badge" style="background:var(--pink-soft);color:var(--pink-text);">Privat (1 Murid)</span>
                            @else
                                <span class="badge" style="background:var(--mint);color:var(--mint-text);">Reguler / Kelompok</span>
                            @endif
                        </td>
                        <td style="padding:14px 20px;">
                            @if($p->requires_room)
                                <span class="badge" style="background:var(--blue-soft);color:var(--blue-text);">Ruangan Bimbel</span>
                            @else
                                <span class="badge" style="background:var(--peach);color:var(--peach-text);">Luar / Rumah Siswa</span>
                            @endif
                        </td>
                        <td style="padding:14px 20px;text-align:center;font-weight:700;">
                            {{ $p->schedules_count }} sesi
                        </td>
                        <td style="padding:14px 20px;text-align:center;font-weight:700;">
                            {{ $p->students_count }} siswa
                        </td>
                        <td style="padding:14px 20px;text-align:right;">
                            <div style="display:inline-flex;gap:6px;align-items:center;justify-content:flex-end;">
                                <button type="button" class="btn-icon btn-icon-edit" title="Edit Program" aria-label="Edit Program"
                                    onclick="editProgram({{ json_encode($p) }})">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </button>
                                <form action="{{ route('admin.programs.destroy', $p->id) }}" method="POST" style="margin:0;"
                                    onsubmit="return confirm('Hapus program ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-danger" title="Hapus Program" aria-label="Hapus Program">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:32px;color:var(--muted);">
                            Belum ada program belajar yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Dialog Tambah Program --}}
<dialog id="dlg-create-program" class="card" style="width:min(90vw,480px);padding:24px;border-radius:18px;margin:auto;">
    <form action="{{ route('admin.programs.store') }}" method="POST">
        @csrf
        <h3 style="font:800 18px 'Plus Jakarta Sans';margin-bottom:16px;">Tambah Program Belajar</h3>
        
        <div class="form-group">
            <label for="create-prog-name">Nama Program</label>
            <input type="text" id="create-prog-name" name="name" class="form-control" placeholder="Contoh: Privat Bimbel Intensif" required>
        </div>
        
        <div class="form-group">
            <label for="create-prog-code">Kode Unik</label>
            <input type="text" id="create-prog-code" name="code" class="form-control" placeholder="Contoh: private_intensif" required>
        </div>

        <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
            <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
                <input type="checkbox" name="is_private" value="1">
                <span>Program Privat (Mengunci kuota 1 murid)</span>
            </label>
            <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
                <input type="checkbox" name="requires_room" value="1" checked>
                <span>Menggunakan Ruangan Fisik Bimbel</span>
            </label>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:20px;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('dlg-create-program').close()">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan program</button>
        </div>
    </form>
</dialog>

{{-- Dialog Edit Program --}}
<dialog id="dlg-edit-program" class="card" style="width:min(90vw,480px);padding:24px;border-radius:18px;margin:auto;">
    <form id="form-edit-program" method="POST">
        @csrf
        @method('PUT')
        <h3 style="font:800 18px 'Plus Jakarta Sans';margin-bottom:16px;">Edit Program Belajar</h3>
        
        <div class="form-group">
            <label for="edit-prog-name">Nama Program</label>
            <input type="text" id="edit-prog-name" name="name" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="edit-prog-code">Kode Unik</label>
            <input type="text" id="edit-prog-code" name="code" class="form-control" required>
        </div>

        <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
            <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
                <input type="checkbox" id="edit-prog-private" name="is_private" value="1">
                <span>Program Privat (Mengunci kuota 1 murid)</span>
            </label>
            <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
                <input type="checkbox" id="edit-prog-room" name="requires_room" value="1">
                <span>Menggunakan Ruangan Fisik Bimbel</span>
            </label>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:20px;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('dlg-edit-program').close()">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan perubahan</button>
        </div>
    </form>
</dialog>

<script>
function editProgram(prog) {
    const form = document.getElementById('form-edit-program');
    form.action = '/admin/programs/' + prog.id;
    document.getElementById('edit-prog-name').value = prog.name;
    document.getElementById('edit-prog-code').value = prog.code;
    document.getElementById('edit-prog-private').checked = !!prog.is_private;
    document.getElementById('edit-prog-room').checked = !!prog.requires_room;
    document.getElementById('dlg-edit-program').showModal();
}
</script>
@endsection
