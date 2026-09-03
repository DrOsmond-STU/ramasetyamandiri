<?php
$pageTitle = 'Identitas & Pengaturan Situs';
$activeNav = 'settings';
require __DIR__ . '/includes/layout-top.php';
require_once __DIR__ . '/includes/csrf.php';

$error = '';
$success = '';

// Field teks yang disimpan apa adanya (key => label ditampilkan, tidak dipakai di sini, hanya daftar key valid)
$textFields = [
    'site_title', 'site_meta_description',
    'company_name_main', 'company_name_accent', 'nav_cta_text',
    'hero_eyebrow', 'hero_headline', 'hero_subtext', 'hero_btn_primary_text', 'hero_btn_secondary_text', 'search_note_text',
    'about_badge_text', 'about_eyebrow', 'about_headline', 'about_paragraph_1', 'about_paragraph_2',
    'vision_text', 'mission_text',
    'services_eyebrow', 'services_headline', 'services_subtext',
    'fleet_eyebrow', 'fleet_headline',
    'process_eyebrow', 'process_headline',
    'partners_eyebrow', 'partners_headline', 'partners_aviation_title', 'partners_ground_title',
    'portfolio_eyebrow', 'portfolio_headline',
    'cta_headline', 'cta_text', 'cta_button_text',
    'footer_tagline', 'footer_address', 'footer_phone_display', 'footer_phone_link', 'whatsapp_number',
    'footer_email', 'footer_website_display', 'footer_website_link', 'footer_copyright_holder',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    try {
        foreach ($textFields as $key) {
            if (isset($_POST[$key])) {
                cms_set_setting($key, trim((string)$_POST[$key]));
            }
        }

        $logoLight = cms_handle_upload('logo_light_file', cms_get_setting('logo_light_path'));
        cms_set_setting('logo_light_path', (string)$logoLight);

        $logoDark = cms_handle_upload('logo_dark_file', cms_get_setting('logo_dark_path'));
        cms_set_setting('logo_dark_path', (string)$logoDark);

        $heroImg = cms_handle_upload('hero_image_file', cms_get_setting('hero_image_path'));
        cms_set_setting('hero_image_path', (string)$heroImg);

        $aboutImg = cms_handle_upload('about_image_file', cms_get_setting('about_image_path'));
        cms_set_setting('about_image_path', (string)$aboutImg);

        $success = 'Pengaturan berhasil disimpan.';
    } catch (RuntimeException $ex) {
        $error = $ex->getMessage();
    }
}

$s = cms_get_all_settings();
function v(array $s, string $key): string { return e($s[$key] ?? ''); }
?>

<div class="page-header">
  <div>
    <h1>Identitas &amp; Pengaturan Situs</h1>
    <p class="sub">Logo, teks, dan informasi kontak yang tampil di halaman depan.</p>
  </div>
  <a href="../index.php" target="_blank" class="btn btn-secondary">Lihat Situs ↗</a>
</div>

<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>

<form method="post" enctype="multipart/form-data">
  <?= csrf_field() ?>

  <fieldset>
    <legend>Logo Perusahaan</legend>
    <div class="field-grid">
      <div class="field">
        <label>Logo — Versi Terang (untuk latar gelap: header transparan &amp; footer)</label>
        <?php if ($s['logo_light_path'] ?? ''): ?>
          <img src="../<?= v($s, 'logo_light_path') ?>" class="current-image on-dark" alt="Logo terang saat ini">
        <?php else: ?>
          <p class="hint">Belum diunggah — situs masih memakai ikon &amp; teks bawaan.</p>
        <?php endif; ?>
        <input type="file" name="logo_light_file" accept=".jpg,.jpeg,.png,.webp,.svg">
        <div class="hint">JPG/PNG/WEBP/SVG, maks 4MB. Gunakan logo versi putih/terang.</div>
      </div>
      <div class="field">
        <label>Logo — Versi Gelap (untuk latar terang: header saat discroll)</label>
        <?php if ($s['logo_dark_path'] ?? ''): ?>
          <img src="../<?= v($s, 'logo_dark_path') ?>" class="current-image" alt="Logo gelap saat ini">
        <?php else: ?>
          <p class="hint">Belum diunggah — situs masih memakai ikon &amp; teks bawaan.</p>
        <?php endif; ?>
        <input type="file" name="logo_dark_file" accept=".jpg,.jpeg,.png,.webp,.svg">
        <div class="hint">Gunakan logo versi warna gelap/navy.</div>
      </div>
    </div>
    <div class="field-grid">
      <div class="field">
        <label for="company_name_main">Nama Perusahaan (bagian utama)</label>
        <input type="text" id="company_name_main" name="company_name_main" value="<?= v($s, 'company_name_main') ?>">
      </div>
      <div class="field">
        <label for="company_name_accent">Nama Perusahaan (bagian aksen emas)</label>
        <input type="text" id="company_name_accent" name="company_name_accent" value="<?= v($s, 'company_name_accent') ?>">
      </div>
    </div>
    <div class="hint">Dipakai sebagai teks logo cadangan bila logo gambar belum diunggah, mis. "Rama Setya" + "Mandiri".</div>
  </fieldset>

  <fieldset>
    <legend>SEO &amp; Navigasi</legend>
    <div class="field">
      <label for="site_title">Judul Situs (tab browser)</label>
      <input type="text" id="site_title" name="site_title" value="<?= v($s, 'site_title') ?>">
    </div>
    <div class="field">
      <label for="site_meta_description">Meta Deskripsi</label>
      <textarea id="site_meta_description" name="site_meta_description"><?= v($s, 'site_meta_description') ?></textarea>
    </div>
    <div class="field">
      <label for="nav_cta_text">Teks Tombol CTA di Navigasi</label>
      <input type="text" id="nav_cta_text" name="nav_cta_text" value="<?= v($s, 'nav_cta_text') ?>">
    </div>
  </fieldset>

  <fieldset>
    <legend>Hero (Bagian Atas Halaman)</legend>
    <div class="field">
      <label>Foto Latar Hero</label>
      <img src="../<?= v($s, 'hero_image_path') ?>" class="current-image" alt="Foto hero saat ini">
      <input type="file" name="hero_image_file" accept=".jpg,.jpeg,.png,.webp">
    </div>
    <div class="field">
      <label for="hero_eyebrow">Label Kecil (Eyebrow)</label>
      <input type="text" id="hero_eyebrow" name="hero_eyebrow" value="<?= v($s, 'hero_eyebrow') ?>">
    </div>
    <div class="field">
      <label for="hero_headline">Judul Utama</label>
      <input type="text" id="hero_headline" name="hero_headline" value="<?= v($s, 'hero_headline') ?>">
    </div>
    <div class="field">
      <label for="hero_subtext">Paragraf Deskripsi</label>
      <textarea id="hero_subtext" name="hero_subtext"><?= v($s, 'hero_subtext') ?></textarea>
    </div>
    <div class="field-grid">
      <div class="field">
        <label for="hero_btn_primary_text">Teks Tombol Utama</label>
        <input type="text" id="hero_btn_primary_text" name="hero_btn_primary_text" value="<?= v($s, 'hero_btn_primary_text') ?>">
      </div>
      <div class="field">
        <label for="hero_btn_secondary_text">Teks Tombol Kedua</label>
        <input type="text" id="hero_btn_secondary_text" name="hero_btn_secondary_text" value="<?= v($s, 'hero_btn_secondary_text') ?>">
      </div>
    </div>
    <div class="field">
      <label for="search_note_text">Catatan di Bawah Form Pengajuan</label>
      <input type="text" id="search_note_text" name="search_note_text" value="<?= v($s, 'search_note_text') ?>">
    </div>
  </fieldset>

  <fieldset>
    <legend>Tentang Kami</legend>
    <div class="field">
      <label>Foto</label>
      <img src="../<?= v($s, 'about_image_path') ?>" class="current-image" alt="Foto tentang kami saat ini">
      <input type="file" name="about_image_file" accept=".jpg,.jpeg,.png,.webp">
    </div>
    <div class="field">
      <label for="about_badge_text">Teks Lencana pada Foto</label>
      <input type="text" id="about_badge_text" name="about_badge_text" value="<?= v($s, 'about_badge_text') ?>">
    </div>
    <div class="field">
      <label for="about_eyebrow">Label Kecil</label>
      <input type="text" id="about_eyebrow" name="about_eyebrow" value="<?= v($s, 'about_eyebrow') ?>">
    </div>
    <div class="field">
      <label for="about_headline">Judul</label>
      <input type="text" id="about_headline" name="about_headline" value="<?= v($s, 'about_headline') ?>">
    </div>
    <div class="field">
      <label for="about_paragraph_1">Paragraf 1</label>
      <textarea id="about_paragraph_1" name="about_paragraph_1"><?= v($s, 'about_paragraph_1') ?></textarea>
    </div>
    <div class="field">
      <label for="about_paragraph_2">Paragraf 2</label>
      <textarea id="about_paragraph_2" name="about_paragraph_2"><?= v($s, 'about_paragraph_2') ?></textarea>
    </div>
    <div class="field">
      <label for="vision_text">Visi Perusahaan</label>
      <textarea id="vision_text" name="vision_text"><?= v($s, 'vision_text') ?></textarea>
    </div>
    <div class="field">
      <label for="mission_text">Misi Perusahaan</label>
      <textarea id="mission_text" name="mission_text"><?= v($s, 'mission_text') ?></textarea>
    </div>
  </fieldset>

  <fieldset>
    <legend>Judul Bagian Layanan / Armada / Mengapa Kami / Mitra / Portofolio</legend>
    <div class="field-grid">
      <div class="field"><label for="services_eyebrow">Layanan — Label Kecil</label><input type="text" id="services_eyebrow" name="services_eyebrow" value="<?= v($s, 'services_eyebrow') ?>"></div>
      <div class="field"><label for="services_headline">Layanan — Judul</label><input type="text" id="services_headline" name="services_headline" value="<?= v($s, 'services_headline') ?>"></div>
    </div>
    <div class="field"><label for="services_subtext">Layanan — Sub Judul</label><input type="text" id="services_subtext" name="services_subtext" value="<?= v($s, 'services_subtext') ?>"></div>

    <div class="field-grid">
      <div class="field"><label for="fleet_eyebrow">Armada — Label Kecil</label><input type="text" id="fleet_eyebrow" name="fleet_eyebrow" value="<?= v($s, 'fleet_eyebrow') ?>"></div>
      <div class="field"><label for="fleet_headline">Armada — Judul</label><input type="text" id="fleet_headline" name="fleet_headline" value="<?= v($s, 'fleet_headline') ?>"></div>
    </div>

    <div class="field-grid">
      <div class="field"><label for="process_eyebrow">Mengapa Kami — Label Kecil</label><input type="text" id="process_eyebrow" name="process_eyebrow" value="<?= v($s, 'process_eyebrow') ?>"></div>
      <div class="field"><label for="process_headline">Mengapa Kami — Judul</label><input type="text" id="process_headline" name="process_headline" value="<?= v($s, 'process_headline') ?>"></div>
    </div>

    <div class="field-grid">
      <div class="field"><label for="partners_eyebrow">Mitra — Label Kecil</label><input type="text" id="partners_eyebrow" name="partners_eyebrow" value="<?= v($s, 'partners_eyebrow') ?>"></div>
      <div class="field"><label for="partners_headline">Mitra — Judul</label><input type="text" id="partners_headline" name="partners_headline" value="<?= v($s, 'partners_headline') ?>"></div>
    </div>
    <div class="field-grid">
      <div class="field"><label for="partners_aviation_title">Mitra — Judul Kolom Transportasi Udara</label><input type="text" id="partners_aviation_title" name="partners_aviation_title" value="<?= v($s, 'partners_aviation_title') ?>"></div>
      <div class="field"><label for="partners_ground_title">Mitra — Judul Kolom Ground Handling</label><input type="text" id="partners_ground_title" name="partners_ground_title" value="<?= v($s, 'partners_ground_title') ?>"></div>
    </div>

    <div class="field-grid">
      <div class="field"><label for="portfolio_eyebrow">Portofolio — Label Kecil</label><input type="text" id="portfolio_eyebrow" name="portfolio_eyebrow" value="<?= v($s, 'portfolio_eyebrow') ?>"></div>
      <div class="field"><label for="portfolio_headline">Portofolio — Judul</label><input type="text" id="portfolio_headline" name="portfolio_headline" value="<?= v($s, 'portfolio_headline') ?>"></div>
    </div>
  </fieldset>

  <fieldset>
    <legend>CTA (Ajakan di Atas Footer)</legend>
    <div class="field"><label for="cta_headline">Judul</label><input type="text" id="cta_headline" name="cta_headline" value="<?= v($s, 'cta_headline') ?>"></div>
    <div class="field"><label for="cta_text">Deskripsi</label><input type="text" id="cta_text" name="cta_text" value="<?= v($s, 'cta_text') ?>"></div>
    <div class="field"><label for="cta_button_text">Teks Tombol</label><input type="text" id="cta_button_text" name="cta_button_text" value="<?= v($s, 'cta_button_text') ?>"></div>
  </fieldset>

  <fieldset>
    <legend>Footer &amp; Kontak</legend>
    <div class="field"><label for="footer_tagline">Tagline Singkat</label><textarea id="footer_tagline" name="footer_tagline"><?= v($s, 'footer_tagline') ?></textarea></div>
    <div class="field"><label for="footer_address">Alamat</label><textarea id="footer_address" name="footer_address"><?= v($s, 'footer_address') ?></textarea></div>
    <div class="field-grid">
      <div class="field"><label for="footer_phone_display">Nomor Telepon (tampilan)</label><input type="text" id="footer_phone_display" name="footer_phone_display" value="<?= v($s, 'footer_phone_display') ?>"></div>
      <div class="field"><label for="footer_phone_link">Nomor Telepon (link tel:, mis. +6281321928034)</label><input type="text" id="footer_phone_link" name="footer_phone_link" value="<?= v($s, 'footer_phone_link') ?>"></div>
    </div>
    <div class="field">
      <label for="whatsapp_number">Nomor WhatsApp (untuk tombol WA, mis. 6281321928034)</label>
      <input type="text" id="whatsapp_number" name="whatsapp_number" value="<?= v($s, 'whatsapp_number') ?>">
      <div class="hint">Tanpa tanda + atau spasi. Dipakai oleh tombol WhatsApp mengambang dan form pengajuan penerbangan.</div>
    </div>
    <div class="field"><label for="footer_email">Email</label><input type="text" id="footer_email" name="footer_email" value="<?= v($s, 'footer_email') ?>"></div>
    <div class="field-grid">
      <div class="field"><label for="footer_website_display">Website (tampilan)</label><input type="text" id="footer_website_display" name="footer_website_display" value="<?= v($s, 'footer_website_display') ?>"></div>
      <div class="field"><label for="footer_website_link">Website (URL lengkap)</label><input type="text" id="footer_website_link" name="footer_website_link" value="<?= v($s, 'footer_website_link') ?>"></div>
    </div>
    <div class="field"><label for="footer_copyright_holder">Nama Pemegang Hak Cipta</label><input type="text" id="footer_copyright_holder" name="footer_copyright_holder" value="<?= v($s, 'footer_copyright_holder') ?>"></div>
  </fieldset>

  <button type="submit" class="btn">Simpan Semua Pengaturan</button>
</form>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
