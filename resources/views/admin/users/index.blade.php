@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@push('styles')
<style>
    /* Premium Dialog Styling */
    .modal-user-dialog {
        border: 1px solid var(--line);
        border-radius: 20px;
        background: var(--surface);
        box-shadow: 0 24px 70px rgba(15, 23, 42, 0.28);
        padding: 0;
        width: min(92vw, 640px);
        max-height: 90vh;
        overflow: hidden;
        margin: auto;
    }
    .modal-user-dialog::backdrop {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(6px);
    }
    .modal-user-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid var(--line);
        background: color-mix(in srgb, var(--surface-2) 60%, var(--surface));
    }
    .modal-user-header h3 {
        font: 800 18px "Plus Jakarta Sans", sans-serif;
        color: var(--text);
        margin: 0;
        letter-spacing: -.3px;
    }
    .modal-user-header p {
        margin: 3px 0 0;
        font-size: 12.5px;
        color: var(--muted);
    }
    .modal-user-close {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--line);
        background: var(--surface);
        color: var(--muted);
        display: grid;
        place-items: center;
        cursor: pointer;
        font-size: 18px;
        line-height: 1;
        transition: all .15s ease;
    }
    .modal-user-close:hover {
        background: var(--pink-soft);
        color: var(--pink-text);
        border-color: transparent;
    }
    .modal-user-body {
        padding: 24px;
        overflow-y: auto;
        max-height: calc(90vh - 145px);
    }
    .modal-user-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding: 16px 24px;
        border-top: 1px solid var(--line);
        background: color-mix(in srgb, var(--surface-2) 40%, var(--surface));
    }
    .form-section-title {
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: var(--primary);
        margin: 20px 0 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-section-title::after {
        content: "";
        flex: 1;
        height: 1px;
        background: var(--line);
    }
    .file-upload-box {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 10px 14px;
        border-radius: 12px;
        background: var(--surface);
        border: 1px solid var(--line);
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .file-upload-box:hover {
        border-color: var(--primary);
    }
    .file-upload-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: var(--surface-2);
        color: var(--muted);
        display: grid;
        place-items: center;
        flex-shrink: 0;
        border: 1px solid var(--line);
    }
    .program-card-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }
    .program-chip-check {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 10px;
        background: var(--surface);
        border: 1px solid var(--line);
        cursor: pointer;
        transition: all .16s ease;
        user-select: none;
    }
    .program-chip-check:hover {
        border-color: var(--primary);
        background: color-mix(in srgb, var(--primary) 4%, var(--surface));
    }
    .program-chip-check input[type="checkbox"] {
        accent-color: var(--primary);
        width: 16px;
        height: 16px;
        margin: 0;
        cursor: pointer;
    }
    .program-chip-check span {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
    }
    .day-chips-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .day-chip-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        background: var(--surface);
        border: 1px solid var(--line);
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text);
        cursor: pointer;
        transition: all .15s ease;
    }
    .day-chip-label:hover {
        border-color: var(--primary);
    }
    .day-chip-label input {
        accent-color: var(--primary);
        margin: 0;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Kelola Pengguna &amp; Data Siswa</h1>
        <p>Kelola data murid (8 kelengkapan profil), pengajar, dan administrator bimbel.</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="document.getElementById('dlg-create-user').showModal()">+ Tambah pengguna</button>
</div>

{{-- Tab Navigasi Role & Pencarian --}}
<div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:12px;margin-bottom:18px;">
    <div style="display:inline-flex;gap:4px;background:var(--surface-2);padding:4px;border-radius:12px;">
        <a href="{{ route('admin.users.index', ['role' => 'student']) }}"
           class="btn {{ $roleFilter === 'student' ? 'btn-primary' : 'btn-secondary' }}"
           style="padding:7px 14px;font-size:13px;border-radius:8px;">
           Siswa / Murid
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'tutor']) }}"
           class="btn {{ $roleFilter === 'tutor' ? 'btn-primary' : 'btn-secondary' }}"
           style="padding:7px 14px;font-size:13px;border-radius:8px;">
           Tutor / Pengajar
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
           class="btn {{ $roleFilter === 'admin' ? 'btn-primary' : 'btn-secondary' }}"
           style="padding:7px 14px;font-size:13px;border-radius:8px;">
           Administrator
        </a>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex;gap:6px;">
        <input type="hidden" name="role" value="{{ $roleFilter }}">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama, email, sekolah..." style="width:240px;padding:8px 12px;font-size:13px;">
        <button type="submit" class="btn btn-primary" style="padding:8px 14px;font-size:13px;">Cari</button>
        @if(request('search'))
            <a href="{{ route('admin.users.index', ['role' => $roleFilter]) }}" class="btn btn-secondary" style="padding:8px 12px;font-size:13px;">Reset</a>
        @endif
    </form>
</div>

{{-- Tabel Pengguna --}}
<div class="card" style="padding:0;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="padding:14px 20px;">Nama &amp; Kontak</th>
                    @if($roleFilter === 'student')
                        <th style="padding:14px 20px;">Asal Sekolah &amp; Kelas</th>
                        <th style="padding:14px 20px;">Program Belajar</th>
                        <th style="padding:14px 20px;">Mapel Pilihan</th>
                        <th style="padding:14px 20px;text-align:center;">Frekuensi &amp; Durasi</th>
                        <th style="padding:14px 20px;">Hari Pilihan</th>
                    @elseif($roleFilter === 'tutor')
                        <th style="padding:14px 20px;">Mapel yang Diajar</th>
                        <th style="padding:14px 20px;">No. WhatsApp</th>
                    @else
                        <th style="padding:14px 20px;">No. WhatsApp</th>
                    @endif
                    <th style="padding:14px 20px;text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    @php
                        $duration = $u->educationLevel && $u->educationLevel->code === 'sd' ? '60 Menit' : '75 Menit';
                    @endphp
                    <tr>
                        <td style="padding:14px 20px;">
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div style="width:40px;height:40px;border-radius:10px;background:var(--primary-soft);color:var(--primary);font-weight:800;display:grid;place-items:center;overflow:hidden;flex-shrink:0;border:1px solid var(--line);">
                                    @if($u->profile_photo_url)
                                        <img src="{{ $u->profile_photo_url }}" alt="{{ $u->name }}" style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                        <span>{{ mb_substr($u->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <strong style="color:var(--text);font-size:14px;">{{ $u->name }}</strong>
                                    <small style="display:block;color:var(--muted);font-size:12px;">{{ $u->email }}</small>
                                    @if($u->phone)
                                        <small style="display:block;color:var(--muted);font-size:11px;">WA: {{ $u->phone }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>

                        @if($roleFilter === 'student')
                            <td style="padding:14px 20px;">
                                <strong>{{ $u->school_origin ?? '-' }}</strong>
                                <small style="display:block;color:var(--muted);font-size:12px;">{{ $u->grade_class ?? ($u->educationLevel->name ?? '-') }}</small>
                            </td>
                            <td style="padding:14px 20px;">
                                <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                    @php
                                        $userProgs = $u->programs->isNotEmpty() ? $u->programs : ($u->program ? collect([$u->program]) : collect());
                                    @endphp
                                    @forelse($userProgs as $up)
                                        @if($up->code === 'reguler')
                                            <span class="badge" style="background:var(--mint);color:var(--mint-text);font-weight:700;">{{ $up->name }}</span>
                                        @elseif($up->code === 'consult')
                                            <span class="badge" style="background:var(--accent-gold-soft);color:var(--accent-gold-text);font-weight:700;">{{ $up->name }}</span>
                                        @elseif($up->code === 'private_home')
                                            <span class="badge" style="background:var(--accent-terra-soft);color:var(--accent-terra-text);font-weight:700;">{{ $up->name }}</span>
                                        @else
                                            <span class="badge" style="background:var(--primary-soft);color:var(--primary);font-weight:700;">{{ $up->name }}</span>
                                        @endif
                                    @empty
                                        <span class="badge" style="background:var(--blue-soft);color:var(--blue-text);">Reguler</span>
                                    @endforelse
                                </div>
                            </td>
                            <td style="padding:14px 20px;">
                                <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                    @forelse($u->enrolledSubjects as $es)
                                        <span class="badge" style="background:var(--surface-2);color:var(--text);">{{ $es->name }}</span>
                                    @empty
                                        <span style="color:var(--muted);font-size:12px;">Semua mapel</span>
                                    @endforelse
                                </div>
                            </td>
                            <td style="padding:14px 20px;text-align:center;">
                                <strong style="color:var(--primary);">{{ $u->weekly_frequency ?? 2 }}x / minggu</strong>
                                <small style="display:block;color:var(--muted);font-size:11px;">{{ $duration }}</small>
                            </td>
                            <td style="padding:14px 20px;">
                                @if(!empty($u->preferred_days) && is_array($u->preferred_days))
                                    <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                        @foreach($u->preferred_days as $pd)
                                            <span class="badge" style="background:var(--mint);color:var(--mint-text);">{{ $pd }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span style="color:var(--muted);font-size:12px;">Fleksibel</span>
                                @endif
                            </td>
                        @elseif($roleFilter === 'tutor')
                            <td style="padding:14px 20px;">
                                <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                    @forelse($u->taughtSubjects as $ts)
                                        <span class="badge" style="background:var(--primary-soft);color:var(--primary);">{{ $ts->name }}</span>
                                    @empty
                                        <span style="color:var(--muted);font-size:12px;">Belum diatur</span>
                                    @endforelse
                                </div>
                            </td>
                            <td style="padding:14px 20px;">
                                {{ $u->phone ?? '-' }}
                            </td>
                        @else
                            <td style="padding:14px 20px;">
                                {{ $u->phone ?? '-' }}
                            </td>
                        @endif

                        <td style="padding:14px 20px;text-align:right;">
                            <div style="display:inline-flex;gap:6px;align-items:center;justify-content:flex-end;">
                                <button type="button" class="btn-icon btn-icon-edit" title="Edit Pengguna" aria-label="Edit Pengguna"
                                    onclick="editUser({{ json_encode($u) }}, {{ json_encode($u->taughtSubjects->pluck('id')->merge($u->enrolledSubjects->pluck('id'))) }}, {{ json_encode($u->programs->pluck('id')) }})">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </button>
                                
                                @if($u->id !== Auth::id())
                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" style="margin:0;"
                                        onsubmit="return confirm('Hapus akun pengguna {{ addslashes($u->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-danger" title="Hapus Pengguna" aria-label="Hapus Pengguna">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:36px;color:var(--muted);">
                            Tidak ada pengguna ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div style="padding:16px 20px;border-top:1px solid var(--line);">
            {{ $users->links() }}
        </div>
    @endif
</div>

{{-- Dialog Tambah Pengguna --}}
<dialog id="dlg-create-user" class="modal-user-dialog">
    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" style="margin:0;display:flex;flex-direction:column;max-height:90vh;">
        @csrf
        <div class="modal-user-header">
            <div>
                <h3>Tambah Pengguna Baru</h3>
                <p>Buat akun murid, tutor pengajar, atau administrator baru.</p>
            </div>
            <button type="button" class="modal-user-close" onclick="document.getElementById('dlg-create-user').close()" aria-label="Tutup">&times;</button>
        </div>

        <div class="modal-user-body">
            {{-- Bagian 1: Akun & Kredensial --}}
            <div class="form-section-title" style="margin-top:0;">Informasi Akun &amp; Kredensial</div>
            <div class="grid grid-cols-2" style="gap:12px;margin-bottom:12px;">
                <div class="form-group" style="margin:0;">
                    <label>Nama Lengkap <span style="color:var(--pink-text)">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Nama lengkap" required>
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Email Login <span style="color:var(--pink-text)">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                </div>
            </div>

            <div class="grid grid-cols-2" style="gap:12px;margin-bottom:14px;">
                <div class="form-group" style="margin:0;">
                    <label>Password <span style="color:var(--pink-text)">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Role Akun <span style="color:var(--pink-text)">*</span></label>
                    <select name="role" id="create-user-role" class="form-control" required onchange="toggleRoleFields('create')">
                        <option value="student" {{ $roleFilter === 'student' ? 'selected' : '' }}>Siswa / Murid</option>
                        <option value="tutor" {{ $roleFilter === 'tutor' ? 'selected' : '' }}>Tutor / Pengajar</option>
                        <option value="admin" {{ $roleFilter === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
            </div>

            {{-- Bagian 2: Foto & Kontak --}}
            <div class="form-section-title">Foto Profil &amp; Kontak</div>
            <div class="form-group" style="margin-bottom:14px;">
                <label>Foto Profil (Opsional)</label>
                <div class="file-upload-box">
                    <div class="file-upload-icon">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    </div>
                    <div style="flex:1;">
                        <input type="file" name="profile_photo" class="form-control" accept="image/*" style="padding:6px 10px;font-size:12px;">
                        <small style="color:var(--muted);font-size:11px;display:block;margin-top:4px;">JPG, PNG, atau WEBP maks. 2MB. Sangat dianjurkan untuk tutor.</small>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2" style="gap:12px;margin-bottom:14px;">
                <div class="form-group" style="margin:0;">
                    <label>No. WhatsApp</label>
                    <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx">
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Alamat Rumah (Privat)</label>
                    <input type="text" name="address" class="form-control" placeholder="Contoh: Jl. Sukajadi No. 12">
                </div>
            </div>

            {{-- Form spesifik murid (8 data profil) --}}
            <div id="create-student-fields">
                <div class="form-section-title">Kelengkapan Data Murid</div>
                <div class="grid grid-cols-2" style="gap:12px;margin-bottom:12px;">
                    <div class="form-group" style="margin:0;">
                        <label>Asal Sekolah</label>
                        <input type="text" name="school_origin" class="form-control" placeholder="Contoh: SMAN 1 Bandung">
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label>Kelas</label>
                        <input type="text" name="grade_class" class="form-control" placeholder="Contoh: Kelas 10">
                    </div>
                </div>

                <div class="grid grid-cols-2" style="gap:12px;margin-bottom:14px;">
                    <div class="form-group" style="margin:0;">
                        <label>Jenjang Belajar</label>
                        <select name="education_level_id" class="form-control">
                            @foreach($educationLevels as $el)
                                <option value="{{ $el->id }}">{{ $el->name }} ({{ $el->code === 'sd' ? '60 Menit' : '75 Menit' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label>Frekuensi / Minggu</label>
                        <input type="number" name="weekly_frequency" class="form-control" min="1" max="7" value="2" placeholder="Jumlah pertemuan">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:14px;">
                    <label>Program Belajar (Pilih 1 atau lebih, termasuk Konsultasi)</label>
                    <div class="program-card-grid">
                        @foreach($programs as $pr)
                            <label class="program-chip-check">
                                <input type="checkbox" name="program_ids[]" value="{{ $pr->id }}" {{ $pr->code === 'reguler' ? 'checked' : '' }}>
                                <span>{{ $pr->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:14px;">
                    <label>Preferensi Hari Belajar</label>
                    <div class="day-chips-wrap">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                            <label class="day-chip-label">
                                <input type="checkbox" name="preferred_days[]" value="{{ $day }}">
                                <span>{{ $day }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Mapel (Tutor / Murid) --}}
            <div class="form-group" id="create-subject-fields" style="margin-bottom:0;">
                <div class="form-section-title">Mata Pelajaran (Tutor / Siswa)</div>
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;max-height:140px;overflow-y:auto;padding:10px;border:1px solid var(--line);border-radius:12px;background:var(--surface-2);">
                    @foreach($subjects as $sub)
                        <label style="display:flex;align-items:center;gap:6px;font-size:12.5px;cursor:pointer;">
                            <input type="checkbox" name="subject_ids[]" value="{{ $sub->id }}" style="accent-color:var(--primary);">
                            <span>{{ $sub->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="modal-user-footer">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('dlg-create-user').close()">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
        </div>
    </form>
</dialog>

{{-- Dialog Edit Pengguna --}}
<dialog id="dlg-edit-user" class="modal-user-dialog">
    <form id="form-edit-user" method="POST" enctype="multipart/form-data" style="margin:0;display:flex;flex-direction:column;max-height:90vh;">
        @csrf
        @method('PUT')
        <div class="modal-user-header">
            <div>
                <h3>Edit Data Pengguna</h3>
                <p>Perbarui profil pengguna, pilihan program, dan hak akses.</p>
            </div>
            <button type="button" class="modal-user-close" onclick="document.getElementById('dlg-edit-user').close()" aria-label="Tutup">&times;</button>
        </div>

        <div class="modal-user-body">
            <div class="form-section-title" style="margin-top:0;">Informasi Akun</div>
            <div class="grid grid-cols-2" style="gap:12px;margin-bottom:12px;">
                <div class="form-group" style="margin:0;">
                    <label>Nama Lengkap <span style="color:var(--pink-text)">*</span></label>
                    <input type="text" name="name" id="edit-user-name" class="form-control" required>
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Email Login <span style="color:var(--pink-text)">*</span></label>
                    <input type="email" name="email" id="edit-user-email" class="form-control" required>
                </div>
            </div>

            <div class="grid grid-cols-2" style="gap:12px;margin-bottom:14px;">
                <div class="form-group" style="margin:0;">
                    <label>Password Baru (Opsional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Role Akun <span style="color:var(--pink-text)">*</span></label>
                    <select name="role" id="edit-user-role" class="form-control" required onchange="toggleRoleFields('edit')">
                        <option value="student">Siswa / Murid</option>
                        <option value="tutor">Tutor / Pengajar</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>

            <div class="form-section-title">Foto Profil &amp; Kontak</div>
            <div class="form-group" style="margin-bottom:14px;">
                <label>Foto Profil (Opsional)</label>
                <div class="file-upload-box">
                    <div id="edit-photo-preview-wrap" style="display:none;align-items:center;flex-shrink:0;">
                        <img id="edit-photo-preview" src="" alt="Foto saat ini" style="width:40px;height:40px;border-radius:10px;object-fit:cover;border:1px solid var(--line);">
                    </div>
                    <div style="flex:1;">
                        <input type="file" name="profile_photo" class="form-control" accept="image/*" style="padding:6px 10px;font-size:12px;">
                        <small style="color:var(--muted);font-size:11px;display:block;margin-top:4px;">Pilih berkas baru untuk memperbarui foto.</small>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2" style="gap:12px;margin-bottom:14px;">
                <div class="form-group" style="margin:0;">
                    <label>No. WhatsApp</label>
                    <input type="text" name="phone" id="edit-user-phone" class="form-control">
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Alamat Rumah (Privat)</label>
                    <input type="text" name="address" id="edit-user-address" class="form-control">
                </div>
            </div>

            <div id="edit-student-fields">
                <div class="form-section-title">Kelengkapan Data Murid</div>
                <div class="grid grid-cols-2" style="gap:12px;margin-bottom:12px;">
                    <div class="form-group" style="margin:0;">
                        <label>Asal Sekolah</label>
                        <input type="text" name="school_origin" id="edit-user-school" class="form-control">
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label>Kelas</label>
                        <input type="text" name="grade_class" id="edit-user-grade" class="form-control">
                    </div>
                </div>

                <div class="grid grid-cols-2" style="gap:12px;margin-bottom:14px;">
                    <div class="form-group" style="margin:0;">
                        <label>Jenjang Belajar</label>
                        <select name="education_level_id" id="edit-user-edu" class="form-control">
                            @foreach($educationLevels as $el)
                                <option value="{{ $el->id }}">{{ $el->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label>Frekuensi / Minggu</label>
                        <input type="number" name="weekly_frequency" id="edit-user-freq" class="form-control" min="1" max="7">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:14px;">
                    <label>Program Belajar (Bisa pilih lebih dari 1 program)</label>
                    <div class="program-card-grid">
                        @foreach($programs as $pr)
                            <label class="program-chip-check">
                                <input type="checkbox" name="program_ids[]" value="{{ $pr->id }}" class="edit-prog-check" data-id="{{ $pr->id }}">
                                <span>{{ $pr->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-group" id="edit-subject-fields" style="margin-bottom:0;">
                <div class="form-section-title">Mata Pelajaran (Tutor / Siswa)</div>
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;max-height:140px;overflow-y:auto;padding:10px;border:1px solid var(--line);border-radius:12px;background:var(--surface-2);">
                    @foreach($subjects as $sub)
                        <label style="display:flex;align-items:center;gap:6px;font-size:12.5px;cursor:pointer;">
                            <input type="checkbox" name="subject_ids[]" value="{{ $sub->id }}" class="edit-sub-check" data-id="{{ $sub->id }}" style="accent-color:var(--primary);">
                            <span>{{ $sub->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="modal-user-footer">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('dlg-edit-user').close()">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</dialog>

<script>
function toggleRoleFields(mode) {
    const role = document.getElementById(mode + '-user-role').value;
    const stdFields = document.getElementById(mode + '-student-fields');
    if (stdFields) {
        stdFields.style.display = (role === 'student') ? 'block' : 'none';
    }
}

function editUser(user, subjectIds, programIds = []) {
    const form = document.getElementById('form-edit-user');
    form.action = '/admin/users/' + user.id;

    document.getElementById('edit-user-name').value = user.name;
    document.getElementById('edit-user-email').value = user.email;
    document.getElementById('edit-user-role').value = user.role;
    document.getElementById('edit-user-phone').value = user.phone || '';
    document.getElementById('edit-user-address').value = user.address || '';
    document.getElementById('edit-user-school').value = user.school_origin || '';
    document.getElementById('edit-user-grade').value = user.grade_class || '';
    document.getElementById('edit-user-edu').value = user.education_level_id || '';
    document.getElementById('edit-user-freq').value = user.weekly_frequency || 2;

    const previewWrap = document.getElementById('edit-photo-preview-wrap');
    const previewImg = document.getElementById('edit-photo-preview');
    if (user.profile_photo_url) {
        previewImg.src = user.profile_photo_url;
        previewWrap.style.display = 'flex';
    } else {
        previewImg.src = '';
        previewWrap.style.display = 'none';
    }

    // Centang program yang diambil murid
    const activeProgIds = (programIds && programIds.length > 0) 
        ? programIds 
        : (user.program_id ? [user.program_id] : []);
    document.querySelectorAll('.edit-prog-check').forEach(chk => {
        const id = parseInt(chk.getAttribute('data-id'));
        chk.checked = activeProgIds.includes(id);
    });

    document.querySelectorAll('.edit-sub-check').forEach(chk => {
        const id = parseInt(chk.getAttribute('data-id'));
        chk.checked = subjectIds.includes(id);
    });

    toggleRoleFields('edit');
    document.getElementById('dlg-edit-user').showModal();
}

toggleRoleFields('create');
</script>
@endsection
