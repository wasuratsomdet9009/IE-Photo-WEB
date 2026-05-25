<?php
// includes/header.php — Mobile-First with Bottom Nav
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/database.php';
if (!isset($base_url)) $base_url = '../';

// Detect current page for active state
$currentPage = basename($_SERVER['SCRIPT_NAME'], '.php');
function isActive($page) {
    global $currentPage;
    return $currentPage === $page ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#ffffff">
    <title>IE-Photo Booking System</title>
    <meta name="description" content="ระบบจองอุปกรณ์ถ่ายภาพและสตูดิโอ IE-Photo KMITL">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/glassmorphism.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <div class="bg-orbs"></div>

    <!-- Top Navbar -->
    <nav class="glass-navbar">
        <div class="nav-container">
            <a href="<?php echo $base_url; ?>auth/login.php" class="nav-brand">
                <i class="ph-bold ph-camera" style="margin-right:3px"></i> IE-PHOTO
            </a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <button class="mobile-toggle" id="mobile-toggle-btn" aria-label="Menu">
                    <i class="ph-bold ph-list"></i>
                </button>
            <?php endif; ?>
            <div class="nav-links" id="nav-links">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if($_SESSION['role'] === 'admin'): ?>
                        <a href="<?php echo $base_url; ?>admin/dashboard.php" class="<?php echo isActive('dashboard');?>"><i class="ph ph-squares-four"></i> แดชบอร์ด</a>
                        <a href="<?php echo $base_url; ?>admin/bookings.php" class="<?php echo isActive('bookings');?>"><i class="ph ph-list-checks"></i> รายการจอง</a>
                        <a href="<?php echo $base_url; ?>member/borrow_form.php" class="<?php echo isActive('borrow_form');?>"><i class="ph ph-hand-grabbing"></i> ยืมอุปกรณ์</a>
                        <a href="<?php echo $base_url; ?>guest/studio_booking.php" class="<?php echo isActive('studio_booking');?>"><i class="ph ph-video-camera"></i> จองสตูดิโอ</a>
                        <a href="<?php echo $base_url; ?>admin/inventory.php" class="<?php echo isActive('inventory');?>"><i class="ph ph-package"></i> คลังอุปกรณ์</a>
                        <a href="<?php echo $base_url; ?>admin/tasks.php" class="<?php echo isActive('tasks');?>"><i class="ph ph-kanban"></i> จัดการงาน</a>
                        <a href="<?php echo $base_url; ?>member/calendar.php" class="<?php echo isActive('calendar');?>"><i class="ph ph-calendar"></i> ปฏิทิน</a>
                        <a href="<?php echo $base_url; ?>member/contact_list.php" class="<?php echo isActive('contact_list');?>"><i class="ph ph-address-book"></i> สมาชิก</a>
                        <a href="<?php echo $base_url; ?>admin/users.php" class="<?php echo isActive('users');?>"><i class="ph ph-users"></i> จัดการสมาชิก</a>
                        <a href="<?php echo $base_url; ?>admin/contact_manage.php" class="<?php echo isActive('contact_manage');?>"><i class="ph ph-users-three"></i> จัดการระบบ</a>
                    <?php else: ?>
                        <a href="<?php echo $base_url; ?>member/feed.php" class="<?php echo isActive('feed');?>"><i class="ph ph-house"></i> ฟีด</a>
                        <a href="<?php echo $base_url; ?>member/borrow_form.php" class="<?php echo isActive('borrow_form');?>"><i class="ph ph-hand-grabbing"></i> ยืมอุปกรณ์</a>
                        <a href="<?php echo $base_url; ?>guest/studio_booking.php" class="<?php echo isActive('studio_booking');?>"><i class="ph ph-video-camera"></i> จองสตูดิโอ</a>
                        <a href="<?php echo $base_url; ?>member/my_tasks.php" class="<?php echo isActive('my_tasks');?>"><i class="ph ph-kanban"></i> งานของฉัน</a>
                        <a href="<?php echo $base_url; ?>member/calendar.php" class="<?php echo isActive('calendar');?>"><i class="ph ph-calendar"></i> ปฏิทิน</a>
                        <a href="<?php echo $base_url; ?>member/contact_list.php" class="<?php echo isActive('contact_list');?>"><i class="ph ph-address-book"></i> สมาชิก</a>
                    <?php endif; ?>
                    <div class="nav-profile">
                        <a href="<?php echo $base_url; ?>member/my_bookings.php"><i class="ph-bold ph-list-dashes"></i> การจองของฉัน</a>
                        <a href="<?php echo $base_url; ?>member/profile.php"><i class="ph-bold ph-user-circle"></i> โปรไฟล์</a>
                        <a href="<?php echo $base_url; ?>auth/logout.php" class="btn btn-outline btn-sm" style="width:100%;justify-content:center;">ออกจากระบบ</a>
                    </div>
                <?php else: ?>
                    <a href="<?php echo $base_url; ?>guest/studio_booking.php"><i class="ph ph-calendar-plus"></i> จองสตูดิโอ</a>
                    <a href="<?php echo $base_url; ?>auth/login.php"><i class="ph ph-sign-in"></i> เข้าสู่ระบบ</a>
                    <a href="<?php echo $base_url; ?>auth/register.php" class="btn btn-primary btn-sm">สมัครสมาชิก</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Nav Overlay (Mobile) -->
    <div class="nav-overlay" id="nav-overlay"></div>

    <!-- Main Content -->
    <main class="main-content">

    <script>
    (function() {
        function initNav() {
            var toggleBtn = document.getElementById('mobile-toggle-btn');
            var navLinks  = document.getElementById('nav-links');
            var overlay   = document.getElementById('nav-overlay');
            if (!toggleBtn || !navLinks) return;

            /* ปิดเมนู */
            function closeMenu() {
                navLinks.classList.remove('active');
                if (overlay) overlay.classList.remove('active');
                var icon = toggleBtn.querySelector('i');
                if (icon) { icon.classList.remove('ph-x'); icon.classList.add('ph-list'); }
                document.body.style.overflow = '';
            }

            /* เปิด/ปิดเมนู */
            function onToggle(e) {
                e.stopPropagation();
                var isOpen = navLinks.classList.toggle('active');
                if (overlay) overlay.classList.toggle('active', isOpen);
                var icon = toggleBtn.querySelector('i');
                if (icon) {
                    icon.classList.toggle('ph-list', !isOpen);
                    icon.classList.toggle('ph-x', isOpen);
                }
                /* ล็อก scroll body เมื่อเมนูเปิด (ป้องกัน scroll ผ่านใต้เมนู) */
                document.body.style.overflow = isOpen ? 'hidden' : '';
            }

            /* ใช้ทั้ง click และ touchend เพื่อรองรับทุก device */
            toggleBtn.addEventListener('click', onToggle);

            /* ปิดเมื่อกด overlay */
            if (overlay) {
                overlay.addEventListener('click', closeMenu);
                overlay.addEventListener('touchend', function(e) {
                    e.preventDefault();
                    closeMenu();
                });
            }

            /* กดลิงก์ในเมนู → ปิดเมนูแล้วค่อย navigate */
            navLinks.querySelectorAll('a').forEach(function(a) {
                a.addEventListener('click', function(e) {
                    /* หยุด event ไม่ให้ bubble ขึ้นไปโดน overlay */
                    e.stopPropagation();
                    closeMenu();
                    /* ถ้า href ปกติ ให้ browser navigate ตามเดิม */
                });
            });

            /* ปิดเมื่อกด Escape */
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeMenu();
            });

            /* ปิดเมื่อหมุนจอ */
            window.addEventListener('orientationchange', function() {
                setTimeout(closeMenu, 300);
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initNav);
        } else {
            initNav();
        }
    })();
    </script>
