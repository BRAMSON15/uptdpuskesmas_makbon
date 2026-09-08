<?php
/**
 * Konfigurasi Fonnte WhatsApp.
 * Lebih aman mengisi token melalui environment variable FONNTE_TOKEN.
 */

return [
    'token' => getenv('FONNTE_TOKEN') ?: 'tEwYp4HuakYRRiyvUwhF',
    'endpoint' => 'https://api.fonnte.com/send',
    'timeout' => 10,
];