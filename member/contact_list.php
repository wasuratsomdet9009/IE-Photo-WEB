<?php
// member/contact_list.php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, student_id, role, profile_image, contact_status FROM users WHERE id != ? ORDER BY role ASC, student_id ASC");
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
            <?php $imgPath = $u['profile_image'] !== 'default.png' ? "../uploads/profiles/".$u['profile_image'] : "https://ui-avatars.com/api/?name=".urlencode($u['student_id'])."&background=F2531C&color=fff&size=256"; ?>
            <img src="<?php echo htmlspecialchars($imgPath); ?>" alt="avatar" class="contact-avatar">
            <h3 style="margin-bottom:.3rem;font-size:1.05rem;"><?php echo htmlspecialchars($u['student_id']); ?></h3>
            <div style="margin-bottom:.8rem;">
                <span class="badge" style="background:<?php echo $u['role']==='admin'?'rgba(242,83,28,.1)':'rgba(0,0,0,.04)';?>;color:<?php echo $u['role']==='admin'?'var(--primary)':'var(--text-muted)';?>;">
                    <?php echo $u['role']==='admin'?'ผู้ดูแลระบบ':'สมาชิก';?>
                </span>
            </div>
            <div style="margin-bottom:1.2rem;font-size:.88rem;">
                <?php
                    $statusLabel = 'ไม่ได้ออนไลน์'; $statusClass = 'status-offline';
                    if($u['contact_status'] === 'available') { $statusLabel = 'ว่าง/ติดต่อได้'; $statusClass = 'status-available'; }
                    if($u['contact_status'] === 'busy') { $statusLabel = 'ยุ่ง/ไม่สะดวก'; $statusClass = 'status-busy'; }
                ?>
                <span class="status-indicator <?php echo $statusClass;?>"></span>
                <span class="text-muted"><?php echo $statusLabel;?></span>
            </div>
            <button class="btn btn-outline w-100 btn-urgent-call btn-sm" data-receiver-id="<?php echo $u['id'];?>">
                <i class="ph-bold ph-phone-call"></i> ติดต่อด่วน
            </button>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
