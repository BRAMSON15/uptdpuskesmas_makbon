<?php
$base = '';
$asset_path = '';
$page_title = 'Kontak';
require_once __DIR__ . '/includes/header.php';
?>

<div class="text-center mb-5">
    <h3 class="title">Hubungi Kami</h3>
    <p class="text-muted">Informasi kontak <?= clean($nama_puskesmas) ?></p>
</div>

<div class="row text-center">
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0 py-4">
            <div class="card-body">
                <i class="lni lni-map-marker display-4 text-success mb-3"></i>
                <h5 class="card-title">Alamat</h5>
                <p class="card-text text-muted"><?= nl2br(clean($profil['alamat'] ?? '-')) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0 py-4">
            <div class="card-body">
                <i class="lni lni-phone display-4 text-success mb-3"></i>
                <h5 class="card-title">Telepon / WhatsApp</h5>
                <p class="card-text text-muted"><?= clean($profil['kontak'] ?? '-') ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0 py-4">
            <div class="card-body">
                <i class="lni lni-envelope display-4 text-success mb-3"></i>
                <h5 class="card-title">Email</h5>
                <p class="card-text text-muted"><?= clean($profil['email'] ?? '-') ?></p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
