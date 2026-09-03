<?php
header('Content-Type: text/plain; charset=utf-8');
echo "open_basedir = " . ini_get('open_basedir') . "\n";
echo "disable_functions = " . ini_get('disable_functions') . "\n\n";

echo "--- glob searches ---\n";
foreach (['/var/lib/mysql/*.sock', '/tmp/*.sock', '/run/*mysql*', '/var/run/*mysql*', '/*.sock'] as $pattern) {
    $r = @glob($pattern);
    echo "$pattern => " . ($r === false ? 'blocked/false' : (empty($r) ? '(none)' : implode(', ', $r))) . "\n";
}

echo "\n--- shell_exec find ---\n";
if (function_exists('shell_exec')) {
    $out = @shell_exec('find / -maxdepth 4 -iname "*mysql*.sock" 2>/dev/null');
    echo $out === null ? '(blocked or empty)' : $out;
} else {
    echo "shell_exec disabled\n";
}

$user = 'ramasety_cms';
$pass = 'YEWA55ayQCo8KDrPbfAA3IB8';
$db = 'ramasety_den821';

echo "\n--- Trying more hosts ---\n";
foreach (['mysql', 'db', 'database', 'localhost:3306'] as $host) {
    try {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_TIMEOUT => 3]);
        echo "$host => OK\n";
    } catch (Throwable $e) {
        echo "$host => FAIL: " . $e->getMessage() . "\n";
    }
}
