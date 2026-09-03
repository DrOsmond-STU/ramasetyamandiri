<?php
/**
 * Autentikasi admin berbasis session + password_hash.
 */

require_once __DIR__ . '/db.php';

function cms_session_start(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        $config = cms_config();
        session_name($config['session_name'] ?? 'rsm_admin_sess');
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

function cms_current_user(): ?array
{
    cms_session_start();
    return $_SESSION['cms_user'] ?? null;
}

/** Panggil di awal halaman admin yang wajib login. Mengembalikan data user. */
function cms_require_login(): array
{
    $user = cms_current_user();
    if (!$user) {
        header('Location: login.php');
        exit;
    }
    return $user;
}

function cms_login(string $username, string $password): bool
{
    $pdo = cms_db();
    $stmt = $pdo->prepare('SELECT * FROM cms_users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        cms_session_start();
        session_regenerate_id(true);
        $_SESSION['cms_user'] = [
            'id' => (int)$user['id'],
            'username' => $user['username'],
            'must_change_password' => (bool)$user['must_change_password'],
        ];
        return true;
    }

    // Perlambat sedikit untuk mengurangi brute-force / timing attack.
    usleep(300000);
    return false;
}

function cms_logout(): void
{
    cms_session_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function cms_change_password(int $userId, string $newPassword): void
{
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = cms_db()->prepare('UPDATE cms_users SET password_hash = ?, must_change_password = 0 WHERE id = ?');
    $stmt->execute([$hash, $userId]);
    if (isset($_SESSION['cms_user']) && $_SESSION['cms_user']['id'] === $userId) {
        $_SESSION['cms_user']['must_change_password'] = false;
    }
}
