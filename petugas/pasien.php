<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Data Pasien';

// Ubah data pasien (nama, no hp, alamat) dari histori antrian
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id_antrian'];
    $stmt = $pdo->prepare("UPDATE antrian_online SET nama_pasien=?, no_hp=?, alamat=? WHERE id_antrian=?");
    $stmt->execute([trim($_POST['nama_pasien']), trim($_POST['no_hp']), trim($_POST['alamat']), $id]);
    set_flash('success', 'Data pasien berhasil diperbarui.');
    redirect('pasien.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM antrian_online WHERE id_antrian = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
}

$cari = trim($_GET['cari'] ?? '');
$sql = "SELECT DISTINCT nama_pasien, no_hp, alamat, MAX(id_antrian) as id_antrian FROM antrian_online";
$params = [];
if ($cari !== '') {
    $sql .= " WHERE nama_pasien LIKE ? OR no_hp LIKE ?";
    $params = ["%$cari%", "%$cari%"];
}
$sql .= " GROUP BY nama_pasien, no_hp, alamat ORDER BY id_antrian DESC LIMIT 50";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$daftar = $stmt->fetchAll();

require_once __DIR__ . '/includes/layout_top.php';
?>

<?php if ($edit): ?>
<div class="panel">
    <div class="panel-head"><h2>Ubah Data Pasien</h2></div>
    <form method="POST">
        <input type="hidden" name="id_antrian" value="<?= $edit['id_antrian'] ?>">
        <div class="form-row">
            <div class="form-group">
                <label>Nama Pasien</label>
                <input type="text" name="nama_pasien" value="<?= clean($edit['nama_pasien']) ?>" required>
            </div>
            <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="no_hp" value="<?= clean($edit['no_hp']) ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" rows="2"><?= clean($edit['alamat']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="pasien.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
<?php endif; ?>

<div class="panel">
    <div class="panel-head">
        <h2>Data Pasien</h2>
        <form method="GET" style="display:flex;gap:8px;">
            <input type="text" name="cari" value="<?= clean($cari) ?>" placeholder="Cari nama/HP..." style="padding:8px;border:1px solid var(--border);border-radius:6px;">
            <button type="submit" class="btn btn-secondary btn-sm">Cari</button>
        </form>
    </div>
    <table>
        <thead><tr><th>Nama Pasien</th><th>No. HP</th><th>Alamat</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($daftar as $d): ?>
            <tr>
                <td><?= clean($d['nama_pasien']) ?></td>
                <td><?= clean($d['no_hp']) ?></td>
                <td><?= clean($d['alamat']) ?></td>
                <td><a href="?edit=<?= $d['id_antrian'] ?>" class="btn btn-secondary btn-sm">Ubah</a></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($daftar)): ?>
            <tr><td colspan="4" class="empty-state">Belum ada data pasien.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>
