<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Jadwal Operasional';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id_jadwal'];
    $stmt = $pdo->prepare("UPDATE jadwal_operasional SET keterangan = ? WHERE id_jadwal = ?");
    $stmt->execute([trim($_POST['keterangan']), $id]);
    set_flash('success', 'Keterangan jadwal berhasil diperbarui.');
    redirect('jadwal.php');
}

$daftar = $pdo->query("SELECT * FROM jadwal_operasional ORDER BY FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")->fetchAll();

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="panel">
    <div class="panel-head"><h2>Jadwal Operasional Puskesmas</h2></div>
    <table>
        <thead><tr><th>Hari</th><th>Jam Buka</th><th>Jam Tutup</th><th>Keterangan (bisa diubah)</th></tr></thead>
        <tbody>
        <?php foreach ($daftar as $d): ?>
            <tr>
                <td><?= clean($d['hari']) ?></td>
                <td><?= substr($d['jam_buka'],0,5) ?></td>
                <td><?= substr($d['jam_tutup'],0,5) ?></td>
                <td>
                    <form method="POST" style="display:flex;gap:6px;">
                        <input type="hidden" name="id_jadwal" value="<?= $d['id_jadwal'] ?>">
                        <input type="text" name="keterangan" value="<?= clean($d['keterangan']) ?>" style="flex:1;padding:6px;border:1px solid var(--border);border-radius:6px;">
                        <button type="submit" class="btn btn-secondary btn-sm">Simpan</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($daftar)): ?>
            <tr><td colspan="4" class="empty-state">Belum ada jadwal.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>
