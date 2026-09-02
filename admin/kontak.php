<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Kelola Kontak';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE profil_puskesmas SET alamat=?, kontak=?, email=? WHERE id_profil=1");
    $stmt->execute([trim($_POST['alamat'] ?? ''), trim($_POST['kontak'] ?? ''), trim($_POST['email'] ?? '')]);
    set_flash('success', 'Informasi kontak berhasil diperbarui.');
    redirect('kontak.php');
}

$profil = $pdo->query("SELECT * FROM profil_puskesmas LIMIT 1")->fetch() ?: [];

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="panel" style="max-width:600px;">
    <div class="panel-head"><h2>Kelola Informasi Kontak</h2></div>
    <form method="POST">
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" rows="3"><?= clean($profil['alamat'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Telepon / WhatsApp</label>
            <input type="text" name="kontak" value="<?= clean($profil['kontak'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= clean($profil['email'] ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>
