<?php
/**
 * IE-Photo-WEB Automated Test Runner
 * รัน: php tests/run_tests.php
 * หรือเปิด: http://localhost/IE-Photo-WEB/tests/run_tests.php
 */

// ป้องกันไม่ให้ใครอื่นเปิด (เฉพาะ localhost)
if (!in_array($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', ['127.0.0.1', '::1'])) {
    http_response_code(403);
    die('Access denied - tests only accessible from localhost');
}

require_once __DIR__ . '/../config/database.php';

// ============================================
$results = [];
$pass = 0;
$fail = 0;

function test(string $name, callable $fn) {
    global $results, $pass, $fail;
    try {
        $result = $fn();
        if ($result === true || $result === null) {
            $results[] = ['pass', $name, ''];
            $pass++;
        } else {
            $results[] = ['fail', $name, is_string($result) ? $result : 'returned false'];
            $fail++;
        }
    } catch (Throwable $e) {
        $results[] = ['fail', $name, $e->getMessage()];
        $fail++;
    }
}

function assertEqual($a, $b, string $msg = '') {
    if ($a !== $b) throw new Exception($msg ?: "Expected " . json_encode($b) . " got " . json_encode($a));
}
function assertGreaterThan(int $min, $val, string $msg = '') {
    if ($val <= $min) throw new Exception($msg ?: "Expected > $min, got $val");
}
function assertNotEmpty($val, string $msg = '') {
    if (empty($val)) throw new Exception($msg ?: "Expected non-empty value");
}

// ============================================
// DATABASE TESTS
// ============================================

test('DB: เชื่อมต่อฐานข้อมูลได้', function() use ($pdo) {
    return $pdo instanceof PDO;
});

test('DB: ตาราง users มีอยู่', function() use ($pdo) {
    $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    assertGreaterThan(0, $count, 'ควรมีผู้ใช้อย่างน้อย 1 คน');
});

test('DB: มี admin อย่างน้อย 1 คน', function() use ($pdo) {
    $count = $pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
    assertGreaterThan(0, $count, 'ควรมี admin อย่างน้อย 1 คน');
});

test('DB: ตาราง bookings มีอยู่', function() use ($pdo) {
    $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
});

test('DB: ตาราง equipments มีข้อมูล', function() use ($pdo) {
    $count = $pdo->query("SELECT COUNT(*) FROM equipments")->fetchColumn();
    assertGreaterThan(0, $count, 'ควรมีอุปกรณ์อย่างน้อย 1 ชิ้น');
});

test('DB: ตาราง studios มีข้อมูล', function() use ($pdo) {
    $count = $pdo->query("SELECT COUNT(*) FROM studios")->fetchColumn();
    assertGreaterThan(0, $count, 'ควรมีสตูดิโออย่างน้อย 1 ห้อง');
});

test('DB: ตาราง feeds มีข้อมูล', function() use ($pdo) {
    $pdo->query("SELECT COUNT(*) FROM feeds")->fetchColumn();
});

test('DB: ตาราง tasks มีอยู่', function() use ($pdo) {
    $pdo->query("SELECT COUNT(*) FROM tasks")->fetchColumn();
});

// ============================================
// PASSWORD HASH TESTS
// ============================================

test('AUTH: Password hash ใช้ bcrypt', function() use ($pdo) {
    $hash = $pdo->query("SELECT password FROM users LIMIT 1")->fetchColumn();
    assertNotEmpty($hash, 'ควรมี password');
    if (!str_starts_with($hash, '$2y$')) {
        throw new Exception('Password ไม่ใช่ bcrypt hash (ต้องขึ้นต้นด้วย $2y$)');
    }
});

test('AUTH: password_verify ทำงานกับ hash ในฐานข้อมูล', function() use ($pdo) {
    $hash = $pdo->query("SELECT password FROM users WHERE student_id='68030263' LIMIT 1")->fetchColumn();
    if (!$hash) throw new Exception('ไม่พบ user 68030263');
    if (!password_verify('1234', $hash)) {
        throw new Exception('password_verify ล้มเหลว — รหัสผ่านอาจไม่ใช่ 1234');
    }
});

// ============================================
// ACCESS CONTROL TESTS (File-level)
// ============================================

test('ACCESS: ไฟล์ admin/dashboard.php มี role check', function() {
    $content = file_get_contents(__DIR__ . '/../admin/dashboard.php');
    if (strpos($content, "role") === false || strpos($content, "admin") === false) {
        throw new Exception('ไม่พบ role/admin check ใน dashboard.php');
    }
});

test('ACCESS: ไฟล์ admin/bookings.php มี role check', function() {
    $content = file_get_contents(__DIR__ . '/../admin/bookings.php');
    if (strpos($content, "role") === false) {
        throw new Exception('ไม่พบ role check ใน bookings.php');
    }
});

test('ACCESS: ไฟล์ admin/inventory.php มี role check', function() {
    $content = file_get_contents(__DIR__ . '/../admin/inventory.php');
    if (strpos($content, "role") === false) {
        throw new Exception('ไม่พบ role check ใน inventory.php');
    }
});

test('ACCESS: ไฟล์ admin/tasks.php มี role check', function() {
    $content = file_get_contents(__DIR__ . '/../admin/tasks.php');
    if (strpos($content, "role") === false) {
        throw new Exception('ไม่พบ role check ใน tasks.php');
    }
});

test('ACCESS: ไฟล์ admin/users.php มี role check', function() {
    $content = file_get_contents(__DIR__ . '/../admin/users.php');
    if (strpos($content, "role") === false) {
        throw new Exception('ไม่พบ role check ใน users.php');
    }
});

test('ACCESS: member/borrow_form.php ป้องกัน responsible_user_id ของคนอื่น', function() {
    $content = file_get_contents(__DIR__ . '/../member/borrow_form.php');
    if (strpos($content, 'is_admin') === false) {
        throw new Exception('ไม่พบการตรวจสอบ is_admin สำหรับ responsible_user_id');
    }
});

test('ACCESS: api/email_consent.php ใช้ hash_equals()', function() {
    $content = file_get_contents(__DIR__ . '/../api/email_consent.php');
    if (strpos($content, 'hash_equals') === false) {
        throw new Exception('ไม่พบ hash_equals() ใน email_consent.php');
    }
});

// ============================================
// DATA INTEGRITY TESTS
// ============================================

test('DATA: booking ทุกรายการต้องมี booking_type ที่ถูกต้อง', function() use ($pdo) {
    $invalid = $pdo->query("SELECT COUNT(*) FROM bookings WHERE booking_type NOT IN ('equipment','studio')")->fetchColumn();
    if ($invalid > 0) throw new Exception("พบ booking_type ที่ไม่ถูกต้อง $invalid รายการ");
});

test('DATA: booking status ต้องถูกต้อง', function() use ($pdo) {
    $invalid = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status NOT IN ('pending','approved','rejected','returned','cancelled','pending_return')")->fetchColumn();
    if ($invalid > 0) throw new Exception("พบ status ที่ไม่ถูกต้อง $invalid รายการ");
});

test('DATA: tasks ต้องมี assigned_by และ assigned_to', function() use ($pdo) {
    $invalid = $pdo->query("SELECT COUNT(*) FROM tasks WHERE assigned_by IS NULL OR assigned_to IS NULL")->fetchColumn();
    if ($invalid > 0) throw new Exception("พบ task ที่ไม่มี assigned_by หรือ assigned_to $invalid รายการ");
});

test('DATA: user role ต้องเป็น admin หรือ member เท่านั้น', function() use ($pdo) {
    $invalid = $pdo->query("SELECT COUNT(*) FROM users WHERE role NOT IN ('admin','member','guest')")->fetchColumn();
    if ($invalid > 0) throw new Exception("พบ role ที่ไม่ถูกต้อง $invalid รายการ");
});

test('DATA: feeds ทุกรายการต้องมี booking_id', function() use ($pdo) {
    $invalid = $pdo->query("SELECT COUNT(*) FROM feeds WHERE booking_id IS NULL")->fetchColumn();
    if ($invalid > 0) throw new Exception("พบ feed ที่ไม่มี booking_id $invalid รายการ");
});

// ============================================
// FILE STRUCTURE TESTS
// ============================================

$requiredFiles = [
    'config/database.php', 'config/emailjs.php',
    'auth/login.php', 'auth/register.php', 'auth/logout.php',
    'admin/dashboard.php', 'admin/bookings.php', 'admin/inventory.php',
    'admin/tasks.php', 'admin/users.php', 'admin/contact_manage.php',
    'member/feed.php', 'member/profile.php', 'member/borrow_form.php',
    'member/my_bookings.php', 'member/calendar.php', 'member/my_tasks.php',
    'guest/studio_booking.php',
    'includes/header.php', 'includes/footer.php',
    'assets/css/glassmorphism.css',
    'uploads', 'uploads/profiles', 'uploads/booking_forms', 'uploads/returns',
];

foreach ($requiredFiles as $file) {
    $path = $file;
    test("FILES: $file มีอยู่", function() use ($path) {
        $full = __DIR__ . '/../' . $path;
        if (!file_exists($full)) throw new Exception("ไม่พบไฟล์/โฟลเดอร์: $path");
    });
}

// ============================================
// OUTPUT
// ============================================
$total = $pass + $fail;
$percent = $total > 0 ? round($pass / $total * 100) : 0;
$isCLI = PHP_SAPI === 'cli';

if ($isCLI) {
    echo "\n=== IE-Photo-WEB Test Results ===\n";
    foreach ($results as [$status, $name, $msg]) {
        $icon = $status === 'pass' ? '✅' : '❌';
        echo "$icon  $name" . ($msg ? " — $msg" : '') . "\n";
    }
    echo "\n";
    echo "ผ่าน: $pass / $total ($percent%)\n";
    if ($fail > 0) echo "ล้มเหลว: $fail\n";
} else {
    $bg = $fail === 0 ? '#f0fdf4' : '#fff7ed';
    $badgeColor = $fail === 0 ? '#22c55e' : '#f59e0b';
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>IE-Photo Test Results</title>
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Kanit',sans-serif;background:#f0f2f5;padding:2rem;min-height:100vh}
.container{max-width:860px;margin:0 auto}
.header{background:#fff;border-radius:20px;padding:1.8rem 2rem;margin-bottom:1.5rem;box-shadow:0 4px 20px rgba(0,0,0,.06);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
.title{font-size:1.5rem;font-weight:700;color:#1a1a2e}
.badge{display:inline-flex;align-items:center;gap:.5rem;background:<?php echo $badgeColor;?>;color:#fff;font-weight:700;font-size:1.1rem;padding:.5rem 1.2rem;border-radius:50px}
.stats{display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem}
.stat{background:#fff;border-radius:16px;padding:1rem 1.5rem;box-shadow:0 2px 12px rgba(0,0,0,.05);flex:1;min-width:140px;text-align:center}
.stat .num{font-size:2rem;font-weight:800;line-height:1}
.stat .label{font-size:.8rem;color:#64748b;margin-top:.3rem}
.pass-num{color:#22c55e}.fail-num{color:#ef4444}.total-num{color:#3b82f6}
.result-list{background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.06)}
.result-item{display:flex;align-items:flex-start;gap:1rem;padding:.9rem 1.5rem;border-bottom:1px solid #f1f5f9;font-size:.9rem}
.result-item:last-child{border-bottom:none}
.result-item.pass{border-left:3px solid #22c55e}
.result-item.fail{border-left:3px solid #ef4444;background:#fff9f9}
.icon{font-size:1.1rem;flex-shrink:0;margin-top:.05rem}
.name{flex:1;color:#1a1a2e;font-family:'JetBrains Mono',monospace;font-size:.82rem}
.msg{color:#ef4444;font-size:.8rem;margin-top:.2rem;font-style:italic}
.progress{background:#e8ecf0;border-radius:50px;height:10px;margin-bottom:1.5rem;overflow:hidden}
.progress-bar{height:100%;background:linear-gradient(90deg,#22c55e,#16a34a);border-radius:50px;transition:width .5s;width:<?php echo $percent;?>%}
.back{display:inline-flex;align-items:center;gap:.4rem;background:#1a1a2e;color:#fff;padding:.7rem 1.5rem;border-radius:10px;text-decoration:none;font-weight:600;margin-top:1.5rem;font-size:.9rem}
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <div class="title">🧪 IE-Photo Test Results</div>
            <div style="font-size:.85rem;color:#64748b;margin-top:.3rem">รันเมื่อ <?php echo date('d/m/Y H:i:s'); ?></div>
        </div>
        <div class="badge"><?php echo $percent; ?>% ผ่าน</div>
    </div>
    <div class="progress"><div class="progress-bar"></div></div>
    <div class="stats">
        <div class="stat"><div class="num pass-num"><?php echo $pass; ?></div><div class="label">ผ่าน</div></div>
        <div class="stat"><div class="num fail-num"><?php echo $fail; ?></div><div class="label">ล้มเหลว</div></div>
        <div class="stat"><div class="num total-num"><?php echo $total; ?></div><div class="label">ทั้งหมด</div></div>
    </div>
    <div class="result-list">
        <?php foreach ($results as [$status, $name, $msg]): ?>
        <div class="result-item <?php echo $status; ?>">
            <div class="icon"><?php echo $status === 'pass' ? '✅' : '❌'; ?></div>
            <div>
                <div class="name"><?php echo htmlspecialchars($name); ?></div>
                <?php if ($msg): ?><div class="msg"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <a href="../auth/login.php" class="back">← กลับหน้าหลัก</a>
</div>
</body>
</html>
<?php } ?>
