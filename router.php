<?php
// router.php — PHP built-in server router for Railway deployment
// Ensures "/" and other paths are routed correctly

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve existing static files (CSS, JS, images, fonts) directly
if ($uri !== '/' && file_exists(__DIR__ . $uri) && !is_dir(__DIR__ . $uri)) {
    return false; // let PHP built-in server handle it natively
}

// For directories, look for index.php inside
if (is_dir(__DIR__ . $uri)) {
    $index = rtrim(__DIR__ . $uri, '/') . '/index.php';
    if (file_exists($index)) {
        // Adjust script path and include
        $_SERVER['SCRIPT_FILENAME'] = $index;
        $_SERVER['SCRIPT_NAME']     = rtrim($uri, '/') . '/index.php';
        require $index;
        return true;
    }
}

// Fall back to root index.php (handles /)
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/index.php';
$_SERVER['SCRIPT_NAME']     = '/index.php';
require __DIR__ . '/index.php';
