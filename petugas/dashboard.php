<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/database.php';
$page_title = 'Dashboard';

$antrianHariIni = $pdo->query("SELECT COUNT(*) c FROM antrian_online WHERE tanggal_antrian = CURDATE()")->fetch()['c'];
$menunggu       = $pdo->query("SELECT COUNT(*) c FROM antrian_online WHERE status = 'Menunggu' AND tanggal_antrian = CURDATE()")->fetch()['c'];
$diproses       = $pdo->query("SELECT COUNT(*) c FROM antrian_online WHERE status = 'Diproses' AND tanggal_antrian = CURDATE()")->fetch()['c'];
$selesai        = $pdo->query("SELECT COUNT(*) c FROM antrian_online WHERE status = 'Selesai' AND tanggal_antrian = CURDATE()")->fetch()['c'];

$antrianHariIniList = $pdo->query("SELECT * FROM antrian_online WHERE tanggal_antrian = CURDATE() ORDER BY FIELD(status,'Menunggu','Diproses','Selesai','Dibatalkan'), nomor_antrian ASC")->fetchAll();

require_once __DIR__ . '/includes/layout_top.php';
?>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-white stat-card-modern" style="background: linear-gradient(135deg, #0d7c66 0%, #119e83 100%);">
            <i class="lni lni-users icon-bg text-white"></i>
            <div class="card-body">
                <h3 class="mb-0"><?= $antrianHariIni ?></h3>
                <small>Total Hari Ini</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white stat-card-modern" style="background: linear-gradient(135deg, #f39c12 0%, #f1c40f 100%);">
            <i class="lni lni-timer icon-bg text-white"></i>
            <div class="card-body">
                <h3 class="mb-0"><?= $menunggu ?></h3>
                <small>Menunggu</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white stat-card-modern" style="background: linear-gradient(135deg, #2980b9 0%, #3498db 100%);">
            <i class="lni lni-spinner-solid icon-bg text-white"></i>
            <div class="card-body">
                <h3 class="mb-0"><?= $diproses ?></h3>
                <small>Diproses</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white stat-card-modern" style="background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);">
            <i class="lni lni-checkmark icon-bg text-white"></i>
            <div class="card-body">
                <h3 class="mb-0"><?= $selesai ?></h3>
                <small>Selesai</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-head">
                <h2>Antrian Hari Ini</h2>
                <a href="antrian.php" class="btn btn-secondary btn-sm">Kelola Antrian</a>
            </div>
            <table>
                <thead><tr><th>No.</th><th>Pasien</th><th>Layanan</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach ($antrianHariIniList as $a): ?>
                    <tr>
                        <td><?= $a['nomor_antrian'] ?></td>
                        <td><?= clean($a['nama_pasien']) ?></td>
                        <td><?= clean($a['layanan']) ?></td>
                        <td><?= badge_status($a['status']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($antrianHariIniList)): ?>
                    <tr><td colspan="4" class="empty-state">Belum ada antrian hari ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-head">
                <h2>Statistik Antrian</h2>
            </div>
            <div style="position: relative; height:300px; width:100%">
                <canvas id="antrianChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('antrianChart').getContext('2d');
    var antrianChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Menunggu', 'Diproses', 'Selesai'],
            datasets: [{
                data: [<?= $menunggu ?>, <?= $diproses ?>, <?= $selesai ?>],
                backgroundColor: [
                    '#ffc107', // warning
                    '#17a2b8', // info
                    '#28a745'  // success
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>
