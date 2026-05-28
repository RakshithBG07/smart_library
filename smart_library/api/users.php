<?php
session_start();
header("Content-Type: application/json");
require_once "../config/db.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $conn->query("SELECT users.id, users.full_name, users.email, users.status, roles.role_name FROM users LEFT JOIN roles ON users.role_id = roles.id");
    $data = $stmt->fetchAll();
    echo json_encode(["status" => "success", "data" => $data]);
} elseif ($method === 'POST') {
    if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'Admin') {
        echo json_encode(["status" => "error", "message" => "Unauthorized"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $full_name = $data['full_name'] ?? '';
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? 'user123';
    $role_id = $data['role_id'] ?? 3;
    $status = $data['status'] ?? 'Active';

    if (empty($full_name) || empty($email)) {
        echo json_encode(["status" => "error", "message" => "Name and Email are required"]);
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password_hash, role_id, status) VALUES (?, ?, ?, ?, ?)");

    if ($stmt->execute([$full_name, $email, $password_hash, $role_id, $status])) {
        echo json_encode(["status" => "success", "message" => "User added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add user"]);
    }
} elseif ($method === 'DELETE') {
    if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'Admin') {
        echo json_encode(["status" => "error", "message" => "Unauthorized"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? null;

    if (!$id || $id == $_SESSION['user_id']) {
        echo json_encode(["status" => "error", "message" => "Invalid ID or cannot delete self"]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo json_encode(["status" => "success", "message" => "User deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete user"]);
    }
}
?>
