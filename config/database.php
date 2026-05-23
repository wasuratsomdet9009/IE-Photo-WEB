<?php
// config/database.php
// แสดง error เฉพาะบน localhost เท่านั้น
$isLocalhost = in_array($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', ['127.0.0.1', '::1', '']);
ini_set('display_errors', $isLocalhost ? 1 : 0);
ini_set('display_startup_errors', $isLocalhost ? 1 : 0);
error_reporting(E_ALL);

$host = 'localhost';
$dbname = 'iephotoo_booking';
$username = 'root'; // localhost
$password = '';    // localhost
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    // Log error แต่ไม่แสดง DB details ต่อผู้ใช้
    error_log("DB connection failed: " . $e->getMessage());
    $isLocalhost = in_array($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', ['127.0.0.1', '::1', '']);
    die($isLocalhost
        ? "Database connection failed: " . $e->getMessage()
        : "ระบบขัดข้องชั่วคราว กรุณาลองใหม่อีกครั้ง");
}
