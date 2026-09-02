<?php $current = basename($_SERVER['SCRIPT_NAME']); ?>
<aside class="sidebar">
    <div class="side-brand">
        <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo">
        <div>
            Puskesmas Makbon
            <small>Panel Admin</small>
        </div>
    </div>
    <a href="dashboard.php" class="<?= $current === 'dashboard.php' ? 'active' : '' ?>"><i class="lni lni-dashboard"></i> Dashboard</a>
    <a href="profil.php" class="<?= $current === 'profil.php' ? 'active' : '' ?>"><i class="lni lni-apartment"></i> Profil Puskesmas</a>
    <a href="layanan.php" class="<?= $current === 'layanan.php' ? 'active' : '' ?>"><i class="lni lni-layers"></i> Kelola Layanan</a>
    <a href="jadwal.php" class="<?= $current === 'jadwal.php' ? 'active' : '' ?>"><i class="lni lni-calendar"></i> Jadwal Operasional</a>
    <a href="antrian.php" class="<?= $current === 'antrian.php' ? 'active' : '' ?>"><i class="lni lni-list"></i> Antrian Online</a>
    <a href="tracking.php" class="<?= $current === 'tracking.php' ? 'active' : '' ?>"><i class="lni lni-map-marker"></i> Tracking Antrian</a>
    <a href="petugas.php" class="<?= $current === 'petugas.php' ? 'active' : '' ?>"><i class="lni lni-users"></i> Kelola Petugas</a>
    <a href="saran.php" class="<?= $current === 'saran.php' ? 'active' : '' ?>"><i class="lni lni-comments"></i> Saran & Masukan</a>
    <a href="kontak.php" class="<?= $current === 'kontak.php' ? 'active' : '' ?>"><i class="lni lni-phone"></i> Kontak</a>
    <div class="logout-link">
        <a href="logout.php"><i class="lni lni-exit"></i> Logout</a>
    </div>
</aside>
