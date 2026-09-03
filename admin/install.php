<?php
/**
 * Skrip instalasi satu-kali: membuat tabel, admin default, dan mengisi
 * seluruh pengaturan/konten dengan isi situs yang sudah tayang saat ini
 * (supaya halaman tidak kosong setelah beralih ke CMS).
 *
 * Setelah berhasil dijalankan sekali, skrip ini mengunci dirinya sendiri
 * lewat admin/data/.installed dan menolak dijalankan ulang.
 */

require_once __DIR__ . '/includes/db.php';

header('Content-Type: text/plain; charset=utf-8');

$dataDir = __DIR__ . '/data';
$lockFile = $dataDir . '/.installed';

if (is_file($lockFile)) {
    http_response_code(403);
    echo "Instalasi sudah pernah dijalankan sebelumnya.\n";
    echo "Hapus admin/data/.installed secara manual jika Anda sengaja ingin menjalankan ulang.\n";
    exit;
}

function out(string $msg): void { echo $msg . "\n"; }

try {
    $pdo = cms_db();

    $pdo->exec("CREATE TABLE IF NOT EXISTS cms_users (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(60) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        must_change_password TINYINT(1) NOT NULL DEFAULT 1,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS cms_settings (
        setting_key VARCHAR(100) NOT NULL PRIMARY KEY,
        setting_value LONGTEXT NULL,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS cms_content_items (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        section VARCHAR(50) NOT NULL,
        sort_order INT NOT NULL DEFAULT 0,
        title VARCHAR(255) NULL,
        subtitle VARCHAR(255) NULL,
        body TEXT NULL,
        image_path VARCHAR(255) NULL,
        icon_key VARCHAR(50) NULL,
        extra_json TEXT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_section_sort (section, sort_order)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    out('Tabel database siap.');

    // ---- Admin user default ------------------------------------------------
    $userCount = (int)$pdo->query('SELECT COUNT(*) FROM cms_users')->fetchColumn();
    $credentialsText = '';
    if ($userCount === 0) {
        $username = 'admin';
        $password = bin2hex(random_bytes(9)); // 18 karakter acak
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO cms_users (username, password_hash, must_change_password) VALUES (?, ?, 1)');
        $stmt->execute([$username, $hash]);
        $credentialsText = "Username: $username\nPassword: $password\n(Wajib diganti setelah login pertama.)\n";
        out('User admin default dibuat.');
    } else {
        out('User admin sudah ada, dilewati.');
    }

    // ---- Pengaturan default (isi situs yang tayang saat ini) ---------------
    $defaultSettings = [
        'site_title' => 'PT Rama Setya Mandiri | Integrated Aviation Solutions',
        'site_meta_description' => 'PT Rama Setya Mandiri — penyedia jasa transportasi udara (helikopter & pesawat charter) dan Ground Handling di Papua. Menjangkau bandara, desa, hingga lahan terbuka dengan standar keselamatan tinggi.',

        'logo_light_path' => '',
        'logo_dark_path' => '',
        'company_name_main' => 'Rama Setya',
        'company_name_accent' => 'Mandiri',

        'nav_cta_text' => 'Ajukan Penerbangan',

        'hero_image_path' => 'assets/img/hero-as350-sky.jpg',
        'hero_eyebrow' => 'Integrated Aviation Solutions — Ground Handling & Exclusive Air Charter',
        'hero_headline' => 'Menjangkau Papua, Melampaui Batas Akses Darat',
        'hero_subtext' => 'PT Rama Setya Mandiri hadir di Sentani, Papua, sebagai mitra transportasi udara dan Ground Handling — melayani rute bandara ke bandara, bandara ke desa, hingga desa ke desa dan lahan terbuka untuk helikopter, dengan keselamatan, efektivitas, dan profesionalisme sebagai prioritas utama.',
        'hero_btn_primary_text' => 'Ajukan Permintaan Penerbangan',
        'hero_btn_secondary_text' => 'Lihat Layanan',
        'search_note_text' => 'Permintaan akan dikirim langsung ke tim operasional kami melalui WhatsApp untuk penjadwalan tercepat.',

        'about_image_path' => 'assets/img/portfolio-kamov-airport.jpg',
        'about_badge_text' => 'Berdiri sejak 29 September 2022',
        'about_eyebrow' => 'Tentang Kami',
        'about_headline' => 'Lahir dari Kebutuhan Nyata Masyarakat Papua',
        'about_paragraph_1' => 'PT Rama Setya Mandiri didirikan di Sentani, Papua, pada 29 September 2022 dengan akta notaris Dr. H. Tri Mulyadi — tergerak oleh besarnya kebutuhan transportasi udara masyarakat Papua, di mana banyak desa dan destinasi tujuan secara geografis belum terhubung akibat terbatasnya akses darat.',
        'about_paragraph_2' => 'Menjawab permintaan rekanan airlines serta peluang di sektor Ground Handling, PT Rama Setya Mandiri resmi dan berizin beroperasi sejak 1 Januari 2026, melayani maskapai di beberapa bandara di Papua dengan mengedepankan aspek keselamatan tinggi, efektivitas, dan efisiensi — didukung sumber daya manusia yang berkualifikasi, bersertifikasi, dan profesional.',
        'vision_text' => 'Berkomitmen untuk memberikan solusi terbaik sebagai penyedia jasa transportasi udara dan Ground Handling yang aman, efektif, efisien, dan profesional.',
        'mission_text' => 'Menjadi perusahaan penyedia jasa transportasi udara dan layanan Ground Handling terbaik, terdepan, dan terpercaya — menjangkau dari daerah terpencil hingga seluruh Indonesia.',

        'services_eyebrow' => 'Layanan Kami',
        'services_headline' => 'Solusi Aviasi Terpadu untuk Setiap Medan',
        'services_subtext' => 'Dari udara ke landasan — kami menangani perjalanan penumpang, kargo, hingga kebutuhan operasional maskapai di darat.',

        'fleet_eyebrow' => 'Armada Kami',
        'fleet_headline' => 'Dipilih untuk Ketahanan dan Medan Papua',

        'process_eyebrow' => 'Mengapa Kami',
        'process_headline' => 'Terpercaya di Medan yang Paling Menantang',

        'partners_eyebrow' => 'Mitra Kami',
        'partners_headline' => 'Dipercaya oleh Institusi dan Komunitas di Papua',
        'partners_aviation_title' => 'Jasa Transportasi Udara',
        'partners_ground_title' => 'Jasa Ground Handling',

        'portfolio_eyebrow' => 'Portofolio',
        'portfolio_headline' => 'Operasional Kami di Lapangan',

        'cta_headline' => 'Mari Bangun Kolaborasi Aviasi yang Aman dan Eksklusif',
        'cta_text' => 'Tim operasional kami siap membantu kebutuhan transportasi udara dan Ground Handling Anda di Papua.',
        'cta_button_text' => 'Hubungi Kami',

        'footer_tagline' => 'Integrated Aviation Solutions — penyedia jasa transportasi udara dan Ground Handling yang aman, efektif, efisien, dan profesional di Papua.',
        'footer_address' => 'Jl. Sosial No. 11, Tifa Residence 2, Sentani, Papua 99352',
        'footer_phone_display' => '0813-2192-8034',
        'footer_phone_link' => '+6281321928034',
        'whatsapp_number' => '6281321928034',
        'footer_email' => 'info@ramasetyamandiri.com',
        'footer_website_display' => 'www.ramasetyamandiri.com',
        'footer_website_link' => 'https://ramasetyamandiri.com',
        'footer_copyright_holder' => 'PT Rama Setya Mandiri',
    ];

    $insertSetting = $pdo->prepare('INSERT IGNORE INTO cms_settings (setting_key, setting_value) VALUES (?, ?)');
    foreach ($defaultSettings as $k => $v) {
        $insertSetting->execute([$k, $v]);
    }
    out('Pengaturan default disiapkan (' . count($defaultSettings) . ' item).');

    // ---- Konten berulang default --------------------------------------------
    $itemCount = (int)$pdo->query('SELECT COUNT(*) FROM cms_content_items')->fetchColumn();
    if ($itemCount === 0) {
        $insertItem = $pdo->prepare(
            'INSERT INTO cms_content_items (section, sort_order, title, subtitle, body, image_path, icon_key, extra_json)
             VALUES (?,?,?,?,?,?,?,?)'
        );

        $rows = [
            // stats
            ['stats', 1, '2022', '', 'Berdiri di Sentani, Papua', null, null, null],
            ['stats', 2, '3', '', 'Jenis Armada Udara', null, null, null],
            ['stats', 3, '10', '+', 'Mitra & Klien', null, null, null],
            ['stats', 4, '100', '%', 'Tingkat Penyelesaian Proyek', null, null, null],

            // services
            ['services', 1, 'Jasa Transportasi Udara', null,
                'Layanan penumpang dan kargo yang menjangkau rute bandara ke bandara, bandara ke desa, desa ke desa, hingga seluruh lahan terbuka untuk pendaratan helikopter.',
                null, 'plane', json_encode(['tags' => ['Bandara ke Bandara', 'Bandara ke Desa', 'Desa ke Desa', 'Lahan Terbuka (Helikopter)'], 'highlight' => false])],
            ['services', 2, 'Ground Handling', null,
                'Penanganan darat bagi maskapai rekanan di bandara-bandara Papua, dari pesawat mendarat hingga siap terbang kembali.',
                null, 'ground', json_encode(['tags' => ['Ground Handling Maskapai', 'Layanan RAMP', 'Aviation Security', 'Bagasi & Kargo', 'Lavatory', 'Layanan VIP'], 'highlight' => false])],
            ['services', 3, 'Private & VIP Charter', null,
                'Penerbangan eksklusif untuk kebutuhan khusus — mengantar wisatawan dan pendaki menuju destinasi seperti Raja Ampat dan Yellow Valley (Carstensz), serta evakuasi medis dan transportasi VIP di wilayah terpencil.',
                null, 'vip', json_encode(['tags' => [], 'highlight' => true])],

            // fleet
            ['fleet', 1, 'KAMOV KA32', 'Kargo', '1 – 3,5 Ton', 'assets/img/fleet-kamov-ka32.jpg', null, null],
            ['fleet', 2, 'Airbus Helicopter AS350B3/B2', 'Penumpang & Kargo', '250 – 600 Kgs', 'assets/img/fleet-as350.jpg', null, null],
            ['fleet', 3, 'Grand Caravan Cessna 208B', 'Penumpang & Kargo', '700 – 1.200 Kgs', 'assets/img/fleet-cessna-caravan.jpg', null, null],

            // process (mengapa kami)
            ['process', 1, 'Keahlian & Akreditasi', null, 'Tim kami terdiri dari personel berpengalaman dan berlisensi lengkap, menjamin layanan yang profesional dan kompeten.', null, 'shield', null],
            ['process', 2, 'Komitmen Layanan', null, 'Telah melaksanakan berbagai proyek di wilayah paling terpencil di Papua dan daerah sulit dijangkau lainnya, dengan tingkat penyelesaian mencapai 100%.', null, 'check', null],
            ['process', 3, 'Peralatan Standar', null, 'Operasional kami didukung peralatan kerja yang memadai dan memenuhi standar keselamatan aviasi.', null, 'arrows', null],
            ['process', 4, 'Efisiensi Proyek', null, 'Rekam jejak terbukti dalam menyelesaikan proyek secara efisien dan konsisten memenuhi target yang ditetapkan.', null, 'grid', null],

            // partners — aviation
            ['partners_aviation', 1, 'Masyarakat Pedalaman / Daerah Terpencil', null, 'sarana transportasi penumpang, logistik, serta material pembangunan dari bandara ke pedalaman/desa terpencil menggunakan helikopter KAMOV KA32 dan AS350B2/B3.', null, null, null],
            ['partners_aviation', 2, 'PT. Lambu Raya Utama', null, 'pengiriman alat berat, material jembatan, dan pekerja menggunakan KAMOV KA32 dan AS350B3.', null, null, null],
            ['partners_aviation', 3, 'PT. Marta Teknik Tunggal', null, 'pengiriman alat berat excavator, stone crusher, dan kendaraan 4x4 menggunakan KAMOV KA32.', null, null, null],
            ['partners_aviation', 4, 'Pemda Puncak Jaya', null, 'pengiriman kendaraan dinas roda 4 ke Ilaga menggunakan KAMOV KA32.', null, null, null],
            ['partners_aviation', 5, 'Pemda Intan Jaya', null, 'pengiriman kendaraan dinas roda 4 ke Ilaga menggunakan KAMOV KA32.', null, null, null],
            ['partners_aviation', 6, 'Yayasan Pengembangan Masyarakat Amungme dan Komoro', null, 'transportasi kelompok pekerja, koperasi, dan sarana medical evacuation menggunakan AS350B2.', null, null, null],
            ['partners_aviation', 7, 'Agen-agen Ekspedisi Pendakian Carstensz', null, 'transportasi pendaki menuju dan dari Yellow Valley menggunakan AS350B3.', null, null, null],
            ['partners_aviation', 8, 'Private Charter', null, 'transportasi VIP menuju destinasi wisata seperti Raja Ampat dan sekitarnya.', null, null, null],

            // partners — ground handling
            ['partners_ground', 1, 'PT. Elang Dhirgantara Candracanta', null, 'penanganan external cargo/external load, RAMP, crew handling, dan refuelling untuk helikopter KAMOV KA32.', null, null, null],
            ['partners_ground', 2, 'PT. Elang Nusantara Air', null, 'penanganan penumpang, bagasi, RAMP, dan kargo.', null, null, null],

            // partner logos
            ['partner_logos', 1, 'PT Lambu Raya Utama', null, null, 'assets/img/partner-lru.jpg', null, null],
            ['partner_logos', 2, 'PT Elang Dhirgantara Candracanta', null, null, 'assets/img/partner-elang-dhirgantara.jpg', null, null],
            ['partner_logos', 3, 'PT Elang Nusantara Air', null, null, 'assets/img/partner-elang-nusantara.jpg', null, null],

            // portfolio / destinations
            ['destinations', 1, 'Persiapan Kargo — AS350', null, null, 'assets/img/portfolio-as350-cargo.jpg', null, null],
            ['destinations', 2, 'Operasional KAMOV KA32', null, null, 'assets/img/portfolio-kamov-sunset.jpg', null, null],
            ['destinations', 3, 'Pendaratan di Pedalaman Papua', null, null, 'assets/img/portfolio-cessna-mountains.jpg', null, null],
        ];

        foreach ($rows as $row) {
            $insertItem->execute($row);
        }
        out('Konten awal disiapkan (' . count($rows) . ' item).');
    } else {
        out('Konten sudah ada, dilewati.');
    }

    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }
    if ($credentialsText !== '') {
        file_put_contents($dataDir . '/install-credentials.txt', $credentialsText);
        chmod($dataDir . '/install-credentials.txt', 0600);
    }
    file_put_contents($lockFile, date('c'));

    out('Instalasi selesai.');
} catch (Throwable $ex) {
    http_response_code(500);
    out('Instalasi gagal: ' . $ex->getMessage());
}
