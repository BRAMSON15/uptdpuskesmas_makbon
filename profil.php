<?php
$base = '';
$asset_path = '';
$page_title = 'Profil';
require_once __DIR__ . '/includes/header.php';
?>

<div class="text-center mb-5">
    <h3 class="title">Profil <?= clean($nama_puskesmas) ?></h3>
    <p class="text-muted">Informasi profil, visi, misi, dan sejarah</p>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Sejarah</h5>
                <p class="card-text text-muted"><?= nl2br(clean($profil['sejarah'] ?? '-')) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Alamat</h5>
                <p class="card-text text-muted"><?= nl2br(clean($profil['alamat'] ?? '-')) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Visi</h5>
                <p class="card-text text-muted"><?= nl2br(clean($profil['visi'] ?? '-')) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Misi</h5>
                <p class="card-text text-muted"><?= nl2br(clean($profil['misi'] ?? '-')) ?></p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
