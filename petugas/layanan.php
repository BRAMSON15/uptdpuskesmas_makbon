<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Layanan BPJS & Non BPJS';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id_layanan'];
    $kuota = (int)$_POST['kuota_harian'];
    $stmt = $pdo->prepare("UPDATE layanan SET kuota_harian = ? WHERE id_layanan = ?");
    $stmt->execute([$kuota, $id]);
    set_flash('success', 'Kuota layanan berhasil diperbarui.');
    redirect('layanan.php');
}

$daftar = $pdo->query(
    "SELECT l.*, 
     (SELECT COUNT(*) FROM antrian_online a WHERE a.id_layanan = l.id_layanan AND a.tanggal_antrian = CURDATE() AND a.status != 'Dibatalkan') AS terpakai_hari_ini
     FROM layanan l ORDER BY l.jenis, l.nama_layanan"
)->fetchAll();

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="panel">
    <div class="panel-head"><h2>Layanan yang Tersedia & Kuota Harian</h2></div>
    <table>
        <thead><tr><th>Layanan</th><th>Jenis</th><th>Status</th><th>Terpakai / Kuota Hari Ini</th><th>Ubah Kuota</th></tr></thead>
        <tbody>
        <?php foreach ($daftar as $d): ?>
            <tr>
                <td><?= clean($d['nama_layanan']) ?></td>
                <td><?= clean($d['jenis']) ?></td>
                <td><span class="badge <?= $d['status']==='Aktif'?'badge-success':'badge-secondary' ?>"><?= clean($d['status']) ?></span></td>
                <td><?= $d['terpakai_hari_ini'] ?> / <?= $d['kuota_harian'] ?></td>
                <td>
                    <form method="POST" style="display:flex;gap:6px;">
                        <input type="hidden" name="id_layanan" value="<?= $d['id_layanan'] ?>">
                        <input type="number" name="kuota_harian" value="<?= $d['kuota_harian'] ?>" min="1" style="width:80px;padding:6px;border:1px solid var(--border);border-radius:6px;">
                        <button type="submit" class="btn btn-secondary btn-sm">Simpan</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($daftar)): ?>
            <tr><td colspan="5" class="empty-state">Belum ada data layanan.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>
