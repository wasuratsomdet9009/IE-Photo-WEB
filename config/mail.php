<?php
// config/mail.php — EmailJS REST API integration
// ส่ง email ผ่าน EmailJS REST API จาก PHP backend

require_once __DIR__ . '/emailjs.php';

/**
 * Send email via EmailJS REST API
 * @param string $to - Recipient email
 * @param string $subject - Email subject
 * @param string $body - HTML email body
 * @param array|null $attachment - Not used with EmailJS (for backward compat)
 * @param string|null $templateId - Specific EmailJS template ID (optional)
 * @param array $extraParams - Extra template parameters
 * @return bool
 */
function sendEmail($to, $subject, $body, $attachment = null, $templateId = null, $extraParams = [])
{
    // Use the admin template if no specific template is provided
    $template = $templateId ?: EMAILJS_TEMPLATE_BOOKING_APPROVED;

    // Build template parameters
    $templateParams = array_merge([
        'to_email' => $to,
        'subject' => $subject,
        'message_html' => $body,
        'from_name' => 'IE-Photo KMITL',
    ], $extraParams);

    $payload = json_encode([
        'service_id' => EMAILJS_SERVICE_ID,
        'template_id' => $template,
        'user_id' => EMAILJS_PUBLIC_KEY,
        'accessToken' => EMAILJS_PRIVATE_KEY,
        'template_params' => $templateParams,
    ]);

    // Only call API if keys are configured
    if (EMAILJS_PUBLIC_KEY === 'YOUR_PUBLIC_KEY' || EMAILJS_PRIVATE_KEY === 'YOUR_PRIVATE_KEY') {
        // Fallback: Log email when EmailJS is not configured
        error_log("[EmailJS Mock] To: {$to} | Subject: {$subject}");
        error_log("[EmailJS Mock] Body preview: " . substr(strip_tags($body), 0, 200));
        return true;
    }

    $ch = curl_init(EMAILJS_API_URL);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'origin: https://iephoto.kmitl.ac.th',
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        error_log("[EmailJS Error] cURL: {$curlError}");
        return false;
    }

    if ($httpCode === 200) {
        error_log("[EmailJS OK] Sent to: {$to} | Subject: {$subject}");
        return true;
    } else {
        error_log("[EmailJS Error] HTTP {$httpCode} | Response: {$response}");
        return false;
    }
}

/**
 * Send email to ALL admins
 * @param PDO $pdo - Database connection
 * @param string $subject
 * @param string $body
 */
function sendEmailToAllAdmins($pdo, $subject, $body)
{
    $stmt = $pdo->query("SELECT email FROM users WHERE role = 'admin'");
    $admins = $stmt->fetchAll();
    foreach ($admins as $admin) {
        if (!empty($admin['email'])) {
            sendEmail($admin['email'], $subject, $body);
        }
    }
}
