<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Dashboard';

$totalAntrianHariIni = $pdo->query("SELECT COUNT(*) c FROM antrian_online WHERE tanggal_antrian = CURDATE()")->fetch()['c'];
$totalAntrianSemua   = $pdo->query("SELECT COUNT(*) c FROM antrian_online")->fetch()['c'];
$totalMenunggu       = $pdo->query("SELECT COUNT(*) c FROM antrian_online WHERE status = 'Menunggu'")->fetch()['c'];
$totalLayanan        = $pdo->query("SELECT COUNT(*) c FROM layanan WHERE status = 'Aktif'")->fetch()['c'];
$totalPetugas        = $pdo->query("SELECT COUNT(*) c FROM petugas")->fetch()['c'];
$totalSaranBaru      = $pdo->query("SELECT COUNT(*) c FROM saran_masukan WHERE status = 'Baru'")->fetch()['c'];

// Jika hari ini kosong, tampilkan antrian mendatang; jika tidak, tampilkan hari ini
$antrianTerbaru = $pdo->query("SELECT * FROM antrian_online ORDER BY id_antrian DESC LIMIT 6")->fetchAll();

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="dashboard-intro">
    <div>
        <h2>Ringkasan operasional</h2>
        <p>Pantau aktivitas layanan Puskesmas Makbon dari satu tempat.</p>
    </div>
    <div class="dashboard-date"><i class="lni lni-calendar"></i> <?= tanggal_indo(date('Y-m-d')) ?></div>
</div>

<div class="quick-actions">
    <a href="antrian.php" class="btn btn-primary"><i class="lni lni-plus"></i> Kelola Antrian</a>
    <a href="layanan.php" class="btn btn-outline-secondary"><i class="lni lni-layers"></i> Atur Layanan</a>
    <a href="saran.php" class="btn btn-outline-secondary"><i class="lni lni-comments"></i> Cek Masukan</a>
</div>

<div class="row mb-4">
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card stat-card-modern accent-teal">
            <i class="lni lni-ticket icon-bg text-white"></i>
            <div class="card-body">
                <h3 class="mb-0"><?= $totalAntrianHariIni > 0 ? $totalAntrianHariIni : $totalAntrianSemua ?></h3>
                <small><?= $totalAntrianHariIni > 0 ? 'Antrian Hari Ini' : 'Total Antrian' ?></small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card-modern accent-amber">
            <i class="lni lni-timer icon-bg text-white"></i>
            <div class="card-body">
                <h3 class="mb-0"><?= $totalMenunggu ?></h3>
                <small>Antrian Menunggu</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card stat-card-modern accent-blue">
            <i class="lni lni-layers icon-bg text-white"></i>
            <div class="card-body">
                <h3 class="mb-0"><?= $totalLayanan ?></h3>
                <small>Layanan Aktif</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card stat-card-modern accent-green">
            <i class="lni lni-users icon-bg text-white"></i>
            <div class="card-body">
                <h3 class="mb-0"><?= $totalPetugas ?></h3>
                <small>Petugas</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card-modern accent-red">
            <i class="lni lni-envelope icon-bg text-white"></i>
            <div class="card-body">
                <h3 class="mb-0"><?= $totalSaranBaru ?></h3>
                <small>Saran Belum Dibalas</small>
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-head">
        <h2>Antrian Terbaru</h2>
        <a href="antrian.php" class="btn btn-secondary btn-sm">Lihat Semua</a>
    </div>
    <table>
        <thead><tr><th>ID</th><th>Nama Pasien</th><th>Layanan</th><th>Tanggal</th><th>No. Antrian</th><th>Status</th></tr></thead>
        <tbody>
            <?php foreach ($antrianTerbaru as $a): ?>
            <tr>
                <td>#<?= $a['id_antrian'] ?></td>
                <td><?= clean($a['nama_pasien']) ?></td>
                <td><?= clean($a['layanan']) ?></td>
                <td><?= tanggal_indo($a['tanggal_antrian']) ?></td>
                <td><?= format_nomor_antrian($a['nomor_antrian'], $a['layanan']) ?></td>
                <td><?= badge_status($a['status']) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($antrianTerbaru)): ?>
            <tr><td colspan="6" class="empty-state">Belum ada data antrian.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>

