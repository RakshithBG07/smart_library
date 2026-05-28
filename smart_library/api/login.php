<?php
session_start();
header("Content-Type: application/json");
require_once "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT users.*, roles.role_name as role_name FROM users LEFT JOIN roles ON users.role_id = roles.id WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        if ($user['status'] === 'Suspended') {
            echo json_encode(["status" => "error", "message" => "Your account has been suspended"]);
            exit;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['role_name'] = $user['role_name'];

        echo json_encode([
            "status" => "success",
            "message" => "Login successful",
            "user" => [
                "name" => $user['full_name'],
                "role" => $user['role_name']
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
    }
}
?>
