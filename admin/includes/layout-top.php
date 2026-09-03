<?php
/**
 * Header + sidebar bersama untuk semua halaman admin.
 * Wajib set $pageTitle sebelum include, dan $activeNav (opsional) untuk highlight menu.
 */
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';
$cmsUser = cms_require_login();
$activeNav = $activeNav ?? '';

function nav_class(string $key, string $active): string
{
    return $key === $active ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle ?? 'Admin') ?> — CMS Rama Setya Mandiri</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<div class="admin-shell">
  <aside class="admin-sidebar">
    <div class="brand">Rama Setya Mandiri<span>Panel Admin</span></div>

    <a href="index.php" class="<?= nav_class('dashboard', $activeNav) ?>">Dashboard</a>

    <div class="admin-nav-group">Pengaturan</div>
    <a href="settings.php" class="<?= nav_class('settings', $activeNav) ?>">Identitas &amp; Logo</a>

    <div class="admin-nav-group">Konten Halaman</div>
    <a href="content.php?section=stats" class="<?= nav_class('stats', $activeNav) ?>">Statistik</a>
    <a href="content.php?section=services" class="<?= nav_class('services', $activeNav) ?>">Layanan</a>
    <a href="content.php?section=fleet" class="<?= nav_class('fleet', $activeNav) ?>">Armada</a>
    <a href="content.php?section=process" class="<?= nav_class('process', $activeNav) ?>">Mengapa Kami</a>
    <a href="content.php?section=partners_aviation" class="<?= nav_class('partners_aviation', $activeNav) ?>">Mitra — Transportasi</a>
    <a href="content.php?section=partners_ground" class="<?= nav_class('partners_ground', $activeNav) ?>">Mitra — Ground Handling</a>
    <a href="content.php?section=partner_logos" class="<?= nav_class('partner_logos', $activeNav) ?>">Logo Mitra</a>
    <a href="content.php?section=destinations" class="<?= nav_class('destinations', $activeNav) ?>">Portofolio</a>

    <div class="admin-nav-group">Akun</div>
    <a href="change-password.php" class="<?= nav_class('password', $activeNav) ?>">Ganti Password</a>
    <a href="../index.php" target="_blank">Lihat Situs ↗</a>
    <a href="logout.php">Keluar</a>
  </aside>

  <div class="admin-main">
    <div class="admin-topbar">
      <div><?= e($pageTitle ?? '') ?></div>
      <div class="user">Masuk sebagai <strong><?= e($cmsUser['username']) ?></strong></div>
    </div>
    <div class="admin-content">
      <?php if (!empty($cmsUser['must_change_password']) && $activeNav !== 'password'): ?>
        <div class="alert alert-error">
          Anda masih menggunakan password sementara dari instalasi awal.
          <a href="change-password.php">Ganti password sekarang</a>.
        </div>
      <?php endif; ?>
