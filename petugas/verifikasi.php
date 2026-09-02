<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Verifikasi & Validasi Antrian';

$hasil = null;
$cari = trim($_GET['cari'] ?? '');

if ($cari !== '') {
    $stmt = $pdo->prepare("SELECT * FROM antrian_online WHERE id_antrian = ? OR nomor_antrian = ?");
    $stmt->execute([$cari, $cari]);
    $hasil = $stmt->fetch();
}

if (isset($_GET['konfirmasi'])) {
    $id = (int)$_GET['konfirmasi'];
    $stmt = $pdo->prepare("UPDATE antrian_online SET status = 'Diproses', id_petugas = ? WHERE id_antrian = ?");
    $stmt->execute([$_SESSION['petugas_id'], $id]);
    $stmt = $pdo->prepare("INSERT INTO tracking_antrian (id_antrian, status, keterangan) VALUES (?, 'Diproses', 'Data pasien telah diverifikasi dan divalidasi petugas.')");
    $stmt->execute([$id]);
    set_flash('success', 'Antrian berhasil diverifikasi dan dikonfirmasi.');
    redirect('verifikasi.php?cari=' . $cari);
}

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="panel" style="max-width:600px;">
    <div class="panel-head"><h2>Cek Data Pasien / Nomor Antrian</h2></div>
    <form method="GET" style="display:flex;gap:8px;margin-bottom:10px;">
        <input type="text" name="cari" value="<?= clean($cari) ?>" placeholder="Masukkan ID Antrian atau Nomor Antrian" style="flex:1;padding:10px;border:1px solid var(--border);border-radius:6px;">
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>

    <?php if ($cari !== '' && !$hasil): ?>
        <div class="alert alert-danger">Data antrian tidak ditemukan.</div>
    <?php elseif ($hasil): ?>
        <table>
            <tr><td><strong>ID Antrian</strong></td><td>#<?= $hasil['id_antrian'] ?></td></tr>
            <tr><td><strong>Nama Pasien</strong></td><td><?= clean($hasil['nama_pasien']) ?></td></tr>
            <tr><td><strong>No. HP</strong></td><td><?= clean($hasil['no_hp']) ?></td></tr>
            <tr><td><strong>Layanan</strong></td><td><?= clean($hasil['layanan']) ?></td></tr>
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>
                    <?= tanggal_indo($hasil['tanggal_antrian']) ?>
                    <?php
                        $tgl_antrian = strtotime($hasil['tanggal_antrian']);
                        $tgl_sekarang = strtotime(date('Y-m-d'));
                        $selisih = ($tgl_antrian - $tgl_sekarang) / 86400; // 60 * 60 * 24
                        
                        $hari_indo = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
                        $nama_hari = $hari_indo[date('l', $tgl_antrian)];

                        if ($selisih == 0) {
                            echo " <span style='color: #28a745; font-weight: bold;'>(Hari Ini)</span>";
                        } elseif ($selisih == 1) {
                            echo " <span style='color: #17a2b8; font-weight: bold;'>(Besok)</span>";
                        } elseif ($selisih == 2) {
                            echo " <span style='color: #ffc107; font-weight: bold;'>(Lusa)</span>";
                        } elseif ($selisih < 0) {
                            echo " <span style='color: #dc3545; font-weight: bold;'>(Sudah Lewat)</span>";
                        } else {
                            echo " <span style='color: var(--primary); font-weight: bold;'>(Hari " . $nama_hari . ")</span>";
                        }
                    ?>
                </td>
            </tr>
            <tr><td><strong>Nomor Antrian</strong></td><td><?= format_nomor_antrian($hasil['nomor_antrian'], $hasil['layanan']) ?></td></tr>
            <tr><td><strong>Status</strong></td><td><?= badge_status($hasil['status']) ?></td></tr>
        </table>
        <?php if ($hasil['status'] === 'Menunggu'): ?>
        <a href="?konfirmasi=<?= $hasil['id_antrian'] ?>&cari=<?= clean($cari) ?>" class="btn btn-primary" style="margin-top:14px;">Verifikasi & Konfirmasi Antrian</a>
        <?php else: ?>
        <p style="margin-top:14px;color:var(--muted);">Antrian ini sudah berstatus "<?= clean($hasil['status']) ?>".</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>

