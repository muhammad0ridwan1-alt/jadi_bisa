<?php
$zipFile = __DIR__ . '/jadibisa_clean.zip';
if (file_exists($zipFile)) {
    unlink($zipFile);
}

$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("Failed to create zip archive\n");
}

$items = [
    'app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', 'storage', 'vendor',
    'artisan', 'composer.json', '.env.production', '.htaccess.production', 'index.php.production',
    'unzip.php', 'deploy_db.php', 'export_jadibisa.sql'
];

echo "Adding files to $zipFile...\n";

foreach ($items as $item) {
    $itemPath = __DIR__ . '/' . $item;
    if (file_exists($itemPath)) {
        if (is_dir($itemPath)) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($itemPath, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen(__DIR__) + 1);
                    // Force forward slashes inside ZIP archive
                    $relativePath = str_replace('\\', '/', $relativePath);
                    $zip->addFile($filePath, $relativePath);
                }
            }
        } else {
            $relativePath = str_replace('\\', '/', $item);
            $zip->addFile($itemPath, $relativePath);
        }
    }
}

$zip->close();
echo "SUCCESS: jadibisa_clean.zip created cleanly! Size: " . filesize($zipFile) . " bytes\n";
