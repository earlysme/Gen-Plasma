@extends('layouts.app')

@section('title', 'Kelola Pengguna')

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
                                <span class="badge" style="background:var(--blue-soft);color:var(--blue-text);">
                                    {{ $u->program->name ?? 'Reguler' }}
                                </span>
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
                                    onclick="editUser({{ json_encode($u) }}, {{ json_encode($u->taughtSubjects->pluck('id')->merge($u->enrolledSubjects->pluck('id'))) }})">
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
<dialog id="dlg-create-user" class="card" style="width:min(92vw,560px);padding:24px;border-radius:18px;margin:auto;">
    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h3 style="font:800 18px 'Plus Jakarta Sans';margin-bottom:16px;">Tambah Pengguna Baru</h3>

        <div class="grid grid-cols-2" style="gap:12px;">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" placeholder="Nama lengkap" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
            </div>
        </div>

        <div class="grid grid-cols-2" style="gap:12px;">
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
            </div>

            <div class="form-group">
                <label>Role Akun</label>
                <select name="role" id="create-user-role" class="form-control" required onchange="toggleRoleFields('create')">
                    <option value="student" {{ $roleFilter === 'student' ? 'selected' : '' }}>Siswa / Murid</option>
                    <option value="tutor" {{ $roleFilter === 'tutor' ? 'selected' : '' }}>Tutor / Pengajar</option>
                    <option value="admin" {{ $roleFilter === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom:12px;">
            <label>Foto Profil (Opsional - Tutor/User)</label>
            <input type="file" name="profile_photo" class="form-control" accept="image/*" style="padding:6px 10px;">
            <small style="color:var(--muted);font-size:11.5px;">Format JPG, PNG, WEBP max 2MB. Sangat dianjurkan untuk tutor agar terlihat di katalog siswa.</small>
        </div>

        <div class="grid grid-cols-2" style="gap:12px;">
            <div class="form-group">
                <label>No. WhatsApp</label>
                <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx">
            </div>

            <div class="form-group">
                <label>Alamat (Untuk Privat Rumah)</label>
                <input type="text" name="address" class="form-control" placeholder="Alamat rumah">
            </div>
        </div>

        {{-- Form spesifik murid (8 data profil) --}}
        <div id="create-student-fields">
            <div class="grid grid-cols-2" style="gap:12px;">
                <div class="form-group">
                    <label>Asal Sekolah</label>
                    <input type="text" name="school_origin" class="form-control" placeholder="Contoh: SMAN 1 Bandung">
                </div>
                <div class="form-group">
                    <label>Kelas</label>
                    <input type="text" name="grade_class" class="form-control" placeholder="Contoh: Kelas 10">
                </div>
            </div>

            <div class="grid grid-cols-2" style="gap:12px;">
                <div class="form-group">
                    <label>Jenjang Belajar</label>
                    <select name="education_level_id" class="form-control">
                        @foreach($educationLevels as $el)
                            <option value="{{ $el->id }}">{{ $el->name }} ({{ $el->code === 'sd' ? '60 Menit' : '75 Menit' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Program Belajar</label>
                    <select name="program_id" class="form-control">
                        @foreach($programs as $pr)
                            <option value="{{ $pr->id }}">{{ $pr->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Frekuensi Belajar per Minggu</label>
                <input type="number" name="weekly_frequency" class="form-control" min="1" max="7" value="2">
            </div>

            <div class="form-group">
                <label>Preferensi Hari Belajar</label>
                <div style="display:flex;flex-wrap:wrap;gap:8px;">
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                        <label style="display:flex;align-items:center;gap:4px;font-size:13px;cursor:pointer;">
                            <input type="checkbox" name="preferred_days[]" value="{{ $day }}">
                            <span>{{ $day }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Mapel (Tutor / Murid) --}}
        <div class="form-group" id="create-subject-fields">
            <label>Mata Pelajaran (Tutor/Murid)</label>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:6px;max-height:120px;overflow-y:auto;padding:8px;border:1px solid var(--line);border-radius:10px;">
                @foreach($subjects as $sub)
                    <label style="display:flex;align-items:center;gap:4px;font-size:12px;cursor:pointer;">
                        <input type="checkbox" name="subject_ids[]" value="{{ $sub->id }}">
                        <span>{{ $sub->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:20px;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('dlg-create-user').close()">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan pengguna</button>
        </div>
    </form>
</dialog>

{{-- Dialog Edit Pengguna --}}
<dialog id="dlg-edit-user" class="card" style="width:min(92vw,560px);padding:24px;border-radius:18px;margin:auto;">
    <form id="form-edit-user" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <h3 style="font:800 18px 'Plus Jakarta Sans';margin-bottom:16px;">Edit Data Pengguna</h3>

        <div class="grid grid-cols-2" style="gap:12px;">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" id="edit-user-name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="edit-user-email" class="form-control" required>
            </div>
        </div>

        <div class="grid grid-cols-2" style="gap:12px;">
            <div class="form-group">
                <label>Password Baru (Opsional)</label>
                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
            </div>

            <div class="form-group">
                <label>Role Akun</label>
                <select name="role" id="edit-user-role" class="form-control" required onchange="toggleRoleFields('edit')">
                    <option value="student">Siswa / Murid</option>
                    <option value="tutor">Tutor / Pengajar</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom:12px;">
            <label>Foto Profil (Opsional - Tutor/User)</label>
            <div id="edit-photo-preview-wrap" style="display:none;margin-bottom:8px;align-items:center;gap:10px;">
                <img id="edit-photo-preview" src="" alt="Foto saat ini" style="width:48px;height:48px;border-radius:10px;object-fit:cover;border:1px solid var(--line);">
                <small style="color:var(--muted);font-size:12px;">Foto saat ini. Pilih file baru untuk mengganti.</small>
            </div>
            <input type="file" name="profile_photo" class="form-control" accept="image/*" style="padding:6px 10px;">
        </div>

        <div class="grid grid-cols-2" style="gap:12px;">
            <div class="form-group">
                <label>No. WhatsApp</label>
                <input type="text" name="phone" id="edit-user-phone" class="form-control">
            </div>

            <div class="form-group">
                <label>Alamat (Untuk Privat Rumah)</label>
                <input type="text" name="address" id="edit-user-address" class="form-control">
            </div>
        </div>

        <div id="edit-student-fields">
            <div class="grid grid-cols-2" style="gap:12px;">
                <div class="form-group">
                    <label>Asal Sekolah</label>
                    <input type="text" name="school_origin" id="edit-user-school" class="form-control">
                </div>
                <div class="form-group">
                    <label>Kelas</label>
                    <input type="text" name="grade_class" id="edit-user-grade" class="form-control">
                </div>
            </div>

            <div class="grid grid-cols-2" style="gap:12px;">
                <div class="form-group">
                    <label>Jenjang Belajar</label>
                    <select name="education_level_id" id="edit-user-edu" class="form-control">
                        @foreach($educationLevels as $el)
                            <option value="{{ $el->id }}">{{ $el->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Program Belajar</label>
                    <select name="program_id" id="edit-user-prog" class="form-control">
                        @foreach($programs as $pr)
                            <option value="{{ $pr->id }}">{{ $pr->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Frekuensi Belajar per Minggu</label>
                <input type="number" name="weekly_frequency" id="edit-user-freq" class="form-control" min="1" max="7">
            </div>
        </div>

        <div class="form-group" id="edit-subject-fields">
            <label>Mata Pelajaran (Tutor/Murid)</label>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:6px;max-height:120px;overflow-y:auto;padding:8px;border:1px solid var(--line);border-radius:10px;">
                @foreach($subjects as $sub)
                    <label style="display:flex;align-items:center;gap:4px;font-size:12px;cursor:pointer;">
                        <input type="checkbox" name="subject_ids[]" value="{{ $sub->id }}" class="edit-sub-check" data-id="{{ $sub->id }}">
                        <span>{{ $sub->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:20px;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('dlg-edit-user').close()">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan perubahan</button>
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

function editUser(user, subjectIds) {
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
    document.getElementById('edit-user-prog').value = user.program_id || '';
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
