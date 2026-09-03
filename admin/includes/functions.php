<?php
/**
 * Helper: pengaturan situs (key/value), konten berulang (content items),
 * unggah gambar, dan registry ikon aman untuk kartu Layanan / Mengapa Kami.
 */

require_once __DIR__ . '/db.php';

define('CMS_UPLOAD_DIR', __DIR__ . '/../../assets/img/uploads/');
define('CMS_UPLOAD_URL', 'assets/img/uploads/');

/** Definisi section konten berulang: label + field yang ditampilkan di form. */
function cms_sections(): array
{
    return [
        'stats' => [
            'label' => 'Statistik (Trust Strip)',
            'fields' => [
                'title' => 'Angka (mis. 2022, 10, 100)',
                'subtitle' => 'Sufiks (mis. + atau %, kosongkan jika tidak ada)',
                'body' => 'Keterangan',
            ],
            'has_icon' => false, 'has_image' => false, 'has_tags' => false, 'has_highlight' => false,
        ],
        'services' => [
            'label' => 'Layanan',
            'fields' => [
                'title' => 'Judul Layanan',
                'body' => 'Deskripsi',
            ],
            'has_icon' => true, 'has_image' => false, 'has_tags' => true, 'has_highlight' => true,
        ],
        'fleet' => [
            'label' => 'Armada',
            'fields' => [
                'title' => 'Nama Pesawat / Helikopter',
                'subtitle' => 'Konfigurasi (mis. Kargo, Penumpang & Kargo)',
                'body' => 'Kapasitas Angkut (mis. 1 – 3,5 Ton)',
            ],
            'has_icon' => false, 'has_image' => true, 'has_tags' => false, 'has_highlight' => false,
        ],
        'process' => [
            'label' => 'Mengapa Kami',
            'fields' => [
                'title' => 'Judul Poin',
                'body' => 'Deskripsi',
            ],
            'has_icon' => true, 'has_image' => false, 'has_tags' => false, 'has_highlight' => false,
        ],
        'partners_aviation' => [
            'label' => 'Mitra — Transportasi Udara',
            'fields' => [
                'title' => 'Nama Mitra',
                'body' => 'Deskripsi Kerja Sama',
            ],
            'has_icon' => false, 'has_image' => false, 'has_tags' => false, 'has_highlight' => false,
        ],
        'partners_ground' => [
            'label' => 'Mitra — Ground Handling',
            'fields' => [
                'title' => 'Nama Mitra',
                'body' => 'Deskripsi Kerja Sama',
            ],
            'has_icon' => false, 'has_image' => false, 'has_tags' => false, 'has_highlight' => false,
        ],
        'partner_logos' => [
            'label' => 'Logo Mitra',
            'fields' => [
                'title' => 'Nama Mitra (teks alt gambar)',
            ],
            'has_icon' => false, 'has_image' => true, 'has_tags' => false, 'has_highlight' => false,
        ],
        'destinations' => [
            'label' => 'Portofolio',
            'fields' => [
                'title' => 'Judul Foto',
            ],
            'has_icon' => false, 'has_image' => true, 'has_tags' => false, 'has_highlight' => false,
        ],
    ];
}

/** Ikon aman (whitelist) untuk kartu Layanan & Mengapa Kami — dipilih lewat dropdown, bukan markup bebas. */
function cms_icons(): array
{
    return [
        'plane' => ['label' => 'Pesawat', 'svg' => '<svg viewBox="0 0 24 24" width="28" height="28"><path fill="currentColor" d="M2.5 19l19-8-19-8v6l14 2-14 2z"/></svg>'],
        'ground' => ['label' => 'Ground Handling (Kotak Kargo)', 'svg' => '<svg viewBox="0 0 24 24" width="28" height="28"><path fill="currentColor" d="M4 10h16v9H4v-9zm2-6h12v4H6V4zm-2 4h16l1 2H3l1-2z"/></svg>'],
        'vip' => ['label' => 'Bintang / VIP', 'svg' => '<svg viewBox="0 0 24 24" width="28" height="28"><path fill="currentColor" d="M12 2l2.4 6.9L21 9.3l-5.5 4.4L17.4 21 12 17l-5.4 4 1.9-7.3L3 9.3l6.6-.4z"/></svg>'],
        'shield' => ['label' => 'Perisai / Keamanan', 'svg' => '<svg viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M12 2l8 4v6c0 5-3.4 8.7-8 10-4.6-1.3-8-5-8-10V6l8-4zm0 4.2L7 8.4v3.6c0 3.4 2 6.3 5 7.5 3-1.2 5-4.1 5-7.5V8.4l-5-2.2z"/></svg>'],
        'check' => ['label' => 'Centang', 'svg' => '<svg viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M9 16.2l-3.5-3.5L4 14.2 9 19l11-11-1.4-1.4z"/></svg>'],
        'arrows' => ['label' => 'Panah Bolak-balik', 'svg' => '<svg viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M22 12l-4-4v3H4.8l3.6-3.6L7 6 2 11l5 5 1.4-1.4L4.8 11H18v3l4-4z"/></svg>'],
        'grid' => ['label' => 'Grid / Target Proyek', 'svg' => '<svg viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>'],
        'users' => ['label' => 'Orang / Komunitas', 'svg' => '<svg viewBox="0 0 24 24" width="26" height="26"><path fill="currentColor" d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>'],
        'pin' => ['label' => 'Lokasi / Pin Peta', 'svg' => '<svg viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg>'],
    ];
}

function cms_icon_svg(?string $key): string
{
    $icons = cms_icons();
    return $icons[$key]['svg'] ?? $icons['check']['svg'];
}

/* ---------------------------------------------------------------------
 * Settings (key/value)
 * ------------------------------------------------------------------- */

function cms_get_all_settings(): array
{
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        $stmt = cms_db()->query('SELECT setting_key, setting_value FROM cms_settings');
        foreach ($stmt as $row) {
            $cache[$row['setting_key']] = $row['setting_value'];
        }
    }
    return $cache;
}

function cms_get_setting(string $key, string $default = ''): string
{
    $all = cms_get_all_settings();
    return $all[$key] ?? $default;
}

function cms_set_setting(string $key, string $value): void
{
    $stmt = cms_db()->prepare(
        'INSERT INTO cms_settings (setting_key, setting_value) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
    );
    $stmt->execute([$key, $value]);
}

/* ---------------------------------------------------------------------
 * Content items (repeatable rows per section)
 * ------------------------------------------------------------------- */

function cms_get_content(string $section): array
{
    $stmt = cms_db()->prepare('SELECT * FROM cms_content_items WHERE section = ? ORDER BY sort_order ASC, id ASC');
    $stmt->execute([$section]);
    return $stmt->fetchAll();
}

function cms_get_content_item(int $id): ?array
{
    $stmt = cms_db()->prepare('SELECT * FROM cms_content_items WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function cms_next_sort_order(string $section): int
{
    $stmt = cms_db()->prepare('SELECT COALESCE(MAX(sort_order), 0) + 1 FROM cms_content_items WHERE section = ?');
    $stmt->execute([$section]);
    return (int)$stmt->fetchColumn();
}

function cms_save_content_item(array $data): int
{
    if (!empty($data['id'])) {
        $stmt = cms_db()->prepare(
            'UPDATE cms_content_items SET section=?, title=?, subtitle=?, body=?, image_path=?, icon_key=?, extra_json=? WHERE id=?'
        );
        $stmt->execute([
            $data['section'], $data['title'], $data['subtitle'], $data['body'],
            $data['image_path'], $data['icon_key'], $data['extra_json'], $data['id'],
        ]);
        return (int)$data['id'];
    }

    $stmt = cms_db()->prepare(
        'INSERT INTO cms_content_items (section, sort_order, title, subtitle, body, image_path, icon_key, extra_json)
         VALUES (?,?,?,?,?,?,?,?)'
    );
    $stmt->execute([
        $data['section'], $data['sort_order'] ?? cms_next_sort_order($data['section']),
        $data['title'], $data['subtitle'], $data['body'],
        $data['image_path'], $data['icon_key'], $data['extra_json'],
    ]);
    return (int)cms_db()->lastInsertId();
}

function cms_delete_content_item(int $id): void
{
    $item = cms_get_content_item($id);
    if ($item && !empty($item['image_path'])) {
        cms_delete_upload($item['image_path']);
    }
    $stmt = cms_db()->prepare('DELETE FROM cms_content_items WHERE id = ?');
    $stmt->execute([$id]);
}

function cms_move_content_item(int $id, string $direction): void
{
    $item = cms_get_content_item($id);
    if (!$item) return;

    $pdo = cms_db();
    $op = $direction === 'up' ? '<' : '>';
    $order = $direction === 'up' ? 'DESC' : 'ASC';

    $stmt = $pdo->prepare("SELECT * FROM cms_content_items WHERE section = ? AND sort_order $op ? ORDER BY sort_order $order LIMIT 1");
    $stmt->execute([$item['section'], $item['sort_order']]);
    $neighbor = $stmt->fetch();
    if (!$neighbor) return;

    $pdo->prepare('UPDATE cms_content_items SET sort_order = ? WHERE id = ?')->execute([$neighbor['sort_order'], $item['id']]);
    $pdo->prepare('UPDATE cms_content_items SET sort_order = ? WHERE id = ?')->execute([$item['sort_order'], $neighbor['id']]);
}

/* ---------------------------------------------------------------------
 * Upload gambar (logo, foto armada/portofolio/mitra)
 * ------------------------------------------------------------------- */

/**
 * Menangani unggahan file dari $_FILES[$field]. Mengembalikan path baru
 * (relatif ke root situs) atau $oldPath jika tidak ada file baru diunggah.
 * Melempar RuntimeException jika file tidak valid.
 */
function cms_handle_upload(string $field, ?string $oldPath = null): ?string
{
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return $oldPath;
    }

    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Unggah gagal (kode error: ' . $file['error'] . ').');
    }

    $maxSize = 4 * 1024 * 1024; // 4MB
    if ($file['size'] > $maxSize) {
        throw new RuntimeException('Ukuran file maksimal 4MB.');
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/svg+xml' => 'svg',
    ];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Format file tidak didukung. Gunakan JPG, PNG, WEBP, atau SVG.');
    }

    if ($mime === 'image/svg+xml') {
        $contents = file_get_contents($file['tmp_name']);
        if ($contents === false || stripos($contents, '<script') !== false || stripos($contents, 'onload=') !== false) {
            throw new RuntimeException('File SVG mengandung konten yang tidak diizinkan.');
        }
    } else {
        $info = @getimagesize($file['tmp_name']);
        if ($info === false) {
            throw new RuntimeException('File gambar tidak valid atau rusak.');
        }
    }

    if (!is_dir(CMS_UPLOAD_DIR)) {
        mkdir(CMS_UPLOAD_DIR, 0755, true);
    }

    $ext = $allowed[$mime];
    $name = bin2hex(random_bytes(8)) . '.' . $ext;
    $dest = CMS_UPLOAD_DIR . $name;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        throw new RuntimeException('Gagal menyimpan file ke server.');
    }
    chmod($dest, 0644);

    if ($oldPath && strpos($oldPath, CMS_UPLOAD_URL) === 0) {
        cms_delete_upload($oldPath);
    }

    return CMS_UPLOAD_URL . $name;
}

/** Hanya menghapus file yang memang berada di folder unggahan CMS (mencegah path traversal). */
function cms_delete_upload(?string $path): void
{
    if (!$path || strpos($path, CMS_UPLOAD_URL) !== 0) {
        return;
    }
    $full = __DIR__ . '/../../' . $path;
    if (is_file($full)) {
        @unlink($full);
    }
}

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
