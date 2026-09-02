<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Kelola Antrian Online';

// Update status / panggil / batalkan
if (isset($_GET['aksi'], $_GET['id'])) {
    $id = (int)$_GET['id'];
    $aksi = $_GET['aksi'];
    $map = [
        'panggil'   => ['status' => 'Diproses', 'ket' => 'Pasien dipanggil, antrian sedang diproses petugas.'],
        'selesai'   => ['status' => 'Selesai',  'ket' => 'Pelayanan selesai dilaksanakan.'],
        'batalkan'  => ['status' => 'Dibatalkan','ket' => 'Antrian dibatalkan oleh petugas.'],
    ];
    if (isset($map[$aksi])) {
        $stmt = $pdo->prepare("UPDATE antrian_online SET status = ?, id_petugas = ? WHERE id_antrian = ?");
        $stmt->execute([$map[$aksi]['status'], $_SESSION['petugas_id'], $id]);

        $stmt = $pdo->prepare("INSERT INTO tracking_antrian (id_antrian, status, keterangan) VALUES (?,?,?)");
        $stmt->execute([$id, $map[$aksi]['status'], $map[$aksi]['ket']]);

        set_flash('success', 'Status antrian berhasil diperbarui.');
    }
    redirect('antrian.php');
}

$filterTanggal = $_GET['tanggal'] ?? '';

if ($filterTanggal !== '') {
    $stmt = $pdo->prepare("SELECT * FROM antrian_online WHERE tanggal_antrian = ? ORDER BY FIELD(status,'Menunggu','Diproses','Selesai','Dibatalkan'), nomor_antrian ASC");
    $stmt->execute([$filterTanggal]);
    $keteranganTabel = "pada tanggal " . tanggal_indo($filterTanggal);
} else {
    $stmt = $pdo->query("SELECT * FROM antrian_online WHERE tanggal_antrian >= CURDATE() ORDER BY tanggal_antrian ASC, FIELD(status,'Menunggu','Diproses','Selesai','Dibatalkan'), nomor_antrian ASC");
    $keteranganTabel = "hari ini dan mendatang";
}
$daftar = $stmt->fetchAll();

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="panel">
    <div class="panel-head">
        <h2>Daftar Antrian Online</h2>
        <form method="GET" style="display:flex;gap:8px; align-items:center;">
            <input type="date" name="tanggal" value="<?= clean($filterTanggal) ?>" style="padding:8px;border:1px solid #dce4e1;border-radius:6px; font-family:inherit;">
            <button type="submit" class="btn btn-secondary btn-sm" style="padding: 8px 16px;">Filter</button>
            <?php if ($filterTanggal !== ''): ?>
                <a href="antrian.php" class="btn btn-primary btn-sm" style="padding: 8px 16px; background:#e74c3c; border-color:#e74c3c;">Reset</a>
            <?php endif; ?>
        </form>
    </div>
    <table>
        <thead><tr><th>Tanggal</th><th>No.</th><th>Pasien</th><th>No. HP</th><th>Layanan</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($daftar as $d): ?>
            <tr>
                <td><?= tanggal_indo($d['tanggal_antrian']) ?></td>
                <td><?= format_nomor_antrian($d['nomor_antrian'], $d['layanan']) ?></td>
                <td><?= clean($d['nama_pasien']) ?></td>
                <td><?= clean($d['no_hp']) ?></td>
                <td><?= clean($d['layanan']) ?></td>
                <td><?= badge_status($d['status']) ?></td>
                <td class="actions">
                    <?php if ($d['status'] === 'Menunggu'): ?>
                        <a href="?aksi=panggil&id=<?= $d['id_antrian'] ?>" class="btn btn-primary btn-sm">Panggil</a>
                        <a href="?aksi=batalkan&id=<?= $d['id_antrian'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Batalkan antrian ini?')">Batalkan</a>
                    <?php elseif ($d['status'] === 'Diproses'): ?>
                        <a href="?aksi=selesai&id=<?= $d['id_antrian'] ?>" class="btn btn-primary btn-sm">Selesaikan</a>
                    <?php else: ?>
                        <span style="color:var(--muted);font-size:0.85rem;">Selesai/Batal</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($daftar)): ?>
            <tr><td colspan="7" class="empty-state">Belum ada antrian <?= $keteranganTabel ?>.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>

