<?php if (!isset($use_card_wrapper) || $use_card_wrapper === true): ?>
                    </div> <!-- end content-card -->
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <!--====== MAIN CONTENT ENDS ======-->

    <!--====== FOOTER PART START ======-->
    <section id="footer" class="footer_area">
        <div class="footer_widget pt-80 pb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 order-md-1 order-lg-1">
                        <div class="footer_about mt-50">
                            <a href="<?= $base ?? '' ?>index.php" class="navbar-brand-text d-flex align-items-center" style="display:inline-flex;margin-bottom:14px; color:#white; font-size:1.4rem; font-weight:700;">
                                <img src="<?= $base ?? '' ?>assets/img/logo.png?v=<?= time() ?>" alt="Logo" style="height: 50px; margin-right: 10px;">
                                <span><?= clean($nama_puskesmas) ?></span>
                            </a>
                            <p><?= clean($profil['deskripsi_beranda'] ?? 'Melayani kesehatan masyarakat dengan cepat, ramah, dan terpercaya.') ?></p>
                        </div>
                    </div>
                    <!-- <div class="col-lg-4 col-md-12 order-md-3 order-lg-2">
                        <div class="footer_link_wrapper d-flex flex-wrap">
                            <div class="footer_link mt-45">
                                <h4 class="footer_title">Tautan Cepat</h4>
                                <ul class="link">
                                    <li><a href="<?= $base ?? '' ?>index.php">Beranda</a></li>
                                    <li><a href="<?= $base ?? '' ?>profil.php">Profil</a></li>
                                    <li><a href="<?= $base ?? '' ?>layanan.php">Layanan</a></li>
                                    <li><a href="<?= $base ?? '' ?>jadwal.php">Jadwal</a></li>
                                    <li><a href="<?= $base ?? '' ?>antrian.php">Antrian Online</a></li>
                                    <li><a href="<?= $base ?? '' ?>tracking.php">Tracking Antrian</a></li>
                                </ul>
                            </div>
                        </div>
                    </div> -->
                    <!-- <div class="col-lg-4 col-md-6 order-md-2 order-lg-3">
                        <div class="footer_subscribe mt-45">
                            <h4 class="footer_title">Lainnya</h4>
                            <ul class="link">
                                <li><a href="<?= $base ?? '' ?>saran.php">Saran & Masukan</a></li>
                                <li><a href="<?= $base ?? '' ?>kontak.php">Kontak</a></li>
                                <li><a href="<?= $base ?? '' ?>admin/login.php">Login Admin</a></li>
                                <li><a href="<?= $base ?? '' ?>petugas/login.php">Login Petugas</a></li>
                            </ul>
                        </div>
                    </div> -->
                </div>
            </div>
            <p class="text-center mt-60">&copy; <?= date('Y') ?> <?= clean($nama_puskesmas) ?>. Sistem Informasi Profil, Layanan & Antrian Online.</p>
        </div>
    </section>
    <!--====== FOOTER PART ENDS ======-->

    <a href="#" class="back-to-top"><i class="lni lni-chevron-up"></i></a>

    <script src="<?= $base ?? '' ?>assets/traveland/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="<?= $base ?? '' ?>assets/traveland/js/vendor/modernizr-3.7.1.min.js"></script>
    <script src="<?= $base ?? '' ?>assets/traveland/js/popper.min.js"></script>
    <script src="<?= $base ?? '' ?>assets/traveland/js/bootstrap.4.5.2.min.js"></script>
    <script src="<?= $base ?? '' ?>assets/traveland/js/jquery.easing.min.js"></script>
    <script src="<?= $base ?? '' ?>assets/traveland/js/scrolling-nav.js"></script>
    <script src="<?= $base ?? '' ?>assets/traveland/js/waypoints.min.js"></script>
    <script src="<?= $base ?? '' ?>assets/traveland/js/jquery.counterup.min.js"></script>
    <script src="<?= $base ?? '' ?>assets/traveland/js/jquery.nice-select.min.js"></script>
    <script src="<?= $base ?? '' ?>assets/traveland/js/wow.min.js"></script>
    <script src="<?= $base ?? '' ?>assets/traveland/js/main.js"></script>

</body>
</html>
