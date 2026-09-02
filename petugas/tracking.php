<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Tracking / Pemantauan Antrian';

$daftar = $pdo->query(
    "SELECT * FROM antrian_online WHERE tanggal_antrian = CURDATE() AND status IN ('Menunggu','Diproses')
     ORDER BY FIELD(status,'Diproses','Menunggu'), nomor_antrian ASC"
)->fetchAll();

$labelSumber = 'Hari Ini';
if (empty($daftar)) {
    $daftar = $pdo->query(
        "SELECT * FROM antrian_online WHERE tanggal_antrian >= CURDATE() AND status IN ('Menunggu','Diproses')
         ORDER BY tanggal_antrian ASC, FIELD(status,'Diproses','Menunggu'), nomor_antrian ASC"
    )->fetchAll();
    $labelSumber = empty($daftar) ? 'Hari Ini' : 'Mendatang';
}

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="panel">
    <div class="panel-head"><h2>Pemantauan Antrian <?= $labelSumber ?></h2></div>
    <table>
        <thead><tr><th>Tanggal</th><th>No.</th><th>Pasien</th><th>Layanan</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($daftar as $d): ?>
            <tr>
                <td><?= tanggal_indo($d['tanggal_antrian']) ?></td>
                <td style="font-size:1.2rem;font-weight:700;color:var(--primary-dark);"><?= format_nomor_antrian($d['nomor_antrian'], $d['layanan']) ?></td>
                <td><?= clean($d['nama_pasien']) ?></td>
                <td><?= clean($d['layanan']) ?></td>
                <td><?= badge_status($d['status']) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($daftar)): ?>
            <tr><td colspan="5" class="empty-state">Tidak ada antrian yang sedang berjalan.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>

