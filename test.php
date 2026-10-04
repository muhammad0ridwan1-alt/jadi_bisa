<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo '<div style="font-family: sans-serif; padding: 20px; line-height: 1.6; max-width: 800px; margin: auto;">';
echo '<h2 style="color: #0284c7;">🔍 DIAGNOSA SISTEM JADIBISA (INFINITYFREE)</h2>';

echo '<hr>';
echo '<h3>1. PHP Version & Extensions:</h3>';
echo '<p>PHP: <b>' . phpversion() . '</b> | PDO MySQL: ' . (extension_loaded('pdo_mysql') ? '✅' : '❌') . ' | Mbstring: ' . (extension_loaded('mbstring') ? '✅' : '❌') . ' | OpenSSL: ' . (extension_loaded('openssl') ? '✅' : '❌') . '</p>';

echo '<h3>2. Cek Folder Vendor (Autoload):</h3>';
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo '<p style="color:green;">✅ <b>vendor/autoload.php DITEMUKAN</b></p>';
    try {
        require __DIR__ . '/vendor/autoload.php';
        echo '<p style="color:green;">✅ Autoload composer berhasil dimuat.</p>';
    } catch (Throwable $t) {
        echo '<p style="color:red;">❌ Autoload error: ' . $t->getMessage() . '</p>';
    }
} else {
    echo '<p style="color:red;">❌ <b>vendor/autoload.php TIDAK DITEMUKAN!</b></p>';
    echo '<p style="background: #fef2f2; border: 1px solid #fecaca; padding: 10px; border-radius: 8px;">Ini penyebab HTTP ERROR 500! Folder <code>vendor/</code> belum lengkap. Pastikan file <code>part2_vendor_laravel.zip</code> sampai <code>part5_vendor_libs2.zip</code> sudah di-upload dan diekstrak.</p>';
}

echo '<h3>3. Cek File .env & Konfigurasi:</h3>';
if (file_exists(__DIR__ . '/.env')) {
    echo '<p style="color:green;">✅ File .env ditemukan</p>';
    $env = file_get_contents(__DIR__ . '/.env');
    preg_match('/APP_KEY=(.*)/', $env, $keyMatch);
    $appKey = trim($keyMatch[1] ?? '');
    if (!empty($appKey)) {
        echo '<p style="color:green;">✅ APP_KEY terisi: <code>' . substr($appKey, 0, 15) . '...</code></p>';
    } else {
        echo '<p style="color:red;">❌ <b>APP_KEY KOSONG!</b> Ini menyebabkan HTTP ERROR 500.</p>';
    }
} else {
    echo '<p style="color:red;">❌ File .env tidak ditemukan!</p>';
}

echo '<h3>4. Cek Folder Storage (Izin Tulis):</h3>';
$storageDirs = [
    'storage/framework/views' => __DIR__ . '/storage/framework/views',
    'storage/framework/sessions' => __DIR__ . '/storage/framework/sessions',
    'storage/framework/cache' => __DIR__ . '/storage/framework/cache',
    'bootstrap/cache' => __DIR__ . '/bootstrap/cache'
];
foreach ($storageDirs as $name => $path) {
    if (!is_dir($path)) @mkdir($path, 0777, true);
    $writable = is_writable($path);
    echo '<p>' . ($writable ? '✅' : '❌') . ' <code>' . $name . '</code>: ' . ($writable ? 'Bisa Ditulis (Writable)' : '<b>Tidak Bisa Ditulis!</b>') . '</p>';
}

echo '<h3>5. Coba Jalankan Boot Laravel:</h3>';
try {
    if (file_exists(__DIR__ . '/bootstrap/app.php') && file_exists(__DIR__ . '/vendor/autoload.php')) {
        $app = require_once __DIR__ . '/bootstrap/app.php';
        echo '<p style="color:green;">✅ <b>Laravel Berhasil Dimuat (Booting OK)!</b></p>';
    } else {
        echo '<p style="color:orange;">⚠️ Melewati boot Laravel karena vendor/autoload.php belum lengkap.</p>';
    }
} catch (Throwable $e) {
    echo '<p style="color:red;">❌ <b>ERROR SAAT BOOT LARAVEL:</b> ' . $e->getMessage() . '</p>';
    echo '<pre style="background: #1e293b; color: #f8fafc; padding: 12px; border-radius: 8px; font-size: 12px; overflow-x: auto;">' . $e->getFile() . ':' . $e->getLine() . "\n" . $e->getMessage() . '</pre>';
}

echo '<hr>';
echo '<p style="color: #64748b; font-size: 13px;">Setelah selesai mendiagnosa, Anda bisa menghapus file test.php ini untuk keamanan.</p>';
echo '</div>';
