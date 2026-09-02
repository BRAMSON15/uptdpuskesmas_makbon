<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Kelola Antrian Online';

if (isset($_GET['hapus'])) {
    $stmt = $pdo->prepare("DELETE FROM antrian_online WHERE id_antrian = ?");
    $stmt->execute([(int)$_GET['hapus']]);
    set_flash('success', 'Data antrian berhasil dihapus.');
    redirect('antrian.php');
}

$filterTanggal = trim($_GET['tanggal'] ?? '');
$sql = "SELECT * FROM antrian_online";
$params = [];
if ($filterTanggal !== '') {
    $sql .= " WHERE tanggal_antrian = ?";
    $params[] = $filterTanggal;
}
$sql .= " ORDER BY tanggal_antrian DESC, nomor_antrian ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$daftar = $stmt->fetchAll();

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="panel">
    <div class="panel-head">
        <h2>Daftar Antrian Online</h2>
        <form method="GET" style="display:flex;gap:8px;">
            <input type="date" name="tanggal" value="<?= clean($filterTanggal) ?>" style="padding:8px;border:1px solid var(--border);border-radius:6px;">
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            <?php if ($filterTanggal): ?><a href="antrian.php" class="btn btn-secondary btn-sm">Reset</a><?php endif; ?>
        </form>
    </div>
    <table>
        <thead><tr><th>No.</th><th>Pasien</th><th>Layanan</th><th>Tanggal</th><th>No. Antrian</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($daftar as $d): ?>
            <tr>
                <td>#<?= $d['id_antrian'] ?></td>
                <td><?= clean($d['nama_pasien']) ?><br><small style="color:var(--muted);"><?= clean($d['no_hp']) ?></small></td>
                <td><?= clean($d['layanan']) ?></td>
                <td><?= tanggal_indo($d['tanggal_antrian']) ?></td>
                <td><?= format_nomor_antrian($d['nomor_antrian'], $d['layanan']) ?></td>
                <td><?= badge_status($d['status']) ?></td>
                <td class="actions">
                    <a href="?hapus=<?= $d['id_antrian'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data antrian ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($daftar)): ?>
            <tr><td colspan="7" class="empty-state">Belum ada data antrian.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>

