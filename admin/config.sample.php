<?php
/**
 * Salin file ini menjadi "config.php" (di folder yang sama) dan isi
 * dengan kredensial database yang sebenarnya. File "config.php" TIDAK
 * boleh masuk ke git — sudah didaftarkan di .gitignore.
 */
return [
    // Di hosting ini database berjalan di server TERPISAH (bukan di server web),
    // jadi db_host harus hostname MySQL remote-nya — cek lewat cPanel > MySQL
    // Databases, atau panggil UAPI Mysql::get_server_information.
    'db_host' => 'aprica-db.id.rapidplex.com',
    // Isi db_socket hanya jika MySQL berjalan lokal lewat unix socket (biarkan
    // kosong untuk koneksi TCP ke db_host di atas, seperti kasus di hosting ini).
    'db_socket' => '',
    'db_name' => 'ramasety_den821',
    'db_user' => 'ramasety_cms',
    'db_pass' => 'GANTI_DENGAN_PASSWORD_ASLI',
    'session_name' => 'rsm_admin_sess',
];
