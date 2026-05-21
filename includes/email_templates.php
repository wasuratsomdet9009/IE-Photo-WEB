<?php
// includes/email_templates.php

/**
 * Get the base URL for email links
 */
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'iephoto.online';
    // Detect the base path from the script
    $scriptDir = dirname(dirname($_SERVER['SCRIPT_NAME']));
    $basePath = rtrim($scriptDir, '/');
    return "{$protocol}://{$host}{$basePath}";
}

function getApprovedEmailTemplate($userName, $itemName, $startDate, $endDate, $consentToken = null) {
    $baseUrl = getBaseUrl();
    
    $consentButtons = '';
    if ($consentToken) {
        $approveUrl = htmlspecialchars("{$baseUrl}/api/email_consent.php?token={$consentToken}&action=approve");
        $rejectUrl = htmlspecialchars("{$baseUrl}/api/email_consent.php?token={$consentToken}&action=reject");
        $consentButtons = "
            <div style='margin: 24px 0; text-align: center;'>
                <p style='font-weight: 600; color: #333; margin-bottom: 16px;'>กรุณากดยืนยันรับทราบการจอง:</p>
                <a href='{$approveUrl}' style='display:inline-block; padding:14px 32px; background:linear-gradient(135deg,#22c55e,#16a34a); color:#fff; text-decoration:none; border-radius:12px; font-weight:700; font-size:16px; margin:6px; box-shadow:0 4px 14px rgba(34,197,94,.3);'>✅ ยืนยันรับทราบ</a>
                <a href='{$rejectUrl}' style='display:inline-block; padding:14px 32px; background:#f1f5f9; color:#64748b; text-decoration:none; border-radius:12px; font-weight:600; font-size:16px; margin:6px; border:2px solid #e2e8f0;'>❌ ปฏิเสธการจอง</a>
            </div>
        ";
    }

    $startFormatted = date('d/m/Y H:i', strtotime($startDate));
    $endFormatted = date('d/m/Y H:i', strtotime($endDate));

    return "
    <div style='font-family: Kanit, Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);'>
        <div style='background: linear-gradient(135deg, #F2531C, #EF3961); padding: 28px; text-align: center;'>
            <h2 style='color: #fff; margin: 0; font-size: 22px;'>📸 การจองได้รับการอนุมัติ</h2>
            <p style='color: rgba(255,255,255,0.85); margin: 8px 0 0; font-size: 14px;'>IE-Photo KMITL Booking System</p>
        </div>
        <div style='padding: 28px;'>
            <p style='font-size: 16px;'>สวัสดี <strong>{$userName}</strong>,</p>
            <p>การจองของคุณสำหรับ <strong style='color: #F2531C;'>{$itemName}</strong> ได้รับการอนุมัติเรียบร้อยแล้ว</p>
            <div style='background: #f8fafc; border-radius: 12px; padding: 16px; margin: 20px 0; border-left: 4px solid #F2531C;'>
                <p style='margin: 4px 0;'><strong>📅 เริ่มต้น:</strong> {$startFormatted}</p>
                <p style='margin: 4px 0;'><strong>📅 สิ้นสุด:</strong> {$endFormatted}</p>
            </div>
            {$consentButtons}
            <p style='font-size: 14px; color: #64748b;'>หากคุณแนบปฏิทิน (.ics) มาด้วย สามารถเพิ่มลงในปฏิทินของคุณได้เลย</p>
        </div>
        <div style='background: #f8fafc; padding: 16px; text-align: center; font-size: 12px; color: #94a3b8;'>
            &copy; " . date('Y') . " IE-Photo KMITL — Faculty of Industrial Education and Technology
        </div>
    </div>
    ";
}

function getReminderEmailTemplate($userName, $itemName, $customMessage = "") {
    $msgHtml = $customMessage ? "<div style='background:#fef2f2; border-radius:10px; padding:14px; margin:16px 0; border-left:4px solid #ef4444;'><strong>ข้อความจากแอดมิน:</strong> {$customMessage}</div>" : "";
    return "
    <div style='font-family: Kanit, Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);'>
        <div style='background: linear-gradient(135deg, #ef4444, #dc2626); padding: 28px; text-align: center;'>
            <h2 style='color: #fff; margin: 0; font-size: 22px;'>⏰ แจ้งเตือนการคืนอุปกรณ์</h2>
        </div>
        <div style='padding: 28px;'>
            <p style='font-size: 16px;'>สวัสดี <strong>{$userName}</strong>,</p>
            <p>นี่คือการแจ้งเตือนเกี่ยวกับอุปกรณ์ที่คุณยืมอยู่: <strong style='color: #ef4444;'>{$itemName}</strong></p>
            {$msgHtml}
            <p>กรุณาดำเนินการคืนอุปกรณ์ตามกำหนดเวลาเพื่อไม่ให้กระทบต่อผู้ใช้คนอื่น</p>
        </div>
        <div style='background: #f8fafc; padding: 16px; text-align: center; font-size: 12px; color: #94a3b8;'>
            &copy; " . date('Y') . " IE-Photo KMITL
        </div>
    </div>
    ";
}

function getUrgentCallTemplate($senderName, $receiverName) {
    return "
    <div style='font-family: Kanit, Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);'>
        <div style='background: linear-gradient(135deg, #f59e0b, #d97706); padding: 28px; text-align: center;'>
            <h2 style='color: #fff; margin: 0; font-size: 22px;'>🔔 แจ้งเตือนเรียกด่วน</h2>
        </div>
        <div style='padding: 28px;'>
            <p style='font-size: 16px;'>สวัสดี <strong>{$receiverName}</strong>,</p>
            <p><strong style='color: #f59e0b;'>{$senderName}</strong> กำลังพยายามติดต่อคุณอย่างเร่งด่วนผ่านระบบ IE-Photo</p>
            <p>กรุณาตรวจสอบระบบหรือติดต่อกลับโดยเร็ว</p>
        </div>
        <div style='background: #f8fafc; padding: 16px; text-align: center; font-size: 12px; color: #94a3b8;'>
            &copy; " . date('Y') . " IE-Photo KMITL
        </div>
    </div>
    ";
}

function getBookingPendingEmailTemplate($userName, $itemName, $bookingType) {
    $typeLabel = $bookingType === 'equipment' ? 'อุปกรณ์' : 'สตูดิโอ';
    return "
    <div style='font-family: Kanit, Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);'>
        <div style='background: linear-gradient(135deg, #F2531C, #ff6b35); padding: 28px; text-align: center;'>
            <h2 style='color: #fff; margin: 0; font-size: 22px;'>📋 คำขอจอง{$typeLabel}ใหม่</h2>
        </div>
        <div style='padding: 28px;'>
            <p style='font-size: 16px;'>สวัสดีแอดมิน,</p>
            <p>มีคำขอจอง{$typeLabel}ใหม่จาก <strong style='color: #F2531C;'>{$userName}</strong></p>
            <p>รายการ: <strong>{$itemName}</strong></p>
            <p>กรุณาเข้าสู่ระบบเพื่อตรวจสอบและอนุมัติคำขอ</p>
        </div>
        <div style='background: #f8fafc; padding: 16px; text-align: center; font-size: 12px; color: #94a3b8;'>
            &copy; " . date('Y') . " IE-Photo KMITL
        </div>
    </div>
    ";
}
?>
