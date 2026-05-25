<?php
// auth/verify.php — ยืนยันอีเมลจาก link
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/mail.php';

$token = trim($_GET['token'] ?? '');
$status = 'invalid'; // invalid | already_verified | success | resent

// ----- RESEND verification email -----
if (isset($_GET['resend']) && !empty($_GET['email'])) {
    $resendEmail = trim($_GET['email']);
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, student_id, email_verified, email_verify_token FROM users WHERE email = ?");
    $stmt->execute([$resendEmail]);
    $u = $stmt->fetch();

    if ($u && !$u['email_verified']) {
        // throttle: ส่งได้ทุก 60 วินาที
        $chk = $pdo->prepare("SELECT email_verify_sent_at FROM users WHERE id = ?");
        $chk->execute([$u['id']]);
        $row = $chk->fetch();
        $lastSent = $row['email_verify_sent_at'] ? strtotime($row['email_verify_sent_at']) : 0;

        if (time() - $lastSent >= 60) {
            $newToken = bin2hex(random_bytes(32));
            $pdo->prepare("UPDATE users SET email_verify_token = ?, email_verify_sent_at = NOW() WHERE id = ?")
                ->execute([$newToken, $u['id']]);

            $scheme    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host      = $_SERVER['HTTP_HOST'];
            $dir       = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
            $verifyUrl = $scheme . '://' . $host . $dir . '/verify.php?token=' . $newToken;

            $emailBody = "
            <div style='font-family:Arial,sans-serif;max-width:520px;margin:auto;padding:24px;border:1px solid #e0e0e0;border-radius:12px;'>
                <h2 style='color:#7c3aed;'>ยืนยันอีเมล IE-Photo KMITL</h2>
                <p>สวัสดี <strong>" . htmlspecialchars($u['first_name']) . "</strong> 👋 นี่คือลิงก์ยืนยันใหม่ของคุณ</p>
                <div style='text-align:center;margin:28px 0;'>
                    <a href='" . $verifyUrl . "'
                       style='background:#7c3aed;color:#fff;padding:14px 32px;border-radius:8px;text-decoration:none;font-weight:bold;font-size:16px;display:inline-block;'>
                        ✅ ยืนยันอีเมล
                    </a>
                </div>
                <p style='font-size:12px;color:#999;word-break:break-all;'>" . $verifyUrl . "</p>
            </div>";

            sendEmail($resendEmail, 'ยืนยันอีเมล IE-Photo KMITL (ส่งใหม่)', $emailBody);
            $status = 'resent';
        } else {
            $waitSec = 60 - (time() - $lastSent);
            $status  = 'throttled';
            $throttleWait = $waitSec;
        }
    } else {
        $status = $u ? 'already_verified' : 'invalid';
    }

// ----- VERIFY token -----
} elseif (!empty($token)) {
    $stmt = $pdo->prepare("SELECT id, email_verified FROM users WHERE email_verify_token = ?");
    $stmt->execute([$token]);
    $u = $stmt->fetch();

    if (!$u) {
        $status = 'invalid';
    } elseif ($u['email_verified']) {
        $status = 'already_verified';
    } else {
        $pdo->prepare("UPDATE users SET email_verified = 1, email_verify_token = NULL WHERE id = ?")
            ->execute([$u['id']]);
        $status = 'success';
    }
}

$base_url = '../';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="auth-wrapper">
    <div class="glass-card" style="max-width:460px;width:100%;text-align:center;">

        <?php if ($status === 'success'): ?>
            <div style="font-size:3.5rem;margin-bottom:1rem;">✅</div>
            <h2 style="color:var(--success);margin-bottom:.5rem;">ยืนยันอีเมลสำเร็จ!</h2>
            <p class="text-muted">อีเมล <strong>@kmitl.ac.th</strong> ของคุณได้รับการยืนยันแล้ว</p>
            <a href="login.php" class="btn btn-primary w-100 mt-4">
                <i class="ph-bold ph-sign-in"></i> เข้าสู่ระบบ
            </a>

        <?php elseif ($status === 'already_verified'): ?>
            <div style="font-size:3.5rem;margin-bottom:1rem;">ℹ️</div>
            <h2 style="margin-bottom:.5rem;">ยืนยันแล้ว</h2>
            <p class="text-muted">อีเมลนี้ได้รับการยืนยันไปแล้ว</p>
            <a href="login.php" class="btn btn-primary w-100 mt-4">
                <i class="ph-bold ph-sign-in"></i> เข้าสู่ระบบ
            </a>

        <?php elseif ($status === 'resent'): ?>
            <div style="font-size:3.5rem;margin-bottom:1rem;">📧</div>
            <h2 style="margin-bottom:.5rem;">ส่งอีเมลใหม่แล้ว!</h2>
            <p class="text-muted">กรุณาตรวจสอบกล่องข้อความที่ <strong><?php echo htmlspecialchars($_GET['email'] ?? ''); ?></strong></p>
            <a href="login.php" class="btn btn-outline w-100 mt-4">
                <i class="ph-bold ph-arrow-left"></i> กลับหน้า Login
            </a>

        <?php elseif ($status === 'throttled'): ?>
            <div style="font-size:3.5rem;margin-bottom:1rem;">⏳</div>
            <h2 style="margin-bottom:.5rem;">รอสักครู่</h2>
            <p class="text-muted">ส่งอีเมลได้ทุก 60 วินาที กรุณารออีก <strong><?php echo $throttleWait ?? 60; ?></strong> วินาที</p>
            <a href="login.php" class="btn btn-outline w-100 mt-4">
                <i class="ph-bold ph-arrow-left"></i> กลับหน้า Login
            </a>

        <?php else: ?>
            <div style="font-size:3.5rem;margin-bottom:1rem;">❌</div>
            <h2 style="color:var(--danger);margin-bottom:.5rem;">ลิงก์ไม่ถูกต้อง</h2>
            <p class="text-muted">ลิงก์ยืนยันหมดอายุหรือใช้ไปแล้ว กรุณาขอลิงก์ใหม่</p>
            <a href="login.php" class="btn btn-primary w-100 mt-4">
                <i class="ph-bold ph-arrow-left"></i> กลับหน้า Login
            </a>
        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
