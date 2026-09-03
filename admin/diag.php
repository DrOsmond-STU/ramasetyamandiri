<?php
header('Content-Type: text/plain; charset=utf-8');

$user = 'ramasety_cms';
$pass = 'YEWA55ayQCo8KDrPbfAA3IB8';
$db = 'ramasety_den821';
$sock = '/tmp/mysql.sock';

for ($i = 1; $i <= 3; $i++) {
    echo "--- Attempt $i ---\n";
    clearstatcache(true, $sock);
    echo "file_exists: " . (file_exists($sock) ? 'yes' : 'no') . "\n";
    if (file_exists($sock)) {
        echo "filetype: " . filetype($sock) . "\n";
        echo "is_readable: " . (is_readable($sock) ? 'yes' : 'no') . "\n";
        echo "is_writable: " . (is_writable($sock) ? 'yes' : 'no') . "\n";
    }
    try {
        $dsn = "mysql:unix_socket=$sock;dbname=$db;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_TIMEOUT => 3]);
        echo "PDO connect: OK\n";
        $v = $pdo->query('SELECT VERSION()')->fetchColumn();
        echo "MySQL version: $v\n";
        break;
    } catch (Throwable $e) {
        echo "PDO connect: FAIL: " . $e->getMessage() . "\n";
    }
    usleep(400000);
}

echo "\n--- mysqli fallback ---\n";
if (function_exists('mysqli_connect')) {
    $m = @mysqli_connect(null, $user, $pass, $db, null, $sock);
    if ($m) {
        echo "mysqli: OK, server info = " . $m->server_info . "\n";
    } else {
        echo "mysqli: FAIL: " . mysqli_connect_error() . "\n";
    }
} else {
    echo "mysqli not available\n";
}
