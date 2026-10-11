# Gen-Plasma: Sistem Penjadwalan Bimbel Quadra
## Aplikasi Manajemen Penjadwalan Berbasis *Cinema Booking Model*

Aplikasi web modern untuk otomatisasi dan manajemen jadwal bimbingan belajar, mengubah sistem audit manual dan Google Forms menjadi sistem mandiri (*self-service*) dengan pencegahan jadwal bentrok secara *real-time*.

---

## 📚 Dokumentasi & Catatan Proyek (Folder `notes/`)

Seluruh dokumen perancangan dan analisis sistem tersimpan rapi di dalam folder **[`notes/`](notes/)**:

1. 📄 **[notes/REQUIREMENTS.md](notes/REQUIREMENTS.md)**:
   - Dokumen Spesifikasi Kebutuhan Sistem (*Software Requirement Document*).
   - Aktor pengguna (Admin, Tutor, Siswa), 4 sesi waktu, 8 ruangan (R1–R8), pemetaan 8 mapel dan tutor, aturan booking 2x/minggu, dan batas toleransi pembatalan H-1.

2. 📄 **[notes/DATABASE_DESIGN.md](notes/DATABASE_DESIGN.md)**:
   - Dokumen Perancangan Basis Data lengkap.
   - Diagram Relasi Entitas (ERD Mermaid), kamus data, tipe data MySQL, 8 atribut profil murid, serta catatan persiapan migrasi ke Supabase.

3. 📄 **[notes/NOTE_PRESENTASI.md](notes/NOTE_PRESENTASI.md)**:
   - Bahan dan panduan presentasi sistem untuk stakeholder/manajemen.
   - Analisis *As-Is* vs *To-Be*, contoh masalah nyata di lapangan (audit data 115 siswa & 23 tutor), serta tabel komparasi Solusi vs Masalah.

---

## 🛠️ Stack Teknologi

- **Backend:** Laravel 12 / PHP 8.5
- **Database:** MySQL (InnoDB) & Spatie Laravel Permission
- **Frontend:** Blade / Tailwind CSS / Vanilla CSS & JavaScript
- **Dev Server:** `php artisan serve` & `npm run dev`

---

## 🚀 Memulai Aplikasi

```bash
# Salin konfigurasi environment
cp .env.example .env

# Generate Application Key
php artisan key:generate

# Jalankan migrasi dan seeder
php artisan migrate --seed

# Jalankan server
php artisan serve
npm run dev
```
