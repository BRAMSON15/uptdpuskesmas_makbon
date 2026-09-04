<?php
require_once __DIR__ . '/../../includes/functions.php';

if (!is_panel_host('petugas')) {
    http_response_code(403);
    exit('Akses panel petugas tidak diizinkan dari host ini.');
}

if (empty($_SESSION['petugas_id'])) {
    set_flash('error', 'Silakan login terlebih dahulu.');
    redirect('login.php');
}
