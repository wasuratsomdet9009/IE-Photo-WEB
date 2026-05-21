<?php
// auth/login.php
session_start();
require_once __DIR__ . '/../config/database.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../member/profile.php");
    }
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = isset($_POST['identifier']) ? trim($_POST['identifier']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($identifier) || empty($password)) {
        $error = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    } else {
        // FIX: Include profile_completed in SELECT
        $stmt = $pdo->prepare("SELECT id, email, password, role, profile_completed FROM users WHERE student_id = ? OR email = ?");
        $stmt->execute([$identifier, $identifier]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

            if ($user['role'] !== 'admin' && !$user['profile_completed']) {
                header("Location: ../member/profile.php?first_login=1");
                exit;
            }

            if ($user['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../member/feed.php");
            }
            exit;
        } else {
            $error = 'อีเมล/รหัสนักศึกษา หรือ รหัสผ่านไม่ถูกต้อง';
        }
    }
}

$base_url = '../';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="auth-wrapper">
    <div class="glass-card" style="max-width:420px; width:100%;">
        <div class="text-center mb-4">
            <div style="width:64px;height:64px;border-radius:20px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;box-shadow:0 8px 24px var(--primary-glow);">
                <i class="ph-bold ph-sign-in" style="font-size:1.8rem;color:#fff"></i>
            </div>
            <h2 style="font-size:1.5rem;margin-bottom:.3rem;">เข้าสู่ระบบ</h2>
            <p class="text-muted" style="font-size:.9rem;">ยินดีต้อนรับกลับสู่ IE-Photo Maker</p>
        </div>

        <?php if(isset($_GET['registered'])): ?>
            <div class="alert alert-success"><i class="ph-bold ph-check-circle"></i> สมัครสมาชิกสำเร็จ! เข้าสู่ระบบเพื่อเริ่มใช้งาน</div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-danger"><i class="ph-bold ph-warning-circle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="identifier"><i class="ph ph-user"></i> รหัสนักศึกษา หรือ อีเมล</label>
                <input type="text" id="identifier" name="identifier" class="form-control" placeholder="6XXXXXXX หรือ @kmitl.ac.th" required>
            </div>
            <div class="form-group">
                <label for="password"><i class="ph ph-lock"></i> รหัสผ่าน</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="ระบุรหัสผ่านของคุณ" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-2">
                <i class="ph-bold ph-sign-in"></i> เข้าสู่ระบบ
            </button>
            <div class="text-center mt-4">
                <p class="text-muted" style="font-size:.9rem;">
                    ยังไม่มีบัญชี? <a href="register.php" style="font-weight:700;">สมัครสมาชิกใหม่</a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
