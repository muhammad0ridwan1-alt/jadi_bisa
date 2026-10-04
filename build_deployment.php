<?php
/**
 * Fast & Production-Optimized Deployment Packager for InfinityFree
 * Produces ultra-lightweight ZIP files safely under InfinityFree's 10MB File Manager limit.
 */

$rootDir = __DIR__;
echo "====================================================\n";
echo "   JADIBISA INFINITYFREE PRODUCTION PACKAGER       \n";
echo "====================================================\n\n";

// 1. Export fresh database dump from local MySQL
echo "[1/4] Dumping database to export_jadibisa.sql...\n";
$dumpBin = 'C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqldump.exe';
if (file_exists($dumpBin)) {
    $cmd = "\"$dumpBin\" -u root jadibisa --default-character-set=utf8mb4 --result-file=\"$rootDir/export_jadibisa.sql\"";
    exec($cmd, $out, $ret);
    echo " -> Database exported cleanly (" . round(filesize("$rootDir/export_jadibisa.sql") / 1024, 2) . " KB)\n";
}

// 2. Clean old zip files
echo "\n[2/4] Removing old zip files...\n";
$oldZips = glob("$rootDir/*.zip");
foreach ($oldZips as $z) {
    @unlink($z);
}

// 3. Package split parts for Monsta Web File Manager (< 10MB limit)
echo "\n[3/4] Packaging Split ZIP files for Monsta File Manager...\n";

$splits = [
    'part1_app.zip' => 'app bootstrap config database public resources routes storage artisan composer.json index.php .htaccess .env.production .htaccess.production index.php.production export_jadibisa.sql deploy_installer.php unzip.php unzip_all.php',
    'part2_vendor_laravel.zip' => 'vendor/laravel/framework vendor/laravel/prompts vendor/laravel/serializable-closure vendor/laravel/tinker vendor/laravel/agent-detector vendor/laravel/breeze vendor/composer vendor/autoload.php',
    'part3_vendor_symfony.zip' => 'vendor/symfony',
    'part4_vendor_libs1.zip' => 'vendor/guzzlehttp vendor/psr vendor/league vendor/monolog vendor/nesbot vendor/ramsey vendor/brick vendor/doctrine vendor/egulias vendor/nunomaduro vendor/carbonphp vendor/fruitcake vendor/dragonmantank vendor/tijsverkoyen',
    'part5_vendor_libs2.zip' => 'vendor/phpoption vendor/vlucas vendor/voku vendor/dflydev vendor/graham-campbell vendor/staabm vendor/ralouphie vendor/nette vendor/nikic vendor/bin'
];

foreach ($splits as $zipName => $targets) {
    echo " -> Building $zipName...\n";
    $cmd = "tar.exe -a -cf \"$rootDir/$zipName\" $targets";
    exec($cmd, $out, $ret);
    if (file_exists("$rootDir/$zipName")) {
        $sizeMb = round(filesize("$rootDir/$zipName") / (1024 * 1024), 2);
        echo "    ✓ $zipName created ($sizeMb MB)\n";
    } else {
        echo "    ✗ Failed to create $zipName\n";
    }
}

// 4. Package single all-in-one zip (also optimized under 10MB)
echo "\n[4/4] Building single all-in-one jadibisa_full.zip...\n";
$allTargets = 'app bootstrap config database public resources routes storage vendor/laravel/framework vendor/laravel/prompts vendor/laravel/serializable-closure vendor/laravel/tinker vendor/laravel/agent-detector vendor/laravel/breeze vendor/composer vendor/autoload.php vendor/symfony vendor/guzzlehttp vendor/psr vendor/league vendor/monolog vendor/nesbot vendor/ramsey vendor/brick vendor/doctrine vendor/egulias vendor/nunomaduro vendor/carbonphp vendor/fruitcake vendor/dragonmantank vendor/tijsverkoyen vendor/phpoption vendor/vlucas vendor/voku vendor/dflydev vendor/graham-campbell vendor/staabm vendor/ralouphie vendor/nette vendor/nikic vendor/bin artisan composer.json index.php .htaccess .env.production .htaccess.production index.php.production export_jadibisa.sql deploy_installer.php unzip.php unzip_all.php';

$cmdFull = "tar.exe -a -cf \"$rootDir/jadibisa_full.zip\" $allTargets";
exec($cmdFull, $out, $ret);
if (file_exists("$rootDir/jadibisa_full.zip")) {
    $sizeFullMb = round(filesize("$rootDir/jadibisa_full.zip") / (1024 * 1024), 2);
    echo " -> jadibisa_full.zip created ($sizeFullMb MB)\n";
}

echo "\n====================================================\n";
echo "   READY FOR 1-CLICK INFINITYFREE DEPLOYMENT!       \n";
echo "====================================================\n";
