@extends('layouts.app')

@section('title', 'Buka Slot Mengajar Baru')

@section('content')
<div style="max-width:680px; margin:0 auto;">
    <div class="page-header" style="display:block;">
        <a href="{{ route('tutor.dashboard') }}" style="color:var(--primary); font-weight:700; font-size:13px;">← Kembali ke Jadwal Saya</a>
        <h1 style="margin-top:8px;">Buka Slot Jadwal Baru</h1>
        <p>Tentukan waktu, materi, dan kapasitas sesi mengajar.</p>
    </div>

    <div class="card">
        <form action="{{ route('tutor.schedules.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label for="date">Tanggal Mengajar</label>
                    <input type="date" id="date" name="date" class="form-control" value="{{ old('date', $prefillDate ?? date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="time_slot_id">Sesi & Jam</label>
                    <select id="time_slot_id" name="time_slot_id" class="form-control" required>
                        <option value="">-- Pilih Sesi Jam --</option>
                        @foreach($timeSlots as $ts)
                            <option value="{{ $ts->id }}" {{ old('time_slot_id', $prefillTimeSlotId ?? '') == $ts->id ? 'selected' : '' }}>
                                {{ $ts->name }} ({{ substr($ts->start_time, 0, 5) }} - {{ substr($ts->end_time, 0, 5) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label for="subject_id">Mata Pelajaran</label>

                    @if($autoSubject)
                        {{-- Hanya 1 mapel: tampilkan langsung, tidak perlu dropdown --}}
                        <div class="form-control" style="background: #f8fafc; color: #334155; display: flex; align-items: center; gap: 8px;">
                            <span style="font-weight: bold;">{{ $autoSubject->name }}</span>
                            <span style="font-size: 12px; color: #64748b;">(otomatis dipilih)</span>
                        </div>
                        <input type="hidden" name="subject_id" value="{{ $autoSubject->id }}">
                    @else
                        {{-- 2+ mapel: tampilkan dropdown pilihan --}}
                        <select id="subject_id" name="subject_id" class="form-control" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}" {{ old('subject_id') == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>


                <div class="form-group">
                    <label for="education_level_id">Jenjang Pendidikan</label>
                    <select id="education_level_id" name="education_level_id" class="form-control" required>
                        <option value="">-- Pilih Jenjang --</option>
                        @foreach($educationLevels as $lvl)
                            <option value="{{ $lvl->id }}" {{ old('education_level_id') == $lvl->id ? 'selected' : '' }}>
                                {{ $lvl->name }} ({{ $lvl->duration_minutes }} Menit)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label for="program_id">Jenis Program</label>
                    <select id="program_id" name="program_id" class="form-control" required onchange="handleProgramChange(this)">
                        <option value="">-- Pilih Program --</option>
                        @foreach($programs as $prg)
                            <option value="{{ $prg->id }}" data-code="{{ $prg->code }}" data-is-private="{{ $prg->is_private ? '1' : '0' }}" data-requires-room="{{ $prg->requires_room ? '1' : '0' }}" {{ old('program_id') == $prg->id ? 'selected' : '' }}>
                                {{ $prg->name }} {{ $prg->is_private ? '(Kunci 1 Murid)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="room_id">Ruangan</label>
                    <select id="room_id" name="room_id" class="form-control" required>
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach($rooms as $rm)
                            <option value="{{ $rm->id }}" data-code="{{ $rm->code }}" data-physical="{{ $rm->is_physical ? '1' : '0' }}" {{ old('room_id', $prefillRoomId ?? '') == $rm->id ? 'selected' : '' }}>
                                {{ $rm->name }} {{ $rm->is_physical ? '(Kapasitas: '.$rm->capacity.' orang)' : '(Non-Fisik Bimbel)' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="program-helper-note" style="display: none; margin-bottom: 16px; padding: 10px 12px; border-radius: 6px; font-size: 12px; line-height: 1.4;"></div>

            <div class="form-group">
                <label for="quota_max">Kapasitas Maksimal Siswa (Opsional / Default ikut Ruangan)</label>
                <input type="number" id="quota_max" name="quota_max" class="form-control" value="{{ old('quota_max') }}" placeholder="Biarkan kosong untuk otomatis ikut kapasitas ruangan">
                <small id="quota-helper-text" style="color: #64748b;">*Untuk program Privat, kuota akan otomatis dikunci menjadi 1 siswa.</small>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; margin-top: 10px;">
                Buka Slot Jadwal Sekarang
            </button>
        </form>
    </div>
</div>

<script>
function handleProgramChange(select) {
    const selectedOpt = select.options[select.selectedIndex];
    const code = selectedOpt.getAttribute('data-code');
    const isPrivate = selectedOpt.getAttribute('data-is-private') === '1';
    const roomSelect = document.getElementById('room_id');
    const noteBox = document.getElementById('program-helper-note');
    const quotaInput = document.getElementById('quota_max');
    const quotaHelp = document.getElementById('quota-helper-text');

    if (code === 'private_home') {
        // Otomatis pilih Rumah Siswa (EXT_HOME)
        for (let i = 0; i < roomSelect.options.length; i++) {
            if (roomSelect.options[i].getAttribute('data-physical') === '0') {
                roomSelect.selectedIndex = i;
                break;
            }
        }
        noteBox.style.display = 'block';
        noteBox.style.background = '#fef3c7';
        noteBox.style.color = '#92400e';
        noteBox.style.border = '1px solid #fde68a';
        noteBox.innerHTML = '<strong>Privat di Rumah:</strong> Sesi bertempat di rumah murid. Ruangan otomatis diarahkan ke <em>Rumah Siswa</em> (tidak memotong kuota 8 ruangan fisik bimbel).';
        quotaInput.value = 1;
        quotaInput.disabled = true;
    } else if (code === 'consult') {
        noteBox.style.display = 'block';
        noteBox.style.background = '#e0f2fe';
        noteBox.style.color = '#0369a1';
        noteBox.style.border = '1px solid #bae6fd';
        noteBox.innerHTML = '<strong>Sesi Konsultasi:</strong> Sesi materi/tugas sekolah berdasarkan request murid. Disarankan memilih ruangan kelompok kecil (R1, R2, atau R6).';
        quotaInput.disabled = false;
        if (!quotaInput.value) quotaInput.value = 4;
    } else if (code === 'private_bimbel') {
        noteBox.style.display = 'block';
        noteBox.style.background = '#f5f3ff';
        noteBox.style.color = '#6d28d9';
        noteBox.style.border = '1px solid #ddd6fe';
        noteBox.innerHTML = '<strong>Privat di Bimbel:</strong> Menggunakan ruangan fisik bimbel (disarankan R1, R2, atau R6). Kuota langsung terkunci 1 siswa (1/1 Full).';
        quotaInput.value = 1;
        quotaInput.disabled = true;
    } else {
        noteBox.style.display = 'none';
        quotaInput.disabled = false;
        if (quotaInput.value === '1') quotaInput.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const progSelect = document.getElementById('program_id');
    if (progSelect && progSelect.value) {
        handleProgramChange(progSelect);
    }
});
</script>
@endsection
