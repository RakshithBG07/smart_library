<?php
session_start();
header("Content-Type: application/json");
require_once "../config/db.php";

$data = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Check for overdue books
    $stmt = $conn->prepare("SELECT books.title FROM borrowings LEFT JOIN books ON borrowings.book_id = books.id WHERE user_id = ? AND borrowings.status = 'Overdue'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $data[] = ["type" => "error", "message" => "Your book '{$row['title']}' is overdue!"];
    }

    // Check for unpaid fines
    $stmt = $conn->prepare("SELECT amount FROM fines WHERE user_id = ? AND status = 'Unpaid'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $data[] = ["type" => "warning", "message" => "You have an unpaid fine of $" . $row['amount']];
    }
}
echo json_encode(["status" => "success", "data" => $data]);
?>
