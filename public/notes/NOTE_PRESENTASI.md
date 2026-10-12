# Bahan Presentasi & Analisis Sistem: Aplikasi Penjadwalan Bimbel Quadra
## Transformasi Digital: Dari Google Form Manual Menuju "Cinema Booking Model"

Dokumen ini disusun sebagai panduan dan materi presentasi untuk menjelaskan latar belakang, permasalahan nyata di lapangan, serta solusi sistem aplikasi penjadwalan modern kepada tim, manajemen, maupun stakeholder bimbel.

---

## 1. Latar Belakang & Fakta Operasional

Berdasarkan audit data operasional riil bulan berjalan:
- **115 Siswa Aktif** melakukan booking jadwal setiap minggu.
- **23 Tutor** dengan variasi mata pelajaran, jenjang, ketersediaan hari, dan kuota masing-masing.
- **8 Ruangan Fisik (R1–R8)** dengan kapasitas berbeda (4 hingga 10 siswa).
- **Kewajiban Belajar:** Setiap siswa wajib mengambil **2 pertemuan per minggu** (Pertemuan 1 & Pertemuan 2).

---

## 2. Analisis Proses Saat Ini (*As-Is*) vs Kondisi Baru (*To-Be*)

### 2.1 Alur Kerja Lama (*As-Is*)
```
[Tutor]  ──> Isi Google Form Audit (Hari, Sesi, Mapel, Catatan Bebas)
                                            │
                                            ▼
                               [Admin / Akademik]
                     (Pusing mencocokkan di Spreadsheet:
                   115 Siswa x 23 Tutor x 8 Ruangan x 4-6 Sesi)
                                            ▲
                                            │
[Siswa]  ──> Isi Google Form Booking (Pilih Mapel, Hari, Sesi Buta)
                                            │
                                            ▼
                    [Terjadi Bentrok / Overbooking]
                                            │
                                            ▼
               [Admin Chat WA Siswa Satu per Satu untuk Ganti Jam]
```

### 2.2 Alur Kerja Sistem Baru (*To-Be*)
```
[Tutor] ──> Login & Pilih Slot Mengajar Mandiri
            (Sistem otomatis KUNCI ruangan kosong, cegah bentrok real-time)
                                 │
                                 ▼
                     [Jadwal Tayang Otomatis]
                     (Mirip Jadwal Tayang Bioskop)
                                 │
                                 ▼
[Siswa] ──> Login & Pilih Kursi/Slot Sendiri (Maksimal 2 Pertemuan/Minggu)
            - Jika kursi penuh, pilih slot lain secara transparan
            - Jika privat, slot otomatis terkunci eksklusif
                                 │
                                 ▼
[Admin] ──> Cukup Memantau Dashboard & Okupansi Ruangan (Tanpa Beban Manual)
```

---

## 3. Contoh Masalah Nyata di Lapangan (*Real Pain Points*)

Berdasarkan temuan langsung dari formulir respon siswa dan tutor:

### Contoh Masalah 1: "Penumpukan Buta" di Slot Favorit (Overbooking)
* **Kondisi Lapangan:** Di Google Form, siswa bebas memilih hari dan sesi tanpa mengetahui kapasitas tersisa. 
* **Kasus Riil:** Pada hari Rabu Sesi 3, ada 18 siswa yang memilih Matematika. Padahal ruangan terbesar (R8) hanya muat 10 siswa, dan tutor yang standby di jam tersebut hanya 1 orang.
* **Dampak:** 8 siswa lainnya tidak tertampung. Admin harus repot menghubungi mereka satu per satu via WhatsApp untuk meminta geser hari/jam, memicu komplain dari siswa dan orang tua.

### Contoh Masalah 2: Matriks Rumit 3 Variabel (Tutor x Ruangan x Siswa)
* **Kondisi Lapangan:** Admin harus memplot secara manual:
  1. Apakah tutor yang bersangkutan bisa mengajar di jam tersebut?
  2. Apakah ada ruangan yang kosong dan muat dengan jumlah siswa?
  3. Apakah siswa jenjang SD tidak digabung dengan materi SMA?
* **Dampak:** Proses pembuatan jadwal mingguan memakan waktu berjam-jam (bahkan seharian penuh), serta rawan terjadi *human error* (salah ketik, tutor dobel jadwal, ruangan dipakai 2 kelas).

### Contoh Masalah 3: Catatan Bebas Tutor yang Sering Terlewat
* **Kondisi Lapangan:** Pada audit form tutor, banyak tutor menulis catatan penting dalam bentuk teks bebas, contoh:
  - *"Khusus hari Jumat baru bisa masuk jam 16.00 sepulang sekolah."*
  - *"Kamis cuma bisa sampai magrib karena ada acara tawasul."*
  - *"Sabtu bisa setelah dzuhur, tolong jangan dikasih kelompok siswa X."*
* **Dampak:** Karena dicatat manual di spreadsheet, admin sering lupa membaca catatan kecil ini saat menyusun jadwal, sehingga tutor terlanjur diplot dan berujung kelas harus dibatalkan mendadak.

### Contoh Masalah 4: Pembatalan & Reschedule Tanpa Aturan Sistem
* **Kondisi Lapangan:** Siswa sering membatalkan sesi beberapa jam sebelum kelas dimulai melalui chat personal ke admin/tutor.
* **Dampak:** Kuota kursi yang batal terbuang sia-sia karena tidak sempat ditawarkan ke siswa lain. Tutor yang sudah datang ke bimbel merasa dirugikan.

---

## 4. Matriks Solusi vs Masalah (Problem vs Solution Matrix)

| No | Masalah Operasional Lama (*As-Is*) | Solusi di Aplikasi Web Baru (*To-Be*) | Keuntungan Utama |
| :-: | :--- | :--- | :--- |
| **1** | **Overbooking Siswa**<br>Siswa mengisi form tanpa tahu kuota tersisa. | **Real-Time Seat Booking (Model Bioskop)**<br>Slot menampilkan sisa kursi secara langsung (misal: "Sisa 2 dari 8 kursi"). Jika penuh, tombol booking otomatis tertutup. | Tidak ada lagi siswa yang ditolak atau dipindah paksa. |
| **2** | **Bentrok Ruangan & Tutor**<br>Admin sering salah memplot ruangan yang sama untuk 2 kelas berbeda. | **Conflict Prevention Engine**<br>Saat tutor memilih ruangan dan jam, ruangan yang sudah terpakai otomatis terkunci (*disabled*) di layar. Database menjaga integritas anti-bentrok. | Jaminan **0% Bentrok** (*Zero Conflict*). |
| **3** | **Beban Kerja Admin Ekstrem**<br>Admin harus mencocokkan 115 siswa dengan 23 tutor di Excel setiap minggu. | **Self-Service System**<br>Tutor membuka slotnya sendiri $\rightarrow$ Siswa memesan kursinya sendiri $\rightarrow$ Admin hanya mengawasi lewat Dashboard. | Menghemat waktu kerja admin hingga **90%**. |
| **4** | **Jadwal Batal Mendadak**<br>Siswa izin mendadak 1 jam sebelum sesi. | **Aturan Pembatalan H-1 (Cut-Off 24 Jam)**<br>Tombol pembatalan otomatis dinonaktifkan jika sesi kurang dari 24 jam. Kuota yang dibatalkan pada H-1 langsung terbuka kembali untuk siswa lain. | Kursi tidak terbuang sia-sia & hak tutor terlindungi. |
| **5** | **Pelanggaran Jatah Sesi**<br>Admin sulit melacak apakah seorang siswa sudah ikut 2x les minggu ini atau belum. | **Validasi Kuota Mingguan Otomatis**<br>Sistem mengunci akun siswa agar tidak bisa booking lebih dari 2 jadwal per minggu, dengan indikator progres (`x/2 Sesi Terbooking`). | Kepatuhan kontrak paket belajar terjaga 100%. |
| **6** | **Kelas Privat vs Reguler Tercampur**<br>Perlakuan kelas privat bimbel vs privat rumah sering membingungkan. | **Logika Tipe Program Khusus**<br>- Privat Bimbel: 1 murid mengunci 1 ruangan fisik.<br>- Privat Rumah: Menggunakan alamat siswa tanpa memotong kuota ruangan fisik bimbel. | Operasional fasilitas fisik bimbel menjadi sangat akurat. |

---

## 5. Dampak Strategis bagi Bimbel (*Business Impact & ROI*)

1. **Efisiensi Waktu & Biaya Operasional:**
   - Proses penyusunan jadwal yang biasanya butuh waktu 1–2 hari kerja setiap akhir pekan dipangkas menjadi **otomatis berjalan sendiri**.
2. **Kepuasan Siswa & Orang Tua:**
   - Memberikan pengalaman modern dan transparan layaknya memesan tiket bioskop atau tiket pesawat. Siswa punya kendali penuh atas waktu belajar mereka.
3. **Pemanfaatan Ruangan Maksimal (*Facility Utilization*):**
   - Melalui dashboard admin, tingkat keterisian 8 ruangan (R1–R8) terpantau jelas dalam bentuk heatmap/matriks, memudahkan bimbel memaksimalkan okupansi kelas.
4. **Kepastian untuk Pengajar (Tutor):**
   - Tutor hanya mengajar pada waktu yang mereka sanggupi, tanpa risiko dipaksa mengajar di jam bentrok.

---

## 6. Panduan Alur Penyampaian Presentasi (Pitch Guide)

Saat mempresentasikan ke tim/manajemen, Anda dapat menggunakan alur 5 menit ini:
1. **Menit 1 - Hook & Fakta:** Tunjukkan beban saat ini (115 siswa, 23 tutor, mencocokkan manual di Excel itu rawan eror).
2. **Menit 2 - Masalah Utama:** Ceritakan contoh riil di lapangan (overbooking di jam favorit, chat WA satu-satu yang menguras energi).
3. **Menit 3 - Konsep Solusi:** Jelaskan konsep **"Booking Bioskop"** (Tutor buat jadwal tayang, Siswa booking kursi, sistem otomatis kunci kuota).
4. **Menit 4 - Fitur Kunci:** Tunjukkan validasi anti-bentrok, pembatalan H-1, dan dashboard monitoring.
5. **Menit 5 - Kesimpulan:** Aplikasi ini bukan hanya mempermudah jadwal, tapi menaikkan citra profesionalitas Bimbel Quadra di mata siswa dan orang tua.
