<?php
$base = '';
$asset_path = '';
$page_title = 'Tracking Antrian';
require_once __DIR__ . '/includes/header.php';

$data = null;
$riwayat = [];
$id_antrian = trim($_GET['id_antrian'] ?? '');

if ($id_antrian !== '') {
    $stmt = $pdo->prepare("SELECT * FROM antrian_online WHERE id_antrian = ?");
    $stmt->execute([$id_antrian]);
    $data = $stmt->fetch();

    if ($data) {
        $stmt = $pdo->prepare("SELECT * FROM tracking_antrian WHERE id_antrian = ? ORDER BY waktu_update ASC");
        $stmt->execute([$id_antrian]);
        $riwayat = $stmt->fetchAll();
    }
}
?>

<h4 class="mb-3">Cek Status Antrian Anda</h4>
<p class="mb-4 text-muted">Masukkan nomor ID antrian Anda (tertera di bukti pendaftaran) untuk melihat status terkini.</p>

<form method="GET">
    <div class="form-group">
        <label for="id_antrian">ID Antrian</label>
        <input type="text" class="form-control form-control-lg" name="id_antrian" id="id_antrian" value="<?= clean($id_antrian) ?>" placeholder="Contoh: 12" required>
    </div>
    <button type="submit" class="main-btn mt-3 w-100">Cek Status</button>
</form>

<?php if ($id_antrian !== '' && !$data): ?>
    <div class="alert alert-danger mt-4">Data antrian dengan ID tersebut tidak ditemukan.</div>
<?php elseif ($data): ?>
    <div class="mt-5">
        <div class="panel-head d-flex justify-content-between align-items-center">
            <span>Detail Antrian #<?= clean($data['id_antrian']) ?></span> 
            <?= badge_status($data['status']) ?>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr><th width="35%">Nama Pasien</th><td><?= clean($data['nama_pasien']) ?></td></tr>
                <tr><th>Layanan</th><td><?= clean($data['layanan']) ?></td></tr>
                <tr><th>Nomor Antrian</th><td><?= format_nomor_antrian($data['nomor_antrian'], $data['layanan']) ?></td></tr>
                <tr><th>Tanggal Kunjungan</th><td><?= tanggal_indo($data['tanggal_antrian']) ?></td></tr>
            </table>
        </div>

        <h5 class="mt-4 mb-3" style="color:#0d7c66;">Riwayat Status</h5>
        <ul class="list-group list-group-flush">
            <?php foreach ($riwayat as $r): ?>
            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                <div>
                    <?= badge_status($r['status']) ?> — <?= clean($r['keterangan']) ?>
                </div>
                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($r['waktu_update'])) ?></small>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

