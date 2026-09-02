<?php
$base = '';
$asset_path = '';
$page_title = 'Layanan';
require_once __DIR__ . '/includes/header.php';

$layanan = $pdo->query("SELECT * FROM layanan WHERE status = 'Aktif' ORDER BY jenis, nama_layanan")->fetchAll();
?>

<div class="text-center mb-5">
    <h3 class="title">Informasi Layanan</h3>
    <p class="text-muted">Daftar layanan BPJS dan Non BPJS yang tersedia</p>
</div>

<div class="row">
    <?php foreach ($layanan as $l): ?>
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <span class="badge <?= $l['jenis'] === 'BPJS' ? 'badge-success' : 'badge-warning' ?> mb-2"><?= clean($l['jenis']) ?></span>
                <h5 class="card-title"><?= clean($l['nama_layanan']) ?></h5>
                <p class="card-text text-muted"><?= clean($l['deskripsi']) ?></p>
                <small class="text-muted"><i class="lni lni-alarm-clock"></i> <?= clean($l['jadwal_layanan']) ?></small>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($layanan)): ?>
        <div class="col-12 text-center text-muted"><p>Belum ada data layanan.</p></div>
    <?php endif; ?>
</div>

<div class="text-center mt-5">
    <a href="antrian.php" class="main-btn">Daftar Antrian untuk Layanan Ini</a>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
