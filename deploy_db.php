<?php
header('Content-Type: text/plain');
$host = 'sql210.infinityfree.com';
$user = 'if0_41354707';
$pass = '4Vv4RUVoDo';
$db   = 'if0_41354707_db_jadibisa';

echo "Connecting to $db at $host...
";
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Connected successfully!
";

    $sql = file_get_contents(__DIR__ . '/export_jadibisa.sql');
    if (!$sql) die("Error: export_jadibisa.sql not found!
");

    echo "Executing SQL import...
";
    $pdo->exec($sql);
    echo "SUCCESS: Database imported cleanly!
";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "
";
}
