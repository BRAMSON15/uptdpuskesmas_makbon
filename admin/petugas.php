<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Kelola Petugas';

if (isset($_GET['hapus'])) {
    $stmt = $pdo->prepare("DELETE FROM petugas WHERE id_petugas = ?");
    $stmt->execute([(int)$_GET['hapus']]);
    set_flash('success', 'Data petugas berhasil dihapus.');
    redirect('petugas.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = (int)($_POST['id_petugas'] ?? 0);
    $nama     = trim($_POST['nama_petugas']);
    $username = trim($_POST['username']);
    $jabatan  = trim($_POST['jabatan']);
    $password = trim($_POST['password'] ?? '');

    if ($id > 0) {
        if ($password !== '') {
            $stmt = $pdo->prepare("UPDATE petugas SET nama_petugas=?, username=?, jabatan=?, password=? WHERE id_petugas=?");
            $stmt->execute([$nama, $username, $jabatan, password_hash($password, PASSWORD_BCRYPT), $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE petugas SET nama_petugas=?, username=?, jabatan=? WHERE id_petugas=?");
            $stmt->execute([$nama, $username, $jabatan, $id]);
        }
        set_flash('success', 'Data petugas berhasil diperbarui.');
    } else {
        if ($password === '') {
            set_flash('error', 'Password wajib diisi untuk petugas baru.');
            redirect('petugas.php');
        }
        $stmt = $pdo->prepare("INSERT INTO petugas (nama_petugas, username, password, jabatan) VALUES (?,?,?,?)");
        $stmt->execute([$nama, $username, password_hash($password, PASSWORD_BCRYPT), $jabatan]);
        set_flash('success', 'Petugas baru berhasil ditambahkan.');
    }
    redirect('petugas.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM petugas WHERE id_petugas = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
}

$daftar = $pdo->query("SELECT * FROM petugas ORDER BY id_petugas DESC")->fetchAll();

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="panel">
    <div class="panel-head"><h2><?= $edit ? 'Ubah Petugas' : 'Tambah Petugas Baru' ?></h2></div>
    <form method="POST">
        <input type="hidden" name="id_petugas" value="<?= $edit['id_petugas'] ?? '' ?>">
        <div class="form-row">
            <div class="form-group">
                <label>Nama Petugas</label>
                <input type="text" name="nama_petugas" value="<?= clean($edit['nama_petugas'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Jabatan</label>
                <input type="text" name="jabatan" value="<?= clean($edit['jabatan'] ?? '') ?>" placeholder="Petugas Pendaftaran">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?= clean($edit['username'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Password <?= $edit ? '(kosongkan jika tidak diubah)' : '' ?></label>
                <input type="password" name="password" <?= $edit ? '' : 'required' ?>>
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><?= $edit ? 'Simpan Perubahan' : 'Tambah Petugas' ?></button>
        <?php if ($edit): ?><a href="petugas.php" class="btn btn-secondary">Batal</a><?php endif; ?>
    </form>
</div>

<div class="panel">
    <div class="panel-head"><h2>Daftar Petugas</h2></div>
    <table>
        <thead><tr><th>Nama</th><th>Username</th><th>Jabatan</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($daftar as $d): ?>
            <tr>
                <td><?= clean($d['nama_petugas']) ?></td>
                <td><?= clean($d['username']) ?></td>
                <td><?= clean($d['jabatan']) ?></td>
                <td class="actions">
                    <a href="?edit=<?= $d['id_petugas'] ?>" class="btn btn-secondary btn-sm">Ubah</a>
                    <a href="?hapus=<?= $d['id_petugas'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus petugas ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($daftar)): ?>
            <tr><td colspan="4" class="empty-state">Belum ada data petugas.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>
