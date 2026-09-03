<?php
require_once __DIR__ . '/config/database.php';
$page_title = 'Layanan';
$use_card_wrapper = false;
$layananList = $pdo->query("SELECT * FROM layanan WHERE status = 'Aktif' ORDER BY jenis, nama_layanan")->fetchAll();
$iconMap = ['BPJS' => 'lni-shield', 'Non BPJS' => 'lni-heart-monitor'];
require_once __DIR__ . '/includes/header.php';
?>
<!--====== LAYANAN (dulu "destination/packages") START ======-->
    <section id="layanan" class="destination_area pt-130 pb-30">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section_title text-center pb-25">
                        <h3 class="title">Layanan Kami</h3>
                        <p>Layanan BPJS dan Non BPJS yang tersedia di <?= clean($nama_puskesmas) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="row justify-content-center pb-40">
                <div class="col-lg-4 col-md-6 col-sm-8 text-center">
                    <select id="layananFilter" class="form-control form-control-lg" style="border-radius: 30px; text-align: center; border: 2px solid #0d7c66; color: #0d7c66; font-weight: 600; cursor: pointer; appearance: none; -webkit-appearance: none; background: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%230d7c66\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"6 9 12 15 18 9\"></polyline></svg>') no-repeat right 15px center; background-color: #fff;">
                        <option value="Semua">Tampilkan Semua Layanan</option>
                        <option value="BPJS">Hanya Layanan BPJS</option>
                        <option value="Non BPJS">Hanya Layanan Non BPJS</option>
                    </select>
                </div>
            </div>
            
            <div class="row" id="layananContainer">
                <?php foreach ($layananList as $l): ?>
                <div class="col-lg-4 col-sm-6 layanan-item" data-jenis="<?= clean($l['jenis']) ?>">
                    <div class="single_service mt-30 text-center wow fadeInUpBig" data-wow-duration="1.3s" data-wow-delay="0.2s">
                        <div class="services_icon">
                            <i class="lni <?= $iconMap[$l['jenis']] ?? 'lni-hospital' ?>"></i>
                        </div>
                        <div class="services_content">
                            <span class="tag-mini <?= $l['jenis'] === 'BPJS' ? 'tag-bpjs' : 'tag-nonbpjs' ?>"><?= clean($l['jenis']) ?></span>
                            <h4 class="title"><?= clean($l['nama_layanan']) ?></h4>
                            <p><?= clean($l['deskripsi']) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($layananList)): ?>
                <div class="col-lg-12"><p class="text-center">Belum ada data layanan.</p></div>
                <?php endif; ?>
            </div>
            <div class="text-center mt-50">
                <a href="layanan.php" class="main-btn">Lihat Semua Layanan</a>
            </div>
        </div>
    </section>
    <!--====== LAYANAN ENDS ======-->
<?php require_once __DIR__ . '/includes/footer.php'; ?>
