<?php
// auth/register.php
session_start();
require_once __DIR__ . '/../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = isset($_POST['student_id']) ? trim($_POST['student_id']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

    if (!preg_match('/^[0-9]{8}$/', $student_id)) {
        $error = 'รหัสนักศึกษาต้องเป็นตัวเลข 8 หลักเท่านั้น';
    } elseif (empty($password) || strlen($password) < 6) {
        $error = 'กรุณากรอกรหัสผ่านอย่างน้อย 6 ตัวอักษร';
    } else {
        $email = $student_id . '@kmitl.ac.th';
        $stmt = $pdo->prepare("SELECT id FROM users WHERE student_id = ? OR email = ?");
        $stmt->execute([$student_id, $email]);
        if ($stmt->fetch()) {
            $error = 'รหัสนักศึกษาหรืออีเมลนี้มีอยู่ในระบบแล้ว';
        } else {
            $hashedPass = password_hash($password, PASSWORD_DEFAULT);
            $insertStmt = $pdo->prepare("INSERT INTO users (student_id, email, password, phone, role) VALUES (?, ?, ?, ?, 'member')");
            if ($insertStmt->execute([$student_id, $email, $hashedPass, $phone])) {
                // Auto redirect to login after successful registration
                header("Location: login.php?registered=1");
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
                    <input type="text" id="student_id" name="student_id" class="form-control" placeholder="6XXXXXXX" pattern="[0-9]{8}" maxlength="8" required>
                    <span class="input-group-text">@kmitl.ac.th</span>
                </div>
                <small class="text-muted" style="font-size:.8rem;margin-top:.3rem;display:block;"><i class="ph ph-info"></i> ระบบจะใช้อีเมลสถาบันในการรับข่าวสาร</small>
            </div>
            <div class="form-group">
                <label for="password"><i class="ph ph-lock"></i> กำหนดรหัสผ่าน</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="อย่างน้อย 6 ตัวอักษร" required minlength="6">
            </div>
            <div class="form-group">
                <label for="phone"><i class="ph ph-phone"></i> เบอร์โทรศัพท์ (เลือกกรอก)</label>
                <input type="text" id="phone" name="phone" class="form-control" placeholder="0XXXXXXXXX">
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
