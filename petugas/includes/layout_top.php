<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <title><?= clean($page_title ?? 'Dashboard') ?> - Petugas Puskesmas Makbon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= public_url('assets/traveland/images/favicon.png') ?>" type="image/png">
    <link rel="stylesheet" href="<?= public_url('assets/traveland/css/LineIcons.2.0.css') ?>">
    <link rel="stylesheet" href="<?= public_url('assets/traveland/css/bootstrap.4.5.2.min.css') ?>">
    <link rel="stylesheet" href="<?= public_url('assets/traveland/css/default.css') ?>">
    <link rel="stylesheet" href="<?= public_url('assets/traveland/css/style.css') ?>">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0d7c66;
            --primary-hover: #0a6552;
            --bg-color: #f4f7f6;
            --sidebar-bg: #112d27;
            --sidebar-hover: #18433a;
            --text-main: #2b3b36;
            --text-muted: #6b7c77;
            --border-color: #eaeff2;
        }
        
        body { 
            background-color: var(--bg-color); 
            font-family: 'Outfit', sans-serif;
            color: var(--text-main);
        }
        
        .dash-wrap { display: flex; min-height: 100vh; }
        
        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: #fff;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 15px rgba(0,0,0,0.05);
            z-index: 10;
        }
        .side-brand {
            padding: 25px 20px;
            font-size: 1.25rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .side-brand img {
            height: 40px;
        }
        .side-brand div {
            text-align: left;
            line-height: 1.2;
        }
        .side-brand small {
            display: block;
            font-size: 0.8rem;
            font-weight: 400;
            color: #cfe9df;
        }
        .sidebar-menu {
            padding: 20px 15px;
            flex-grow: 1;
        }
        .sidebar a {
            color: #cfe9df;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .sidebar a:hover {
            background: var(--sidebar-hover);
            color: #fff;
            transform: translateX(4px);
        }
        .sidebar a.active {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 10px rgba(13, 124, 102, 0.3);
        }
        .sidebar .lni { 
            margin-right: 12px; 
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }
        .logout-link { 
            padding: 15px;
            border-top: 1px solid rgba(255,255,255,0.05); 
        }
        .logout-link a { 
            background: rgba(220,53,69,0.1); 
            color: #ff8e99;
            justify-content: center;
        }
        .logout-link a:hover { 
            background: #dc3545; 
            color: #fff;
            transform: none;
        }
        
        /* MAIN CONTENT */
        .main-content {
            flex-grow: 1;
            padding: 30px 40px;
            overflow-y: auto;
            max-height: 100vh;
        }
        
        /* TOPBAR */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            border: 1px solid rgba(255,255,255,0.4);
        }
        .topbar h1 { 
            margin: 0; 
            font-size: 1.6rem; 
            font-weight: 700;
            color: var(--text-main);
        }
        .user-chip {
            background: #e4f4ec;
            color: #1f8a55;
            padding: 8px 18px;
            border-radius: 30px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* PANEL / CARDS */
        .panel {
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03);
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
        }
        .panel-head {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bg-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .panel-head h2 { 
            margin: 0; 
            font-size: 1.3rem; 
            font-weight: 600;
            color: var(--text-main);
        }
        
        /* MODERN TABLES */
        .panel table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0;
            margin-top: 10px; 
        }
        .panel th, .panel td { 
            padding: 16px 15px; 
            border-bottom: 1px solid var(--border-color); 
            text-align: left; 
            vertical-align: middle;
        }
        .panel th { 
            background: var(--bg-color); 
            font-weight: 600; 
            color: var(--text-muted); 
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .panel th:first-child { border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
        .panel th:last-child { border-top-right-radius: 8px; border-bottom-right-radius: 8px; }
        .panel tbody tr { transition: all 0.2s; }
        .panel tbody tr:hover { background: #fcfdfe; }
        .empty-state { 
            text-align: center !important; 
            padding: 40px !important; 
            color: var(--text-muted); 
        }
        
        /* BUTTONS & FORMS */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 8px 16px;
            transition: all 0.2s;
        }
        .btn-sm { padding: 6px 12px; font-size: 0.85rem; }
        .form-control {
            border-radius: 8px;
            border: 1px solid #dce4e1;
            padding: 10px 15px;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(13, 124, 102, 0.15);
            border-color: var(--primary);
        }
        
        /* STAT CARDS (for dashboard) */
        .stat-card-modern {
            border-radius: 16px;
            border: none;
            overflow: hidden;
            position: relative;
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
        }
        .stat-card-modern .card-body {
            padding: 25px;
            position: relative;
            z-index: 2;
        }
        .stat-card-modern .icon-bg {
            position: absolute;
            right: -10px;
            bottom: -15px;
            font-size: 5rem;
            opacity: 0.2;
            z-index: 1;
            transform: rotate(-15deg);
        }
        .stat-card-modern h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .stat-card-modern small {
            font-size: 0.95rem;
            opacity: 0.9;
            font-weight: 500;
        }
        
        /* BADGES */
        .badge {
            padding: 6px 10px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
<div class="dash-wrap">
    <?php require_once __DIR__ . '/sidebar.php'; ?>
    <main class="main-content">
        <div class="topbar">
            <h1><?= clean($page_title ?? 'Dashboard') ?></h1>
            <div class="user-chip"><i class="lni lni-user"></i> <?= clean($_SESSION['petugas_nama'] ?? 'Petugas') ?></div>
        </div>
        <?php show_flash(); ?>
