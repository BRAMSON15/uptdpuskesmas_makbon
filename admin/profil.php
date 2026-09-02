<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Kelola Profil Puskesmas';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_puskesmas = trim($_POST['nama_puskesmas'] ?? '');

    if ($nama_puskesmas === '') {
        set_flash('error', 'Nama Puskesmas wajib diisi. Perubahan tidak disimpan.');
        redirect('profil.php');
    }

    // Ambil data profil lama agar qr_bpjs tidak terhapus jika tidak upload gambar baru
    $existing_profil = $pdo->query("SELECT qr_bpjs FROM profil_puskesmas LIMIT 1")->fetch();
    $qr_bpjs = $existing_profil['qr_bpjs'] ?? null;

    // Upload QR Code if provided
    if (isset($_FILES['qr_bpjs']) && $_FILES['qr_bpjs']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../storage/app/public/qr/';
        // Hilangkan spasi dan karakter khusus pada nama file
        $safeName = preg_replace('/[^a-zA-Z0-9.-]/', '_', basename($_FILES['qr_bpjs']['name']));
        $fileName = 'bpjs_' . time() . '_' . $safeName;
        $targetFile = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['qr_bpjs']['tmp_name'], $targetFile)) {
            // Hapus file lama jika ada
            if ($qr_bpjs && file_exists($uploadDir . $qr_bpjs)) {
                @unlink($uploadDir . $qr_bpjs);
            }
            $qr_bpjs = $fileName;
        } else {
            set_flash('error', 'Gagal mengunggah QR Code.');
        }
    }

    $stmt = $pdo->prepare(
        "UPDATE profil_puskesmas SET nama_puskesmas=?, deskripsi_beranda=?, visi=?, misi=?, sejarah=?, alamat=?, kontak=?, email=?, jam_operasional=?, qr_bpjs=? WHERE id_profil=1"
    );
    $stmt->execute([
        $nama_puskesmas,
        trim($_POST['deskripsi_beranda'] ?? ''),
        trim($_POST['visi'] ?? ''),
        trim($_POST['misi'] ?? ''),
        trim($_POST['sejarah'] ?? ''),
        trim($_POST['alamat'] ?? ''),
        trim($_POST['kontak'] ?? ''),
        trim($_POST['email'] ?? ''),
        trim($_POST['jam_operasional'] ?? ''),
        $qr_bpjs
    ]);
    set_flash('success', 'Profil puskesmas berhasil diperbarui.');
    redirect('profil.php');
}

$profil = $pdo->query("SELECT * FROM profil_puskesmas LIMIT 1")->fetch() ?: [];

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="panel">
    <div class="panel-head"><h2>Kelola Beranda, Profil, Visi & Misi, Sejarah</h2></div>
    <form method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label>Nama Puskesmas</label>
            <input type="text" name="nama_puskesmas" value="<?= clean($profil['nama_puskesmas'] ?? '') ?>" required placeholder="Nama lengkap puskesmas">
        </div>

        <div class="form-group">
            <label>Deskripsi Beranda</label>
            <textarea name="deskripsi_beranda" rows="3" placeholder="Kalimat singkat yang tampil di halaman utama..."><?= clean($profil['deskripsi_beranda'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Visi</label>
                <textarea name="visi" rows="5" placeholder="Visi puskesmas..."><?= clean($profil['visi'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label>Misi</label>
                <textarea name="misi" rows="5" placeholder="Misi puskesmas (pisahkan tiap poin dengan baris baru)..."><?= clean($profil['misi'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="form-group">
            <label>Sejarah</label>
            <textarea name="sejarah" rows="5" placeholder="Sejarah singkat puskesmas..."><?= clean($profil['sejarah'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" rows="2" placeholder="Alamat lengkap puskesmas..."><?= clean($profil['alamat'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Kontak (Telp / WA)</label>
                <input type="text" name="kontak" value="<?= clean($profil['kontak'] ?? '') ?>" placeholder="Contoh: 081234567890">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= clean($profil['email'] ?? '') ?>" placeholder="email@puskesmas.com">
            </div>
        </div>

        <div class="form-group">
            <label>Jam Operasional (Ringkas)</label>
            <input type="text" name="jam_operasional" value="<?= clean($profil['jam_operasional'] ?? '') ?>" placeholder="Contoh: Senin - Sabtu, 08.00 - 14.00 WIT">
        </div>

        <div class="form-group">
            <label>QR Code BPJS (Opsional)</label>
            <?php if (!empty($profil['qr_bpjs'])): ?>
                <div style="margin-bottom: 10px;">
                    <img src="<?= base_url('storage/app/public/qr/' . $profil['qr_bpjs']) ?>" alt="QR Code BPJS" style="max-height: 150px; border: 1px solid #dce4e1; border-radius: 8px; padding: 4px;">
                </div>
            <?php endif; ?>
            <input type="file" name="qr_bpjs" accept="image/*">
            <small style="color: #888; font-size:0.83rem;">Biarkan kosong jika tidak ingin mengubah QR Code. Format: JPG, PNG.</small>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top:10px; padding: 12px 32px;">
            <i class="lni lni-save"></i> Simpan Perubahan
        </button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>
