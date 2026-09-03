<?php
$pageTitle = 'Dashboard';
$activeNav = 'dashboard';
require __DIR__ . '/includes/layout-top.php';

$sections = cms_sections();
?>

<div class="page-header">
  <div>
    <h1>Selamat datang, <?= e($cmsUser['username']) ?></h1>
    <p class="sub">Kelola logo, teks, dan konten halaman situs dari sini.</p>
  </div>
</div>

<div class="dashboard-grid">
  <?php foreach ($sections as $key => $section):
      $count = count(cms_get_content($key));
  ?>
    <div class="dashboard-card">
      <div class="count"><?= $count ?></div>
      <p><?= e($section['label']) ?></p>
      <a href="content.php?section=<?= e($key) ?>" class="btn btn-sm btn-secondary">Kelola →</a>
    </div>
  <?php endforeach; ?>

  <div class="dashboard-card">
    <div class="count">⚙</div>
    <p>Identitas perusahaan, logo, teks hero, tentang kami, kontak, dan bagian lain</p>
    <a href="settings.php" class="btn btn-sm btn-secondary">Kelola →</a>
  </div>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
