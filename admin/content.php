<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
$cmsUser = cms_require_login();
require_once __DIR__ . '/includes/csrf.php';

$sections = cms_sections();
$section = $_GET['section'] ?? '';
if (!isset($sections[$section])) {
    http_response_code(404);
    die('Section tidak ditemukan.');
}
$def = $sections[$section];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    if ($action === 'move' && $id) {
        cms_move_content_item($id, $_POST['direction'] === 'up' ? 'up' : 'down');
    }
    header('Location: content.php?section=' . urlencode($section));
    exit;
}

$pageTitle = $def['label'];
$activeNav = $section;
require __DIR__ . '/includes/layout-top.php';

$items = cms_get_content($section);
?>

<div class="page-header">
  <div>
    <h1><?= e($def['label']) ?></h1>
    <p class="sub">Urutkan, tambah, ubah, atau hapus item pada bagian ini.</p>
  </div>
  <a href="content-edit.php?section=<?= e($section) ?>" class="btn">+ Tambah Item</a>
</div>

<div class="item-list">
  <?php if (!$items): ?>
    <div class="card empty-state">Belum ada item. Klik "Tambah Item" untuk membuat yang pertama.</div>
  <?php endif; ?>

  <?php foreach ($items as $i => $item): ?>
    <div class="item-row">
      <?php if ($def['has_image']): ?>
        <?php if ($item['image_path']): ?>
          <img src="../<?= e($item['image_path']) ?>" class="thumb" alt="">
        <?php else: ?>
          <div class="thumb"></div>
        <?php endif; ?>
      <?php elseif ($def['has_icon']): ?>
        <div class="icon-preview"><?= cms_icon_svg($item['icon_key']) ?></div>
      <?php endif; ?>

      <div class="info">
        <strong><?= e($item['title'] ?: '(tanpa judul)') ?></strong>
        <span><?= e($item['subtitle'] ?: $item['body'] ?: '') ?></span>
      </div>

      <div class="actions">
        <form method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="move">
          <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
          <input type="hidden" name="direction" value="up">
          <button type="submit" class="btn btn-sm btn-secondary" <?= $i === 0 ? 'disabled' : '' ?>>↑</button>
        </form>
        <form method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="move">
          <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
          <input type="hidden" name="direction" value="down">
          <button type="submit" class="btn btn-sm btn-secondary" <?= $i === count($items) - 1 ? 'disabled' : '' ?>>↓</button>
        </form>
        <a href="content-edit.php?section=<?= e($section) ?>&id=<?= (int)$item['id'] ?>" class="btn btn-sm btn-secondary">Ubah</a>
        <form method="post" action="content-delete.php" onsubmit="return confirm('Hapus item ini?');">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
          <input type="hidden" name="section" value="<?= e($section) ?>">
          <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
