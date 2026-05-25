<?php
// config/database.php
// แสดง error เฉพาะบน localhost เท่านั้น
$isLocalhost = in_array($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', ['127.0.0.1', '::1', '']);
ini_set('display_errors', $isLocalhost ? 1 : 0);
ini_set('display_startup_errors', $isLocalhost ? 1 : 0);
error_reporting(E_ALL);

// Railway MySQL env vars (fallback to localhost for development)
$host     = getenv('MYSQLHOST')     ?: getenv('DB_HOST')     ?: 'localhost';
$dbname   = getenv('MYSQLDATABASE') ?: getenv('DB_NAME')     ?: 'iephotoo_booking';
$username = getenv('MYSQLUSER')     ?: getenv('DB_USER')     ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: getenv('DB_PASS')     ?: '';
$port     = getenv('MYSQLPORT')     ?: getenv('DB_PORT')     ?: '3306';
$charset  = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

// Enable SSL for Railway MySQL (required for caching_sha2_password without RSA exchange)
$sslCa = file_exists('/etc/ssl/certs/ca-certificates.crt') ? '/etc/ssl/certs/ca-certificates.crt' : '';
$options = [
    PDO::ATTR_ERRMODE                      => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE           => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES             => false,
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
];
if ($sslCa) {
    $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
}

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
