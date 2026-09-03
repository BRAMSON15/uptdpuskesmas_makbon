<?php
require_once __DIR__ . '/config/database.php';
$page_title = 'Kontak';
$use_card_wrapper = false;
require_once __DIR__ . '/includes/header.php';
?>
<!--====== KONTAK (dulu "contact") START ======-->
    <section id="kontak" class="contact_area pt-120 pb-130">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section_title text-center pb-25">
                        <h3 class="title">Hubungi Kami</h3>
                        <p>Sampaikan pertanyaan atau masukan Anda kepada kami</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="quick_access_card mb-30">
                        <i class="lni lni-map-marker"></i>
                        <h4>Alamat</h4>
                        <p><?= nl2br(clean($profil['alamat'] ?? '-')) ?></p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="quick_access_card mb-30">
                        <i class="lni lni-phone"></i>
                        <h4>Telepon / WhatsApp</h4>
                        <p><?= clean($profil['kontak'] ?? '-') ?></p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="quick_access_card mb-30">
                        <i class="lni lni-envelope"></i>
                        <h4>Email</h4>
                        <p><?= clean($profil['email'] ?? '-') ?></p>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($profil['qr_bpjs'])): ?>
            <div class="row justify-content-center mt-20 mb-40">
                <div class="col-lg-5 text-center">
                    <div class="quick_access_card" style="padding: 30px;">
                        <h4 class="mb-3"><i class="lni lni-mobile"></i> Scan QR Code BPJS</h4>
                        <img src="assets/storage/qr/<?= clean($profil['qr_bpjs']) ?>" alt="QR Code BPJS" class="img-fluid" style="max-height: 220px; border: 1px solid #eaeff2; border-radius: 12px; padding: 10px; background: #fff;" onerror="this.src='assets/img/placeholder.png'; this.title='Gambar tidak ditemukan';">
                        <p class="mt-3 text-muted" style="font-size: 0.9rem;">Gunakan Aplikasi Mobile JKN untuk melakukan pemindaian (scan) QR Code saat berkunjung ke Puskesmas.</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="contact_form">
                <?php show_flash(); ?>
                <form action="saran_proses.php" method="POST" id="contact-form">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="single_form">
                                <input type="text" name="nama_pengirim" id="nama_pengirim" placeholder="Nama" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="single_form">
                                <input type="email" name="email" id="email" placeholder="Email (opsional)">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="single_form">
                                <textarea name="pesan" id="pesan" placeholder="Pesan / Saran / Masukan" required></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="single_form">
                                <button type="submit" class="main-btn">Kirim Pesan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!--====== KONTAK ENDS ======-->
<?php require_once __DIR__ . '/includes/footer.php'; ?>
