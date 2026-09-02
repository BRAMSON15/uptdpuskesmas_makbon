# Sistem Informasi Profil, Layanan & Antrian Online — Puskesmas Makbon

Dibangun dengan **PHP native**, **MySQL**, **HTML/CSS/JavaScript** (tanpa framework), sesuai proposal penelitian.

## 🎨 Template Beranda

Halaman **beranda (`index.php`)** kini menggunakan template **Traveland** (lisensi MIT, gratis dari ThemeWagon/UIdeck) yang telah disesuaikan penuh untuk Puskesmas Makbon:
- Semua teks, gambar placeholder, dan bagian "free lite version" dari template asli sudah **diganti dengan data nyata** (nama puskesmas, layanan, jadwal, statistik dari database)
- Section yang tidak relevan (Gallery, Testimonial, Blog asli, Team asli) sudah **dihapus/diganti** dengan section yang relevan (Layanan, Cara Kerja Antrian, Jadwal Operasional, Akses Cepat, Kontak & Saran)
- Form kontak template sudah disambungkan ke `saran_proses.php` (backend saran & masukan yang sudah ada)
- Asset template (CSS/JS/font) disimpan terpisah di `assets/traveland/` agar tidak bercampur dengan `assets/css/style.css` yang dipakai halaman lain (profil, layanan, jadwal, dll — halaman-halaman ini **masih memakai desain lama**, belum diseragamkan dengan template baru)
- Baris kredit "Designed by UIdeck / Distributed by ThemeWagon" di footer **tetap dipertahankan** sesuai syarat lisensi versi gratis template ini

> Catatan: karena hanya beranda yang diminta diganti, ada perbedaan gaya visual antara beranda (bertema Traveland) dan halaman lain (profil/layanan/jadwal/dll yang masih bertema lama). Beri tahu saya jika ingin semua halaman diseragamkan mengikuti gaya template baru.

## 📁 Struktur Folder

```
puskesmas-makbon/
├── admin/              -> Panel Admin (login, dashboard, semua modul kelola)
│   └── includes/       -> sidebar, layout, auth guard khusus admin
├── petugas/             -> Panel Petugas (login, dashboard, kelola antrian dsb)
│   └── includes/
├── includes/            -> header, footer, functions.php (dipakai halaman publik)
├── config/
│   └── database.php     -> konfigurasi koneksi database (PDO)
├── database/
│   ├── puskesmas_makbon.sql   -> skema + data awal (import ini ke phpMyAdmin)
│   └── seed_password.php      -> generate password login yang valid (jalankan 1x)
├── assets/
│   ├── css/style.css
│   └── js/
├── index.php, profil.php, layanan.php, jadwal.php,
│   antrian.php, antrian_proses.php, antrian_sukses.php,
│   tracking.php, saran.php, saran_proses.php, kontak.php   -> halaman publik (Pengguna)
```

## 🚀 Cara Menjalankan (XAMPP)

1. **Copy folder** `puskesmas-makbon` ke dalam `C:\xampp\htdocs\` (Windows) atau `/Applications/XAMPP/htdocs/` (Mac).
2. **Jalankan XAMPP** → nyalakan modul **Apache** dan **MySQL**.
3. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`) → buat database baru atau langsung import:
   - Klik tab **Import** → pilih file `database/puskesmas_makbon.sql` → klik **Go**.
   - Ini akan otomatis membuat database `puskesmas_makbon` beserta seluruh tabel dan data awal (contoh layanan, jadwal, dsb).
4. **Generate password login** yang valid (wajib, sekali saja):
   - Buka browser: `http://localhost/puskesmas-makbon/database/seed_password.php`
   - Catat username/password yang muncul, lalu **hapus file `seed_password.php`** setelah itu.
5. Buka sistem:
   - **Halaman publik (Pengguna):** `http://localhost/puskesmas-makbon/index.php`
   - **Login Admin:** `http://localhost/puskesmas-makbon/admin/login.php`
   - **Login Petugas:** `http://localhost/puskesmas-makbon/petugas/login.php`

## 🔑 Akun Default (setelah menjalankan seed_password.php)

| Role     | Username   | Password      |
|----------|-----------|---------------|
| Admin    | `admin`    | `admin123`    | 
| Petugas  | `petugas1` | `petugas123`  |

## ✅ Fitur yang Sudah Dibangun (sesuai flowchart di proposal)

### Pengguna (Publik)
- Beranda, Profil (visi, misi, sejarah), Layanan (BPJS/Non BPJS), Jadwal Operasional
- **Pendaftaran Antrian Online**: pilih layanan → isi data pasien → pilih tanggal → dapat nomor antrian otomatis (dengan validasi kuota harian)
- **Tracking Antrian**: cek status antrian (Menunggu/Diproses/Selesai/Dibatalkan) + riwayat status
- Saran & Masukan, Kontak

### Admin
- Login & Dashboard (statistik ringkas)
- Kelola Profil Puskesmas (beranda, visi-misi, sejarah, alamat)
- Kelola Layanan (CRUD, jenis BPJS/Non BPJS, kuota harian, status aktif/nonaktif)
- Kelola Jadwal Operasional (CRUD per hari)
- Kelola Antrian Online (lihat, filter tanggal, hapus)
- Monitoring Tracking Antrian
- Kelola Petugas (CRUD akun petugas)
- Kelola Saran & Masukan (lihat + balas)
- Kelola Kontak

### Petugas
- Login & Dashboard (statistik antrian hari ini)
- Kelola Antrian Online: **panggil** → **proses** → **selesaikan** / batalkan (update status realtime + tercatat di tracking)
- Verifikasi & Validasi Antrian (cari berdasarkan ID/nomor antrian, konfirmasi)
- Data Pasien (lihat & ubah dari histori antrian)
- Layanan BPJS/Non BPJS (lihat kuota terpakai, ubah kuota harian)
- Tracking/Pemantauan antrian yang sedang berjalan
- Jadwal Operasional (lihat & ubah keterangan)
- Saran & Masukan (lihat)
- Laporan harian: rekap antrian per layanan, laporan data pasien

## 🔒 Keamanan yang Diterapkan
- Password di-hash dengan **bcrypt** (`password_hash` / `password_verify`)
- Semua query database memakai **PDO prepared statements** (aman dari SQL Injection)
- Output ditampilkan lewat fungsi `clean()` (htmlspecialchars) untuk mencegah XSS
- Session-based authentication dengan guard di setiap halaman admin/petugas

## 📝 Catatan Pengembangan Lanjutan (opsional)
- Bisa ditambahkan validasi nomor HP/format, notifikasi WA/email otomatis
- Bisa ditambahkan export laporan ke PDF/Excel
- Bisa ditambahkan upload foto profil Puskesmas
- Sebaiknya ganti kredensial database (`config/database.php`) sesuai environment saat deploy ke hosting
