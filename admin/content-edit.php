<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
cms_require_login();
require_once __DIR__ . '/includes/csrf.php';

$sections = cms_sections();
$section = $_GET['section'] ?? $_POST['section'] ?? '';
if (!isset($sections[$section])) {
    http_response_code(404);
    die('Section tidak ditemukan.');
}
$def = $sections[$section];

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$item = $id ? cms_get_content_item($id) : null;
if ($id && !$item) {
    http_response_code(404);
    die('Item tidak ditemukan.');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    try {
        $imagePath = $item['image_path'] ?? null;
        if ($def['has_image']) {
            $imagePath = cms_handle_upload('image_file', $imagePath);
        }

        $extra = null;
        if ($def['has_tags'] || $def['has_highlight']) {
            $tagsRaw = trim((string)($_POST['tags'] ?? ''));
            $tags = $tagsRaw === '' ? [] : array_values(array_filter(array_map('trim', explode("\n", $tagsRaw))));
            $extra = json_encode([
                'tags' => $tags,
                'highlight' => !empty($_POST['highlight']),
            ]);
        }

        $data = [
            'id' => $id ?: null,
            'section' => $section,
            'title' => trim((string)($_POST['title'] ?? '')),
            'subtitle' => trim((string)($_POST['subtitle'] ?? '')),
            'body' => trim((string)($_POST['body'] ?? '')),
            'image_path' => $imagePath,
            'icon_key' => $def['has_icon'] ? (string)($_POST['icon_key'] ?? 'check') : null,
            'extra_json' => $extra,
        ];

        cms_save_content_item($data);
        header('Location: content.php?section=' . urlencode($section));
        exit;
    } catch (RuntimeException $ex) {
        $error = $ex->getMessage();
    }
}

$extraData = [];
if ($item && $item['extra_json']) {
    $extraData = json_decode($item['extra_json'], true) ?: [];
}

$pageTitle = ($item ? 'Ubah' : 'Tambah') . ' — ' . $def['label'];
$activeNav = $section;
require __DIR__ . '/includes/layout-top.php';
?>

<div class="page-header">
  <div>
    <h1><?= e($pageTitle) ?></h1>
  </div>
  <a href="content.php?section=<?= e($section) ?>" class="btn btn-secondary">← Kembali ke Daftar</a>
</div>

<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

<div class="card">
  <form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="section" value="<?= e($section) ?>">
    <?php if ($id): ?><input type="hidden" name="id" value="<?= $id ?>"><?php endif; ?>

    <?php if (isset($def['fields']['title'])): ?>
      <div class="field">
        <label for="title"><?= e($def['fields']['title']) ?></label>
        <input type="text" id="title" name="title" value="<?= e($item['title'] ?? '') ?>">
      </div>
    <?php endif; ?>

    <?php if (isset($def['fields']['subtitle'])): ?>
      <div class="field">
        <label for="subtitle"><?= e($def['fields']['subtitle']) ?></label>
        <input type="text" id="subtitle" name="subtitle" value="<?= e($item['subtitle'] ?? '') ?>">
      </div>
    <?php endif; ?>

    <?php if (isset($def['fields']['body'])): ?>
      <div class="field">
        <label for="body"><?= e($def['fields']['body']) ?></label>
        <textarea id="body" name="body"><?= e($item['body'] ?? '') ?></textarea>
      </div>
    <?php endif; ?>

    <?php if ($def['has_icon']): ?>
      <div class="field">
        <label for="icon_key">Ikon</label>
        <select id="icon_key" name="icon_key">
          <?php foreach (cms_icons() as $key => $icon): ?>
            <option value="<?= e($key) ?>" <?= ($item['icon_key'] ?? 'check') === $key ? 'selected' : '' ?>><?= e($icon['label']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php endif; ?>

    <?php if ($def['has_image']): ?>
      <div class="field">
        <label>Gambar</label>
        <?php if (!empty($item['image_path'])): ?>
          <img src="../<?= e($item['image_path']) ?>" class="current-image" alt="">
        <?php endif; ?>
        <input type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp,.svg" <?= $item ? '' : 'required' ?>>
        <div class="hint">JPG/PNG/WEBP/SVG, maks 4MB.</div>
      </div>
    <?php endif; ?>

    <?php if ($def['has_tags']): ?>
      <div class="field">
        <label for="tags">Daftar Tag (satu per baris, boleh kosong)</label>
        <textarea id="tags" name="tags"><?= e(implode("\n", $extraData['tags'] ?? [])) ?></textarea>
      </div>
    <?php endif; ?>

    <?php if ($def['has_highlight']): ?>
      <div class="field">
        <label><input type="checkbox" name="highlight" value="1" <?= !empty($extraData['highlight']) ? 'checked' : '' ?> style="width:auto; margin-right:8px;"> Tampilkan sebagai kartu highlight (lebar penuh)</label>
      </div>
    <?php endif; ?>

    <button type="submit" class="btn">Simpan</button>
  </form>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
