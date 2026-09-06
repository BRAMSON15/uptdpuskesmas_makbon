<?php
/**
 * Koneksi Database - Puskesmas Makbon
 * Menggunakan PDO agar aman dari SQL Injection (prepared statement)
 */

// $DB_HOST = 'localhost';
// $DB_NAME = 'u636563619_pkmmakbon02';
// $DB_USER = 'u636563619_pkmmakbon02';
// $DB_PASS = '*6iGLe^W';

$DB_HOST = 'localhost';
$DB_NAME = 'puskesmas_makbon';
$DB_USER = 'root';
$DB_PASS = '';  

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage());
}
