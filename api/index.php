<?php

// Buat direktori storage sementara di /tmp agar Vercel Serverless environment berjalan lancar
$directories = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/storage/app',
    '/tmp/views',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Teruskan request ke entrypoint Laravel public/index.php
require __DIR__ . '/../public/index.php';
