<?php
$base = '';
$asset_path = '';
$page_title = 'Saran & Masukan';
require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="text-center mb-4">
            <h3 class="title">Saran & Masukan</h3>
            <p class="text-muted">Sampaikan saran, kritik, atau masukan Anda untuk peningkatan layanan kami.</p>
        </div>
        
        <?php show_flash(); ?>
        
        <form action="saran_proses.php" method="POST">
            <div class="form-group mb-3">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama_pengirim" required placeholder="Nama Anda">
            </div>
            <div class="form-group mb-3">
                <label>Email (opsional)</label>
                <input type="email" class="form-control" name="email" placeholder="email@contoh.com">
            </div>
            <div class="form-group mb-4">
                <label>Pesan</label>
                <textarea name="pesan" class="form-control" rows="5" required placeholder="Tulis saran atau masukan Anda di sini..."></textarea>
            </div>
            <button type="submit" class="main-btn w-100">Kirim Masukan</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
