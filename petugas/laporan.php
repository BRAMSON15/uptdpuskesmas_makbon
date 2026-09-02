<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Laporan';

$tanggal = trim($_GET['tanggal'] ?? date('Y-m-d'));

$stmt = $pdo->prepare("SELECT * FROM antrian_online WHERE tanggal_antrian = ? ORDER BY nomor_antrian ASC");
$stmt->execute([$tanggal]);
$antrianHarian = $stmt->fetchAll();

$rekapLayanan = $pdo->prepare(
    "SELECT layanan, COUNT(*) AS jumlah,
     SUM(CASE WHEN status='Selesai' THEN 1 ELSE 0 END) AS selesai,
     SUM(CASE WHEN status='Dibatalkan' THEN 1 ELSE 0 END) AS batal
     FROM antrian_online WHERE tanggal_antrian = ? GROUP BY layanan"
);
$rekapLayanan->execute([$tanggal]);
$rekap = $rekapLayanan->fetchAll();

$totalPasienUnik = $pdo->prepare("SELECT COUNT(DISTINCT nama_pasien) c FROM antrian_online WHERE tanggal_antrian = ?");
$totalPasienUnik->execute([$tanggal]);
$totalPasien = $totalPasienUnik->fetch()['c'];

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="panel">
    <div class="panel-head">
        <h2>Laporan Harian</h2>
        <form method="GET" style="display:flex;gap:8px;">
            <input type="date" name="tanggal" value="<?= clean($tanggal) ?>" style="padding:8px;border:1px solid var(--border);border-radius:6px;">
            <button type="submit" class="btn btn-secondary btn-sm">Tampilkan</button>
        </form>
    </div>
    <div class="row mt-3">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary h-100 shadow-sm border-0">
                <div class="card-body p-3 text-center">
                    <h3 class="mb-0"><?= count($antrianHarian) ?></h3>
                    <small>Total Antrian</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-info h-100 shadow-sm border-0">
                <div class="card-body p-3 text-center">
                    <h3 class="mb-0"><?= $totalPasien ?></h3>
                    <small>Pasien (Unik)</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success h-100 shadow-sm border-0">
                <div class="card-body p-3 text-center">
                    <h3 class="mb-0"><?= count(array_filter($antrianHarian, fn($a) => $a['status']==='Selesai')) ?></h3>
                    <small>Selesai Dilayani</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-head"><h2>Rekapitulasi per Layanan — <?= tanggal_indo($tanggal) ?></h2></div>
    <table>
        <thead><tr><th>Layanan</th><th>Jumlah Antrian</th><th>Selesai</th><th>Dibatalkan</th></tr></thead>
        <tbody>
        <?php foreach ($rekap as $r): ?>
            <tr>
                <td><?= clean($r['layanan']) ?></td>
                <td><?= $r['jumlah'] ?></td>
                <td><?= $r['selesai'] ?></td>
                <td><?= $r['batal'] ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($rekap)): ?>
            <tr><td colspan="4" class="empty-state">Tidak ada data pada tanggal ini.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="panel">
    <div class="panel-head"><h2>Laporan Data Pasien Harian</h2></div>
    <table>
        <thead><tr><th>No.</th><th>Nama Pasien</th><th>Layanan</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($antrianHarian as $a): ?>
            <tr>
                <td><?= format_nomor_antrian($a['nomor_antrian'], $a['layanan']) ?></td>
                <td><?= clean($a['nama_pasien']) ?></td>
                <td><?= clean($a['layanan']) ?></td>
                <td><?= badge_status($a['status']) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($antrianHarian)): ?>
            <tr><td colspan="4" class="empty-state">Tidak ada data pada tanggal ini.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>

