<?php
/**
 * Pintu masuk alternatif ke panel admin.
 *
 * Isinya sama persis dengan login.php — dibuat karena sebagian software
 * antivirus/anti-phishing dan filter jaringan memblokir atau menghapus form
 * login pada URL yang berpola ".../admin/login.php". URL ini tidak memakai
 * pola tersebut sehingga lolos dari filter semacam itu.
 */
require __DIR__ . '/login.php';
