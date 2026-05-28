<?php
session_start();
header("Content-Type: application/json");
require_once "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $book_id = $data['book_id'] ?? null;

    if (!$book_id) {
        echo json_encode(["status" => "error", "message" => "Book ID is required"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT available_copies FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
    $book = $stmt->fetch();

    if (!$book || $book['available_copies'] <= 0) {
        echo json_encode(["status" => "error", "message" => "Book is currently out of stock"]);
        exit;
    }

    $issue_date = date('Y-m-d');
    $due_date = date('Y-m-d', strtotime('+14 days'));

    $stmt = $conn->prepare("INSERT INTO borrowings (user_id, book_id, issue_date, due_date, status) VALUES (?, ?, ?, ?, 'Active')");
    if ($stmt->execute([$user_id, $book_id, $issue_date, $due_date])) {
        // Add notification
        $stmtBook = $conn->prepare("SELECT title FROM books WHERE id = ?");
        $stmtBook->execute([$book_id]);
        $bookTitle = $stmtBook->fetchColumn();
        
        $msg = "Successfully borrowed '$bookTitle'. Please return by $due_date.";
        $stmtNotif = $conn->prepare("INSERT INTO notifications (user_id, message, type) VALUES (?, ?, 'success')");
        $stmtNotif->execute([$user_id, $msg]);

        echo json_encode(["status" => "success", "message" => $msg]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to borrow book"]);
    }

} elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    $borrowing_id = $data['borrowing_id'] ?? null;

    if (!$borrowing_id) {
        echo json_encode(["status" => "error", "message" => "Borrowing ID is required"]);
        exit;
    }

    $return_date = date('Y-m-d');
    
    $stmt = $conn->prepare("SELECT due_date, user_id FROM borrowings WHERE id = ? AND status = 'Active'");
    $stmt->execute([$borrowing_id]);
    $borrowing = $stmt->fetch();
    
    if (!$borrowing) {
        echo json_encode(["status" => "error", "message" => "Invalid or already returned borrowing"]);
        exit;
    }
    
    $due_date = $borrowing['due_date'];
    $borrower_id = $borrowing['user_id'];
    
    $fine_amount = 0;
    if (strtotime($return_date) > strtotime($due_date)) {
        $days_overdue = floor((strtotime($return_date) - strtotime($due_date)) / (60 * 60 * 24));
        if ($days_overdue > 0) {
            $fine_amount = $days_overdue * 2.00;
            $stmtFine = $conn->prepare("INSERT INTO fines (borrowing_id, user_id, amount, status) VALUES (?, ?, ?, 'Unpaid')");
            $stmtFine->execute([$borrowing_id, $borrower_id, $fine_amount]);
        }
    }

    $stmt = $conn->prepare("UPDATE borrowings SET return_date = ?, status = 'Returned' WHERE id = ?");
    if ($stmt->execute([$return_date, $borrowing_id])) {
        // Add notification
        $stmtBook = $conn->prepare("SELECT books.title FROM books JOIN borrowings ON books.id = borrowings.book_id WHERE borrowings.id = ?");
        $stmtBook->execute([$borrowing_id]);
        $bookTitle = $stmtBook->fetchColumn();

        $msg = "Book '$bookTitle' returned successfully.";
        if ($fine_amount > 0) $msg .= " A fine of $" . number_format($fine_amount, 2) . " was generated.";
        
        $stmtNotif = $conn->prepare("INSERT INTO notifications (user_id, message, type) VALUES (?, ?, 'info')");
        $stmtNotif->execute([$borrower_id, $msg]);

        echo json_encode(["status" => "success", "message" => "Book returned successfully. Fine: $" . number_format($fine_amount, 2)]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to return book"]);
    }
}
?>
