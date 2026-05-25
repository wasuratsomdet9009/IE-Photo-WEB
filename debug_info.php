<?php
// Temporary diagnostic page - REMOVE AFTER DEBUGGING
if (($_GET['key'] ?? '') !== 'railway-debug-2026') { http_response_code(403); die('Forbidden'); }
echo '<h2>PHP Extensions:</h2><pre>';
$exts = get_loaded_extensions();
sort($exts);
echo implode("\n", $exts);
echo '</pre>';
echo '<h2>PDO Drivers:</h2><pre>';
echo implode("\n", PDO::getAvailableDrivers());
echo '</pre>';
echo '<h2>PHP Version:</h2><pre>' . phpversion() . '</pre>';
echo '<h2>ENV vars (MySQL):</h2><pre>';
foreach (['MYSQLHOST','MYSQLPORT','MYSQLUSER','MYSQLDATABASE'] as $k) {
    echo $k . '=' . (getenv($k) ?: '(not set)') . "\n";
}
echo '</pre>';
