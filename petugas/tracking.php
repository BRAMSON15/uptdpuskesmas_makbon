<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Tracking / Pemantauan Antrian';

$daftar = $pdo->query(
    "SELECT * FROM antrian_online WHERE tanggal_antrian = CURDATE() AND status IN ('Menunggu','Diproses')
     ORDER BY FIELD(status,'Diproses','Menunggu'), nomor_antrian ASC"
)->fetchAll();

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="panel">
    <div class="panel-head"><h2>Pemantauan Antrian Hari Ini</h2></div>
    <table>
        <thead><tr><th>No.</th><th>Pasien</th><th>Layanan</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($daftar as $d): ?>
            <tr>
                <td style="font-size:1.2rem;font-weight:700;color:var(--primary-dark);"><?= $d['nomor_antrian'] ?></td>
                <td><?= clean($d['nama_pasien']) ?></td>
                <td><?= clean($d['layanan']) ?></td>
                <td><?= badge_status($d['status']) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($daftar)): ?>
            <tr><td colspan="4" class="empty-state">Tidak ada antrian yang sedang berjalan.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>
