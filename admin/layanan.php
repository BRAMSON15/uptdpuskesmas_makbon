<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Kelola Layanan';

// Hapus
if (isset($_GET['hapus'])) {
    $stmt = $pdo->prepare("DELETE FROM layanan WHERE id_layanan = ?");
    $stmt->execute([(int)$_GET['hapus']]);
    set_flash('success', 'Layanan berhasil dihapus.');
    redirect('layanan.php');
}

// Tambah / Ubah
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = (int)($_POST['id_layanan'] ?? 0);
    $nama     = trim($_POST['nama_layanan'] ?? '');
    $jenis    = $_POST['jenis'] ?? 'Non BPJS';
    $desk     = trim($_POST['deskripsi'] ?? '');
    $jadwal   = trim($_POST['jadwal_layanan'] ?? '');
    $kuota    = (int)($_POST['kuota_harian'] ?? 30);
    $status   = $_POST['status'] ?? 'Aktif';

    if ($nama === '') {
        set_flash('error', 'Nama layanan wajib diisi.');
        redirect('layanan.php');
    }

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE layanan SET nama_layanan=?, jenis=?, deskripsi=?, jadwal_layanan=?, kuota_harian=?, status=? WHERE id_layanan=?");
        $stmt->execute([$nama, $jenis, $desk, $jadwal, $kuota, $status, $id]);
        set_flash('success', 'Layanan berhasil diperbarui.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO layanan (nama_layanan, jenis, deskripsi, jadwal_layanan, kuota_harian, status) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$nama, $jenis, $desk, $jadwal, $kuota, $status]);
        set_flash('success', 'Layanan baru berhasil ditambahkan.');
    }
    redirect('layanan.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM layanan WHERE id_layanan = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
}

$daftar = $pdo->query("SELECT * FROM layanan ORDER BY id_layanan DESC")->fetchAll();

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="panel">
    <div class="panel-head"><h2><?= $edit ? 'Ubah Layanan' : 'Tambah Layanan Baru' ?></h2></div>
    <form method="POST">
        <input type="hidden" name="id_layanan" value="<?= $edit['id_layanan'] ?? '' ?>">
        <div class="form-row">
            <div class="form-group">
                <label>Nama Layanan</label>
                <input type="text" name="nama_layanan" value="<?= clean($edit['nama_layanan'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Jenis</label>
                <select name="jenis" required>
                    <option value="BPJS" <?= ($edit['jenis'] ?? '') === 'BPJS' ? 'selected' : '' ?>>BPJS</option>
                    <option value="Non BPJS" <?= ($edit['jenis'] ?? '') === 'Non BPJS' ? 'selected' : '' ?>>Non BPJS</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="2"><?= clean($edit['deskripsi'] ?? '') ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Jadwal Layanan</label>
                <input type="text" name="jadwal_layanan" value="<?= clean($edit['jadwal_layanan'] ?? '') ?>" placeholder="Senin - Sabtu, 08.00 - 12.00">
            </div>
            <div class="form-group">
                <label>Kuota Harian</label>
                <input type="number" name="kuota_harian" value="<?= clean($edit['kuota_harian'] ?? 30) ?>" min="1" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="Aktif" <?= ($edit['status'] ?? 'Aktif') === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="Nonaktif" <?= ($edit['status'] ?? '') === 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><?= $edit ? 'Simpan Perubahan' : 'Tambah Layanan' ?></button>
        <?php if ($edit): ?><a href="layanan.php" class="btn btn-secondary">Batal</a><?php endif; ?>
    </form>
</div>

<div class="panel">
    <div class="panel-head"><h2>Daftar Layanan</h2></div>
    <table>
        <thead><tr><th>Nama</th><th>Jenis</th><th>Jadwal</th><th>Kuota</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($daftar as $d): ?>
            <tr>
                <td><?= clean($d['nama_layanan']) ?></td>
                <td><?= clean($d['jenis']) ?></td>
                <td><?= clean($d['jadwal_layanan']) ?></td>
                <td><?= $d['kuota_harian'] ?></td>
                <td><span class="badge <?= $d['status']==='Aktif'?'badge-success':'badge-secondary' ?>"><?= clean($d['status']) ?></span></td>
                <td class="actions">
                    <a href="?edit=<?= $d['id_layanan'] ?>" class="btn btn-secondary btn-sm">Ubah</a>
                    <a href="?hapus=<?= $d['id_layanan'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus layanan ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($daftar)): ?>
            <tr><td colspan="6" class="empty-state">Belum ada data layanan.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>
