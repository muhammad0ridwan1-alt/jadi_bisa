<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register Composer autoloader
if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require __DIR__.'/vendor/autoload.php';
} else {
    header('Content-Type: text/html; charset=utf-8');
    echo '<div style="font-family: sans-serif; text-align: center; padding: 50px;">';
    echo '<h2 style="color: #0284c7;">Aplikasi Sedang Dipersiapkan</h2>';
    echo '<p>Folder <code>vendor</code> belum lengkap. Pastikan Anda sudah mengupload semua file zip dan membuka <a href="unzip_all.php">unzip_all.php</a> untuk mengekstraknya.</p>';
    echo '</div>';
    exit;
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
