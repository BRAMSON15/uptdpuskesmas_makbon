<?php
require_once __DIR__ . '/config/database.php';
$page_title = 'Alur Pelayanan';
$use_card_wrapper = false;
require_once __DIR__ . '/includes/header.php';
?>
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
<?php require_once __DIR__ . '/includes/footer.php'; ?>
