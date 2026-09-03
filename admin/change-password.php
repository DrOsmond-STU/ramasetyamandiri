<?php
$pageTitle = 'Ganti Password';
$activeNav = 'password';
require __DIR__ . '/includes/layout-top.php';
require_once __DIR__ . '/includes/csrf.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $current = (string)($_POST['current_password'] ?? '');
    $new = (string)($_POST['new_password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');

    $stmt = cms_db()->prepare('SELECT * FROM cms_users WHERE id = ?');
    $stmt->execute([$cmsUser['id']]);
    $userRow = $stmt->fetch();

    if (!$userRow || !password_verify($current, $userRow['password_hash'])) {
        $error = 'Password saat ini salah.';
    } elseif (strlen($new) < 8) {
        $error = 'Password baru minimal 8 karakter.';
    } elseif ($new !== $confirm) {
        $error = 'Konfirmasi password baru tidak cocok.';
    } else {
        cms_change_password((int)$cmsUser['id'], $new);
        $success = 'Password berhasil diganti.';
    }
}
?>

<div class="page-header">
  <div>
    <h1>Ganti Password</h1>
    <p class="sub">Gunakan password yang kuat dan tidak dipakai di tempat lain.</p>
  </div>
</div>

<div class="card" style="max-width:480px;">
  <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
  <?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>

  <form method="post">
    <?= csrf_field() ?>
    <div class="field">
      <label for="current_password">Password Saat Ini</label>
      <input type="password" id="current_password" name="current_password" autocomplete="current-password" required>
    </div>
    <div class="field">
      <label for="new_password">Password Baru</label>
      <input type="password" id="new_password" name="new_password" autocomplete="new-password" minlength="8" required>
      <div class="hint">Minimal 8 karakter.</div>
    </div>
    <div class="field">
      <label for="confirm_password">Konfirmasi Password Baru</label>
      <input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password" minlength="8" required>
    </div>
    <button type="submit" class="btn">Simpan Password Baru</button>
  </form>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
