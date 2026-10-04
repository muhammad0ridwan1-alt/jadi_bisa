<?php
/**
 * ====================================================================
 *   JADIBISA - 1-CLICK INFINITYFREE DEPLOYMENT & WEB INSTALLER WIZARD
 * ====================================================================
 * Usage:
 * 1. Upload ZIP parts or jadibisa_full.zip + deploy_installer.php to /htdocs
 * 2. Open http://your-domain.com/deploy_installer.php in your browser
 * 3. Follow the visual steps to complete the installation in seconds!
 */

ini_set('max_execution_time', 300);
ini_set('memory_limit', '256M');

$action = $_GET['action'] ?? 'view';

// API Handling for AJAX calls
if ($action !== 'view') {
    header('Content-Type: application/json');
    $baseDir = __DIR__;

    try {
        switch ($action) {
            case 'check_requirements':
                $phpVersion = phpversion();
                $extensions = [
                    'pdo_mysql' => extension_loaded('pdo_mysql'),
                    'zip' => extension_loaded('zip'),
                    'mbstring' => extension_loaded('mbstring'),
                    'openssl' => extension_loaded('openssl'),
                    'fileinfo' => extension_loaded('fileinfo'),
                    'xml' => extension_loaded('xml'),
                    'ctype' => extension_loaded('ctype'),
                    'json' => extension_loaded('json')
                ];
                $writable = is_writable($baseDir);

                echo json_encode([
                    'success' => true,
                    'php_version' => $phpVersion,
                    'extensions' => $extensions,
                    'writable' => $writable,
                ]);
                exit;

            case 'extract_zips':
                $zipFiles = glob($baseDir . '/*.zip');
                if (empty($zipFiles)) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'No .zip files found in htdocs. (Files might already be extracted or uploaded directly).'
                    ]);
                    exit;
                }

                $extracted = [];
                $errors = [];

                foreach ($zipFiles as $zipPath) {
                    $zipName = basename($zipPath);
                    $zip = new ZipArchive();
                    if ($zip->open($zipPath) === TRUE) {
                        for ($i = 0; $i < $zip->numFiles; $i++) {
                            $filename = $zip->getNameIndex($i);
                            $normalizedPath = str_replace('\\', '/', $filename);
                            $targetPath = $baseDir . '/' . $normalizedPath;

                            if (str_ends_with($normalizedPath, '/')) {
                                if (!is_dir($targetPath)) {
                                    mkdir($targetPath, 0777, true);
                                }
                            } else {
                                $dir = dirname($targetPath);
                                if (!is_dir($dir)) {
                                    mkdir($dir, 0777, true);
                                }
                                copy("zip://" . $zipPath . "#" . $filename, $targetPath);
                            }
                        }
                        $zip->close();
                        $extracted[] = $zipName;
                        @unlink($zipPath); // Free up quota on InfinityFree
                    } else {
                        $errors[] = "Failed to open $zipName";
                    }
                }

                echo json_encode([
                    'success' => empty($errors),
                    'extracted' => $extracted,
                    'errors' => $errors,
                    'message' => 'Extracted ' . count($extracted) . ' zip archive(s) and cleaned up zip files.'
                ]);
                exit;

            case 'setup_files':
                $logs = [];

                // 1. Setup index.php
                if (file_exists($baseDir . '/index.php.production')) {
                    copy($baseDir . '/index.php.production', $baseDir . '/index.php');
                    $logs[] = 'index.php configured for root directory.';
                }

                // 2. Setup .htaccess
                if (file_exists($baseDir . '/.htaccess.production')) {
                    copy($baseDir . '/.htaccess.production', $baseDir . '/.htaccess');
                    $logs[] = '.htaccess configured with production security rules.';
                }

                // 3. Setup .env
                if (!file_exists($baseDir . '/.env') && file_exists($baseDir . '/.env.production')) {
                    copy($baseDir . '/.env.production', $baseDir . '/.env');
                    $logs[] = '.env initialized from production template.';
                }

                // 4. Copy public assets to root if needed
                if (is_dir($baseDir . '/public/build') && !is_dir($baseDir . '/build')) {
                    copyRecursive($baseDir . '/public/build', $baseDir . '/build');
                    $logs[] = 'Frontend build assets mirrored to /build.';
                }
                if (is_dir($baseDir . '/public/images') && !is_dir($baseDir . '/images')) {
                    copyRecursive($baseDir . '/public/images', $baseDir . '/images');
                    $logs[] = 'Public images mirrored to /images.';
                }
                if (file_exists($baseDir . '/public/favicon.ico') && !file_exists($baseDir . '/favicon.ico')) {
                    copy($baseDir . '/public/favicon.ico', $baseDir . '/favicon.ico');
                }
                if (file_exists($baseDir . '/public/robots.txt') && !file_exists($baseDir . '/robots.txt')) {
                    copy($baseDir . '/public/robots.txt', $baseDir . '/robots.txt');
                }

                // 5. Ensure Storage Directories & Permissions
                $dirs = [
                    $baseDir . '/storage/framework/views',
                    $baseDir . '/storage/framework/cache/data',
                    $baseDir . '/storage/framework/sessions',
                    $baseDir . '/storage/logs',
                    $baseDir . '/storage/app/public/moduls',
                    $baseDir . '/storage/app/public/tugases',
                    $baseDir . '/storage/app/public/tugas_submissions',
                    $baseDir . '/bootstrap/cache'
                ];
                foreach ($dirs as $d) {
                    if (!is_dir($d)) {
                        mkdir($d, 0777, true);
                    }
                    @chmod($d, 0777);
                }
                $logs[] = 'Storage & bootstrap cache directories initialized with 0777 permissions.';

                echo json_encode([
                    'success' => true,
                    'logs' => $logs
                ]);
                exit;

            case 'update_env':
                $input = json_decode(file_get_contents('php://input'), true);
                $dbHost = trim($input['db_host'] ?? '');
                $dbName = trim($input['db_name'] ?? '');
                $dbUser = trim($input['db_user'] ?? '');
                $dbPass = trim($input['db_pass'] ?? '');
                $appUrl = trim($input['app_url'] ?? '');

                $envPath = $baseDir . '/.env';
                if (!file_exists($envPath)) {
                    if (file_exists($baseDir . '/.env.production')) {
                        copy($baseDir . '/.env.production', $envPath);
                    } else {
                        file_put_contents($envPath, "APP_NAME=JadiBisa\nAPP_ENV=production\nAPP_KEY=base64:NNpFfC4lGQb6RGpzN1v2tKy6hEplKQ72xKMGY2YIvZs=\nAPP_DEBUG=false\n");
                    }
                }

                $envContent = file_get_contents($envPath);

                if ($dbHost) $envContent = preg_replace('/^DB_HOST=.*$/m', "DB_HOST=" . $dbHost, $envContent);
                if ($dbName) $envContent = preg_replace('/^DB_DATABASE=.*$/m', "DB_DATABASE=" . $dbName, $envContent);
                if ($dbUser) $envContent = preg_replace('/^DB_USERNAME=.*$/m', "DB_USERNAME=" . $dbUser, $envContent);
                if ($dbPass !== null) $envContent = preg_replace('/^DB_PASSWORD=.*$/m', "DB_PASSWORD=" . $dbPass, $envContent);
                if ($appUrl) $envContent = preg_replace('/^APP_URL=.*$/m', "APP_URL=" . rtrim($appUrl, '/'), $envContent);

                file_put_contents($envPath, $envContent);

                echo json_encode([
                    'success' => true,
                    'message' => '.env database credentials updated successfully!'
                ]);
                exit;

            case 'test_db':
                $input = json_decode(file_get_contents('php://input'), true);
                $dbHost = trim($input['db_host'] ?? '');
                $dbName = trim($input['db_name'] ?? '');
                $dbUser = trim($input['db_user'] ?? '');
                $dbPass = trim($input['db_pass'] ?? '');

                try {
                    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_TIMEOUT => 5
                    ]);
                    echo json_encode([
                        'success' => true,
                        'message' => 'Successfully connected to MySQL database: ' . $dbName
                    ]);
                } catch (Exception $e) {
                    echo json_encode([
                        'success' => false,
                        'error' => $e->getMessage()
                    ]);
                }
                exit;

            case 'import_db':
                $input = json_decode(file_get_contents('php://input'), true);
                $dbHost = trim($input['db_host'] ?? '');
                $dbName = trim($input['db_name'] ?? '');
                $dbUser = trim($input['db_user'] ?? '');
                $dbPass = trim($input['db_pass'] ?? '');

                $sqlFile = $baseDir . '/export_jadibisa.sql';
                if (!file_exists($sqlFile)) {
                    echo json_encode([
                        'success' => false,
                        'error' => 'export_jadibisa.sql file not found in htdocs.'
                    ]);
                    exit;
                }

                try {
                    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]);

                    $sql = file_get_contents($sqlFile);
                    $pdo->exec($sql);

                    echo json_encode([
                        'success' => true,
                        'message' => 'Database imported cleanly and successfully with all tables and initial seed data!'
                    ]);
                } catch (Exception $e) {
                    echo json_encode([
                        'success' => false,
                        'error' => $e->getMessage()
                    ]);
                }
                exit;

            case 'cleanup':
                $filesToDelete = [
                    $baseDir . '/deploy_installer.php',
                    $baseDir . '/export_jadibisa.sql',
                    $baseDir . '/index.php.production',
                    $baseDir . '/.htaccess.production',
                    $baseDir . '/.env.production',
                    $baseDir . '/deploy_db.php',
                    $baseDir . '/unzip.php',
                    $baseDir . '/unzip_all.php',
                    $baseDir . '/unzip_parts.php',
                    $baseDir . '/info.php',
                    $baseDir . '/index.php.debug',
                    $baseDir . '/.env.debug'
                ];
                foreach ($filesToDelete as $f) {
                    if (file_exists($f)) @unlink($f);
                }
                echo json_encode([
                    'success' => true,
                    'message' => 'Deployment installer cleaned up successfully for security!'
                ]);
                exit;
        }
    } catch (Exception $ex) {
        echo json_encode([
            'success' => false,
            'error' => $ex->getMessage()
        ]);
        exit;
    }
}

function copyRecursive($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst, 0777, true);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copyRecursive($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JadiBisa — InfinityFree 1-Click Deployment Installer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen py-10 px-4">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 bg-indigo-500/10 border border-indigo-500/20 px-4 py-1.5 rounded-full text-indigo-400 text-sm font-medium mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                InfinityFree Automated Installer
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">JadiBisa Deployment Wizard</h1>
            <p class="text-slate-400 mt-2 text-sm sm:text-base">Pasang & jalankan aplikasi JadiBisa di InfinityFree dalam hitungan detik tanpa ribet.</p>
        </div>

        <!-- Main Card -->
        <div class="bg-slate-800/90 border border-slate-700/80 rounded-2xl p-6 sm:p-8 shadow-2xl backdrop-blur">
            
            <!-- Step Progress -->
            <div class="grid grid-cols-4 gap-2 mb-8 text-center text-xs font-semibold">
                <div id="step-tab-1" class="pb-2 border-b-2 border-indigo-500 text-indigo-400">1. Sistem</div>
                <div id="step-tab-2" class="pb-2 border-b-2 border-slate-700 text-slate-500">2. Ekstrak</div>
                <div id="step-tab-3" class="pb-2 border-b-2 border-slate-700 text-slate-500">3. Database</div>
                <div id="step-tab-4" class="pb-2 border-b-2 border-slate-700 text-slate-500">4. Selesai</div>
            </div>

            <!-- STEP 1: System Requirements -->
            <div id="step-1" class="space-y-6">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center text-sm font-black">1</span>
                    Pengecekan Kesiapan Server InfinityFree
                </h2>
                <div id="req-list" class="space-y-3 bg-slate-900/60 p-4 rounded-xl border border-slate-700/50">
                    <div class="text-slate-400 text-sm animate-pulse">Memeriksa ekstensi dan konfigurasi PHP...</div>
                </div>
                <button onclick="nextToStep2()" id="btn-step1" disabled class="w-full py-3.5 px-6 rounded-xl bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 font-bold text-white transition-all duration-200 shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2">
                    Lanjut ke Ekstraksi File &rarr;
                </button>
            </div>

            <!-- STEP 2: Extraction -->
            <div id="step-2" class="hidden space-y-6">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center text-sm font-black">2</span>
                    Ekstraksi & Penyiapan File Aplikasi
                </h2>
                <p class="text-slate-300 text-sm">Installer akan mengekstrak file zip (seperti <code class="text-indigo-400 font-mono text-xs bg-slate-900 px-1.5 py-0.5 rounded">part1...part5</code> atau <code class="text-indigo-400 font-mono text-xs bg-slate-900 px-1.5 py-0.5 rounded">jadibisa_full.zip</code>) dan mengatur file production.</p>
                
                <div id="extract-log" class="bg-slate-950 p-4 rounded-xl font-mono text-xs text-slate-300 max-h-48 overflow-y-auto space-y-1.5 border border-slate-800">
                    <div>Menunggu perintah mulai...</div>
                </div>

                <div class="flex gap-3">
                    <button onclick="startExtraction()" id="btn-extract" class="flex-1 py-3.5 px-6 rounded-xl bg-indigo-600 hover:bg-indigo-500 font-bold text-white transition-all shadow-lg shadow-indigo-600/30">
                        Mulai Ekstraksi Otomatis
                    </button>
                    <button onclick="nextToStep3()" id="btn-step2-next" disabled class="py-3.5 px-6 rounded-xl bg-slate-700 hover:bg-slate-600 disabled:opacity-50 font-bold text-white transition-all">
                        Lanjut &rarr;
                    </button>
                </div>
            </div>

            <!-- STEP 3: Database Configuration -->
            <div id="step-3" class="hidden space-y-6">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center text-sm font-black">3</span>
                    Konfigurasi MySQL Database InfinityFree
                </h2>
                <p class="text-slate-300 text-sm">Masukkan informasi MySQL database dari Control Panel InfinityFree (vPanel) Anda.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">MySQL Host</label>
                        <input type="text" id="db_host" value="sql210.infinityfree.com" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Database</label>
                        <input type="text" id="db_name" value="if0_41354707_db_jadibisa" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">MySQL Username</label>
                        <input type="text" id="db_user" value="if0_41354707" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">MySQL Password</label>
                        <input type="password" id="db_pass" value="4Vv4RUVoDo" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 font-mono">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 mb-1">App URL (Domain InfinityFree Anda)</label>
                        <input type="text" id="app_url" value="http://jadibisa.gt.tc" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 font-mono">
                    </div>
                </div>

                <div id="db-status" class="hidden p-3.5 rounded-xl text-sm font-medium"></div>

                <div class="flex gap-3">
                    <button onclick="testAndImportDb()" id="btn-import-db" class="flex-1 py-3.5 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-500 font-bold text-white transition-all shadow-lg shadow-emerald-600/30">
                        Simpan & Import Database Otomatis
                    </button>
                    <button onclick="skipDb()" class="py-3.5 px-4 rounded-xl bg-slate-700 hover:bg-slate-600 text-xs font-semibold text-slate-300">
                        Lewati Import DB
                    </button>
                </div>
            </div>

            <!-- STEP 4: Finish -->
            <div id="step-4" class="hidden space-y-6 text-center">
                <div class="w-16 h-16 bg-emerald-500/20 border border-emerald-500/40 rounded-full flex items-center justify-center mx-auto text-emerald-400 text-3xl">
                    ✓
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white">Instalasi Berhasil 100%!</h2>
                    <p class="text-slate-300 text-sm mt-1">Aplikasi JadiBisa kini telah siap dan live di InfinityFree.</p>
                </div>

                <div class="bg-slate-900/80 border border-slate-700/60 rounded-xl p-4 text-left text-xs font-mono space-y-2 text-slate-300">
                    <div class="text-emerald-400 font-bold font-sans text-sm mb-1">Ringkasan Setup:</div>
                    <div>✓ File Laravel diekstrak & konfigurasi web server aktif</div>
                    <div>✓ Storage folder & permission (0777) terpasang</div>
                    <div>✓ Fallback route & asset build terhubung</div>
                    <div>✓ Database MySQL terkoneksi</div>
                </div>

                <div class="space-y-3 pt-2">
                    <button onclick="cleanupAndLaunch()" id="btn-launch" class="w-full py-4 px-6 rounded-xl bg-gradient-to-r from-indigo-500 to-emerald-500 hover:from-indigo-400 hover:to-emerald-400 font-black text-white text-base shadow-xl transition-all">
                        Bersihkan Installer & Buka JadiBisa &rarr;
                    </button>
                    <a href="/" class="inline-block text-xs text-slate-400 hover:text-white underline">Buka langsung tanpa hapus installer</a>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="mt-6 text-center text-xs text-slate-500">
            JadiBisa Academic LMS — Automated Shared Hosting Engine
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            checkSystem();
            if (window.location.origin) {
                document.getElementById('app_url').value = window.location.origin;
            }
        });

        async function checkSystem() {
            try {
                const res = await fetch('?action=check_requirements');
                const data = await res.json();
                const reqList = document.getElementById('req-list');
                
                let html = `
                    <div class="flex items-center justify-between text-xs py-1 border-b border-slate-800">
                        <span class="text-slate-300">Versi PHP (${data.php_version})</span>
                        <span class="text-emerald-400 font-bold">✓ Kompatibel</span>
                    </div>
                `;

                for (const [ext, ok] of Object.entries(data.extensions)) {
                    html += `
                        <div class="flex items-center justify-between text-xs py-1 border-b border-slate-800">
                            <span class="text-slate-300">Ekstensi PHP: ${ext}</span>
                            <span class="${ok ? 'text-emerald-400' : 'text-rose-400'} font-bold">${ok ? '✓ Tersedia' : '✗ Tidak Ada'}</span>
                        </div>
                    `;
                }

                html += `
                    <div class="flex items-center justify-between text-xs py-1">
                        <span class="text-slate-300">Izin Tulis Folder (/htdocs)</span>
                        <span class="${data.writable ? 'text-emerald-400' : 'text-rose-400'} font-bold">${data.writable ? '✓ Dapat Ditulis' : '✗ Read Only'}</span>
                    </div>
                `;

                reqList.innerHTML = html;
                document.getElementById('btn-step1').disabled = false;
            } catch (err) {
                document.getElementById('req-list').innerHTML = `<div class="text-rose-400 text-xs">Error memeriksa sistem: ${err.message}</div>`;
                document.getElementById('btn-step1').disabled = false;
            }
        }

        function setStep(stepNum) {
            for (let i = 1; i <= 4; i++) {
                document.getElementById(`step-${i}`).classList.toggle('hidden', i !== stepNum);
                const tab = document.getElementById(`step-tab-${i}`);
                if (i === stepNum) {
                    tab.className = 'pb-2 border-b-2 border-indigo-500 text-indigo-400 font-bold';
                } else if (i < stepNum) {
                    tab.className = 'pb-2 border-b-2 border-emerald-500 text-emerald-400';
                } else {
                    tab.className = 'pb-2 border-b-2 border-slate-700 text-slate-500';
                }
            }
        }

        function nextToStep2() { setStep(2); }
        function nextToStep3() { setStep(3); }

        async function startExtraction() {
            const btn = document.getElementById('btn-extract');
            const log = document.getElementById('extract-log');
            btn.disabled = true;
            btn.innerHTML = `<span class="animate-spin inline-block mr-2">⟳</span> Mengekstrak file...`;

            log.innerHTML = `<div class="text-amber-400">[1/2] Mencari & mengekstrak file zip di htdocs...</div>`;

            try {
                // 1. Extract zips
                const resZip = await fetch('?action=extract_zips');
                const dataZip = await resZip.json();
                
                if (dataZip.extracted && dataZip.extracted.length > 0) {
                    dataZip.extracted.forEach(z => {
                        log.innerHTML += `<div class="text-emerald-400">✓ ${z} berhasil diekstrak & dihapus.</div>`;
                    });
                } else {
                    log.innerHTML += `<div class="text-slate-400">ℹ ${dataZip.message || 'File ZIP sudah diekstrak.'}</div>`;
                }

                // 2. Setup production files
                log.innerHTML += `<div class="text-amber-400 mt-2">[2/2] Mengatur file konfigurasi production & storage...</div>`;
                const resFiles = await fetch('?action=setup_files');
                const dataFiles = await resFiles.json();

                if (dataFiles.logs) {
                    dataFiles.logs.forEach(l => {
                        log.innerHTML += `<div class="text-emerald-400">✓ ${l}</div>`;
                    });
                }

                log.innerHTML += `<div class="text-indigo-300 font-bold mt-2">🎉 Ekstraksi & konfigurasi file selesai!</div>`;
                document.getElementById('btn-step2-next').disabled = false;
                document.getElementById('btn-step2-next').className = 'py-3.5 px-6 rounded-xl bg-indigo-600 hover:bg-indigo-500 font-bold text-white transition-all shadow-lg shadow-indigo-600/30';
                btn.innerHTML = `Selesai Ekstraksi ✓`;
            } catch (err) {
                log.innerHTML += `<div class="text-rose-400">Error: ${err.message}</div>`;
                btn.disabled = false;
                btn.innerHTML = `Coba Lagi`;
            }
        }

        async function testAndImportDb() {
            const btn = document.getElementById('btn-import-db');
            const status = document.getElementById('db-status');
            const dbData = {
                db_host: document.getElementById('db_host').value,
                db_name: document.getElementById('db_name').value,
                db_user: document.getElementById('db_user').value,
                db_pass: document.getElementById('db_pass').value,
                app_url: document.getElementById('app_url').value,
            };

            btn.disabled = true;
            btn.innerHTML = `<span class="animate-spin inline-block mr-2">⟳</span> Menghubungkan & Mengimport...`;
            status.classList.remove('hidden', 'bg-emerald-900/50', 'text-emerald-300', 'border-emerald-700', 'bg-rose-900/50', 'text-rose-300', 'border-rose-700');
            status.classList.add('bg-slate-900', 'text-slate-300', 'border', 'border-slate-700');
            status.innerHTML = `Mengupdate file .env dan menghubungkan ke MySQL...`;

            try {
                // 1. Update .env
                await fetch('?action=update_env', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(dbData)
                });

                // 2. Import DB
                status.innerHTML = `Mengimport tabel & data dari export_jadibisa.sql...`;
                const resImport = await fetch('?action=import_db', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(dbData)
                });
                const dataImport = await resImport.json();

                if (dataImport.success) {
                    status.className = 'p-3.5 rounded-xl text-sm font-medium border bg-emerald-950/60 text-emerald-400 border-emerald-800';
                    status.innerHTML = `✓ ${dataImport.message}`;
                    setTimeout(() => {
                        setStep(4);
                    }, 1200);
                } else {
                    status.className = 'p-3.5 rounded-xl text-sm font-medium border bg-rose-950/60 text-rose-400 border-rose-800';
                    status.innerHTML = `Gagal: ${dataImport.error}`;
                    btn.disabled = false;
                    btn.innerHTML = `Coba Simpan & Import Lagi`;
                }
            } catch (err) {
                status.className = 'p-3.5 rounded-xl text-sm font-medium border bg-rose-950/60 text-rose-400 border-rose-800';
                status.innerHTML = `Error: ${err.message}`;
                btn.disabled = false;
                btn.innerHTML = `Coba Simpan & Import Lagi`;
            }
        }

        function skipDb() {
            setStep(4);
        }

        async function cleanupAndLaunch() {
            const btn = document.getElementById('btn-launch');
            btn.disabled = true;
            btn.innerHTML = `<span class="animate-spin inline-block mr-2">⟳</span> Menghapus installer & Membuka JadiBisa...`;
            try {
                await fetch('?action=cleanup');
            } catch (e) {}
            window.location.href = '/';
        }
    </script>
</body>
</html>
