<?php
// auth/register.php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/mail.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = isset($_POST['student_id']) ? trim($_POST['student_id']) : '';
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name  = isset($_POST['last_name'])  ? trim($_POST['last_name'])  : '';
    $password   = isset($_POST['password'])   ? $_POST['password']         : '';
    $phone      = isset($_POST['phone'])      ? trim($_POST['phone'])      : '';

    if (!preg_match('/^[0-9]{8}$/', $student_id)) {
        $error = 'รหัสนักศึกษาต้องเป็นตัวเลข 8 หลักเท่านั้น';
    } elseif (empty($first_name) || mb_strlen($first_name) > 100) {
        $error = empty($first_name) ? 'กรุณากรอกชื่อจริง' : 'ชื่อจริงยาวเกินไป (สูงสุด 100 ตัวอักษร)';
    } elseif (empty($last_name) || mb_strlen($last_name) > 100) {
        $error = empty($last_name) ? 'กรุณากรอกนามสกุล' : 'นามสกุลยาวเกินไป (สูงสุด 100 ตัวอักษร)';
    } elseif (!empty($phone) && !preg_match('/^0[0-9]{8,9}$/', $phone)) {
        $error = 'รูปแบบเบอร์โทรไม่ถูกต้อง (ต้องขึ้นต้นด้วย 0 และมี 9-10 หลัก)';
    } elseif (empty($password) || strlen($password) < 6) {
        $error = 'กรุณากรอกรหัสผ่านอย่างน้อย 6 ตัวอักษร';
    } elseif (strlen($password) > 255) {
        $error = 'รหัสผ่านยาวเกินไป';
    } else {
        $email = $student_id . '@kmitl.ac.th';
        $stmt = $pdo->prepare("SELECT id FROM users WHERE student_id = ? OR email = ?");
        $stmt->execute([$student_id, $email]);
        if ($stmt->fetch()) {
            $error = 'รหัสนักศึกษาหรืออีเมลนี้มีอยู่ในระบบแล้ว';
        } else {
            $hashedPass  = password_hash($password, PASSWORD_DEFAULT);
            $verifyToken = bin2hex(random_bytes(32));
            $insertStmt  = $pdo->prepare("INSERT INTO users (student_id, email, password, phone, first_name, last_name, role, email_verified, email_verify_token, email_verify_sent_at) VALUES (?, ?, ?, ?, ?, ?, 'member', 0, ?, NOW())");
            if ($insertStmt->execute([$student_id, $email, $hashedPass, $phone, $first_name, $last_name, $verifyToken])) {
                // สร้าง verification link
                $scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $host     = $_SERVER['HTTP_HOST'];
                $dir      = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
                $verifyUrl = $scheme . '://' . $host . $dir . '/verify.php?token=' . $verifyToken;

                // ส่งอีเมลยืนยัน
                $emailBody = "
                <div style='font-family:Arial,sans-serif;max-width:520px;margin:auto;padding:24px;border:1px solid #e0e0e0;border-radius:12px;'>
                    <h2 style='color:#7c3aed;margin-bottom:8px;'>ยืนยันอีเมล IE-Photo KMITL</h2>
                    <p>สวัสดี <strong>" . htmlspecialchars($first_name . ' ' . $last_name) . "</strong> 👋</p>
                    <p>คุณได้สมัครสมาชิกด้วยรหัสนักศึกษา <strong>" . htmlspecialchars($student_id) . "</strong></p>
                    <p>กรุณากดปุ่มด้านล่างเพื่อยืนยันอีเมลของคุณ:</p>
                    <div style='text-align:center;margin:28px 0;'>
                        <a href='" . $verifyUrl . "'
                           style='background:#7c3aed;color:#fff;padding:14px 32px;border-radius:8px;text-decoration:none;font-weight:bold;font-size:16px;display:inline-block;'>
                            ✅ ยืนยันอีเมล
                        </a>
                    </div>
                    <p style='font-size:13px;color:#666;'>หรือคัดลอกลิงก์นี้ไปวางในเบราว์เซอร์:</p>
                    <p style='font-size:12px;color:#999;word-break:break-all;'>" . $verifyUrl . "</p>
                    <hr style='margin:20px 0;border:none;border-top:1px solid #eee;'>
                    <p style='font-size:12px;color:#aaa;'>หากคุณไม่ได้สมัครสมาชิก ให้ละเว้นอีเมลนี้</p>
                </div>";

                sendEmail($email, 'ยืนยันอีเมล IE-Photo KMITL', $emailBody);

                header("Location: login.php?verify_sent=1&email=" . urlencode($email));
                exit;
            } else {
                $error = 'เกิดข้อผิดพลาดในการสมัครสมาชิก โปรดลองอีกครั้ง';
            }
        }
    }
}

$base_url = '../';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="auth-wrapper">
    <div class="glass-card" style="max-width:450px; width:100%;">
        <div class="text-center mb-4">
            <div style="width:64px;height:64px;border-radius:20px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;box-shadow:0 8px 24px var(--primary-glow);">
                <i class="ph-bold ph-user-plus" style="font-size:1.8rem;color:#fff"></i>
            </div>
            <h2 style="font-size:1.5rem;margin-bottom:.3rem;">สมัครสมาชิก</h2>
            <p class="text-muted" style="font-size:.9rem;">เข้าร่วมครอบครัว IE-Photo KMITL</p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger"><i class="ph-bold ph-warning-circle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><i class="ph-bold ph-check-circle"></i> <?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="student_id"><i class="ph ph-identification-card"></i> รหัสนักศึกษา (8 หลัก)</label>
                <div class="input-group">
                    <input type="text" id="student_id" name="student_id" class="form-control"
                           placeholder="6XXXXXXX" pattern="[0-9]{8}" maxlength="8"
                           value="<?php echo htmlspecialchars($_POST['student_id'] ?? ''); ?>" required>
                    <span class="input-group-text">@kmitl.ac.th</span>
                </div>
                <small class="text-muted" style="font-size:.8rem;margin-top:.3rem;display:block;">
                    <i class="ph ph-info"></i> ระบบจะใช้อีเมลสถาบันในการรับข่าวสาร
                </small>
            </div>

            <!-- ชื่อ-นามสกุล -->
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name"><i class="ph ph-user"></i> ชื่อจริง</label>
                    <input type="text" id="first_name" name="first_name" class="form-control"
                           placeholder="เช่น สมชาย"
                           value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name"><i class="ph ph-user"></i> นามสกุล</label>
                    <input type="text" id="last_name" name="last_name" class="form-control"
                           placeholder="เช่น ใจดี"
                           value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password"><i class="ph ph-lock"></i> กำหนดรหัสผ่าน</label>
                <div style="position:relative;">
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="อย่างน้อย 6 ตัวอักษร" required minlength="6"
                           style="padding-right:3rem;">
                    <button type="button" onclick="togglePwd()" tabindex="-1"
                            style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);
                                   background:none;border:none;cursor:pointer;color:var(--text-muted);
                                   padding:0;line-height:1;font-size:1.1rem;">
                        <i class="ph ph-eye" id="pwd-eye"></i>
                    </button>
                </div>
                <small class="text-muted" style="font-size:.8rem;margin-top:.3rem;display:block;">
                    <i class="ph ph-info"></i> อย่างน้อย 6 ตัวอักษร
                </small>
            </div>

            <div class="form-group">
                <label for="phone"><i class="ph ph-phone"></i> เบอร์โทรศัพท์
                    <span style="font-weight:400;color:var(--text-muted);">(เลือกกรอก)</span>
                </label>
                <input type="tel" id="phone" name="phone" class="form-control"
                       placeholder="0XXXXXXXXX" maxlength="10"
                       value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-2">
                <i class="ph-bold ph-user-plus"></i> สมัครสมาชิก
            </button>
            <div class="text-center mt-4">
                <p class="text-muted" style="font-size:.9rem;">
                    มีบัญชีอยู่แล้ว? <a href="login.php" style="font-weight:700;">เข้าสู่ระบบที่นี่</a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
function togglePwd() {
    var inp = document.getElementById('password');
    var eye = document.getElementById('pwd-eye');
    if (inp.type === 'password') {
        inp.type = 'text';
        eye.className = 'ph ph-eye-slash';
    } else {
        inp.type = 'password';
        eye.className = 'ph ph-eye';
    }
}
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
