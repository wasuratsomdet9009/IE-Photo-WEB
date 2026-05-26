<?php
// member/contact_list.php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
// ลบ contact_status ออก — column นี้ไม่มีในตาราง users
$stmt = $pdo->prepare("SELECT id, student_id, first_name, last_name, profile_image FROM users WHERE id != ? ORDER BY student_id ASC");
$stmt->execute([$user_id]);
$users = $stmt->fetchAll();

$base_url = '../';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <h2>ทำเนียบสมาชิก</h2>
    <p>ติดต่อแอดมินหรือสมาชิกคนอื่นๆ สำหรับธุระด่วน</p>
</div>

<div class="contact-grid">
    <?php foreach($users as $u): ?>
        <div class="contact-card animate-in">
            <?php
                $displayName = trim($u['first_name'] . ' ' . $u['last_name']) ?: $u['student_id'];
                $imgPath = (!empty($u['profile_image']) && $u['profile_image'] !== 'default.png')
                    ? "../uploads/profiles/" . $u['profile_image']
                    : "https://ui-avatars.com/api/?name=" . urlencode($displayName) . "&background=F2531C&color=fff&size=256";
            ?>
            <img src="<?php echo htmlspecialchars($imgPath); ?>" alt="avatar" class="contact-avatar">
            <h3 style="margin-bottom:.1rem;font-size:.95rem;line-height:1.3;"><?php echo htmlspecialchars($displayName); ?></h3>
            <p style="font-size:.78rem;color:var(--text-muted);margin-bottom:1rem;"><?php echo htmlspecialchars($u['student_id']); ?></p>
            <button class="btn btn-outline w-100 btn-urgent-call btn-sm" data-receiver-id="<?php echo $u['id'];?>">
                <i class="ph-bold ph-phone-call"></i> ติดต่อด่วน
            </button>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
