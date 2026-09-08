<?php
/**
 * Konfigurasi Fonnte WhatsApp.
 * Lebih aman mengisi token melalui environment variable FONNTE_TOKEN.
 */

return [
    'token' => getenv('FONNTE_TOKEN') ?: 'dvYDFiePNEgc1Kodgebt',
    'endpoint' => 'https://api.fonnte.com/send',
    'timeout' => 10,
];