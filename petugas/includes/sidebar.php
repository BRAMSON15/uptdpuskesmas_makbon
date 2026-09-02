<?php $current = basename($_SERVER['SCRIPT_NAME']); ?>
<aside class="sidebar">
    <div class="side-brand">
        <img src="../assets/img/logo.png" alt="Logo" style="height:40px; width:40px; object-fit:contain;">
        <div>
            Puskesmas Makbon
            <small>Panel Petugas</small>
        </div>
    </div>
    <a href="dashboard.php" class="<?= $current === 'dashboard.php' ? 'active' : '' ?>"><i class="lni lni-dashboard"></i> Dashboard</a>
    <a href="antrian.php" class="<?= $current === 'antrian.php' ? 'active' : '' ?>"><i class="lni lni-list"></i> Kelola Antrian Online</a>
    <a href="verifikasi.php" class="<?= $current === 'verifikasi.php' ? 'active' : '' ?>"><i class="lni lni-checkmark-circle"></i> Verifikasi & Validasi</a>
    <a href="pasien.php" class="<?= $current === 'pasien.php' ? 'active' : '' ?>"><i class="lni lni-users"></i> Data Pasien</a>
    <a href="layanan.php" class="<?= $current === 'layanan.php' ? 'active' : '' ?>"><i class="lni lni-layers"></i> Layanan (BPJS/Non)</a>
    <a href="tracking.php" class="<?= $current === 'tracking.php' ? 'active' : '' ?>"><i class="lni lni-map-marker"></i> Tracking Antrian</a>
    <a href="jadwal.php" class="<?= $current === 'jadwal.php' ? 'active' : '' ?>"><i class="lni lni-calendar"></i> Jadwal Operasional</a>
    <a href="saran.php" class="<?= $current === 'saran.php' ? 'active' : '' ?>"><i class="lni lni-comments"></i> Saran & Masukan</a>
    <a href="laporan.php" class="<?= $current === 'laporan.php' ? 'active' : '' ?>"><i class="lni lni-printer"></i> Laporan</a>
    <div class="logout-link">
        <a href="logout.php"><i class="lni lni-exit"></i> Logout</a>
    </div>
</aside>
