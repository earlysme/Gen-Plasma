@extends('layouts.app')

@section('title', 'Kelola Ruangan')

@section('content')
<div class="page-header">
    <div>
        <h1>Kelola Ruangan</h1>
        <p>Atur 8 ruangan fisik bimbel (R1–R8) dan lokasi khusus luar bimbel.</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="document.getElementById('dlg-create-room').showModal()">+ Tambah ruangan</button>
</div>

<div class="card" style="padding:0;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="padding:14px 20px;">Kode</th>
                    <th style="padding:14px 20px;">Nama Ruangan</th>
                    <th style="padding:14px 20px;">Kapasitas Kursi</th>
                    <th style="padding:14px 20px;">Jenis Fasilitas</th>
                    <th style="padding:14px 20px;">Status</th>
                    <th style="padding:14px 20px;text-align:center;">Total Jadwal</th>
                    <th style="padding:14px 20px;text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rooms as $r)
                    <tr>
                        <td style="padding:14px 20px;">
                            <code style="font-weight:700;color:var(--primary);background:var(--primary-soft);padding:3px 8px;border-radius:6px;">{{ $r->code }}</code>
                        </td>
                        <td style="padding:14px 20px;">
                            <strong style="font-size:14px;color:var(--text);">{{ $r->name }}</strong>
                        </td>
                        <td style="padding:14px 20px;">
                            <span style="font-weight:700;font-size:14px;">{{ $r->capacity }}</span> kursi
                        </td>
                        <td style="padding:14px 20px;">
                            @if($r->is_physical)
                                <span class="badge" style="background:var(--mint);color:var(--mint-text);">Fisik Bimbel</span>
                            @else
                                <span class="badge" style="background:var(--peach);color:var(--peach-text);">Non-Fisik (Rumah Siswa)</span>
                            @endif
                        </td>
                        <td style="padding:14px 20px;">
                            @if($r->is_active)
                                <span class="badge" style="background:var(--mint);color:var(--mint-text);">Aktif</span>
                            @else
                                <span class="badge" style="background:var(--pink-soft);color:var(--pink-text);">Nonaktif</span>
                            @endif
                        </td>
                        <td style="padding:14px 20px;text-align:center;font-weight:700;">
                            {{ $r->schedules_count }} sesi
                        </td>
                        <td style="padding:14px 20px;text-align:right;">
                            <div style="display:inline-flex;gap:6px;">
                                <button type="button" class="btn btn-secondary" style="padding:6px 10px;font-size:12px;"
                                    onclick="editRoom({{ json_encode($r) }})">Edit</button>
                                <form action="{{ route('admin.rooms.destroy', $r->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus ruangan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding:6px 10px;font-size:12px;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:32px;color:var(--muted);">
                            Belum ada ruangan yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Dialog Tambah Ruangan --}}
<dialog id="dlg-create-room" class="card" style="width:min(90vw,480px);padding:24px;border-radius:18px;margin:auto;">
    <form action="{{ route('admin.rooms.store') }}" method="POST">
        @csrf
        <h3 style="font:800 18px 'Plus Jakarta Sans';margin-bottom:16px;">Tambah Ruangan</h3>
        
        <div class="form-group">
            <label for="create-room-code">Kode Ruang</label>
            <input type="text" id="create-room-code" name="code" class="form-control" placeholder="Contoh: R9" required>
        </div>

        <div class="form-group">
            <label for="create-room-name">Nama Ruangan</label>
            <input type="text" id="create-room-name" name="name" class="form-control" placeholder="Contoh: Ruang 9" required>
        </div>
        
        <div class="form-group">
            <label for="create-room-capacity">Kapasitas Kursi</label>
            <input type="number" id="create-room-capacity" name="capacity" class="form-control" min="1" max="50" value="8" required>
        </div>

        <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
            <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
                <input type="checkbox" name="is_physical" value="1" checked>
                <span>Ruangan Fisik di Bimbel (Masuk ke Matriks Grid)</span>
            </label>
            <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
                <input type="checkbox" name="is_active" value="1" checked>
                <span>Status Aktif</span>
            </label>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:20px;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('dlg-create-room').close()">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan ruangan</button>
        </div>
    </form>
</dialog>

{{-- Dialog Edit Ruangan --}}
<dialog id="dlg-edit-room" class="card" style="width:min(90vw,480px);padding:24px;border-radius:18px;margin:auto;">
    <form id="form-edit-room" method="POST">
        @csrf
        @method('PUT')
        <h3 style="font:800 18px 'Plus Jakarta Sans';margin-bottom:16px;">Edit Ruangan</h3>
        
        <div class="form-group">
            <label for="edit-room-code">Kode Ruang</label>
            <input type="text" id="edit-room-code" name="code" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="edit-room-name">Nama Ruangan</label>
            <input type="text" id="edit-room-name" name="name" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="edit-room-capacity">Kapasitas Kursi</label>
            <input type="number" id="edit-room-capacity" name="capacity" class="form-control" min="1" max="50" required>
        </div>

        <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
            <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
                <input type="checkbox" id="edit-room-physical" name="is_physical" value="1">
                <span>Ruangan Fisik di Bimbel (Masuk ke Matriks Grid)</span>
            </label>
            <label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer;">
                <input type="checkbox" id="edit-room-active" name="is_active" value="1">
                <span>Status Aktif</span>
            </label>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:20px;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('dlg-edit-room').close()">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan perubahan</button>
        </div>
    </form>
</dialog>

<script>
function editRoom(room) {
    const form = document.getElementById('form-edit-room');
    form.action = '/admin/rooms/' + room.id;
    document.getElementById('edit-room-code').value = room.code;
    document.getElementById('edit-room-name').value = room.name;
    document.getElementById('edit-room-capacity').value = room.capacity;
    document.getElementById('edit-room-physical').checked = !!room.is_physical;
    document.getElementById('edit-room-active').checked = !!room.is_active;
    document.getElementById('dlg-edit-room').showModal();
}
</script>
@endsection
