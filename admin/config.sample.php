<?php
/**
 * Salin file ini menjadi "config.php" (di folder yang sama) dan isi
 * dengan kredensial database yang sebenarnya. File "config.php" TIDAK
 * boleh masuk ke git — sudah didaftarkan di .gitignore.
 */
return [
    'db_host' => 'localhost',
    // Isi db_socket jika koneksi TCP/socket default tidak berhasil (lihat error
    // "SQLSTATE[HY000] [2002]"). Di hosting ini soket MySQL yang benar ada di
    // /tmp/mysql.sock, bukan lokasi default PHP. Kosongkan ('') untuk memakai
    // db_host di atas.
    'db_socket' => '/tmp/mysql.sock',
    'db_name' => 'ramasety_den821',
    'db_user' => 'ramasety_cms',
    'db_pass' => 'GANTI_DENGAN_PASSWORD_ASLI',
    'session_name' => 'rsm_admin_sess',
];
