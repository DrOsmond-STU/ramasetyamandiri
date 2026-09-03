<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
cms_require_login();
require_once __DIR__ . '/includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed.');
}
csrf_verify();

$id = (int)($_POST['id'] ?? 0);
$section = (string)($_POST['section'] ?? '');
if ($id) {
    cms_delete_content_item($id);
}

header('Location: content.php?section=' . urlencode($section));
exit;
