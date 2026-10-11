# Bimbel Gen Plasma — Prototipe Frontend Bimbel (fokus penjadwalan)

Antarmuka saja (HTML + CSS + JS murni), tanpa backend. Data hanya contoh.

## Struktur
```
gen-plasma/
├─ index.html          Halaman login
├─ dashboard.html      Ringkasan (isi berbeda per role)
├─ jadwal.html         Jadwal belajar / mengajar
├─ program.html        Program bimbel
├─ ruangan.html        Ruangan            (admin)
├─ durasi.html         Durasi & sesi      (admin)
├─ jenjang.html        Jenjang            (admin)
├─ backup.html         Backup & pemulihan data (admin)
├─ akun.html           Akun / murid saya  (admin, tutor)
├─ navbar.html         Sidebar + topbar bersama (dipanggil tiap halaman, posisi fixed)
└─ assets/
   ├─ css/  style.css (dasar & komponen) · navbar.css · login.css
   ├─ js/   data.js (data contoh, role, menu) · core.js (sesi, navbar, modal, helper) · theme-init.js
   │  └─ pages/  login.js dashboard.js jadwal.js program.js ruangan.js durasi.js jenjang.js akun.js backup.js
   └─ img/  logo-terang.png · logo-gelap.png · favicon.png
```
Satu halaman = satu file HTML + satu file JS di `assets/js/pages/`. CSS komponen dipakai bersama.

## Cara menjalankan
`navbar.html` dimuat dengan `fetch`, jadi **jangan klik dua kali file HTML**. Pakai server lokal:
- VS Code: ekstensi **Live Server** → klik kanan `index.html` → *Open with Live Server*
- atau di folder ini: `python -m http.server 8000`, lalu buka http://localhost:8000

## Akun demo
| Role | Email | Kata sandi |
|---|---|---|
| Admin | admin@genplasma.id | admin123 |
| Tutor | nadia@genplasma.id | tutor123 |
| Siswa | raka@genplasma.id | siswa123 |

## Catatan
- Login hanya simulasi: role disimpan di `sessionStorage` (`gp_role`), tema di `localStorage` (`gp_theme`). Ganti dengan autentikasi backend nanti.
- Hak akses halaman diatur di `MENU` pada `assets/js/data.js`; halaman di luar menu role dialihkan ke dashboard.
- Saat dipindah ke Laravel, `navbar.html` cukup jadi partial Blade (`@include`), dan `data.js` diganti data dari database.
