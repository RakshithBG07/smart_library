<?php
session_start();
header("Content-Type: application/json");
require_once "../config/db.php";

// For SQLite/PDO version
if (isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "success", 
        "user" => [
            "id" => $_SESSION['user_id'],
            "name" => $_SESSION['user_name'],
            "role" => $_SESSION['role_name']
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
}
?>
