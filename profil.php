<?php
require_once __DIR__ . '/config/database.php';
$page_title = "Profil";
$use_card_wrapper = false;
$totalLayanan = $pdo->query("SELECT COUNT(*) c FROM layanan WHERE status='Aktif'")->fetch()['c'];
$totalPetugas = $pdo->query("SELECT COUNT(*) c FROM petugas")->fetch()['c'];
$totalAntrianHariIni = $pdo->query("SELECT COUNT(*) c FROM antrian_online WHERE tanggal_antrian = CURDATE()")->fetch()['c'];
$totalAntrianSemua   = $pdo->query("SELECT COUNT(*) c FROM antrian_online")->fetch()['c'];
$totalPasienTerlayani = $pdo->query("SELECT COUNT(DISTINCT nama_pasien) c FROM antrian_online")->fetch()['c'];

require_once __DIR__ . "/includes/header.php";
?>
<!--====== ABOUT PART START ======-->
    <section id="about" class="about_area pt-130 pb-130">
        <div class="about_wrapper">
            <div class="about_image bg_cover" style="background-image: url(https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&q=80)"></div>
            <div class="container">
                <div class="row justify-content-end">
                    <div class="col-lg-6">
                        <div class="about_content">
                            <div class="section_title">
                                <h3 class="title">Profil <br> <?= clean($nama_puskesmas) ?> dalam <span>Angka</span></h3>
                                <p><?= nl2br(clean($profil['visi'] ?? '-')) ?></p>
                            </div>
                            <a href="profil.php" class="main-btn">Baca Profil Lengkap</a>
                        </div>
                        <div class="about_counter d-flex flex-wrap">
                            <div class="single_counter counter_1 d-flex justify-content-center align-items-center wow fadeInUpBig" data-wow-duration="1.3s" data-wow-delay="0.2s">
                                <div class="counter_wrapper">
                                    <span class="counter"><?= (int)$totalLayanan ?></span>
                                    <p>Layanan Aktif</p>
                                </div>
                            </div>
                            <div class="single_counter counter_2 d-flex justify-content-center align-items-center wow fadeInUpBig" data-wow-duration="1.3s" data-wow-delay="0.5s">
                                <div class="counter_wrapper">
                                    <span class="counter"><?= (int)$totalPetugas ?></span>
                                    <p>Petugas Pelayanan</p>
                                </div>
                            </div>
                            <div class="single_counter counter_2 d-flex justify-content-center align-items-center wow fadeInUpBig" data-wow-duration="1.3s" data-wow-delay="0.8s">
                                <div class="counter_wrapper">
                                    <span class="counter"><?= (int)($totalAntrianHariIni > 0 ? $totalAntrianHariIni : $totalAntrianSemua) ?></span>
                                    <p><?= $totalAntrianHariIni > 0 ? 'Antrian Hari Ini' : 'Total Antrian' ?></p>
                                </div>
                            </div>
                            <div class="single_counter counter_1 d-flex justify-content-center align-items-center wow fadeInUpBig" data-wow-duration="1.3s" data-wow-delay="1.1s">
                                <div class="counter_wrapper">
                                    <span class="counter"><?= (int)$totalPasienTerlayani ?></span>
                                    <p>Pasien Terlayani</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== ABOUT PART ENDS ======-->
<div class="container mt-5 pt-5 pb-5"><div class="text-center mb-5">
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

</div>
<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . "/includes/footer.php"; ?>

