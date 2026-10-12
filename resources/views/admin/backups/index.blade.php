@extends('layouts.app')

@section('title', 'Backup Data & Spreadsheet')

@push('styles')
<style>
    .bk-wrap {
        max-width: 1300px;
        margin: 0 auto;
    }

    /* Grid Statistik */
    .bk-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .bk-stat-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px 22px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
    }

    .bk-stat-icon {
        display: grid;
        place-items: center;
        width: 46px;
        height: 46px;
        border-radius: 12px;
        font-size: 22px;
        flex-shrink: 0;
    }

    .bk-icon-excel {
        background: #e8f5e9;
        color: #1b5e20;
    }

    .bk-icon-clock {
        background: var(--primary-soft);
        color: var(--primary);
    }

    .bk-icon-sheets {
        background: #e6f4ea;
        color: #137333;
    }

    html[data-theme="dark"] .bk-icon-excel {
        background: #14381b;
        color: #81c784;
    }

    html[data-theme="dark"] .bk-icon-sheets {
        background: #123519;
        color: #81c995;
    }

    .bk-stat-content strong {
        display: block;
        font: 800 20px/1.2 "Plus Jakarta Sans", sans-serif;
        color: var(--text);
        letter-spacing: -.5px;
    }

    .bk-stat-content span {
        display: block;
        margin-top: 3px;
        color: var(--muted);
        font-size: 12px;
        font-weight: 600;
    }

    /* Modul Ekspor Cepat */
    .bk-module-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .bk-module-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 16px 18px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        transition: transform .18s ease, border-color .18s ease;
    }

    .bk-module-card:hover {
        transform: translateY(-2px);
        border-color: var(--primary);
    }

    .bk-module-top {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .bk-module-top span {
        font-size: 20px;
    }

    .bk-module-top strong {
        font-size: 13.5px;
        color: var(--text);
    }

    .bk-module-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        color: #1b5e20;
        background: #e8f5e9;
        border: 1px solid #c8e6c9;
        cursor: pointer;
        transition: all .15s ease;
        text-decoration: none;
    }

    html[data-theme="dark"] .bk-module-btn {
        color: #a5d6a7;
        background: #173d1e;
        border-color: #27562f;
    }

    .bk-module-btn:hover {
        background: #2e7d32;
        color: #fff;
    }

    /* Desktop Table & Mobile Stack */
    .bk-mobile-stack {
        display: none;
    }

    @media (max-width: 900px) {
        .bk-stats-grid {
            grid-template-columns: 1fr;
        }
        .bk-module-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 680px) {
        .bk-module-grid {
            grid-template-columns: 1fr;
        }

        /* Sembunyikan tabel geser di mobile */
        .bk-desktop-table {
            display: none !important;
        }

        /* Tampilkan Card Stack di mobile */
        .bk-mobile-stack {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .bk-m-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 14px 16px;
            box-shadow: 0 2px 8px rgba(23,63,58,.04);
        }

        .bk-m-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 8px;
        }

        .bk-m-file {
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
            word-break: break-all;
        }

        .bk-m-meta {
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 12px;
        }

        .bk-m-actions {
            display: flex;
            gap: 8px;
            padding-top: 10px;
            border-top: 1px dashed var(--line);
        }

        .bk-m-actions .btn {
            flex: 1;
            font-size: 12px;
            padding: 8px 10px;
        }
    }
</style>
@endpush

@section('content')
<div class="bk-wrap">
    {{-- Header Halaman --}}
    <div class="page-header">
        <div>
            <h1>Backup Data &amp; Spreadsheet</h1>
            <p>Pencadangan data berkala dalam format Excel (.xlsx / .csv) dan sinkronisasi Google Sheets.</p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('sec-sheets').scrollIntoView({behavior:'smooth'})">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:2px;"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                Google Spreadsheet
            </button>
            <form action="{{ route('admin.backups.create') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    Buat Backup Excel Sekarang
                </button>
            </form>
        </div>
    </div>

    {{-- 1. Kartu Statistik & Jadwal --}}
    <section class="bk-stats-grid">
        <div class="bk-stat-card">
            <div class="bk-stat-icon bk-icon-clock">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="bk-stat-content">
                <strong>{{ $autoBackupTime }}</strong>
                <span>Jadwal Backup Otomatis Harian</span>
            </div>
        </div>

        <div class="bk-stat-card">
            <div class="bk-stat-icon bk-icon-excel">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" x2="16" y1="13" y2="13"/><line x1="8" x2="16" y1="17" y2="17"/><line x1="10" x2="14" y1="9" y2="9"/></svg>
            </div>
            <div class="bk-stat-content">
                <strong>{{ $sampleBackups->count() }} File Tersedia</strong>
                <span>Arsip Excel (.xlsx) Tersimpan</span>
            </div>
        </div>

        <div class="bk-stat-card">
            <div class="bk-stat-icon bk-icon-sheets">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" x2="21" y1="9" y2="9"/><line x1="3" x2="21" y1="15" y2="15"/><line x1="9" x2="9" y1="3" y2="21"/><line x1="15" x2="15" y1="3" y2="21"/></svg>
            </div>
            <div class="bk-stat-content">
                <strong style="color:var(--mint-text);">Terhubung</strong>
                <span>Sinkronisasi Google Spreadsheet</span>
            </div>
        </div>
    </section>

    {{-- 2. Modul Ekspor Data Cepat (Excel) --}}
    <div style="margin-bottom:12px;">
        <h2 style="font-size:15px;font-weight:700;color:var(--text);margin-bottom:4px;">Ekspor Cepat Berdasarkan Modul</h2>
        <p style="font-size:12.5px;color:var(--muted);margin:0;">Unduh data spesifik ke format Excel langsung tanpa menunggu backup otomatis.</p>
    </div>

    <section class="bk-module-grid">
        <div class="bk-module-card">
            <div class="bk-module-top">
                <span>📅</span>
                <div>
                    <strong>Data Jadwal Bimbel</strong>
                    <div style="font-size:11px;color:var(--muted);">Sesi, ruang, &amp; mapel</div>
                </div>
            </div>
            <a href="javascript:void(0)" onclick="alert('Modul backend ekspor jadwal siap dihubungkan oleh pengembang.')" class="bk-module-btn">
                <span>⬇ Unduh .xlsx</span>
            </a>
        </div>

        <div class="bk-module-card">
            <div class="bk-module-top">
                <span>👨‍🎓</span>
                <div>
                    <strong>Data Murid / Siswa</strong>
                    <div style="font-size:11px;color:var(--muted);">Profil, kontak, &amp; akun</div>
                </div>
            </div>
            <a href="javascript:void(0)" onclick="alert('Modul backend ekspor siswa siap dihubungkan oleh pengembang.')" class="bk-module-btn">
                <span>⬇ Unduh .xlsx</span>
            </a>
        </div>

        <div class="bk-module-card">
            <div class="bk-module-top">
                <span>👨‍🏫</span>
                <div>
                    <strong>Data Pengajar / Tutor</strong>
                    <div style="font-size:11px;color:var(--muted);">Guru, jam les, &amp; mapel</div>
                </div>
            </div>
            <a href="javascript:void(0)" onclick="alert('Modul backend ekspor pengajar siap dihubungkan oleh pengembang.')" class="bk-module-btn">
                <span>⬇ Unduh .xlsx</span>
            </a>
        </div>

        <div class="bk-module-card">
            <div class="bk-module-top">
                <span>📝</span>
                <div>
                    <strong>Riwayat Booking</strong>
                    <div style="font-size:11px;color:var(--muted);">Tiket les &amp; konsultasi</div>
                </div>
            </div>
            <a href="javascript:void(0)" onclick="alert('Modul backend ekspor booking siap dihubungkan oleh pengembang.')" class="bk-module-btn">
                <span>⬇ Unduh .xlsx</span>
            </a>
        </div>
    </section>

    {{-- 3. Tabel Riwayat Backup Harian (Desktop Table + Mobile Stack) --}}
    <section class="card" style="padding:22px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:10px;">
            <div>
                <h2 style="font-size:16px;font-weight:700;color:var(--text);margin:0;">Riwayat File Backup Harian</h2>
                <p style="font-size:12.5px;color:var(--muted);margin:2px 0 0;">File tersimpan dalam format spreadsheet dan dapat diunduh kapan saja.</p>
            </div>
            <span class="badge badge-student">Format Excel (.xlsx)</span>
        </div>

        {{-- Versi Desktop: Table --}}
        <div class="bk-desktop-table" style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Nama File</th>
                        <th>Cakupan Data</th>
                        <th>Ukuran</th>
                        <th>Waktu Pembuatan</th>
                        <th>Google Sheets</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sampleBackups as $bk)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <span style="font-size:18px;">📊</span>
                                    <strong style="font-size:13px;color:var(--text);">{{ $bk['filename'] }}</strong>
                                </div>
                            </td>
                            <td>
                                <span style="font-size:12.5px;color:var(--text);">{{ $bk['type'] }}</span>
                            </td>
                            <td>
                                <span class="badge" style="background:var(--surface-2);color:var(--muted);">{{ $bk['size'] }}</span>
                            </td>
                            <td>
                                <small style="color:var(--muted);font-weight:600;">{{ $bk['created_at'] }}</small>
                            </td>
                            <td>
                                @if($bk['is_synced_sheets'])
                                    <a href="{{ $bk['sheets_url'] }}" target="_blank" rel="noopener" class="badge" style="background:#e8f5e9;color:#2e7d32;text-decoration:none;">
                                        ✓ Tersinkron Sheets
                                    </a>
                                @else
                                    <span class="badge" style="background:var(--surface-2);color:var(--muted);">Lokal Saja</span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <div style="display:flex;gap:6px;justify-content:center;">
                                    <button type="button" class="btn-icon btn-icon-primary" title="Unduh File Excel" onclick="alert('Logika download file backend siap dihubungkan oleh pengembang.')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                    </button>
                                    @if($bk['sheets_url'])
                                        <a href="{{ $bk['sheets_url'] }}" target="_blank" rel="noopener" class="btn-icon btn-icon-secondary" title="Buka di Google Sheets">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Versi Mobile: Card Stack --}}
        <div class="bk-mobile-stack">
            @foreach($sampleBackups as $bk)
                <div class="bk-m-card">
                    <div class="bk-m-head">
                        <div class="bk-m-file">📊 {{ $bk['filename'] }}</div>
                        <span class="badge" style="background:var(--surface-2);color:var(--muted);font-size:10.5px;">{{ $bk['size'] }}</span>
                    </div>

                    <div class="bk-m-meta">
                        <div>Cakupan: <strong>{{ $bk['type'] }}</strong></div>
                        <div>Waktu: {{ $bk['created_at'] }}</div>
                        <div>
                            @if($bk['is_synced_sheets'])
                                <span style="color:#2e7d32;font-weight:700;">✓ Tersinkron ke Google Sheets</span>
                            @else
                                <span style="color:var(--muted);">Tersimpan lokal di server</span>
                            @endif
                        </div>
                    </div>

                    <div class="bk-m-actions">
                        <button type="button" class="btn btn-primary" onclick="alert('Logika download file backend siap dihubungkan oleh pengembang.')">
                            ⬇ Unduh Excel
                        </button>
                        @if($bk['sheets_url'])
                            <a href="{{ $bk['sheets_url'] }}" target="_blank" rel="noopener" class="btn btn-secondary">
                                🔗 Buka Sheets
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 4. Pengaturan Integrasi Google Spreadsheet --}}
    <section class="card" id="sec-sheets" style="padding:22px;margin-top:20px;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <div style="width:40px;height:40px;border-radius:10px;background:#e8f5e9;color:#2e7d32;display:grid;place-items:center;font-size:20px;">
                📑
            </div>
            <div>
                <h2 style="font-size:16px;font-weight:700;color:var(--text);margin:0;">Pengaturan Integrasi Google Spreadsheet</h2>
                <p style="font-size:12.5px;color:var(--muted);margin:2px 0 0;">Hubungkan URL Google Sheets tempat data harian akan otomatis dikirim.</p>
            </div>
        </div>

        <form action="{{ route('admin.backups.updateSpreadsheet') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="spreadsheet_url" style="font-weight:700;font-size:13px;color:var(--text);">URL Google Spreadsheet / Spreadsheet ID</label>
                <input type="url" id="spreadsheet_url" name="spreadsheet_url" class="form-control" value="{{ $googleSheetUrl }}" placeholder="https://docs.google.com/spreadsheets/d/your-spreadsheet-id/edit" required>
                <small style="display:block;margin-top:5px;font-size:11.5px;color:var(--muted);">
                    Pastikan spreadsheet telah diberi akses edit ke akun service account / email sinkronisasi backend Anda.
                </small>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;padding-top:10px;border-top:1px solid var(--line);">
                <div style="font-size:12px;color:var(--muted);">
                    ℹ️ <em>Form ini siap diproses oleh logika backend teman Anda untuk sinkronisasi otomatis.</em>
                </div>
                <button type="submit" class="btn btn-primary">
                    Simpan Pengaturan Spreadsheet
                </button>
            </div>
        </form>
    </section>
</div>
@endsection
