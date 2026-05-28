<?php
session_start();
header("Content-Type: application/json");
require_once "../config/db.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $search = $_GET['search'] ?? '';
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;
    
    $query = "
        SELECT books.*, authors.name as author_name, categories.name as category_name 
        FROM books 
        LEFT JOIN authors ON books.author_id = authors.id 
        LEFT JOIN categories ON books.category_id = categories.id
    ";
    
    if (!empty($search)) {
        $searchTerm = "%{$search}%";
        $query .= " WHERE books.title LIKE ? OR authors.name LIKE ? OR categories.name LIKE ? LIMIT ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $limit]);
    } else {
        $query .= " LIMIT ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$limit]);
    }
    
    $books = $stmt->fetchAll();
    echo json_encode(["status" => "success", "data" => $books]);

} elseif ($method === 'POST') {
    if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] === 'Member') {
        echo json_encode(["status" => "error", "message" => "Unauthorized"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true) ?: $_POST;
    
    $title = $data['title'] ?? '';
    $author_id = $data['author_id'] ?? null;
    $publisher_id = $data['publisher_id'] ?? null;
    $category_id = $data['category_id'] ?? null;
    $location_id = $data['location_id'] ?? null;
    $isbn = $data['isbn'] ?? '';
    $total_copies = $data['total_copies'] ?? 1;

    if (empty($title)) {
        echo json_encode(["status" => "error", "message" => "Title is required"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO books (title, isbn, author_id, publisher_id, category_id, location_id, total_copies, available_copies) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$title, $isbn, $author_id, $publisher_id, $category_id, $location_id, $total_copies, $total_copies])) {
        echo json_encode(["status" => "success", "message" => "Book added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add book"]);
    }

} elseif ($method === 'DELETE') {
    if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== 'Admin') {
        echo json_encode(["status" => "error", "message" => "Unauthorized"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? null;

    if (!$id) {
        echo json_encode(["status" => "error", "message" => "ID required"]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo json_encode(["status" => "success", "message" => "Book deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete book"]);
    }
}
?>
