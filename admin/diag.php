<?php
header('Content-Type: text/plain; charset=utf-8');
echo "gethostname() = " . gethostname() . "\n";
echo "php_uname()   = " . php_uname() . "\n";
echo "SERVER_NAME   = " . ($_SERVER['SERVER_NAME'] ?? '') . "\n";
echo "SERVER_ADDR   = " . ($_SERVER['SERVER_ADDR'] ?? '') . "\n";
echo "SERVER_ADMIN  = " . ($_SERVER['SERVER_ADMIN'] ?? '') . "\n";
echo "DOCUMENT_ROOT = " . ($_SERVER['DOCUMENT_ROOT'] ?? '') . "\n";
echo "\n";

$user = 'ramasety_cms';
$pass = 'YEWA55ayQCo8KDrPbfAA3IB8';
$db = 'ramasety_den821';

$hostCandidates = array_unique(array_filter([
    'localhost',
    '127.0.0.1',
    gethostname(),
    $_SERVER['SERVER_NAME'] ?? null,
    $_SERVER['SERVER_ADDR'] ?? null,
    'mysql.ramasetyamandiri.com',
    'mysql.' . ($_SERVER['SERVER_NAME'] ?? ''),
]));

echo "--- Trying connections ---\n";
foreach ($hostCandidates as $host) {
    try {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_TIMEOUT => 3]);
        echo "$host => OK\n";
    } catch (Throwable $e) {
        echo "$host => FAIL: " . $e->getMessage() . "\n";
    }
}
