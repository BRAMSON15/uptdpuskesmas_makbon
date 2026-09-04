<?php
require_once __DIR__ . '/../../includes/functions.php';

if (!is_panel_host('admin')) {
    http_response_code(403);
    exit('Akses panel admin tidak diizinkan dari host ini.');
}

if (empty($_SESSION['admin_id'])) {
    set_flash('error', 'Silakan login terlebih dahulu.');
    redirect('login.php');
}
