<?php
require_once "C:/xampp/htdocs/smart_library/config/db.php";
try {
    $stmt = $conn->prepare("INSERT INTO roles (role_name) VALUES (?)");
    $stmt->execute(["TestConnectionRole"]);
    echo "SUCCESS: Wrote to SQLite file. ID: " . $conn->lastInsertId();
} catch (Exception $e) {
    echo "FAILED: " . $e->getMessage();
}
?>
