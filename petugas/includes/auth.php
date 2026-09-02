<?php
require_once __DIR__ . '/../../includes/functions.php';

if (empty($_SESSION['petugas_id'])) {
    set_flash('error', 'Silakan login terlebih dahulu.');
    redirect('login.php');
}
