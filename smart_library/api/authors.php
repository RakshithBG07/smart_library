<?php
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';
try {
    $stmt = $conn->query("SELECT * FROM authors");
    $data = $stmt->fetchAll();
    echo json_encode(['status' => 'success', 'data' => $data]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>