<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$page_title = 'Beranda';
$use_card_wrapper = false;

// Variables needed for the hero banner text
$stmt = $pdo->query("SELECT * FROM profil_puskesmas LIMIT 1");
$profil = $stmt->fetch() ?: [];
$nama_puskesmas = $profil['nama_puskesmas'] ?? 'Puskesmas Makbon';

require_once __DIR__ . '/includes/header.php';
?>

    <!--====== AKSES CEPAT (dulu "blog") START ======-->
    <section class="blog_area pt-120 pb-90">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section_title text-center pb-25">
                        <h3 class="title">Akses Cepat</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mt-30">
                    <div class="quick_access_card">
                        <i class="lni lni-list"></i>
                        <h4>Daftar Antrian Online</h4>
                        <p>Isi data dan dapatkan nomor antrian tanpa harus mengantre di lokasi.</p>
                        <a href="antrian.php" class="main-btn main-btn-2">Daftar Sekarang</a>
                    </div>
                </div>
                <div class="col-lg-4 mt-30">
                    <div class="quick_access_card">
                        <i class="lni lni-map-marker"></i>
                        <h4>Tracking Antrian</h4>
                        <p>Pantau status antrian Anda secara realtime menggunakan ID antrian.</p>
                        <a href="tracking.php" class="main-btn main-btn-2">Cek Status</a>
                    </div>
                </div>
                <div class="col-lg-4 mt-30">
                    <div class="quick_access_card">
                        <i class="lni lni-comments"></i>
                        <h4>Saran & Masukan</h4>
                        <p>Sampaikan kritik dan saran Anda untuk peningkatan pelayanan kami.</p>
                        <a href="saran.php" class="main-btn main-btn-2">Kirim Masukan</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== AKSES CEPAT ENDS ======-->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
