<?php
$base = '';
$asset_path = '';
$page_title = 'Antrian Online';
require_once __DIR__ . '/includes/header.php';

$layananList = $pdo->query("SELECT * FROM layanan WHERE status = 'Aktif' ORDER BY nama_layanan")->fetchAll();
?>
<style>
    .nice-select .list {
        max-height: 250px !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }
</style>

<h4 class="mb-3">Pendaftaran Antrian Online</h4>
<p class="mb-4 text-muted">Isi data berikut untuk mendapatkan nomor antrian. Bukti antrian akan ditampilkan setelah pendaftaran berhasil.</p>
<?php show_flash(); ?>

<form action="antrian_proses.php" method="POST">
    <div class="form-group mb-3">
        <label>Nama Pasien</label>
        <input type="text" class="form-control" name="nama_pasien" required placeholder="Nama lengkap pasien">
    </div>
    <div class="row">
        <div class="col-md-6 form-group mb-3">
            <label>No. HP / WhatsApp</label>
            <input type="text" class="form-control" name="no_hp" required placeholder="08xxxxxxxxxx">
        </div>
        <div class="col-md-6 form-group mb-3">
            <label>Tanggal Kunjungan</label>
            <input type="date" class="form-control" name="tanggal_antrian" min="<?= date('Y-m-d') ?>" required>
        </div>
    </div>
    <div class="form-group mb-3">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat domisili"></textarea>
    </div>
    <div class="form-group mb-4">
        <label>Pilih Layanan / Poli</label>
        <select name="id_layanan" class="form-control" required>
            <option value="">-- Pilih Layanan --</option>
            <?php foreach ($layananList as $l): ?>
            <option value="<?= $l['id_layanan'] ?>">
                <?= clean($l['nama_layanan']) ?> (<?= clean($l['jenis']) ?>)
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="main-btn w-100">Daftar Antrian Sekarang</button>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
