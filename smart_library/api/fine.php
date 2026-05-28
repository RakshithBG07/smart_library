<?php
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        // If user is a member, only show their fines
        if (isset($_SESSION['role_name']) && $_SESSION['role_name'] === 'Member') {
            $stmt = $conn->prepare("SELECT fines.* FROM fines WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
        } else {
            // Admin/Librarian sees all fines with user names
            $stmt = $conn->query("SELECT fines.*, users.full_name as user_name FROM fines LEFT JOIN users ON fines.user_id = users.id");
        }
        $data = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'data' => $data]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} elseif ($method === 'POST') {
    // Pay Fine logic
    $data = json_decode(file_get_contents("php://input"), true);
    $fine_id = $data['fine_id'] ?? null;

    if (!$fine_id) {
        echo json_encode(["status" => "error", "message" => "Fine ID is required"]);
        exit;
    }

    try {
        // Security: Members can only pay their own fines
        if (isset($_SESSION['role_name']) && $_SESSION['role_name'] === 'Member') {
            $stmt = $conn->prepare("SELECT user_id FROM fines WHERE id = ?");
            $stmt->execute([$fine_id]);
            $fine = $stmt->fetch();
            if (!$fine || $fine['user_id'] != $_SESSION['user_id']) {
                echo json_encode(["status" => "error", "message" => "Unauthorized to pay this fine"]);
                exit;
            }
        }

        $stmt = $conn->prepare("UPDATE fines SET status = 'Paid' WHERE id = ?");
        if ($stmt->execute([$fine_id])) {
            // Get amount for the notification
            $stmtAmount = $conn->prepare("SELECT amount, user_id FROM fines WHERE id = ?");
            $stmtAmount->execute([$fine_id]);
            $fineData = $stmtAmount->fetch();
            
            // Add notification
            $msg = "Success! Your fine of $" . number_format($fineData['amount'], 2) . " has been cleared.";
            $stmtNotif = $conn->prepare("INSERT INTO notifications (user_id, message, type) VALUES (?, ?, 'success')");
            $stmtNotif->execute([$fineData['user_id'], $msg]);

            echo json_encode(["status" => "success", "message" => $msg]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to process payment"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    }
}
?>