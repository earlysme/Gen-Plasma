<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    /**
     * Halaman Utama Backup & Ekspor Data Excel / Spreadsheet.
     *
     * Catatan untuk Pengembang Backend:
     * Logika ekspor file Excel (.xlsx / .csv) dan integrasi Google Sheets
     * dapat dihubungkan langsung ke controller ini.
     */
    public function index()
    {
        return view('admin.backups.index');
    }

    /**
     * Placeholder eksekusi backup manual (bisa diisi logika backend oleh teman Anda).
     */
    public function createBackup(Request $request)
    {
        // TODO: Logika pembuatan file Excel (misal via Laravel Excel / PhpSpreadsheet)
        return redirect()->route('admin.backups.index')->with('success', 'Backup data Excel berhasil dipicu! File baru siap diunduh.');
    }

    /**
     * Placeholder simpan URL / setting Spreadsheet.
     */
    public function updateSpreadsheet(Request $request)
    {
        // TODO: Simpan URL atau credentials Google Sheets
        return redirect()->route('admin.backups.index')->with('success', 'Pengaturan integrasi Google Spreadsheet berhasil disimpan.');
    }
}
