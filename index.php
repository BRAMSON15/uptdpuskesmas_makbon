<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$profil = $pdo->query("SELECT * FROM profil_puskesmas LIMIT 1")->fetch() ?: [];
$nama_puskesmas = $profil['nama_puskesmas'] ?? 'Puskesmas Makbon';

$layananList = $pdo->query("SELECT * FROM layanan WHERE status = 'Aktif' ORDER BY jenis, nama_layanan")->fetchAll();
$jadwalList  = $pdo->query("SELECT * FROM jadwal_operasional ORDER BY FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")->fetchAll();

$totalLayanan = $pdo->query("SELECT COUNT(*) c FROM layanan WHERE status='Aktif'")->fetch()['c'];
$totalPetugas = $pdo->query("SELECT COUNT(*) c FROM petugas")->fetch()['c'];
$totalAntrianHariIni = $pdo->query("SELECT COUNT(*) c FROM antrian_online WHERE tanggal_antrian = CURDATE()")->fetch()['c'];
$totalAntrianSemua   = $pdo->query("SELECT COUNT(*) c FROM antrian_online")->fetch()['c'];
$totalPasienTerlayani = $pdo->query("SELECT COUNT(DISTINCT nama_pasien) c FROM antrian_online")->fetch()['c'];

$strukturList = $pdo->query("SELECT * FROM struktur_organisasi ORDER BY urutan ASC, id ASC")->fetchAll();

function buildFrontTree($elements, $parentId = null) {
    $html = '';
    $children = array_filter($elements, function($e) use ($parentId) {
        return $e['parent_id'] == $parentId;
    });
    
    if (count($children) > 0) {
        $html .= '<ul>';
        foreach ($children as $child) {
            $html .= '<li>';
            $html .= '<div class="node-box">';
            $html .= '<span class="jabatan">' . clean($child['jabatan']) . '</span>';
            $html .= '<span class="nama">' . clean($child['nama']) . '</span>';
            $html .= '</div>';
            $html .= buildFrontTree($elements, $child['id']);
            $html .= '</li>';
        }
        $html .= '</ul>';
    }
    return $html;
}

$iconMap = ['BPJS' => 'lni-shield', 'Non BPJS' => 'lni-heart-monitor'];
?>
<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <title>Beranda - <?= clean($nama_puskesmas) ?></title>
    <meta name="description" content="Sistem Informasi Profil, Layanan, dan Antrian Online <?= clean($nama_puskesmas) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="assets/traveland/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="assets/traveland/css/animate.css">
    <link rel="stylesheet" href="assets/traveland/css/nice-select.css">
    <link rel="stylesheet" href="assets/traveland/css/LineIcons.2.0.css">
    <link rel="stylesheet" href="assets/traveland/css/bootstrap.4.5.2.min.css">
    <link rel="stylesheet" href="assets/traveland/css/default.css">
    <link rel="stylesheet" href="assets/traveland/css/style.css">
    <style>
        /* ==== Penyesuaian kecil khusus Puskesmas Makbon (di luar template asli) ==== */
        .navbar-brand-text { font-size: 1.3rem; font-weight: 700; color: #fff; letter-spacing: .02em; }
        .header_navbar .navbar-brand-text { color: #17332c; }
        @media (min-width: 992px) {
            .navbar-nav .nav-item a { padding: 10px 14px !important; font-size: 0.95rem !important; }
            .navbar-brand-text { font-size: 1.15rem; }
            .navbar-brand-text img { height: 40px !important; margin-right: 8px !important; }
        }
        @media (max-width: 767px) {
            .navbar-brand-text { font-size: 1rem; }
            .navbar-brand-text img { height: 35px !important; margin-right: 8px !important; }
            .navbar-brand-text span { max-width: 200px; white-space: normal; line-height: 1.2; display: inline-block; }
            .navbar-toggler { padding: 4px 8px; }
        }
        .tag-mini { display:inline-block; font-size:.72rem; font-weight:700; padding:3px 12px; border-radius:12px; margin-bottom:12px; }
        .tag-bpjs { background:#e4f4ec; color:#1f8a55; }
        .tag-nonbpjs { background:#fdf1de; color:#a5680f; }
        .quick_access_card { background:#fff; border-radius:10px; padding:34px 26px; text-align:center; box-shadow:0 10px 30px rgba(0,0,0,.06); height:100%; }
        .quick_access_card i { font-size:42px; color:#0d7c66; margin-bottom:16px; display:inline-block; }
        .quick_access_card h4 { margin-bottom:10px; }
        .jadwal_table th { background:#f0f5f3; }
        .top_info_bar { background:#0d3d33; color:#cfe9df; font-size:.82rem; padding:8px 0; }
        .top_info_bar a { color:#cfe9df; }
        .top_info_bar .lni { margin-right:6px; }
        section#jadwal, section#kontak { background:#f7faf9; }
        
        /* Struktur Organisasi Tree CSS */
        .org-chart-container { overflow-x: auto; padding: 20px 0; text-align: center; }
        .org-chart { display: inline-block; white-space: nowrap; }
        .org-chart ul { padding-top: 20px; position: relative; transition: all 0.5s; display: flex; justify-content: center; padding-left: 0; }
        .org-chart li { float: left; text-align: center; list-style-type: none; position: relative; padding: 20px 5px 0 5px; transition: all 0.5s; }
        .org-chart li::before, .org-chart li::after { content: ''; position: absolute; top: 0; right: 50%; border-top: 2px solid #0d7c66; width: 50%; height: 20px; }
        .org-chart li::after { right: auto; left: 50%; border-left: 2px solid #0d7c66; }
        .org-chart li:only-child::after, .org-chart li:only-child::before { display: none; }
        .org-chart li:only-child { padding-top: 0; }
        .org-chart li:first-child::before, .org-chart li:last-child::after { border: 0 none; }
        .org-chart li:last-child::before { border-right: 2px solid #0d7c66; border-radius: 0 5px 0 0; }
        .org-chart li:first-child::after { border-radius: 5px 0 0 0; }
        .org-chart ul ul::before { content: ''; position: absolute; top: 0; left: 50%; border-left: 2px solid #0d7c66; width: 0; height: 20px; }
        .org-chart li .node-box { border: 2px solid #0d7c66; padding: 15px 20px; text-decoration: none; color: #333; display: inline-block; border-radius: 8px; transition: all 0.5s; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.1); min-width: 150px; white-space: nowrap; }
        .org-chart li .node-box .jabatan { font-weight: 700; display: block; color: #0d3d33; margin-bottom: 5px; font-size: 14px; }
        .org-chart li .node-box .nama { font-size: 13px; color: #555; }
        .org-chart li .node-box:hover { background: #e4f4ec; transform: translateY(-3px); box-shadow: 0 6px 12px rgba(0,0,0,0.15); }
    </style>
</head>
<body>
    <!--====== HEADER PART START ======-->
    <section class="header_area">
        <div class="header_navbar">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <nav class="navbar navbar-expand-lg">
                            <a class="navbar-brand navbar-brand-text d-flex align-items-center" href="index.php">
                                <img src="assets/img/logo.png?v=<?= time() ?>" alt="Logo" style="height: 50px; margin-right: 10px;">
                                <span><?= clean($nama_puskesmas) ?></span>
                            </a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                                <ul id="nav" class="navbar-nav ml-auto">
                                    <li class="nav-item active"><a class="page-scroll" href="#home">Beranda</a></li>
                                    <li class="nav-item"><a class="page-scroll" href="#about">Profil</a></li>
                                    <li class="nav-item"><a class="page-scroll" href="#struktur-organisasi">Struktur</a></li>
                                    <li class="nav-item"><a class="page-scroll" href="#layanan">Layanan</a></li>
                                    <li class="nav-item"><a class="page-scroll" href="#alur">Alur</a></li>
                                    <li class="nav-item"><a class="page-scroll" href="#jadwal">Jadwal</a></li>
                                    <li class="nav-item"><a class="page-scroll" href="#kontak">Kontak</a></li>
                                    <li class="nav-item"><a href="tracking.php">Cek Antrian</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div id="home" class="header_slider">
            <div class="single_slider bg_cover d-flex align-items-center" style="background-image: url(assets/img/puskesmas01.png)">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7 col-md-9">
                            <div class="slider_content">
                                <h2 class="slider_title wow fadeInLeftBig" data-wow-duration="1.3s" data-wow-delay="0.2s">Selamat Datang di<span> <?= clean($nama_puskesmas) ?></span></h2>
                                <p class="wow fadeInLeftBig" data-wow-duration="1.3s" data-wow-delay="0.5s"><?= clean($profil['deskripsi_beranda'] ?? 'Melayani kesehatan masyarakat dengan cepat, ramah, dan terpercaya.') ?></p>
                                <a href="antrian.php" class="main-btn wow fadeInLeftBig" data-wow-duration="1.3s" data-wow-delay="0.8s">Daftar Antrian Online</a>
                                <a href="tracking.php" class="main-btn main-btn-2 wow fadeInLeftBig" data-wow-duration="1.3s" data-wow-delay="1.0s" style="margin-left:12px;">Cek Status Antrian</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== HEADER PART ENDS ======-->

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

    <!--====== STRUKTUR ORGANISASI START ======-->
    <section id="struktur-organisasi" class="pt-130 pb-30" style="background:#f7faf9;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section_title text-center pb-25">
                        <h3 class="title">Struktur Organisasi</h3>
                        <p>Bagan struktur organisasi di <?= clean($nama_puskesmas) ?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="org-chart-container wow fadeInUpBig" data-wow-duration="1.3s" data-wow-delay="0.2s">
                        <div class="org-chart">
                            <?php 
                                $treeHtml = buildFrontTree($strukturList);
                                if ($treeHtml) {
                                    echo $treeHtml;
                                } else {
                                    echo '<p class="text-center">Belum ada data struktur organisasi.</p>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== STRUKTUR ORGANISASI ENDS ======-->

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

    <!--====== CARA KERJA (dulu "services") START ======-->
    <section id="alur" class="services_area pt-120 pb-90">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section_title text-center pb-25">
                        <h3 class="title">Cara Kerja Antrian Online</h3>
                        <p>Tiga langkah mudah tanpa harus antre lama di lokasi</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-3 col-sm-6">
                    <div class="single_service mt-30 text-center wow fadeInUpBig" data-wow-duration="1.3s" data-wow-delay="0.2s">
                        <div class="services_icon"><i class="lni lni-list"></i></div>
                        <div class="services_content">
                            <h4 class="title">1. Pilih Layanan</h4>
                            <p>Pilih jenis layanan (BPJS/Non BPJS) dan isi data diri Anda secara online.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single_service mt-30 text-center wow fadeInUpBig" data-wow-duration="1.3s" data-wow-delay="0.4s">
                        <div class="services_icon"><i class="lni lni-calendar"></i></div>
                        <div class="services_content">
                            <h4 class="title">2. Pilih Jadwal</h4>
                            <p>Tentukan tanggal kunjungan sesuai jadwal operasional Puskesmas.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single_service mt-30 text-center wow fadeInUpBig" data-wow-duration="1.3s" data-wow-delay="0.6s">
                        <div class="services_icon"><i class="lni lni-timer"></i></div>
                        <div class="services_content">
                            <h4 class="title">3. Nomor Antrian</h4>
                            <p>Dapatkan nomor antrian dan pantau statusnya secara realtime.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== CARA KERJA ENDS ======-->

    <!--====== JADWAL OPERASIONAL (dulu "team") START ======-->
    <section id="jadwal" class="team_area pt-120 pb-130">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section_title text-center pb-25">
                        <h3 class="title">Jadwal Operasional</h3>
                        <p>Jam pelayanan <?= clean($nama_puskesmas) ?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <table class="table table-bordered bg-white jadwal_table">
                        <thead>
                            <tr><th>Hari</th><th>Jam Buka</th><th>Jam Tutup</th><th>Keterangan</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jadwalList as $j): ?>
                            <tr>
                                <td><?= clean($j['hari']) ?></td>
                                <td><?= substr($j['jam_buka'], 0, 5) ?></td>
                                <td><?= substr($j['jam_tutup'], 0, 5) ?></td>
                                <td><?= clean($j['keterangan']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($jadwalList)): ?>
                            <tr><td colspan="4" class="text-center">Belum ada jadwal operasional.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!--====== JADWAL OPERASIONAL ENDS ======-->

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

    <!--====== KONTAK (dulu "contact") START ======-->
    <section id="kontak" class="contact_area pt-120 pb-130">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section_title text-center pb-25">
                        <h3 class="title">Hubungi Kami</h3>
                        <p>Sampaikan pertanyaan atau masukan Anda kepada kami</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="quick_access_card mb-30">
                        <i class="lni lni-map-marker"></i>
                        <h4>Alamat</h4>
                        <p><?= nl2br(clean($profil['alamat'] ?? '-')) ?></p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="quick_access_card mb-30">
                        <i class="lni lni-phone"></i>
                        <h4>Telepon / WhatsApp</h4>
                        <p><?= clean($profil['kontak'] ?? '-') ?></p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="quick_access_card mb-30">
                        <i class="lni lni-envelope"></i>
                        <h4>Email</h4>
                        <p><?= clean($profil['email'] ?? '-') ?></p>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($profil['qr_bpjs'])): ?>
            <div class="row justify-content-center mt-20 mb-40">
                <div class="col-lg-5 text-center">
                    <div class="quick_access_card" style="padding: 30px;">
                        <h4 class="mb-3"><i class="lni lni-mobile"></i> Scan QR Code BPJS</h4>
                        <img src="assets/storage/qr/<?= clean($profil['qr_bpjs']) ?>" alt="QR Code BPJS" class="img-fluid" style="max-height: 220px; border: 1px solid #eaeff2; border-radius: 12px; padding: 10px; background: #fff;" onerror="this.src='assets/img/placeholder.png'; this.title='Gambar tidak ditemukan';">
                        <p class="mt-3 text-muted" style="font-size: 0.9rem;">Gunakan Aplikasi Mobile JKN untuk melakukan pemindaian (scan) QR Code saat berkunjung ke Puskesmas.</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="contact_form">
                <?php show_flash(); ?>
                <form action="saran_proses.php" method="POST" id="contact-form">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="single_form">
                                <input type="text" name="nama_pengirim" id="nama_pengirim" placeholder="Nama" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="single_form">
                                <input type="email" name="email" id="email" placeholder="Email (opsional)">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="single_form">
                                <textarea name="pesan" id="pesan" placeholder="Pesan / Saran / Masukan" required></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="single_form">
                                <button type="submit" class="main-btn">Kirim Pesan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!--====== KONTAK ENDS ======-->

    <!--====== FOOTER PART START ======-->
    <section id="footer" class="footer_area">
        <div class="footer_widget pt-80 pb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 order-md-1 order-lg-1">
                        <div class="footer_about mt-50">
                            <a href="index.php" class="navbar-brand-text d-flex align-items-center" style="display:inline-flex;margin-bottom:14px;">
                                <img src="assets/img/logo.png?v=<?= time() ?>" alt="Logo" style="height: 50px; margin-right: 10px;">
                                <span><?= clean($nama_puskesmas) ?></span>
                            </a>
                            <p><?= clean($profil['deskripsi_beranda'] ?? '') ?></p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 order-md-3 order-lg-2">
                        <div class="footer_link_wrapper d-flex flex-wrap">
                            <div class="footer_link mt-45">
                                <h4 class="footer_title">Tautan Cepat</h4>
                                <ul class="link">
                                    <li><a href="index.php">Beranda</a></li>
                                    <li><a href="profil.php">Profil</a></li>
                                    <li><a href="layanan.php">Layanan</a></li>
                                    <li><a href="jadwal.php">Jadwal</a></li>
                                    <li><a href="antrian.php">Antrian Online</a></li>
                                    <li><a href="tracking.php">Tracking Antrian</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 order-md-2 order-lg-3">
                        <div class="footer_subscribe mt-45">
                            <h4 class="footer_title">Lainnya</h4>
                            <ul class="link">
                                <li><a href="saran.php">Saran & Masukan</a></li>
                                <li><a href="kontak.php">Kontak</a></li>
                                <li><a href="admin/login.php">Login Admin</a></li>
                                <li><a href="petugas/login.php">Login Petugas</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-center mt-60">&copy; <?= date('Y') ?> <?= clean($nama_puskesmas) ?>. Sistem Informasi Profil, Layanan & Antrian Online.</p>
            <p class="text-center">Template oleh <a href="https://uideck.com/" target="_blank">UIdeck</a>, didistribusikan oleh <a href="https://themewagon.com" target="_blank">ThemeWagon</a>.</p>
        </div>
    </section>
    <!--====== FOOTER PART ENDS ======-->

    <a href="#" class="back-to-top"><i class="lni lni-chevron-up"></i></a>

    <script src="assets/traveland/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="assets/traveland/js/vendor/modernizr-3.7.1.min.js"></script>
    <script src="assets/traveland/js/popper.min.js"></script>
    <script src="assets/traveland/js/bootstrap.4.5.2.min.js"></script>
    <script src="assets/traveland/js/jquery.easing.min.js"></script>
    <script src="assets/traveland/js/scrolling-nav.js"></script>
    <script src="assets/traveland/js/waypoints.min.js"></script>
    <script src="assets/traveland/js/jquery.counterup.min.js"></script>
    <script src="assets/traveland/js/jquery.nice-select.min.js"></script>
    <script src="assets/traveland/js/wow.min.js"></script>
    <script src="assets/traveland/js/main.js"></script>
    
    <script>
        $(document).ready(function() {
            // Karena template menggunakan nice-select, event change harus dideteksi lewat jQuery
            $('#layananFilter').on('change', function() {
                var selected = $(this).val();
                var hasVisible = false;
                
                $('.layanan-item').each(function() {
                    if (selected === 'Semua' || $(this).data('jenis') === selected) {
                        $(this).fadeIn(300);
                        hasVisible = true;
                    } else {
                        $(this).hide();
                    }
                });
                
                // Handle empty state
                var emptyMsg = $('#emptyFilterMsg');
                if (!hasVisible) {
                    if (emptyMsg.length === 0) {
                        $('#layananContainer').append('<div id="emptyFilterMsg" class="col-lg-12 text-center mt-30"><p>Tidak ada layanan yang sesuai dengan filter.</p></div>');
                    } else {
                        emptyMsg.show();
                    }
                } else {
                    if (emptyMsg.length > 0) {
                        emptyMsg.hide();
                    }
                }
            });
        });
    </script>
</body>
</html>
