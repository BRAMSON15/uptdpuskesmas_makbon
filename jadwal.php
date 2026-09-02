<?php
$base = '';
$asset_path = '';
$page_title = 'Jadwal Operasional';
require_once __DIR__ . '/includes/header.php';

$jadwal = $pdo->query("SELECT * FROM jadwal_operasional ORDER BY FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")->fetchAll();
?>

<div class="text-center mb-5">
    <h3 class="title">Jadwal Operasional</h3>
    <p class="text-muted">Jam pelayanan <?= clean($nama_puskesmas) ?></p>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped text-center">
        <thead class="bg-light">
            <tr><th>Hari</th><th>Jam Buka</th><th>Jam Tutup</th><th>Keterangan</th></tr>
        </thead>
        <tbody>
            <?php foreach ($jadwal as $j): ?>
            <tr>
                <td><?= clean($j['hari']) ?></td>
                <td><?= substr($j['jam_buka'],0,5) ?></td>
                <td><?= substr($j['jam_tutup'],0,5) ?></td>
                <td><?= clean($j['keterangan']) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($jadwal)): ?>
            <tr><td colspan="4" class="text-muted">Belum ada jadwal operasional.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
