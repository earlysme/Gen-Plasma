<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
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
        // Sample data riwayat backup untuk tampilan visual UI
        $sampleBackups = collect([
            [
                'id' => 1,
                'filename' => 'backup_gen_plasma_' . Carbon::today()->format('Y_m_d') . '.xlsx',
                'format' => 'xlsx',
                'type' => 'Full Backup (Jadwal, Siswa, Booking, Ruang)',
                'size' => '184 KB',
                'created_at' => Carbon::today()->setTime(0, 0)->isoFormat('D MMMM Y, 00:00') . ' WIB',
                'is_synced_sheets' => true,
                'sheets_url' => 'https://docs.google.com/spreadsheets/d/example-gen-plasma-active',
            ],
            [
                'id' => 2,
                'filename' => 'backup_gen_plasma_' . Carbon::yesterday()->format('Y_m_d') . '.xlsx',
                'format' => 'xlsx',
                'type' => 'Full Backup (Jadwal, Siswa, Booking, Ruang)',
                'size' => '178 KB',
                'created_at' => Carbon::yesterday()->setTime(0, 0)->isoFormat('D MMMM Y, 00:00') . ' WIB',
                'is_synced_sheets' => true,
                'sheets_url' => 'https://docs.google.com/spreadsheets/d/example-gen-plasma-yesterday',
            ],
            [
                'id' => 3,
                'filename' => 'backup_gen_plasma_' . Carbon::today()->subDays(2)->format('Y_m_d') . '.xlsx',
                'format' => 'xlsx',
                'type' => 'Full Backup (Jadwal, Siswa, Booking, Ruang)',
                'size' => '175 KB',
                'created_at' => Carbon::today()->subDays(2)->setTime(0, 0)->isoFormat('D MMMM Y, 00:00') . ' WIB',
                'is_synced_sheets' => false,
                'sheets_url' => null,
            ],
            [
                'id' => 4,
                'filename' => 'backup_gen_plasma_' . Carbon::today()->subDays(3)->format('Y_m_d') . '.xlsx',
                'format' => 'xlsx',
                'type' => 'Full Backup (Jadwal, Siswa, Booking, Ruang)',
                'size' => '169 KB',
                'created_at' => Carbon::today()->subDays(3)->setTime(0, 0)->isoFormat('D MMMM Y, 00:00') . ' WIB',
                'is_synced_sheets' => true,
                'sheets_url' => 'https://docs.google.com/spreadsheets/d/example-gen-plasma-prev',
            ],
        ]);

        $googleSheetUrl = 'https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit';
        $autoBackupTime = '00:00 WIB (Tiap Malam)';

        return view('admin.backups.index', compact('sampleBackups', 'googleSheetUrl', 'autoBackupTime'));
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
