<?php
header('Content-Type: text/plain; charset=utf-8');
echo "pdo_mysql.default_socket = " . ini_get('pdo_mysql.default_socket') . "\n";
echo "mysqli.default_socket    = " . ini_get('mysqli.default_socket') . "\n";
echo "PHP_BINARY = " . PHP_BINARY . "\n";
echo "PHP_VERSION = " . PHP_VERSION . "\n";

$candidates = [
    '/var/lib/mysql/mysql.sock',
    '/tmp/mysql.sock',
    '/var/run/mysqld/mysqld.sock',
    '/var/run/mysql/mysql.sock',
    '/opt/alt/mysql-server/var/lib/mysql/mysql.sock',
    '/home/mysql/mysql.sock',
];
foreach ($candidates as $c) {
    echo ($c) . ' => ' . (file_exists($c) ? 'EXISTS' : 'missing') . "\n";
}

echo "\n--- Trying connections ---\n";
$configs = [
    'localhost (default socket)' => ['host' => 'localhost'],
    '127.0.0.1:3306' => ['host' => '127.0.0.1'],
];
foreach ($candidates as $c) {
    if (file_exists($c)) {
        $configs["unix_socket=$c"] = ['socket' => $c];
    }
}

$user = 'ramasety_cms';
$pass = 'YEWA55ayQCo8KDrPbfAA3IB8';
$db = 'ramasety_den821';

foreach ($configs as $label => $cfg) {
    try {
        if (isset($cfg['socket'])) {
            $dsn = "mysql:unix_socket={$cfg['socket']};dbname=$db;charset=utf8mb4";
        } else {
            $dsn = "mysql:host={$cfg['host']};dbname=$db;charset=utf8mb4";
        }
        $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_TIMEOUT => 3]);
        echo "$label => OK\n";
    } catch (Throwable $e) {
        echo "$label => FAIL: " . $e->getMessage() . "\n";
    }
}
