<?php
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';
try {
    $stmt = $conn->query("SELECT borrowings.*, users.full_name as user_name, books.title as book_title FROM borrowings LEFT JOIN users ON borrowings.user_id = users.id LEFT JOIN books ON borrowings.book_id = books.id");
    $data = $stmt->fetchAll();
    echo json_encode(['status' => 'success', 'data' => $data]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>