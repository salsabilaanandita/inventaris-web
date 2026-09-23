<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

try {
    // Sanitasi environment variables kosong untuk driver Laravel
    // Pada serverless/Vercel, jika environment variable bernilai string kosong (""),
    // env() mengembalikan "" dan memicu ArgumentCountError pada Manager::createDriver()
    $driverDefaults = [
        'SESSION_DRIVER' => 'database',
        'CACHE_STORE' => 'database',
        'CACHE_DRIVER' => 'database',
        'LOG_CHANNEL' => 'stderr',
        'QUEUE_CONNECTION' => 'database',
        'FILESYSTEM_DISK' => 'local',
        'BROADCAST_CONNECTION' => 'log',
        'MAIL_MAILER' => 'log',
        'DB_CONNECTION' => 'pgsql',
        'APP_MAINTENANCE_DRIVER' => 'file',
    ];

    foreach ($driverDefaults as $key => $defaultVal) {
        $val = getenv($key);
        if ($val === false || trim((string)$val) === '') {
            putenv("{$key}={$defaultVal}");
            $_ENV[$key] = $defaultVal;
            $_SERVER[$key] = $defaultVal;
        }
    }

    // Buat direktori temporary yang dibutuhkan Laravel di /tmp
    $storageDirs = [
        '/tmp/storage',
        '/tmp/storage/app',
        '/tmp/storage/app/public',
        '/tmp/storage/framework',
        '/tmp/storage/framework/cache',
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/views',
        '/tmp/storage/logs',
        '/tmp/views',
    ];

    foreach ($storageDirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    // Register Composer autoloader
    require __DIR__ . '/../vendor/autoload.php';

    // Bootstrap Laravel 11 / 12
    /** @var \Illuminate\Foundation\Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // Arahkan storage path ke /tmp/storage
    $app->useStoragePath('/tmp/storage');

    // Handle request
    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    http_response_code(500);
    echo "<h1>Laravel Startup Error</h1>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . ":" . $e->getLine() . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
