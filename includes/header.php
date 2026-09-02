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
        .header_slider_mini {
            height: 350px;
            position: relative;
            z-index: 1;
        }
        .main-content-section {
            padding: 80px 0;
            background: #f7faf9;
            min-height: 50vh;
        }
        .content-card {
            background: #fff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,.06);
            margin-top: -80px;
            position: relative;
            z-index: 9;
        }
        .panel-head { font-weight: bold; margin-bottom: 20px; font-size: 1.2rem; }
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
                                    <li class="nav-item <?= (isset($page_title) && $page_title == 'Beranda') ? 'active' : '' ?>"><a href="<?= $base ?>index.php">Beranda</a></li>
                                    <li class="nav-item <?= (isset($page_title) && $page_title == 'Profil') ? 'active' : '' ?>"><a href="<?= $base ?>profil.php">Profil</a></li>
                                    <li class="nav-item <?= (isset($page_title) && $page_title == 'Layanan') ? 'active' : '' ?>"><a href="<?= $base ?>layanan.php">Layanan</a></li>
                                    <li class="nav-item <?= (isset($page_title) && $page_title == 'Jadwal') ? 'active' : '' ?>"><a href="<?= $base ?>jadwal.php">Jadwal</a></li>
                                    <li class="nav-item <?= (isset($page_title) && $page_title == 'Kontak') ? 'active' : '' ?>"><a href="<?= $base ?>kontak.php">Kontak</a></li>
                                    <li class="nav-item <?= (isset($page_title) && $page_title == 'Tracking Antrian') ? 'active' : '' ?>"><a href="<?= $base ?>tracking.php">Cek Antrian</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

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
    </section>
    <!--====== HEADER PART ENDS ======-->

    <!--====== MAIN CONTENT START ======-->
    <section class="main-content-section pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="content-card wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.4s">
