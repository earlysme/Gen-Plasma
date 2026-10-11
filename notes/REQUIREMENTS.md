# Software Requirement Document (SRD)
## Sistem Penjadwalan Bimbingan Belajar (Bimbel) Berbasis Slot Booking ("Cinema Booking Model")

---

## 1. Ringkasan Eksekutif & Konsep Utama

Sistem ini adalah aplikasi web manajemen penjadwalan bimbingan belajar dengan pendekatan **"Booking Bioskop" (*Cinema Booking Model*)**. 

Pada model ini:
- **Pengajar (Tutor)** bertindak sebagai pembuat slot tayang jadwal (*showtimes creator*), memilih tanggal, jam sesi, ruangan, mapel, dan program.
- **Murid (Student)** bertindak sebagai pemesan (*booker*), memilih tutor dan slot waktu yang tersedia hingga kuota terpenuhi.
- **Admin** bertindak sebagai penyedia infrastruktur & pengelola *master data* (ruangan, sesi, mapel, akun tutor/murid).

---

## 2. Aktor & Hak Akses (User Roles)

### 2.1 Admin (Master Data Provider)
- Mengelola data master: Ruangan, Pengajar/Tutor, Murid, Mata Pelajaran, Jenjang, Sesi Waktu, dan Program Belajar.
- Memantau operasional lewat Dashboard: okupansi ruangan, status sesi harian, dan kepatuhan booking siswa.
- Mengontrol akses dan akun pengguna.

### 2.2 Pengajar / Tutor (Slot Creator & Practitioner)
- **Membuka Jadwal / Slot:** Menentukan tanggal (Senin–Sabtu), sesi waktu, ruangan fisik (R1–R8) / rumah murid, mata pelajaran, jenjang, dan program.
- **Direct Assignment:** Memilih dan memasukkan murid tertentu secara langsung ke dalam slot jadwalnya.
- **Manajemen Sesi:**
  - Melihat kalender dan agenda mengajar pribadi.
  - Mengubah status sesi (*Belum Selesai* / *Sudah Selesai*).
  - Mengisi catatan materi pembelajaran setelah kelas selesai.
- **Pembatalan Jadwal:** Membatalkan slot jadwal dengan batas toleransi waktu minimal **H-1**.

### 2.3 Murid / Siswa (Booker)
- **8 Kelengkapan Data Profil Murid:**
  1. **Nama:** Nama lengkap siswa (`users.name`).
  2. **Asal Sekolah:** Nama sekolah asal siswa (`users.school_origin`, misal: SMAN 1, SMPN 3).
  3. **Kelas:** Tingkat kelas siswa (`users.grade_class`, misal: Kelas 10, Kelas 12).
  4. **Program:** Program belajar yang didaftarkan (`users.program_id` $\rightarrow$ Privat Bimbel, Privat Rumah, Reguler, Konsul).
  5. **Pelajaran:** Mata pelajaran yang diambil/diminati siswa (tabel relasi `student_subject`).
  6. **Frekuensi:** Target frekuensi belajar per minggu (`users.weekly_frequency`, default: 2 jadwal/minggu).
  7. **Durasi:** Durasi belajar per pertemuan (otomatis dari jenjang: SD = 60 menit, SMP & SMA = 75 menit).
  8. **Hari Pilihan:** Preferensi hari belajar siswa (`users.preferred_days`, misal: `["Senin", "Kamis"]`).
- **Kewajiban Booking Mingguan:** Setiap siswa wajib melakukan booking sesuai frekuensi mingguan (**2 jadwal per minggu**).
- Memilih slot belajar secara mandiri berdasarkan ketersediaan kuota, jenjang, mata pelajaran, dan tutor favorit.
- Membatalkan booking dengan batas toleransi waktu minimal **H-1** (kursi kembali terbuka untuk murid lain).
- Melihat riwayat jadwal belajar pribadi di dashboard.

---

## 3. Master Data Operasional (Data Riil Sistem)

### 3.1 Hari Operasional
- **Senin s/d Sabtu** (Minggu libur / non-operasional).

### 3.2 Master Sesi Waktu (Time Slots) & Durasi Jenjang
Operasional bimbel dibagi ke dalam 4 sesi harian:

| Sesi | Jam Operasional | Durasi Slot | Catatan Kesesuaian Jenjang |
| :--- | :--- | :--- | :--- |
| **Sesi 1** | 14:45 – 16:00 | 75 Menit | Cocok untuk SMP & SMA (75 Menit) |
| **Sesi 2** | 16:15 – 17:45 | 90 Menit | Sesi Reguler / Fleksibel |
| **Sesi 3** | 18:00 – 19:30 | 90 Menit | Sesi Malam Utama |
| **Sesi 4** | 19:45 – 20:45 | 60 Menit | Sangat Cocok untuk SD (60 Menit) |

> **Ketentuan Durasi Belajar per Jenjang:**
> - **SD:** 60 Menit
> - **SMP & SMA:** 75 Menit

### 3.3 Master Ruangan & Kapasitas Kursi
Bimbel memiliki 8 ruangan fisik dengan kapasitas maksimal murid:

| Kode Ruang | Nama Ruangan | Kapasitas Maksimal | Karakteristik / Alokasi |
| :--- | :--- | :--- | :--- |
| **R1** | Ruang 1 | 5 Siswa | Reguler Kecil / Konsul / Privat |
| **R2** | Ruang 2 | 5 Siswa | Reguler Kecil / Konsul / Privat |
| **R3** | Ruang 3 | 8 Siswa | Reguler Sedang |
| **R4** | Ruang 4 | 7 Siswa | Reguler Sedang |
| **R5** | Ruang 5 | 8 Siswa | Reguler Sedang |
| **R6** | Ruang 6 | 4 Siswa | Kelas Intensif / Konsul / Privat |
| **R7** | Ruang 7 | 8 Siswa | Reguler Sedang |
| **R8** | Ruang 8 | 10 Siswa | Kelas Reguler Besar |
| **Ext-Home** | Rumah Siswa | 1 Siswa | *Khusus Program Privat di Rumah (Non-fisik bimbel)* |

### 3.4 Master Mata Pelajaran (Mapel) & Pemetaan Tutor

| No | Mata Pelajaran | Tim Pengajar / Tutor Terdaftar |
| :--- | :--- | :--- |
| 1 | **Bahasa Inggris** | Zaki, Sitey, Ainy, Fina, Acep, Cahya |
| 2 | **Bahasa Indonesia** | Fahmi, Alifa, Nurul |
| 3 | **Matematika (MTK)** | Resha, Ajun, Resti, Isma |
| 4 | **Geografi** | Ira |
| 5 | **Sosiologi** | Ira, Acep |
| 6 | **Ekonomi** | Selva |
| 7 | **Akuntansi** | Selva |
| 8 | **Kimia** | Ayuni |

*(Catatan: Pengajar seperti **Ira**, **Acep**, dan **Selva** mengajar lebih dari satu mata pelajaran).*

### 3.5 Master Program Belajar
1. **Privat:**
   - **Privat di Bimbel:** Menggunakan 1 ruangan fisik (R1–R8). 1 murid langsung mengunci kuota menjadi 1/1 (Full).
   - **Privat di Rumah:** Bertempat di rumah murid (Lokasi: Ext-Home). Tidak memotong kapasitas ruangan bimbel.
2. **Reguler:**
   - Kelas kelompok belajar dengan batas kuota maksimal mengikuti kapasitas ruangan (misal: R8 maks 10 siswa).
3. **Konsul (Konsultasi):**
   - Sesi bimbingan intensif materi/tugas sekolah dengan kuota kecil (misal: R6 maks 4 siswa).

---

## 4. Mesin & Logika Bisnis Penjadwalan (Scheduling Logic)

### 4.1 Alur Pengisian Ketersediaan Mandiri oleh Tutor (Tutor Self-Scheduling)
Pengajar mengelola dan menentukan ketersediaan jadwal mengajarnya sendiri secara mandiri dengan alur sebagai berikut:

1. **Waktu Pengisian (Planning Window):**
   - Tutor login ke sistem untuk mengisi jadwal ketersediaan mereka (misalnya di **hari Minggu** untuk menetapkan jadwal 1 minggu ke depan: Senin s/d Sabtu).
   - Tutor juga dapat menambah atau menyesuaikan slot kapan saja sebelum H-1 selama slot belum bentrok dan belum ada murid yang terikat.

2. **Form / Grid Pemilihan Slot Interaktif:**
   - Tutor memilih **Hari & Tanggal** (Senin s/d Sabtu).
   - Tutor memilih **Sesi Waktu** (Sesi 1, 2, 3, atau 4).
   - Tutor memilih **Mata Pelajaran** (otomatis difilter hanya mapel yang dikuasai tutor).
   - Tutor memilih **Jenjang** (SD, SMP, atau SMA).
   - Tutor memilih **Program** (Privat, Reguler, atau Konsul).

3. **Pencegahan Bentrok Ruangan Secara Visual & Real-Time (Conflict-Free Room Picker):**
   - Saat tutor memilih Tanggal & Sesi Waktu, sistem langsung menampilkan status 8 Ruangan (R1–R8):
     - Ruangan yang **sudah diambil** oleh tutor lain otomatis berstatus **Terkunci / Disabled** (disertai info nama tutor yang memakai).
     - Ruangan yang **masih kosong** dapat dipilih langsung oleh tutor.
   - Dengan sistem ini, **bentrok ruangan dicegah 100% sejak awal pengisian** (preventif, bukan baru error saat disimpan).
   - Jika tutor memilih opsi **"Privat di Rumah Siswa"**, alokasi ruangan fisik bimbel dilewati (*bypass room locking*).

4. **Kapasitas Kuota Kursi:**
   - **Privat:** Otomatis dikunci pada kuota 1 murid.
   - **Reguler / Konsul:** Kuota default mengikuti kapasitas maksimal ruangan yang dipilih (contoh: jika memilih R8 maka default kuota 10, namun tutor bisa menyesuaikan batas kuota lebih kecil jika diinginkan).

### 4.2 Validasi Sistem Anti-Bentrok (System Guard Validation)
Sebagai lapisan perlindungan di level backend:
1. **Validasi Tutor Double-Booking:** Sistem menolak jika seorang tutor mencoba membuka 2 slot berbeda pada tanggal dan sesi waktu yang sama.
2. **Validasi Ruangan Double-Booking:** Sistem memastikan constraint unik `(room_id, date, time_slot_id)` tidak pernah terduplikasi di database.

### 4.3 Kebijakan Booking Siswa (Student Booking Rules)
1. **Kewajiban Kuota Mingguan:**
   - Sistem memvalidasi bahwa setiap siswa memiliki kuota booking **2 jadwal per minggu** (Senin s/d Sabtu).
   - Siswa tidak dapat melakukan booking ke-3 dalam minggu yang sama tanpa izin admin.
2. **Model Kursi Terbuka:**
   - Murid bebas memilih jadwal mapel dan tutor mana pun selama kuota slot masih tersedia dan jenjangnya sesuai.
3. **Penguncian Slot Privat:**
   - Begitu 1 murid mem-booking slot program Privat, status slot langsung terkunci (*Full*) pada sesi tersebut.
4. **Direct Assignment:**
   - Pengajar dapat memasukkan siswa secara manual ke dalam jadwalnya; sistem otomatis menghitung booking tersebut ke dalam kuota mingguan siswa.

### 4.4 Kebijakan Pembatalan & Perubahan (H-1 Cut-off)
- Siswa atau Pengajar hanya diizinkan membatalkan jadwal maksimal **H-1 (24 jam sebelum tanggal sesi dimulai)**.
- Jika siswa membatalkan pada H-1:
  - Kuota mingguan siswa dikembalikan (dapat booking ulang ke slot lain).
  - Kursi pada slot tersebut kembali terbuka (*available*) untuk siswa lain.
- Pada hari H (kurang dari 24 jam), tombol pembatalan dinonaktifkan (*locked*).

### 4.5 Penyelesaian Sesi & Catatan Pembelajaran
- Status sesi: `Belum Selesai` $\rightarrow$ `Sudah Selesai`.
- Pengajar yang telah menyelesaikan sesi wajib/dapat mengisi **Catatan Materi** (misal: "Membahas Bab 3 Aljabar & latihan soal hal 45").

---

## 5. Kebutuhan Antarmuka & Dashboard

### 5.1 Dashboard Admin
- **Matrix Okupansi Ruangan:** Tampilan visual 8 ruangan (R1–R8) vs 4 sesi waktu harian (hijau = kosong, merah = terpakai).
- **Monitoring Kuota Siswa:** Daftar siswa yang belum memenuhi kewajiban 2 jadwal dalam minggu berjalan.
- **Master Data Panel:** CRUD Ruangan, Tutor & Mapel, Murid, Sesi, dan Program.

### 5.2 Dashboard Pengajar
- **Kalender Slot Mengajar:** Jadwal mengajar mingguan pengajar.
- **Tombol "Buka Slot":** Form ringkas pembuatan slot dengan pencegahan bentrok otomatis.
- **Daftar Peserta & Direct Assign:** Melihat siswa yang memesan kursi dan tombol input siswa langsung.
- **Jurnal Selesai:** Tandai selesai dan tulis catatan materi.

### 5.3 Dashboard Murid
- **Status Kuota Mingguan:** Indikator visual booking minggu ini (contoh: `1/2 Jadwal Terbooking`).
- **Jelajah Slot (Cinema Booking Grid):**
  - Tampilan slot jadwal per hari & sesi.
  - Informasi jelas: Nama Mapel, Nama Tutor, Ruangan, Sisa Kursi (misal: "Sisa 3 dari 8 kursi").
  - Tombol Booking interaktif.
- **Jadwal Saya:** Daftar sesi terbooking beserta info waktu, ruangan, nama tutor, dan tombol "Batalkan (H-1)".

---

## 6. Struktur Basis Data (Entity Relationship)

```
+--------------------+            +--------------------+
|       users        |            |      subjects      |
+--------------------+            +--------------------+
| id                 |            | id                 |
| name               |            | name (Inggris/MTK) |
| email              |            +--------------------+
| role (admin/tutor/ |                      |
|       student)     |                      | N:M (tutor_subject)
| phone              |                      v
| address            |            +--------------------+
| education_level_id |            |       rooms        |
+--------------------+            +--------------------+
          |                       | id                 |
          |                       | code (R1-R8, Ext)  |
          |                       | name               |
          |                       | capacity (4-10)    |
          v                       +--------------------+
+--------------------+                      |
|     time_slots     |                      |
+--------------------+                      |
| id                 |                      |
| session_number(1-4)|                      |
| start_time         |                      |
| end_time           |                      |
+--------------------+                      |
          |                                 |
          +----------------+----------------+
                           |
                           v
                 +--------------------+
                 |     schedules      |  (Slot Tayang Bioskop)
                 +--------------------+
                 | id                 |
                 | tutor_id (FK user) |
                 | subject_id (FK)    |
                 | room_id (FK room)  |
                 | program_id (FK)    |
                 | education_level_id |
                 | time_slot_id (FK)  |
                 | date (Senin-Sabtu) |
                 | quota_max          |
                 | is_completed (bool)|
                 | notes (materi)     |
                 +--------------------+
                           |
                           | 1:N
                           v
                 +--------------------+
                 |      bookings      |  (Pemesanan Kursi Siswa)
                 +--------------------+
                 | id                 |
                 | schedule_id (FK)   |
                 | student_id (FK)    |
                 | booked_by          |
                 | status (active/    |
                 |         cancelled) |
                 | booked_at          |
                 +--------------------+
```

---

## 7. Rencana Data Awal (Database Seeder)
Untuk mempercepat proses development, data berikut siap dijadikan seeder:
- **8 Ruangan:** R1 (5), R2 (5), R3 (8), R4 (7), R5 (8), R6 (4), R7 (8), R8 (10).
- **4 Sesi Waktu:** 14:45–16:00, 16:15–17:45, 18:00–19:30, 19:45–20:45.
- **8 Mata Pelajaran:** Inggris, Indonesia, Matematika, Geografi, Sosiologi, Ekonomi, Akuntansi, Kimia.
- **14 Tutor:** Zaki, Sitey, Ainy, Fina, Acep, Cahya, Fahmi, Alifa, Nurul, Resha, Ajun, Resti, Isma, Ira, Selva, Ayuni.
- **3 Jenjang:** SD (60 mnt), SMP (75 mnt), SMA (75 mnt).
