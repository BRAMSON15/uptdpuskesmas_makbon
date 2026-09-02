<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('antrian.php');
}

$nama_pasien     = trim($_POST['nama_pasien'] ?? '');
$no_hp           = trim($_POST['no_hp'] ?? '');
$alamat          = trim($_POST['alamat'] ?? '');
$id_layanan      = (int)($_POST['id_layanan'] ?? 0);
$tanggal_antrian = trim($_POST['tanggal_antrian'] ?? '');

if ($nama_pasien === '' || $no_hp === '' || $id_layanan <= 0 || $tanggal_antrian === '') {
    set_flash('error', 'Semua data wajib diisi dengan lengkap.');
    redirect('antrian.php');
}

// Ambil nama layanan & cek kuota harian
$stmt = $pdo->prepare("SELECT * FROM layanan WHERE id_layanan = ? AND status = 'Aktif'");
$stmt->execute([$id_layanan]);
$layanan = $stmt->fetch();

if (!$layanan) {
    set_flash('error', 'Layanan tidak ditemukan atau tidak aktif.');
    redirect('antrian.php');
}

$stmt = $pdo->prepare("SELECT COUNT(*) AS jumlah FROM antrian_online WHERE id_layanan = ? AND tanggal_antrian = ? AND status != 'Dibatalkan'");
$stmt->execute([$id_layanan, $tanggal_antrian]);
$jumlah = (int)$stmt->fetch()['jumlah'];

if ($jumlah >= (int)$layanan['kuota_harian']) {
    set_flash('error', 'Kuota antrian untuk layanan dan tanggal tersebut sudah penuh. Silakan pilih tanggal lain.');
    redirect('antrian.php');
}

$nomor_antrian = nomor_antrian_berikutnya($pdo, $id_layanan, $tanggal_antrian);

$stmt = $pdo->prepare(
    "INSERT INTO antrian_online (nama_pasien, no_hp, alamat, id_layanan, layanan, tanggal_antrian, nomor_antrian, status)
     VALUES (?, ?, ?, ?, ?, ?, ?, 'Menunggu')"
);
$stmt->execute([$nama_pasien, $no_hp, $alamat, $id_layanan, $layanan['nama_layanan'], $tanggal_antrian, $nomor_antrian]);
$id_antrian = $pdo->lastInsertId();

$stmt = $pdo->prepare("INSERT INTO tracking_antrian (id_antrian, status, keterangan) VALUES (?, 'Menunggu', 'Pendaftaran antrian berhasil dibuat.')");
$stmt->execute([$id_antrian]);

redirect('antrian_sukses.php?id=' . $id_antrian);
