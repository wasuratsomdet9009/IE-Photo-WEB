<?php
// config/emailjs.php — EmailJS Configuration
// ตั้งค่า EmailJS credentials ที่นี่
// สมัครได้ที่ https://www.emailjs.com/

define('EMAILJS_SERVICE_ID', 'service_1a47klq');     // ← ใส่ Service ID ของคุณ
define('EMAILJS_PUBLIC_KEY', 'kEpZ0GchxY4JmPsjT');      // ← ใส่ Public Key
define('EMAILJS_PRIVATE_KEY', 'pY-pMUobnuwEj6FOtDFrq');    // ← ใส่ Private Key (สำหรับ REST API)

// Template IDs
define('EMAILJS_TEMPLATE_BOOKING_APPROVED', 'BOOKING_APPROVED45'); // Template แจ้งแอดมินมี booking ใหม่
define('EMAILJS_TEMPLATE_BOOKING_PENDING', 'BOOKING_PENDING23');   // Template ยืนยันการรับคำขอจองถึงผู้ใช้งาน

// EmailJS REST API endpoint
define('EMAILJS_API_URL', 'https://api.emailjs.com/api/v1.0/email/send');
