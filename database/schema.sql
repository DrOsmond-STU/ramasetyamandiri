-- Skema database CMS PT Rama Setya Mandiri.
-- Referensi saja — tabel ini dibuat otomatis (idempotent) oleh admin/install.php
-- saat pertama kali dijalankan. Tidak perlu dieksekusi manual dalam kondisi normal.

CREATE TABLE IF NOT EXISTS cms_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(60) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    must_change_password TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pengaturan tunggal (logo, teks hero, kontak, dsb) sebagai key/value.
CREATE TABLE IF NOT EXISTS cms_settings (
    setting_key VARCHAR(100) NOT NULL PRIMARY KEY,
    setting_value LONGTEXT NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Konten berulang (statistik, layanan, armada, mengapa kami, mitra, logo mitra, portofolio).
-- Dibedakan lewat kolom `section`; lihat admin/includes/functions.php -> cms_sections().
CREATE TABLE IF NOT EXISTS cms_content_items (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
