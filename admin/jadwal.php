<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Kelola Jadwal Operasional';

if (isset($_GET['hapus'])) {
    $stmt = $pdo->prepare("DELETE FROM jadwal_operasional WHERE id_jadwal = ?");
    $stmt->execute([(int)$_GET['hapus']]);
    set_flash('success', 'Jadwal berhasil dihapus.');
    redirect('jadwal.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)($_POST['id_jadwal'] ?? 0);
    $hari   = $_POST['hari'];
    $buka   = $_POST['jam_buka'];
    $tutup  = $_POST['jam_tutup'];
    $ket    = trim($_POST['keterangan']);

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE jadwal_operasional SET hari=?, jam_buka=?, jam_tutup=?, keterangan=? WHERE id_jadwal=?");
        $stmt->execute([$hari, $buka, $tutup, $ket, $id]);
        set_flash('success', 'Jadwal berhasil diperbarui.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO jadwal_operasional (hari, jam_buka, jam_tutup, keterangan) VALUES (?,?,?,?)");
        $stmt->execute([$hari, $buka, $tutup, $ket]);
        set_flash('success', 'Jadwal baru berhasil ditambahkan.');
    }
    redirect('jadwal.php');
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM jadwal_operasional WHERE id_jadwal = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
}

$daftar = $pdo->query("SELECT * FROM jadwal_operasional ORDER BY FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")->fetchAll();
$hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="panel">
    <div class="panel-head"><h2><?= $edit ? 'Ubah Jadwal' : 'Tambah Jadwal' ?></h2></div>
    <form method="POST">
        <input type="hidden" name="id_jadwal" value="<?= $edit['id_jadwal'] ?? '' ?>">
        <div class="form-row">
            <div class="form-group">
                <label>Hari</label>
                <select name="hari" required>
                    <?php foreach ($hariList as $h): ?>
                    <option value="<?= $h ?>" <?= ($edit['hari'] ?? '') === $h ? 'selected' : '' ?>><?= $h ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Jam Buka</label>
                <input type="time" name="jam_buka" value="<?= clean(substr($edit['jam_buka'] ?? '08:00', 0, 5)) ?>" required>
            </div>
            <div class="form-group">
                <label>Jam Tutup</label>
                <input type="time" name="jam_tutup" value="<?= clean(substr($edit['jam_tutup'] ?? '14:00', 0, 5)) ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="keterangan" value="<?= clean($edit['keterangan'] ?? '') ?>" placeholder="Pelayanan penuh / setengah hari">
        </div>
        <button type="submit" class="btn btn-primary"><?= $edit ? 'Simpan Perubahan' : 'Tambah Jadwal' ?></button>
        <?php if ($edit): ?><a href="jadwal.php" class="btn btn-secondary">Batal</a><?php endif; ?>
    </form>
</div>

<div class="panel">
    <div class="panel-head"><h2>Daftar Jadwal Operasional</h2></div>
    <table>
        <thead><tr><th>Hari</th><th>Jam Buka</th><th>Jam Tutup</th><th>Keterangan</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($daftar as $d): ?>
            <tr>
                <td><?= clean($d['hari']) ?></td>
                <td><?= substr($d['jam_buka'],0,5) ?></td>
                <td><?= substr($d['jam_tutup'],0,5) ?></td>
                <td><?= clean($d['keterangan']) ?></td>
                <td class="actions">
                    <a href="?edit=<?= $d['id_jadwal'] ?>" class="btn btn-secondary btn-sm">Ubah</a>
                    <a href="?hapus=<?= $d['id_jadwal'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus jadwal ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($daftar)): ?>
            <tr><td colspan="5" class="empty-state">Belum ada jadwal.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>
