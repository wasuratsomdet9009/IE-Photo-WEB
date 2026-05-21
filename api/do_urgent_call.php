<?php
// api/do_urgent_call.php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/mail.php';
require_once __DIR__ . '/../includes/email_templates.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_id = intval($_POST['receiver_id'] ?? 0);
    $sender_id = $_SESSION['user_id'];

    if ($receiver_id > 0 && $receiver_id !== $sender_id) {
        // Prevent spam: Check if there's already an active (calling/acknowledged) call within last 5 minutes
        $checkStmt = $pdo->prepare("
            SELECT id FROM urgent_contacts 
            WHERE sender_id = ? AND receiver_id = ? AND status != 'resolved' 
            AND created_at > (NOW() - INTERVAL 5 MINUTE)
        ");
        $checkStmt->execute([$sender_id, $receiver_id]);

        if ($checkStmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Please wait before sending another urgent call to this member.']);
            exit;
        }

        // Insert new urgent call
        $insert = $pdo->prepare("INSERT INTO urgent_contacts (sender_id, receiver_id, status) VALUES (?, ?, 'calling')");
        if ($insert->execute([$sender_id, $receiver_id])) {
            
            // Send Email Notification
            // Need sender logic
            $getSender = $pdo->prepare("SELECT student_id, email FROM users WHERE id = ?");
            $getSender->execute([$sender_id]);
            $sender = $getSender->fetch();
            $senderName = $sender['student_id'];

            $getReceiver = $pdo->prepare("SELECT email, student_id FROM users WHERE id = ?");
            $getReceiver->execute([$receiver_id]);
            $receiver = $getReceiver->fetch();

            if ($receiver) {
                $emailBody = getUrgentCallTemplate($senderName, $receiver['student_id']);
                sendEmail($receiver['email'], "Urgent Call Alert from " . $senderName, $emailBody);
            }

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid receiver']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
