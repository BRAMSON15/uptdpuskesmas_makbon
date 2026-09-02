<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('saran.php');
}

$nama  = trim($_POST['nama_pengirim'] ?? '');
$email = trim($_POST['email'] ?? '');
$pesan = trim($_POST['pesan'] ?? '');

if ($nama === '' || $pesan === '') {
    set_flash('error', 'Nama dan pesan wajib diisi.');
    redirect('saran.php');
}

$stmt = $pdo->prepare("INSERT INTO saran_masukan (nama_pengirim, email, pesan) VALUES (?, ?, ?)");
$stmt->execute([$nama, $email, $pesan]);

set_flash('success', 'Terima kasih, masukan Anda telah kami terima.');
redirect('saran.php');
