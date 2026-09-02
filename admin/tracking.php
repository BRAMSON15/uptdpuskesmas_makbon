<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Tracking Antrian';

$daftar = $pdo->query(
    "SELECT a.*, 
        (SELECT keterangan FROM tracking_antrian t WHERE t.id_antrian = a.id_antrian ORDER BY waktu_update DESC LIMIT 1) AS keterangan_terakhir,
        (SELECT waktu_update FROM tracking_antrian t WHERE t.id_antrian = a.id_antrian ORDER BY waktu_update DESC LIMIT 1) AS waktu_terakhir
     FROM antrian_online a
     WHERE a.status IN ('Menunggu','Diproses')
     ORDER BY a.tanggal_antrian ASC, a.nomor_antrian ASC"
)->fetchAll();

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="panel">
    <div class="panel-head"><h2>Monitoring Status Antrian (Aktif)</h2></div>
    <table>
        <thead><tr><th>ID</th><th>Pasien</th><th>Layanan</th><th>Tanggal</th><th>No.</th><th>Status</th><th>Update Terakhir</th></tr></thead>
        <tbody>
        <?php foreach ($daftar as $d): ?>
            <tr>
                <td>#<?= $d['id_antrian'] ?></td>
                <td><?= clean($d['nama_pasien']) ?></td>
                <td><?= clean($d['layanan']) ?></td>
                <td><?= tanggal_indo($d['tanggal_antrian']) ?></td>
                <td><?= $d['nomor_antrian'] ?></td>
                <td><?= badge_status($d['status']) ?></td>
                <td>
                    <?= clean($d['keterangan_terakhir'] ?? '-') ?><br>
                    <small style="color:var(--muted);"><?= $d['waktu_terakhir'] ? date('d/m/Y H:i', strtotime($d['waktu_terakhir'])) : '' ?></small>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($daftar)): ?>
            <tr><td colspan="7" class="empty-state">Tidak ada antrian aktif saat ini.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>

