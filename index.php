<?php
require_once __DIR__ . '/admin/includes/db.php';
require_once __DIR__ . '/admin/includes/functions.php';

try {
    $settings = cms_get_all_settings();
    $stats = cms_get_content('stats');
    $services = cms_get_content('services');
    $fleet = cms_get_content('fleet');
    $process = cms_get_content('process');
    $partnersAviation = cms_get_content('partners_aviation');
    $partnersGround = cms_get_content('partners_ground');
    $partnerLogos = cms_get_content('partner_logos');
    $destinations = cms_get_content('destinations');
    $cmsOk = true;
} catch (Throwable $ex) {
    // Database sedang tidak tersedia — tetap tampilkan halaman dengan nilai kosong
    // daripada menampilkan error mentah ke pengunjung.
    $settings = [];
    $stats = $services = $fleet = $process = $partnersAviation = $partnersGround = $partnerLogos = $destinations = [];
    $cmsOk = false;
}

function s(string $key, string $default = ''): string
{
    global $settings;
    return $settings[$key] ?? $default;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e(s('site_title', 'PT Rama Setya Mandiri')) ?></title>
<meta name="description" content="<?= e(s('site_meta_description')) ?>">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='80'%3E%E2%9C%88%EF%B8%8F%3C/text%3E%3C/svg%3E">
</head>
<body>

<!-- ============ HEADER ============ -->
<header class="site-header" id="siteHeader">
  <div class="container header-inner">
    <a href="#top" class="logo">
      <?php if (s('logo_light_path') || s('logo_dark_path')): ?>
        <?php if (s('logo_light_path')): ?>
          <img src="<?= e(s('logo_light_path')) ?>" alt="Logo <?= e(s('company_name_main').' '.s('company_name_accent')) ?>" class="logo-img logo-swap-light">
        <?php endif; ?>
        <?php if (s('logo_dark_path')): ?>
          <img src="<?= e(s('logo_dark_path')) ?>" alt="Logo <?= e(s('company_name_main').' '.s('company_name_accent')) ?>" class="logo-img logo-swap-dark">
        <?php endif; ?>
      <?php else: ?>
        <span class="logo-icon" aria-hidden="true">
          <svg viewBox="0 0 48 48" width="34" height="34"><path fill="currentColor" d="M44.5 24c0 1.1-2.5 2.4-6.9 3.4l-9.4 2.1-1 8.6 4.4 3.4c.5.4.2 1.2-.4 1.2h-7.1l-2.1 2.6c-.3.4-1 .4-1.3 0l-2.1-2.6H11.5c-.6 0-.9-.8-.4-1.2l4.4-3.4-1-8.6-9.4-2.1C1.1 26.4-1.4 25.1-1.4 24c0-1.1 2.5-2.4 6.9-3.4l9.4-2.1 1-8.6-4.4-3.4c-.5-.4-.2-1.2.4-1.2h7.1l2.1-2.6c.3-.4 1-.4 1.3 0l2.1 2.6h7.1c.6 0 .9.8.4 1.2l-4.4 3.4 1 8.6 9.4 2.1c4.4 1 6.9 2.3 6.9 3.4z" transform="translate(3 0)"/></svg>
        </span>
        <span class="logo-text"><?= e(s('company_name_main', 'Rama Setya')) ?> <em><?= e(s('company_name_accent', 'Mandiri')) ?></em></span>
      <?php endif; ?>
    </a>

    <nav class="main-nav" id="mainNav">
      <a href="#tentang">Tentang</a>
      <a href="#layanan">Layanan</a>
      <a href="#armada">Armada</a>
      <a href="#mitra">Mitra</a>
      <a href="#portofolio">Portofolio</a>
      <a href="#kontak">Kontak</a>
    </nav>

    <a href="#pengajuan" class="btn btn-outline nav-cta"><?= e(s('nav_cta_text', 'Ajukan Penerbangan')) ?></a>

    <button class="nav-toggle" id="navToggle" aria-label="Buka menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>

<!-- ============ HERO ============ -->
<section class="hero" id="top">
  <div class="hero-photo">
    <img src="<?= e(s('hero_image_path', 'assets/img/hero-as350-sky.jpg')) ?>" alt="Foto latar hero PT Rama Setya Mandiri">
  </div>
  <div class="hero-sky" aria-hidden="true">
    <div class="cloud cloud-1"></div>
    <div class="cloud cloud-2"></div>
    <div class="cloud cloud-3"></div>
    <svg class="plane-path" viewBox="0 0 1000 400" preserveAspectRatio="none" aria-hidden="true">
      <path id="flightPath" d="M -50,340 C 200,300 350,120 620,150 C 820,175 900,90 1050,60" fill="none"/>
    </svg>
    <div class="plane" id="heroPlane" aria-hidden="true">
      <svg viewBox="0 0 64 64" width="42" height="42"><path fill="currentColor" d="M62 32c0 1.5-3.3 3.2-9.2 4.5L40.3 39l-1.3 11.5 5.9 4.5c.7.5.3 1.6-.6 1.6h-9.5l-2.8 3.5c-.4.5-1.3.5-1.7 0l-2.8-3.5H18c-.9 0-1.3-1.1-.6-1.6l5.9-4.5L22 39 9.2 36.5C3.3 35.2 0 33.5 0 32s3.3-3.2 9.2-4.5L22 25l1.3-11.5-5.9-4.5c-.7-.5-.3-1.6.6-1.6h9.5l2.8-3.5c.4-.5 1.3-.5 1.7 0l2.8 3.5H43c.9 0 1.3 1.1.6 1.6L37.7 13 39 25l12.8 2.5C57.7 28.8 62 30.5 62 32z"/></svg>
    </div>
  </div>

  <div class="container hero-inner">
    <p class="eyebrow reveal"><?= e(s('hero_eyebrow')) ?></p>
    <h1 class="reveal"><?= e(s('hero_headline')) ?></h1>
    <p class="hero-sub reveal"><?= e(s('hero_subtext')) ?></p>
    <div class="hero-actions reveal">
      <a href="#pengajuan" class="btn btn-primary"><?= e(s('hero_btn_primary_text')) ?></a>
      <a href="#layanan" class="btn btn-ghost"><?= e(s('hero_btn_secondary_text')) ?></a>
    </div>
  </div>

  <!-- Quick charter request card -->
  <div class="container">
    <form class="search-card reveal" id="searchForm">
      <div class="search-field">
        <label for="from">Dari</label>
        <input type="text" id="from" name="from" placeholder="Bandara / kota asal" autocomplete="off" required>
      </div>
      <div class="search-swap" aria-hidden="true">
        <svg viewBox="0 0 24 24" width="20" height="20"><path fill="currentColor" d="M6 3l4 4H7v10h2l-4 4-4-4h2V7H1l4-4h1zm12 0l4 4h-2v10h2l-4 4-4-4h2V7h-3l4-4h1z" transform="scale(.9) translate(1,1)"/></svg>
      </div>
      <div class="search-field">
        <label for="to">Ke</label>
        <input type="text" id="to" name="to" placeholder="Desa / lahan tujuan" autocomplete="off" required>
      </div>
      <div class="search-field">
        <label for="depart">Tanggal Dibutuhkan</label>
        <input type="date" id="depart" name="depart" required>
      </div>
      <div class="search-field">
        <label for="pax">Jenis Muatan</label>
        <select id="pax" name="pax">
          <option>Penumpang</option>
          <option>Kargo / Logistik</option>
          <option>Penumpang &amp; Kargo</option>
          <option>Private / VIP Charter</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary search-submit">
        <span>Ajukan Sekarang</span>
        <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M2 21l21-9L2 3v7l15 2-15 2z"/></svg>
      </button>
    </form>
    <p class="search-note" id="pengajuan"><?= e(s('search_note_text')) ?></p>
  </div>
</section>

<!-- ============ TRUST STRIP ============ -->
<section class="stats-strip">
  <div class="container stats-grid">
    <?php foreach ($stats as $stat): ?>
      <div class="stat reveal">
        <span class="stat-num" data-count="<?= e($stat['title']) ?>">0</span><?php if ($stat['subtitle']): ?><span class="stat-suffix"><?= e($stat['subtitle']) ?></span><?php endif; ?>
        <p><?= e($stat['body']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ============ TENTANG KAMI ============ -->
<section class="about" id="tentang">
  <div class="container about-inner">
    <div class="about-visual reveal">
      <div class="about-frame">
        <img src="<?= e(s('about_image_path')) ?>" alt="Foto PT Rama Setya Mandiri" class="about-illustration">
        <span class="badge-float"><?= e(s('about_badge_text')) ?></span>
      </div>
    </div>
    <div class="about-copy">
      <p class="eyebrow reveal"><?= e(s('about_eyebrow')) ?></p>
      <h2 class="reveal"><?= e(s('about_headline')) ?></h2>
      <p class="reveal"><?= e(s('about_paragraph_1')) ?></p>
      <p class="reveal"><?= e(s('about_paragraph_2')) ?></p>

      <div class="vision-mission reveal">
        <div class="vm-card">
          <h4>Visi Perusahaan</h4>
          <p><?= e(s('vision_text')) ?></p>
        </div>
        <div class="vm-card">
          <h4>Misi Perusahaan</h4>
          <p><?= e(s('mission_text')) ?></p>
        </div>
      </div>

      <a href="#kontak" class="btn btn-outline reveal">Konsultasi Layanan</a>
    </div>
  </div>
</section>

<!-- ============ LAYANAN ============ -->
<section class="services" id="layanan">
  <div class="container">
    <p class="eyebrow center reveal"><?= e(s('services_eyebrow')) ?></p>
    <h2 class="center reveal"><?= e(s('services_headline')) ?></h2>
    <p class="section-sub center reveal"><?= e(s('services_subtext')) ?></p>

    <?php
      $highlightServices = array_filter($services, function ($it) {
          $extra = $it['extra_json'] ? json_decode($it['extra_json'], true) : [];
          return !empty($extra['highlight']);
      });
      $gridServices = array_filter($services, function ($it) {
          $extra = $it['extra_json'] ? json_decode($it['extra_json'], true) : [];
          return empty($extra['highlight']);
      });
    ?>

    <?php if ($gridServices): ?>
      <div class="services-grid services-grid-2">
        <?php foreach ($gridServices as $svc): $extra = $svc['extra_json'] ? json_decode($svc['extra_json'], true) : []; ?>
          <article class="service-card service-card-lg reveal">
            <div class="service-icon"><?= cms_icon_svg($svc['icon_key']) ?></div>
            <h3><?= e($svc['title']) ?></h3>
            <p><?= e($svc['body']) ?></p>
            <?php if (!empty($extra['tags'])): ?>
              <ul class="tag-list">
                <?php foreach ($extra['tags'] as $tag): ?><li><?= e($tag) ?></li><?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php foreach ($highlightServices as $svc): ?>
      <article class="service-card service-highlight reveal">
        <div class="service-icon"><?= cms_icon_svg($svc['icon_key']) ?></div>
        <div>
          <h3><?= e($svc['title']) ?></h3>
          <p><?= e($svc['body']) ?></p>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<!-- ============ ARMADA ============ -->
<section class="fleet" id="armada">
  <div class="container">
    <p class="eyebrow center reveal"><?= e(s('fleet_eyebrow')) ?></p>
    <h2 class="center reveal"><?= e(s('fleet_headline')) ?></h2>

    <div class="fleet-grid">
      <?php foreach ($fleet as $f): ?>
        <article class="fleet-card reveal">
          <div class="fleet-photo"><img src="<?= e($f['image_path']) ?>" alt="<?= e($f['title']) ?>"></div>
          <div class="fleet-body">
            <h3><?= e($f['title']) ?></h3>
            <p class="fleet-config">Konfigurasi: <?= e($f['subtitle']) ?></p>
            <p class="fleet-cap">Kapasitas angkut: <?= e($f['body']) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ MENGAPA KAMI ============ -->
<section class="process" id="mengapa">
  <div class="container">
    <p class="eyebrow center reveal"><?= e(s('process_eyebrow')) ?></p>
    <h2 class="center reveal"><?= e(s('process_headline')) ?></h2>

    <div class="process-track">
      <div class="process-steps process-steps-4">
        <?php foreach ($process as $p): ?>
          <div class="process-step reveal">
            <div class="step-icon"><?= cms_icon_svg($p['icon_key']) ?></div>
            <h3><?= e($p['title']) ?></h3>
            <p><?= e($p['body']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ============ MITRA ============ -->
<section class="partners" id="mitra">
  <div class="container">
    <p class="eyebrow center reveal"><?= e(s('partners_eyebrow')) ?></p>
    <h2 class="center reveal"><?= e(s('partners_headline')) ?></h2>

    <div class="partner-cols">
      <div class="partner-col reveal">
        <h3><?= e(s('partners_aviation_title')) ?></h3>
        <ul class="partner-list">
          <?php foreach ($partnersAviation as $p): ?>
            <li><strong><?= e($p['title']) ?></strong> — <?= e($p['body']) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="partner-col reveal">
        <h3><?= e(s('partners_ground_title')) ?></h3>
        <ul class="partner-list">
          <?php foreach ($partnersGround as $p): ?>
            <li><strong><?= e($p['title']) ?></strong> — <?= e($p['body']) ?></li>
          <?php endforeach; ?>
        </ul>
        <?php if ($partnerLogos): ?>
          <div class="partner-logos">
            <?php foreach ($partnerLogos as $logo): ?>
              <img src="<?= e($logo['image_path']) ?>" alt="Logo <?= e($logo['title']) ?>">
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- ============ PORTOFOLIO ============ -->
<section class="destinations" id="portofolio">
  <div class="container">
    <p class="eyebrow center reveal"><?= e(s('portfolio_eyebrow')) ?></p>
    <h2 class="center reveal"><?= e(s('portfolio_headline')) ?></h2>

    <div class="dest-grid">
      <?php foreach ($destinations as $d): ?>
        <figure class="dest-card dest-photo reveal">
          <img src="<?= e($d['image_path']) ?>" alt="<?= e($d['title']) ?>">
          <figcaption><span><?= e($d['title']) ?></span></figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ CTA ============ -->
<section class="cta">
  <div class="container cta-inner reveal">
    <div>
      <h2><?= e(s('cta_headline')) ?></h2>
      <p><?= e(s('cta_text')) ?></p>
    </div>
    <a href="#kontak" class="btn btn-light"><?= e(s('cta_button_text')) ?></a>
  </div>
</section>

<!-- ============ KONTAK / FOOTER ============ -->
<footer class="site-footer" id="kontak">
  <div class="container footer-grid">
    <div class="footer-brand">
      <a href="#top" class="logo logo-light">
        <?php if (s('logo_light_path')): ?>
          <img src="<?= e(s('logo_light_path')) ?>" alt="Logo <?= e(s('company_name_main').' '.s('company_name_accent')) ?>" class="logo-img">
        <?php else: ?>
          <span class="logo-icon" aria-hidden="true">
            <svg viewBox="0 0 48 48" width="30" height="30"><path fill="currentColor" d="M44.5 24c0 1.1-2.5 2.4-6.9 3.4l-9.4 2.1-1 8.6 4.4 3.4c.5.4.2 1.2-.4 1.2h-7.1l-2.1 2.6c-.3.4-1 .4-1.3 0l-2.1-2.6H11.5c-.6 0-.9-.8-.4-1.2l4.4-3.4-1-8.6-9.4-2.1C1.1 26.4-1.4 25.1-1.4 24c0-1.1 2.5-2.4 6.9-3.4l9.4-2.1 1-8.6-4.4-3.4c-.5-.4-.2-1.2.4-1.2h7.1l2.1-2.6c.3-.4 1-.4 1.3 0l2.1 2.6h7.1c.6 0 .9.8.4 1.2l-4.4 3.4 1 8.6 9.4 2.1c4.4 1 6.9 2.3 6.9 3.4z" transform="translate(3 0)"/></svg>
          </span>
          <span class="logo-text"><?= e(s('company_name_main', 'Rama Setya')) ?> <em><?= e(s('company_name_accent', 'Mandiri')) ?></em></span>
        <?php endif; ?>
      </a>
      <p><?= e(s('footer_tagline')) ?></p>
    </div>

    <div class="footer-col">
      <h4>Navigasi</h4>
      <a href="#tentang">Tentang Kami</a>
      <a href="#layanan">Layanan</a>
      <a href="#armada">Armada</a>
      <a href="#mitra">Mitra Kami</a>
      <a href="#portofolio">Portofolio</a>
    </div>

    <div class="footer-col">
      <h4>Layanan</h4>
      <a href="#layanan">Transportasi Udara</a>
      <a href="#layanan">Ground Handling</a>
      <a href="#layanan">Private &amp; VIP Charter</a>
    </div>

    <div class="footer-col footer-contact">
      <h4>Kontak</h4>
      <p><?= nl2br(e(s('footer_address'))) ?></p>
      <p><a href="tel:<?= e(s('footer_phone_link')) ?>"><?= e(s('footer_phone_display')) ?></a></p>
      <p><a href="mailto:<?= e(s('footer_email')) ?>"><?= e(s('footer_email')) ?></a></p>
      <p><a href="<?= e(s('footer_website_link')) ?>" target="_blank" rel="noopener"><?= e(s('footer_website_display')) ?></a></p>
    </div>
  </div>

  <div class="container footer-bottom">
    <p>&copy; <span id="year"></span> <?= e(s('footer_copyright_holder')) ?>. Seluruh hak cipta dilindungi.</p>
  </div>
</footer>

<a href="#" class="whatsapp-float" id="waFloat" target="_blank" rel="noopener" aria-label="Chat via WhatsApp">
  <svg viewBox="0 0 32 32" width="26" height="26"><path fill="currentColor" d="M16 2C8.3 2 2 8.3 2 16c0 2.7.8 5.3 2.2 7.5L2 30l6.7-2.1C10.8 29.2 13.3 30 16 30c7.7 0 14-6.3 14-14S23.7 2 16 2zm0 25.5c-2.4 0-4.7-.7-6.7-1.9l-.5-.3-4 1.2 1.2-3.9-.3-.5C4.4 20.1 3.7 18.1 3.7 16c0-6.8 5.5-12.3 12.3-12.3S28.3 9.2 28.3 16 22.8 27.5 16 27.5zm6.8-9.2c-.4-.2-2.2-1.1-2.5-1.2-.3-.1-.6-.2-.8.2-.2.4-.9 1.2-1.1 1.4-.2.2-.4.3-.8.1-.4-.2-1.6-.6-3-1.9-1.1-1-1.9-2.2-2.1-2.6-.2-.4 0-.6.2-.8.2-.2.4-.4.6-.7.2-.2.3-.4.4-.7.1-.3.1-.5 0-.7-.1-.2-.8-1.9-1.1-2.6-.3-.7-.6-.6-.8-.6h-.7c-.2 0-.6.1-.9.4-.3.3-1.2 1.2-1.2 2.9s1.3 3.4 1.4 3.6c.2.2 2.5 3.9 6.2 5.3.9.4 1.5.6 2.1.7.9.3 1.6.2 2.2.1.7-.1 2.2-.9 2.5-1.8.3-.9.3-1.6.2-1.8-.1-.2-.3-.3-.7-.5z"/></svg>
</a>

<script>window.CMS_WHATSAPP_NUMBER = <?= json_encode(s('whatsapp_number', '6281321928034')) ?>;</script>
<script src="assets/js/main.js"></script>
</body>
</html>
