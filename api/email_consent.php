<?php
// api/email_consent.php — Handle consent response from email links
require_once __DIR__ . '/../config/database.php';

$token = $_GET['token'] ?? '';
$action = $_GET['action'] ?? '';

$pageTitle = 'IE-Photo — ยืนยันการจอง';
$message = '';
$success = false;

if (empty($token) || !in_array($action, ['approve', 'reject'])) {
    $message = 'ลิงก์ไม่ถูกต้องหรือหมดอายุ กรุณาติดต่อผู้ดูแลระบบ';
} else {
    // Find booking with this consent token
    $stmt = $pdo->prepare("SELECT id, status, consent_responded_at FROM bookings WHERE consent_token = ?");
    $stmt->execute([$token]);
    $booking = $stmt->fetch();

    if (!$booking) {
        $message = 'ไม่พบรายการจองที่เชื่อมกับลิงก์นี้ อาจหมดอายุแล้ว';
    } elseif ($booking['consent_responded_at'] !== null) {
        $message = 'คุณได้ตอบรับ/ปฏิเสธคำขอนี้ไปแล้ว ไม่สามารถดำเนินการซ้ำได้';
    } else {
        $pdo->beginTransaction();
        try {
            // Update booking consent
            $update = $pdo->prepare("UPDATE bookings SET consent_responded_at = NOW() WHERE id = ?");
            $update->execute([$booking['id']]);

            // Log in email_consents table
            $log = $pdo->prepare("INSERT INTO email_consents (booking_id, token, action, responded_at) VALUES (?, ?, ?, NOW())");
            $log->execute([$booking['id'], $token, $action]);

            // Insert feed message
            $statusText = $action === 'approve' ? 'ผู้จองยืนยันรับทราบ ✅' : 'ผู้จองปฏิเสธการจอง ❌';
            $feedMsg = "อัปเดต: การจอง #{$booking['id']} — {$statusText} (ตอบกลับผ่าน Email)";
            $feedInsert = $pdo->prepare("INSERT INTO feeds (booking_id, message) VALUES (?, ?)");
            $feedInsert->execute([$booking['id'], $feedMsg]);

            // If user rejects, update booking status
            if ($action === 'reject') {
                $reject = $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?");
                $reject->execute([$booking['id']]);
            }

            $pdo->commit();
            $success = true;

            if ($action === 'approve') {
                $message = 'ขอบคุณที่ยืนยันรับทราบการจอง! ข้อมูลของคุณถูกบันทึกเรียบร้อยแล้ว';
            } else {
                $message = 'คุณปฏิเสธการจองเรียบร้อยแล้ว ระบบจะยกเลิกรายการนี้';
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Kanit',sans-serif;background:#f0f2f5;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
        .card{background:rgba(255,255,255,.85);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.8);border-radius:24px;padding:2.5rem;max-width:480px;width:100%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,.08)}
        .icon{width:80px;height:80px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;font-size:2.5rem}
        .icon.success{background:rgba(34,197,94,.1);color:#22c55e}
        .icon.error{background:rgba(239,68,68,.1);color:#ef4444}
        h1{font-size:1.5rem;margin-bottom:.8rem;color:#1a1a2e}
        p{color:#64748b;font-size:1rem;line-height:1.6;margin-bottom:1.5rem}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:.8rem 2rem;border-radius:12px;font-weight:600;text-decoration:none;font-size:.95rem;transition:all .25s;font-family:inherit;border:none;cursor:pointer}
        .btn-primary{background:linear-gradient(135deg,#F2531C,#ff6b35);color:#fff;box-shadow:0 4px 14px rgba(242,83,28,.25)}
        .btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(242,83,28,.3)}
        .bg-orbs{position:fixed;inset:0;z-index:-1;overflow:hidden;pointer-events:none}
        .bg-orbs::before,.bg-orbs::after{content:'';position:absolute;border-radius:50%;filter:blur(100px);opacity:.3;animation:orbFloat 20s ease-in-out infinite alternate}
        .bg-orbs::before{width:50vw;height:50vw;max-width:500px;max-height:500px;background:#F2531C;top:-15%;left:-10%}
        .bg-orbs::after{width:40vw;height:40vw;max-width:400px;max-height:400px;background:#EF3961;bottom:-15%;right:-10%;animation-delay:-7s}
        @keyframes orbFloat{0%{transform:translate(0,0) scale(1)}50%{transform:translate(4vw,4vh) scale(1.1)}100%{transform:translate(-2vw,2vh) scale(.95)}}
    </style>
</head>
<body>
    <div class="bg-orbs"></div>
    <div class="card">
        <div class="icon <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $success ? '✅' : '⚠️'; ?>
        </div>
        <h1><?php echo $success ? ($action === 'approve' ? 'ยืนยันสำเร็จ!' : 'ปฏิเสธสำเร็จ') : 'ไม่สามารถดำเนินการได้'; ?></h1>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="../index.html" class="btn btn-primary">กลับหน้าหลัก</a>
    </div>
</body>
</html>
