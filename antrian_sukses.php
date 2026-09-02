<?php
$base = '';
$asset_path = '';
$page_title = 'Bukti Antrian';
require_once __DIR__ . '/includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM antrian_online WHERE id_antrian = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    echo "<div class='alert alert-danger text-center'>Data antrian tidak ditemukan.</div>";
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
?>

<div class="row justify-content-center">
    <div class="col-lg-8 text-center">
        <h3 class="text-success mb-3">Pendaftaran Berhasil</h3>
        <p class="text-muted mb-4">Simpan nomor antrian Anda di bawah ini</p>

        <div class="p-4 mb-4" style="background:#0d7c66; color:#fff; border-radius:12px;">
            <div class="text-uppercase mb-2" style="font-size:0.85rem; letter-spacing:1px; opacity:0.8;">Nomor Antrian</div>
            <div class="display-3 font-weight-bold mb-2"><?= format_nomor_antrian($data['nomor_antrian'], $data['layanan']) ?></div>
            <div style="font-size:0.9rem; opacity:0.85;"><?= clean($data['layanan']) ?></div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered text-left">
                <tr><th width="40%">ID Antrian</th><td>#<?= clean($data['id_antrian']) ?></td></tr>
                <tr><th>Nama Pasien</th><td><?= clean($data['nama_pasien']) ?></td></tr>
                <tr><th>Tanggal Kunjungan</th><td><?= tanggal_indo($data['tanggal_antrian']) ?></td></tr>
                <tr><th>Status</th><td><?= badge_status($data['status']) ?></td></tr>
            </table>
        </div>

        <div class="mt-4">
            <a href="tracking.php?id_antrian=<?= $data['id_antrian'] ?>" class="main-btn me-2">Lacak Status Antrian</a>
            <a href="index.php" class="main-btn main-btn-2">Kembali ke Beranda</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

