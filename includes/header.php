<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../config/database.php';

$stmt = $pdo->query("SELECT * FROM profil_puskesmas LIMIT 1");
$profil = $stmt->fetch() ?: [];
$nama_puskesmas = $profil['nama_puskesmas'] ?? 'Puskesmas Makbon';
$base = $base ?? '';
?>
<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <title><?= isset($page_title) ? clean($page_title) . ' - ' : '' ?><?= clean($nama_puskesmas) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="<?= $base ?>assets/traveland/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="<?= $base ?>assets/traveland/css/animate.css">
    <link rel="stylesheet" href="<?= $base ?>assets/traveland/css/nice-select.css">
    <link rel="stylesheet" href="<?= $base ?>assets/traveland/css/LineIcons.2.0.css">
    <link rel="stylesheet" href="<?= $base ?>assets/traveland/css/bootstrap.4.5.2.min.css">
    <link rel="stylesheet" href="<?= $base ?>assets/traveland/css/default.css">
    <link rel="stylesheet" href="<?= $base ?>assets/traveland/css/style.css">

    <style>
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
        .header_slider_mini { height: 350px; position: relative; z-index: 1; }
        .main-content-section { padding: 80px 0; background: #f7faf9; min-height: 50vh; }
        .content-card { background: #fff; border-radius: 10px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,.06); margin-top: -80px; position: relative; z-index: 9; }
        .panel-head { font-weight: bold; margin-bottom: 20px; font-size: 1.2rem; }

        /* Custom CSS from index.php */
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
    <section class="header_area">
        <div class="header_navbar sticky">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <nav class="navbar navbar-expand-lg">
                            <a class="navbar-brand navbar-brand-text d-flex align-items-center" href="<?= $base ?>index.php">
                                <img src="<?= $base ?>assets/img/logo.png?v=<?= time() ?>" alt="Logo" style="height: 50px; margin-right: 10px;">
                                <span><?= clean($nama_puskesmas) ?></span>
                            </a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                                <ul id="nav" class="navbar-nav ml-auto">
                                    <li class="nav-item <?= (isset($page_title) && strpos($page_title, 'Beranda') !== false) ? 'active' : '' ?>"><a href="<?= $base ?>index.php">Beranda</a></li>
                                    <li class="nav-item <?= (isset($page_title) && strpos($page_title, 'Profil') !== false) ? 'active' : '' ?>"><a href="<?= $base ?>profil.php">Profil</a></li>
                                    <li class="nav-item <?= (isset($page_title) && strpos($page_title, 'Struktur') !== false) ? 'active' : '' ?>"><a href="<?= $base ?>struktur.php">Struktur</a></li>
                                    <li class="nav-item <?= (isset($page_title) && strpos($page_title, 'Layanan') !== false) ? 'active' : '' ?>"><a href="<?= $base ?>layanan.php">Layanan</a></li>
                                    <li class="nav-item <?= (isset($page_title) && strpos($page_title, 'Alur') !== false) ? 'active' : '' ?>"><a href="<?= $base ?>alur.php">Alur</a></li>
                                    <li class="nav-item <?= (isset($page_title) && strpos($page_title, 'Jadwal') !== false) ? 'active' : '' ?>"><a href="<?= $base ?>jadwal.php">Jadwal</a></li>
                                    <li class="nav-item <?= (isset($page_title) && strpos($page_title, 'Kontak') !== false) ? 'active' : '' ?>"><a href="<?= $base ?>kontak.php">Kontak</a></li>
                                    <li class="nav-item <?= (isset($page_title) && strpos($page_title, 'Tracking') !== false || isset($page_title) && strpos($page_title, 'Cek') !== false) ? 'active' : '' ?>"><a href="<?= $base ?>tracking.php">Cek Antrian</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!isset($page_title) || $page_title !== 'Beranda'): ?>
        <div class="header_slider header_slider_mini">
            <div class="single_slider bg_cover d-flex align-items-center" style="background-image: url(<?= $base ?>assets/img/lobby.png); height:100%;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="slider_content text-center">
                                <h2 class="slider_title wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.2s"><?= clean($page_title ?? 'Puskesmas Makbon') ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div id="home" class="header_slider">
            <div class="single_slider bg_cover d-flex align-items-center" style="background-image: url(<?= $base ?>assets/img/puskesmas01.png)">
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
        <?php endif; ?>
    </section>
    
    <?php if (!isset($use_card_wrapper) || $use_card_wrapper === true): ?>
    <section class="main-content-section pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="content-card wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.4s">
    <?php endif; ?>
