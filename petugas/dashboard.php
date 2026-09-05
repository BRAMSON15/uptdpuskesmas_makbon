<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Dashboard';

// Statistik hari ini
$antrianHariIni = $pdo->query("SELECT COUNT(*) c FROM antrian_online WHERE tanggal_antrian = CURDATE()")->fetch()['c'];
$menunggu       = $pdo->query("SELECT COUNT(*) c FROM antrian_online WHERE status = 'Menunggu' AND tanggal_antrian = CURDATE()")->fetch()['c'];
$diproses       = $pdo->query("SELECT COUNT(*) c FROM antrian_online WHERE status = 'Diproses' AND tanggal_antrian = CURDATE()")->fetch()['c'];
$selesai        = $pdo->query("SELECT COUNT(*) c FROM antrian_online WHERE status = 'Selesai' AND tanggal_antrian = CURDATE()")->fetch()['c'];

// Statistik total keseluruhan
$totalMenunggu  = $pdo->query("SELECT COUNT(*) c FROM antrian_online WHERE status = 'Menunggu'")->fetch()['c'];
$totalDiproses  = $pdo->query("SELECT COUNT(*) c FROM antrian_online WHERE status = 'Diproses'")->fetch()['c'];
$totalSelesai   = $pdo->query("SELECT COUNT(*) c FROM antrian_online WHERE status = 'Selesai'")->fetch()['c'];
$totalSemua     = $pdo->query("SELECT COUNT(*) c FROM antrian_online")->fetch()['c'];

// Daftar antrian hari ini, jika kosong tampilkan yang akan datang
$antrianHariIniList = $pdo->query(
    "SELECT * FROM antrian_online WHERE tanggal_antrian = CURDATE()
     ORDER BY FIELD(status,'Menunggu','Diproses','Selesai','Dibatalkan'), nomor_antrian ASC"
)->fetchAll();

$labelSumber = 'Hari Ini';
if (empty($antrianHariIniList)) {
    // Tampilkan antrian mendatang jika tidak ada yang hari ini
    $antrianHariIniList = $pdo->query(
        "SELECT * FROM antrian_online WHERE tanggal_antrian >= CURDATE()
         ORDER BY tanggal_antrian ASC, nomor_antrian ASC LIMIT 10"
    )->fetchAll();
    $labelSumber = 'Mendatang';
}

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="dashboard-intro">
    <div>
        <h2>Ruang kerja petugas</h2>
        <p>Kelola alur antrian dan pelayanan pasien hari ini.</p>
    </div>
    <div class="dashboard-date"><i class="lni lni-calendar"></i> <?= tanggal_indo(date('Y-m-d')) ?></div>
</div>

<div class="quick-actions">
    <a href="antrian.php" class="btn btn-primary"><i class="lni lni-list"></i> Kelola Antrian</a>
    <a href="verifikasi.php" class="btn btn-outline-secondary"><i class="lni lni-checkmark-circle"></i> Verifikasi Pasien</a>
    <a href="laporan.php" class="btn btn-outline-secondary"><i class="lni lni-printer"></i> Buka Laporan</a>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card-modern accent-teal">
            <i class="lni lni-users icon-bg text-white"></i>
            <div class="card-body">
                <h3 class="mb-0"><?= $totalSemua ?></h3>
                <small>Total Semua Antrian</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card-modern accent-amber">
            <i class="lni lni-timer icon-bg text-white"></i>
            <div class="card-body">
                <h3 class="mb-0"><?= $totalMenunggu ?></h3>
                <small>Menunggu</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card-modern accent-blue">
            <i class="lni lni-spinner-solid icon-bg text-white"></i>
            <div class="card-body">
                <h3 class="mb-0"><?= $totalDiproses ?></h3>
                <small>Diproses</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card-modern accent-green">
            <i class="lni lni-checkmark icon-bg text-white"></i>
            <div class="card-body">
                <h3 class="mb-0"><?= $totalSelesai ?></h3>
                <small>Selesai</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-head">
                <h2>Antrian <?= $labelSumber ?></h2>
                <a href="antrian.php" class="btn btn-secondary btn-sm">Kelola Antrian</a>
            </div>
            <table>
                <thead><tr><th>No.</th><th>Pasien</th><th>Layanan</th><th>Tanggal</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach ($antrianHariIniList as $a): ?>
                    <tr>
                        <td><?= format_nomor_antrian($a['nomor_antrian'], $a['layanan']) ?></td>
                        <td><?= clean($a['nama_pasien']) ?></td>
                        <td><?= clean($a['layanan']) ?></td>
                        <td><?= tanggal_indo($a['tanggal_antrian']) ?></td>
                        <td><?= badge_status($a['status']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($antrianHariIniList)): ?>
                    <tr><td colspan="5" class="empty-state">Belum ada data antrian.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-head">
                <h2>Statistik Antrian <?= $labelSumber ?></h2>
            </div>
            <?php 
                // Gunakan statistik hari ini. Jika kosong, gunakan statistik keseluruhan
                $chartMenunggu = $menunggu > 0 || $diproses > 0 || $selesai > 0 ? $menunggu : $totalMenunggu;
                $chartDiproses = $menunggu > 0 || $diproses > 0 || $selesai > 0 ? $diproses : $totalDiproses;
                $chartSelesai  = $menunggu > 0 || $diproses > 0 || $selesai > 0 ? $selesai : $totalSelesai;
                $totalChart = $chartMenunggu + $chartDiproses + $chartSelesai; 
            ?>
            <?php if ($totalChart > 0): ?>
                <div style="position: relative; height:260px; width:100%">
                    <canvas id="antrianChart"></canvas>
                </div>
            <?php else: ?>
                <div style="text-align:center; padding: 40px 20px; color:#aaa;">
                    <i class="lni lni-bar-chart" style="font-size:3rem; display:block; margin-bottom:12px;"></i>
                    <p style="font-size:0.9rem;">Belum ada data antrian.<br>Statistik akan muncul saat ada antrian masuk.</p>
                </div>
                <div style="position: relative; height:80px; width:100%">
                    <canvas id="antrianChart"></canvas>
                </div>
            <?php endif; ?>
            <div style="margin-top:12px; display:flex; gap:14px; justify-content:center; font-size:0.82rem;">
                <span><span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:#ffc107;margin-right:4px;"></span>Menunggu <strong><?= $chartMenunggu ?></strong></span>
                <span><span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:#17a2b8;margin-right:4px;"></span>Diproses <strong><?= $chartDiproses ?></strong></span>
                <span><span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:#28a745;margin-right:4px;"></span>Selesai <strong><?= $chartSelesai ?></strong></span>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('antrianChart').getContext('2d');
    var total = <?= $totalChart ?? 0 ?>;
    var antrianChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Menunggu', 'Diproses', 'Selesai'],
            datasets: [{
                data: total > 0
                    ? [<?= $chartMenunggu ?>, <?= $chartDiproses ?>, <?= $chartSelesai ?>]
                    : [1, 1, 1],
                backgroundColor: total > 0
                    ? ['#ffc107', '#17a2b8', '#28a745']
                    : ['#e9ecef', '#dee2e6', '#ced4da'],
                borderWidth: total > 0 ? 2 : 0,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            if (total === 0) return 'Tidak ada data';
                            return ctx.label + ': ' + ctx.raw;
                        }
                    }
                }
            }
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>

