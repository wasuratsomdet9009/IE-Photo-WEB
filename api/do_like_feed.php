<?php
// api/do_like_feed.php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feed_id = intval($_POST['feed_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    $user_id = $_SESSION['user_id'];

    if ($feed_id > 0) {
        if ($action === 'like') {
            // Check if already liked
            $stmt = $pdo->prepare("SELECT id FROM feed_likes WHERE feed_id = ? AND user_id = ?");
            $stmt->execute([$feed_id, $user_id]);
            if (!$stmt->fetch()) {
                $insert = $pdo->prepare("INSERT INTO feed_likes (feed_id, user_id) VALUES (?, ?)");
                $insert->execute([$feed_id, $user_id]);
            }
        } elseif ($action === 'unlike') {
            $delete = $pdo->prepare("DELETE FROM feed_likes WHERE feed_id = ? AND user_id = ?");
            $delete->execute([$feed_id, $user_id]);
        }

        // Get new count
        $countStmt = $pdo->prepare("SELECT COUNT(*) as count FROM feed_likes WHERE feed_id = ?");
        $countStmt->execute([$feed_id]);
        $count = $countStmt->fetchColumn();

        echo json_encode(['success' => true, 'new_count' => $count]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid feed ID']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
