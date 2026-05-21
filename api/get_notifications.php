<?php
// api/get_notifications.php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'notifications' => []]);
    exit;
}

$user_id = $_SESSION['user_id'];
$notifications = [];

// Fetch incoming urgent calls that are "calling" and within last 1 minute (to not reshow old ones)
// In a real robust system, we would have a table just for user_notifications with 'is_read' flag
$stmt = $pdo->prepare("
    SELECT uc.id, u.student_id as sender 
    FROM urgent_contacts uc
    JOIN users u ON uc.sender_id = u.id
    WHERE uc.receiver_id = ? AND uc.status = 'calling' AND uc.created_at >= (NOW() - INTERVAL 1 MINUTE)
");
$stmt->execute([$user_id]);
$calls = $stmt->fetchAll();

foreach ($calls as $call) {
    $notifications[] = [
        'id' => 'call_'.$call['id'],
        'message' => 'Urgent Call from ' . $call['sender'],
        'type' => 'danger' 
    ];
}

// Update status to acknowledged so it doesn't show again in 1-min window next poll
if (!empty($calls)) {
    $callIds = array_column($calls, 'id');
    $placeholders = str_repeat('?,', count($callIds) - 1) . '?';
    $updateStmt = $pdo->prepare("UPDATE urgent_contacts SET status = 'acknowledged' WHERE id IN ($placeholders)");
    $updateStmt->execute($callIds);
}

echo json_encode(['success' => true, 'notifications' => $notifications]);
?>
