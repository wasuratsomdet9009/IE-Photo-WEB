<?php
// index.php — Entry point
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: /admin/dashboard.php");
    } else {
        header("Location: /member/feed.php");
    }
} else {
    // ไม่ได้ login → ไปหน้าจองสตูดิโอ (public)
    header("Location: /guest/studio_booking.php");
}
exit;
