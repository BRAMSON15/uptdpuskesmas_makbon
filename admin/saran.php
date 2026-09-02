<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Saran & Masukan';

if (isset($_GET['hapus'])) {
    $stmt = $pdo->prepare("DELETE FROM saran_masukan WHERE id_saran = ?");
    $stmt->execute([(int)$_GET['hapus']]);
    set_flash('success', 'Pesan berhasil dihapus.');
    redirect('saran.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = (int)$_POST['id_saran'];
    $balasan = trim($_POST['balasan']);
    $stmt = $pdo->prepare("UPDATE saran_masukan SET balasan = ?, status = 'Dibalas' WHERE id_saran = ?");
    $stmt->execute([$balasan, $id]);
    set_flash('success', 'Balasan berhasil dikirim/disimpan.');
    redirect('saran.php');
}

$daftar = $pdo->query("SELECT * FROM saran_masukan ORDER BY created_at DESC")->fetchAll();

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="panel">
    <div class="panel-head"><h2>Daftar Saran & Masukan</h2></div>
    <?php foreach ($daftar as $d): ?>
    <div class="card" style="margin-bottom:14px;">
        <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px;">
            <div>
                <strong><?= clean($d['nama_pengirim']) ?></strong>
                <?php if ($d['email']): ?> &middot; <span style="color:var(--muted);font-size:0.85rem;"><?= clean($d['email']) ?></span><?php endif; ?>
            </div>
            <span class="badge <?= $d['status']==='Dibalas'?'badge-success':'badge-warning' ?>"><?= clean($d['status']) ?></span>
        </div>
        <p style="margin:10px 0;"><?= nl2br(clean($d['pesan'])) ?></p>
        <?php if ($d['balasan']): ?>
            <div class="alert alert-info" style="margin-bottom:10px;"><strong>Balasan:</strong> <?= nl2br(clean($d['balasan'])) ?></div>
        <?php endif; ?>
        <form method="POST" style="display:flex;gap:8px;flex-wrap:wrap;">
            <input type="hidden" name="id_saran" value="<?= $d['id_saran'] ?>">
            <input type="text" name="balasan" placeholder="Tulis balasan..." value="<?= clean($d['balasan'] ?? '') ?>" style="flex:1;min-width:200px;padding:8px 10px;border:1px solid var(--border);border-radius:6px;">
            <button type="submit" class="btn btn-primary btn-sm">Kirim Balasan</button>
            <a href="?hapus=<?= $d['id_saran'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pesan ini?')">Hapus</a>
        </form>
    </div>
    <?php endforeach; ?>
    <?php if (empty($daftar)): ?>
        <p class="empty-state">Belum ada saran atau masukan.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>
