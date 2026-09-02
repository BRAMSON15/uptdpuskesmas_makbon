<?php
/**
 * Fungsi-fungsi umum (helper) sistem Puskesmas Makbon
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Base URL
 * -----------------------------------------------------------
 * Kosongkan '' → otomatis mendeteksi (localhost).
 * Isi dengan URL ngrok → langsung pakai URL tersebut.
 *
 * Contoh ngrok:
 *   $CUSTOM_BASE_URL = 'https://abcd-1234.ngrok-free.app/';
 * -----------------------------------------------------------
 */

//tempat custom link url atau domain hosting
$CUSTOM_BASE_URL = 'https://uptdpuskesmasmakbon.com/index.php'; // Kosongi untuk auto-detect (localhost), atau isi dengan URL ngrok tanpa trailing space
//$CUSTOM_BASE_URL = 'http://localhost/puskesmas-makbon/index.php'; // Kosongi untuk auto-detect (localhost), atau isi dengan URL ngrok tanpa trailing space
if (!defined('BASE_URL')) {
    if (!empty(trim($CUSTOM_BASE_URL))) {
        // Pakai URL manual (ngrok, dll)
        define('BASE_URL', rtrim(trim($CUSTOM_BASE_URL), '/') . '/');
    } else {
        // Auto-detect dari server
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $doc_root = realpath($_SERVER['DOCUMENT_ROOT']);
        $project_root = realpath(__DIR__ . '/..');
        $base_path = str_replace('\\', '/', str_replace($doc_root, '', $project_root));
        define('BASE_URL', $protocol . '://' . $host . $base_path . '/');
    }
}

/** Mendapatkan URL lengkap dari path relatif */
function base_url($path = '')
{ 
    return BASE_URL . ltrim($path, '/');
}

/** Bersihkan input dari karakter berbahaya sebelum ditampilkan */
function clean($str)
{
    return htmlspecialchars(trim($str ?? ''), ENT_QUOTES, 'UTF-8');
}

/** Format tanggal Indonesia, contoh: 04 Juli 2026 */
function tanggal_indo($tanggal)
{
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    $ts = strtotime($tanggal);
    return date('d', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

/** Label warna badge status antrian */
function badge_status($status)
{
    $map = [
        'Menunggu'   => 'badge-warning',
        'Diproses'   => 'badge-info',
        'Selesai'    => 'badge-success',
        'Dibatalkan' => 'badge-danger',
    ];
    $class = $map[$status] ?? 'badge-secondary';
    return "<span class='badge {$class}'>" . clean($status) . "</span>";
}

/** Redirect helper */
function redirect($url)
{
    header("Location: {$url}");
    exit;
}

/** Flash message sederhana via session */
function set_flash($type, $msg)
{
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function show_flash()
{
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        $type = $f['type'] === 'error' ? 'danger' : $f['type'];
        echo "<div class='alert alert-{$type}'>" . clean($f['msg']) . "</div>";
        unset($_SESSION['flash']);
    }
}

/** Ambil nomor antrian berikutnya untuk layanan & tanggal tertentu */
function nomor_antrian_berikutnya(PDO $pdo, $id_layanan, $tanggal)
{
    $stmt = $pdo->prepare(
        "SELECT MAX(nomor_antrian) AS max_no FROM antrian_online WHERE id_layanan = ? AND tanggal_antrian = ?"
    );
    $stmt->execute([$id_layanan, $tanggal]);
    $row = $stmt->fetch();
    return ((int)($row['max_no'] ?? 0)) + 1;
}

/** Format nomor antrian agar unik per layanan (Contoh: PF-001) */
function format_nomor_antrian($nomor, $layanan)
{
    $words = explode(' ', str_replace(['&', '-'], '', strtoupper($layanan)));
    $prefix = '';
    foreach ($words as $w) {
        if (trim($w) !== '') {
            $prefix .= $w[0];
        }
        if (strlen($prefix) >= 2) break;
    }
    if (empty($prefix)) $prefix = 'Q';
    return $prefix . '-' . str_pad($nomor, 3, '0', STR_PAD_LEFT);
}
