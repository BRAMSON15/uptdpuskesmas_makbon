<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

if (!empty($_SESSION['admin_id'])) {
    redirect('dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id']   = $admin['id_admin'];
        $_SESSION['admin_nama'] = $admin['nama_admin'];
        redirect('dashboard.php');
    } else {
        set_flash('error', 'Username atau password salah.');
        redirect('login.php');
    }
}
?>
<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8">
    <title>Login Admin - Puskesmas Makbon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../assets/traveland/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="../assets/traveland/css/bootstrap.4.5.2.min.css">
    <link rel="stylesheet" href="../assets/traveland/css/default.css">
    <link rel="stylesheet" href="../assets/traveland/css/style.css">
    <style>
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=1200&q=80') center center / cover no-repeat;
            position: relative;
            z-index: 1;
        }
        .login-wrapper::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(13, 124, 102, 0.7); /* Puskesmas theme color overlay */
            z-index: -1;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 450px;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card text-center">
            <h2 class="mb-2">Login Admin</h2>
            <p class="text-muted mb-4">Sistem Informasi Puskesmas Makbon</p>
            
            <?php show_flash(); ?>
            
            <form method="POST" class="text-left">
                <div class="form-group mb-3">
                    <label>Username</label>
                    <input type="text" class="form-control form-control-lg" name="username" required autofocus>
                </div>
                <div class="form-group mb-4">
                    <label>Password</label>
                    <input type="password" class="form-control form-control-lg" name="password" required>
                </div>
                <button type="submit" class="main-btn w-100 mb-3">Masuk</button>
            </form>
            
            <a href="../index.php" class="text-muted text-decoration-none">&larr; Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
