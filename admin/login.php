<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';

if (cms_current_user()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $username = trim($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    if ($username !== '' && $password !== '' && cms_login($username, $password)) {
        header('Location: index.php');
        exit;
    }
    $error = 'Username atau password salah.';
}

// Halaman login sengaja dibuat mandiri total: seluruh CSS ditanam inline,
// tanpa font/stylesheet eksternal, supaya tetap tampil utuh walau ada
// proxy, cache, atau optimizer yang memodifikasi/ memblokir resource luar.
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('X-LiteSpeed-Cache-Control: no-cache');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Masuk — CMS Rama Setya Mandiri</title>
<style>
* { box-sizing: border-box; }
body {
  margin: 0;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 24px;
  background: #0b5fa5;
  background: linear-gradient(135deg, #0a2a43, #0b5fa5);
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
  color: #16324a;
  line-height: 1.6;
}
.auth-card {
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 10px 30px -14px rgba(10, 42, 67, .45);
  padding: 36px 32px;
  width: 100%;
  max-width: 380px;
}
h1 {
  font-family: Georgia, "Times New Roman", serif;
  color: #0a2a43;
  font-size: 1.4rem;
  margin: 0 0 .2em;
}
p.sub { color: #5b7286; font-size: .88rem; margin: 0 0 1.6em; }
label { display: block; font-weight: 600; font-size: .85rem; margin-bottom: 6px; color: #0a2a43; }
.field { margin-bottom: 18px; }
input[type=text], input[type=password] {
  display: block;
  width: 100%;
  padding: 11px 12px;
  border: 1px solid #dfe6ec;
  border-radius: 8px;
  font-family: inherit;
  font-size: .95rem;
  color: #16324a;
  background: #ffffff;
}
input[type=text]:focus, input[type=password]:focus {
  outline: none;
  border-color: #2e8bc0;
  box-shadow: 0 0 0 3px rgba(46,139,192,.18);
}
button {
  display: block;
  width: 100%;
  padding: 12px 22px;
  border: 0;
  border-radius: 999px;
  font-family: inherit;
  font-weight: 600;
  font-size: .92rem;
  color: #ffffff;
  background: #0b5fa5;
  cursor: pointer;
}
button:hover { background: #0a2a43; }
.alert {
  padding: 11px 14px;
  border-radius: 8px;
  margin-bottom: 18px;
  font-size: .88rem;
  background: #fdecea;
  color: #c0392b;
}
</style>
</head>
<body>
  <div class="auth-card">
    <h1>Panel Admin</h1>
    <p class="sub">PT Rama Setya Mandiri — masuk untuk mengelola konten situs.</p>

    <?php if ($error !== ''): ?>
      <div class="alert"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= e(basename($_SERVER['SCRIPT_NAME'] ?? 'login.php')) ?>">
      <?= csrf_field() ?>
      <div class="field">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" autocomplete="username" autofocus>
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" autocomplete="current-password">
      </div>
      <button type="submit">Masuk</button>
    </form>
  </div>
</body>
</html>
