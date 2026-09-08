<?php
$base = '';
$asset_path = '';
$page_title = 'Tracking Antrian';
require_once __DIR__ . '/includes/header.php';

$data = null;
$riwayat = [];
$nomorSaatIni = null;
$sisaSebelum = 0;
$totalMenunggu = 0;
$nomor_antrian = strtoupper(trim($_GET['nomor_antrian'] ?? ''));
$kodeNomor = preg_match('/^([A-Z]+)-(\d+)$/', $nomor_antrian, $nomorParts)
    ? (int)$nomorParts[2]
    : null;

if ($kodeNomor !== null) {
    $stmt = $pdo->prepare("SELECT * FROM antrian_online WHERE nomor_antrian = ? ORDER BY id_antrian DESC");
    $stmt->execute([$kodeNomor]);
    foreach ($stmt->fetchAll() as $calon) {
        if (format_nomor_antrian($calon['nomor_antrian'], $calon['layanan']) === $nomor_antrian) {
            $data = $calon;
            break;
        }
    }

    if ($data) {
        $stmt = $pdo->prepare("SELECT * FROM tracking_antrian WHERE id_antrian = ? ORDER BY waktu_update ASC");
        $stmt->execute([$data['id_antrian']]);
        $riwayat = $stmt->fetchAll();

        $stmt = $pdo->prepare("SELECT MAX(nomor_antrian) AS nomor FROM antrian_online WHERE id_layanan = ? AND tanggal_antrian = ? AND status = 'Diproses'");
        $stmt->execute([$data['id_layanan'], $data['tanggal_antrian']]);
        $nomorSaatIni = (int)($stmt->fetch()['nomor'] ?? 0);

        $stmt = $pdo->prepare("SELECT COUNT(*) AS jumlah FROM antrian_online WHERE id_layanan = ? AND tanggal_antrian = ? AND status = 'Menunggu' AND nomor_antrian < ?");
        $stmt->execute([$data['id_layanan'], $data['tanggal_antrian'], $data['nomor_antrian']]);
        $sisaSebelum = (int)$stmt->fetch()['jumlah'];

        $stmt = $pdo->prepare("SELECT COUNT(*) AS jumlah FROM antrian_online WHERE id_layanan = ? AND tanggal_antrian = ? AND status = 'Menunggu'");
        $stmt->execute([$data['id_layanan'], $data['tanggal_antrian']]);
        $totalMenunggu = (int)$stmt->fetch()['jumlah'];
    }
}
?>

<h4 class="mb-3">Cek Status Antrian Anda</h4>
<p class="mb-4 text-muted">Masukkan nomor antrian yang tertera di bukti pendaftaran untuk melihat status terkini.</p>

<form method="GET">
    <div class="form-group">
        <label for="nomor_antrian">Nomor Antrian</label>
        <input type="text" class="form-control form-control-lg" name="nomor_antrian" id="nomor_antrian" value="<?= clean($nomor_antrian) ?>" placeholder="Contoh: PA-001" required>
    </div>
    <button type="submit" class="main-btn mt-3 w-100">Cek Status</button>
</form>

<?php if ($nomor_antrian !== '' && !$data): ?>
    <div class="alert alert-danger mt-4">Data dengan nomor antrian tersebut tidak ditemukan.</div>
<?php elseif ($data): ?>
    <div class="mt-5">
        <div class="panel-head d-flex justify-content-between align-items-center">
            <span>Detail Nomor Antrian <?= clean(format_nomor_antrian($data['nomor_antrian'], $data['layanan'])) ?></span> 
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

        <?php if ($data['status'] === 'Menunggu' || $data['status'] === 'Diproses'): ?>
        <div class="alert alert-info mt-4">
            <?php if ($nomorSaatIni > 0): ?>
                <strong>Sedang dilayani: <?= clean(format_nomor_antrian($nomorSaatIni, $data['layanan'])) ?></strong><br>
            <?php else: ?>
                <strong>Belum ada nomor yang sedang dilayani.</strong><br>
            <?php endif; ?>
            <?php if ($data['status'] === 'Menunggu'): ?>
                Ada <strong><?= $sisaSebelum ?></strong> antrean sebelum nomor Anda dan <strong><?= $totalMenunggu ?></strong> antrean masih menunggu.
            <?php else: ?>
                Nomor Anda sedang diproses oleh petugas.
            <?php endif; ?>
            <div class="small mt-1">Halaman memperbarui posisi secara otomatis.</div>
        </div>
        <?php endif; ?>

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

<?php if ($data && in_array($data['status'], ['Menunggu', 'Diproses'], true)): ?>
<script>
    setTimeout(function () { window.location.reload(); }, 15000);
</script>
<?php endif; ?>

