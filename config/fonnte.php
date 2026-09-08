<?php
/**
 * Konfigurasi Fonnte WhatsApp.
 * Lebih aman mengisi token melalui environment variable FONNTE_TOKEN.
 */

return [
    'token' => getenv('FONNTE_TOKEN') ?: 'f4t3NCU6ab6tNyCC9Cas',
    'endpoint' => 'https://api.fonnte.com/send',
    'timeout' => 10,
];