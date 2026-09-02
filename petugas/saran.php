<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Saran & Masukan';

$daftar = $pdo->query("SELECT * FROM saran_masukan ORDER BY created_at DESC")->fetchAll();

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="panel">
    <div class="panel-head"><h2>Saran & Masukan dari Pengguna</h2></div>
    <?php foreach ($daftar as $d): ?>
    <div class="card" style="margin-bottom:12px;">
        <div style="display:flex;justify-content:space-between;">
            <strong><?= clean($d['nama_pengirim']) ?></strong>
            <span class="badge <?= $d['status']==='Dibalas'?'badge-success':'badge-warning' ?>"><?= clean($d['status']) ?></span>
        </div>
        <p style="margin-top:8px;"><?= nl2br(clean($d['pesan'])) ?></p>
        <?php if ($d['balasan']): ?>
        <div class="alert alert-info" style="margin-top:8px;margin-bottom:0;"><strong>Balasan Admin:</strong> <?= nl2br(clean($d['balasan'])) ?></div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php if (empty($daftar)): ?>
        <p class="empty-state">Belum ada saran atau masukan.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>
