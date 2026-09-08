<?php

function fonnte_config()
{
    static $config;

    if ($config === null) {
        $config = require __DIR__ . '/../config/fonnte.php';
    }

    return $config;
}

function normalisasi_nomor_whatsapp($nomor)
{
    $nomor = preg_replace('/[^0-9+]/', '', trim((string)$nomor));

    if (strpos($nomor, '+') === 0) {
        $nomor = substr($nomor, 1);
    }
    if (strpos($nomor, '0') === 0) {
        $nomor = '62' . substr($nomor, 1);
    }

    return preg_match('/^62[0-9]{9,13}$/', $nomor) ? $nomor : '';
}

function kirim_whatsapp_fonnte($nomor, $pesan)
{
    $config = fonnte_config();
    $token = trim((string)($config['token'] ?? ''));
    $target = normalisasi_nomor_whatsapp($nomor);

    if ($token === '' || $target === '' || !function_exists('curl_init')) {
        error_log('Fonnte: konfigurasi token, nomor tujuan, atau ekstensi cURL tidak tersedia.');
        return false;
    }

    $curl = curl_init($config['endpoint']);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            'target' => $target,
            'message' => $pesan,
        ],
        CURLOPT_HTTPHEADER => ['Authorization: ' . $token],
        CURLOPT_TIMEOUT => (int)($config['timeout'] ?? 10),
    ]);

    $response = curl_exec($curl);
    $curlError = curl_error($curl);
    $httpCode = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($response === false) {
        error_log('Fonnte cURL error: ' . $curlError);
        return false;
    }

    $result = json_decode($response, true);
    if ($httpCode < 200 || $httpCode >= 300 || (isset($result['status']) && $result['status'] === false)) {
        error_log('Fonnte API error HTTP ' . $httpCode . ': ' . substr($response, 0, 500));
        return false;
    }

    return true;
}

function pesan_status_antrian($data, $status, $nomorSaatIni = null, $sisaSebelum = null)
{
    $nomor = format_nomor_antrian($data['nomor_antrian'], $data['layanan']);
    $nama = $data['nama_pasien'];
    $tanggal = tanggal_indo($data['tanggal_antrian']);

    if ($status === 'Menunggu') {
        return "Halo {$nama}, pendaftaran antrean Puskesmas Makbon berhasil.\n"
            . "Nomor: {$nomor}\nLayanan: {$data['layanan']}\nTanggal: {$tanggal}\n"
            . "Pantau posisi antrean: " . base_url('tracking.php?nomor_antrian=' . urlencode($nomor));
    }

    if ($status === 'Diproses') {
        return "Halo {$nama}, nomor antrean Anda {$nomor} sedang dipanggil.\n"
            . "Nomor yang sedang dilayani: " . format_nomor_antrian($nomorSaatIni, $data['layanan']) . "\n"
            . "Silakan menuju loket/poli {$data['layanan']}.";
    }

    if ($status === 'Selesai') {
        return "Halo {$nama}, pelayanan untuk nomor antrean {$nomor} telah selesai. Terima kasih.";
    }

    return "Halo {$nama}, nomor antrean {$nomor} pada {$tanggal} telah dibatalkan. Silakan hubungi petugas untuk informasi lebih lanjut.";
}