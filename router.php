<?php
// router.php — PHP built-in server router for Railway deployment
// Handles: static files, PHP files, directory index, and the root "/"

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = __DIR__ . $uri;

// 1. Existing non-directory files → serve natively (CSS/JS/images/PHP pages)
if ($uri !== '/' && file_exists($file) && !is_dir($file)) {
    return false;
}

// 2. Directory → look for index.php inside
if (is_dir($file)) {
    $index = rtrim($file, '/') . '/index.php';
    if (file_exists($index)) {
        $_SERVER['SCRIPT_FILENAME'] = $index;
        $_SERVER['SCRIPT_NAME']     = rtrim($uri, '/') . '/index.php';
        require $index;
        return true;
    }
}

// 3. Root "/" (or anything not matched) → root index.php
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/index.php';
$_SERVER['SCRIPT_NAME']     = '/index.php';
require __DIR__ . '/index.php';
