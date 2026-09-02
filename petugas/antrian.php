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

$filterTanggal = trim($_GET['tanggal'] ?? date('Y-m-d'));
$stmt = $pdo->prepare("SELECT * FROM antrian_online WHERE tanggal_antrian = ? ORDER BY FIELD(status,'Menunggu','Diproses','Selesai','Dibatalkan'), nomor_antrian ASC");
$stmt->execute([$filterTanggal]);
$daftar = $stmt->fetchAll();

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="panel">
    <div class="panel-head">
        <h2>Daftar Antrian Online</h2>
        <form method="GET" style="display:flex;gap:8px;">
            <input type="date" name="tanggal" value="<?= clean($filterTanggal) ?>" style="padding:8px;border:1px solid var(--border);border-radius:6px;">
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        </form>
    </div>
    <table>
        <thead><tr><th>No.</th><th>Pasien</th><th>No. HP</th><th>Layanan</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($daftar as $d): ?>
            <tr>
                <td><?= $d['nomor_antrian'] ?></td>
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
                        <span style="color:var(--muted);font-size:0.85rem;">Tidak ada aksi</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($daftar)): ?>
            <tr><td colspan="6" class="empty-state">Belum ada antrian pada tanggal ini.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>
