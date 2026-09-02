<?php
/**
 * Jalankan file ini SEKALI dari browser (misal: http://localhost/puskesmas-makbon/database/seed_password.php)
 * setelah import database, untuk mengatur ulang password default akun admin & petugas
 * menjadi hash bcrypt yang valid. Setelah selesai, sebaiknya file ini dihapus.
 */

require_once __DIR__ . '/../config/database.php';

$passwordAdmin   = 'admin123';
$passwordPetugas = 'petugas123';

$hashAdmin   = password_hash($passwordAdmin, PASSWORD_BCRYPT);
$hashPetugas = password_hash($passwordPetugas, PASSWORD_BCRYPT);

$pdo->prepare("UPDATE admin SET password = ? WHERE username = 'admin'")->execute([$hashAdmin]);
$pdo->prepare("UPDATE petugas SET password = ? WHERE username = 'petugas1'")->execute([$hashPetugas]);

echo "<h2>Seeding password berhasil!</h2>";
echo "<p><strong>Login Admin</strong> &rarr; username: <code>admin</code> / password: <code>{$passwordAdmin}</code></p>";
echo "<p><strong>Login Petugas</strong> &rarr; username: <code>petugas1</code> / password: <code>{$passwordPetugas}</code></p>";
echo "<p style='color:red;'>Hapus file seed_password.php ini setelah selesai digunakan (alasan keamanan).</p>";
