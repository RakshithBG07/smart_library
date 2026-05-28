<?php
$conn = new mysqli("localhost", "root", "", "smart_library", 3307);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. Roles (Insert 50 dummy roles)
for($i=1; $i<=50; $i++) {
    $conn->query("INSERT IGNORE INTO roles (role_name) VALUES ('Generated Role $i')");
}

// 2. Categories
for($i=1; $i<=50; $i++) {
    $conn->query("INSERT IGNORE INTO categories (name, description) VALUES ('Generated Category $i', 'Auto-generated description $i')");
}

// 3. Authors
for($i=1; $i<=50; $i++) {
    $conn->query("INSERT IGNORE INTO authors (name, bio) VALUES ('Generated Author $i', 'Auto-generated bio for author $i')");
}

// Publishers (Need at least 50 for books)
for($i=1; $i<=50; $i++) {
    $conn->query("INSERT IGNORE INTO publishers (name, contact) VALUES ('Generated Publisher $i', 'publisher$i@example.com')");
}

// 4. Locations
for($i=1; $i<=50; $i++) {
    $conn->query("INSERT IGNORE INTO locations (shelf_no, floor) VALUES ('Shelf X$i', 'Floor Y$i')");
}

// 5. Users
for($i=1; $i<=50; $i++) {
    $hash = password_hash('password', PASSWORD_DEFAULT);
    $conn->query("INSERT IGNORE INTO users (role_id, full_name, email, password_hash, status) VALUES (3, 'Generated User $i', 'gen_user$i@example.com', '$hash', 'Active')");
}

// Fetch generated IDs to use as Foreign Keys
$author_ids = array_column($conn->query("SELECT id FROM authors")->fetch_all(MYSQLI_ASSOC), 'id');
$pub_ids = array_column($conn->query("SELECT id FROM publishers")->fetch_all(MYSQLI_ASSOC), 'id');
$cat_ids = array_column($conn->query("SELECT id FROM categories")->fetch_all(MYSQLI_ASSOC), 'id');
$loc_ids = array_column($conn->query("SELECT id FROM locations")->fetch_all(MYSQLI_ASSOC), 'id');
$user_ids = array_column($conn->query("SELECT id FROM users")->fetch_all(MYSQLI_ASSOC), 'id');

// 6. Books
for($i=1; $i<=50; $i++) {
    $a_id = $author_ids[array_rand($author_ids)];
    $p_id = $pub_ids[array_rand($pub_ids)];
    $c_id = $cat_ids[array_rand($cat_ids)];
    $l_id = $loc_ids[array_rand($loc_ids)];
    
    // Generate random ISBN
    $isbn = "978" . str_pad(rand(1, 999999999), 10, '0', STR_PAD_LEFT);
    
    $conn->query("INSERT IGNORE INTO books (title, isbn, author_id, publisher_id, category_id, location_id, total_copies, available_copies) 
                  VALUES ('Generated Book Title $i', '$isbn', $a_id, $p_id, $c_id, $l_id, 10, 10)");
}

$book_ids = array_column($conn->query("SELECT id FROM books")->fetch_all(MYSQLI_ASSOC), 'id');

// 7. Borrowings
for($i=1; $i<=50; $i++) {
    $u_id = $user_ids[array_rand($user_ids)];
    $b_id = $book_ids[array_rand($book_ids)];
    
    $status = rand(0, 1) ? 'Active' : 'Overdue';
    
    $conn->query("INSERT IGNORE INTO borrowings (user_id, book_id, issue_date, due_date, status) 
                  VALUES ($u_id, $b_id, DATE_SUB(CURDATE(), INTERVAL 10 DAY), DATE_ADD(CURDATE(), INTERVAL 4 DAY), '$status')");
}

$borrowing_ids = array_column($conn->query("SELECT id FROM borrowings")->fetch_all(MYSQLI_ASSOC), 'id');

// 8. Fines
for($i=1; $i<=50; $i++) {
    $u_id = $user_ids[array_rand($user_ids)];
    $br_id = $borrowing_ids[array_rand($borrowing_ids)];
    
    $status = rand(0, 1) ? 'Paid' : 'Unpaid';
    $amount = rand(5, 50) . '.00';
    
    $conn->query("INSERT IGNORE INTO fines (borrowing_id, user_id, amount, status) 
                  VALUES ($br_id, $u_id, $amount, '$status')");
}

// 9. Reservations
for($i=1; $i<=50; $i++) {
    $u_id = $user_ids[array_rand($user_ids)];
    $b_id = $book_ids[array_rand($book_ids)];
    
    $conn->query("INSERT IGNORE INTO reservations (user_id, book_id, reservation_date, status) 
                  VALUES ($u_id, $b_id, CURDATE(), 'pending')");
}

echo "Successfully seeded 50+ records into all tables!\n";
?>
