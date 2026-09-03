<?php
require_once __DIR__ . '/config/database.php';
$page_title = 'Jadwal';
$use_card_wrapper = false;
$jadwalList = $pdo->query("SELECT * FROM jadwal_operasional ORDER BY FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")->fetchAll();
require_once __DIR__ . '/includes/header.php';
?>
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
<?php require_once __DIR__ . '/includes/footer.php'; ?>
