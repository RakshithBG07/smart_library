<?php
$conn = new mysqli("localhost", "root", "", "smart_library", 3307);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Clean up old generated data
$conn->query("DELETE FROM reservations WHERE user_id IN (SELECT id FROM users WHERE full_name LIKE 'Generated%')");
$conn->query("DELETE FROM fines WHERE user_id IN (SELECT id FROM users WHERE full_name LIKE 'Generated%')");
$conn->query("DELETE FROM borrowings WHERE user_id IN (SELECT id FROM users WHERE full_name LIKE 'Generated%')");
$conn->query("DELETE FROM books WHERE title LIKE 'Generated%'");
$conn->query("DELETE FROM users WHERE full_name LIKE 'Generated%'");
$conn->query("DELETE FROM authors WHERE name LIKE 'Generated%'");
$conn->query("DELETE FROM publishers WHERE name LIKE 'Generated%'");
$conn->query("DELETE FROM categories WHERE name LIKE 'Generated%'");
$conn->query("DELETE FROM locations WHERE shelf_no LIKE 'Shelf X%'");
$conn->query("DELETE FROM roles WHERE role_name LIKE 'Generated%'");

// Arrays of 50 real-world names
$authors = ['J.R.R. Tolkien','Jane Austen','George Orwell','Mark Twain','Charles Dickens','Ernest Hemingway','F. Scott Fitzgerald','William Shakespeare','Agatha Christie','J.K. Rowling','Stephen King','Arthur Conan Doyle','Homer','Leo Tolstoy','Fyodor Dostoevsky','Gabriel Garcia Marquez','Victor Hugo','Edgar Allan Poe','H.P. Lovecraft','H.G. Wells','Jules Verne','Mary Shelley','Bram Stoker','Oscar Wilde','Arthur C. Clarke','Isaac Asimov','Ray Bradbury','Philip K. Dick','Frank Herbert','Kurt Vonnegut','Aldous Huxley','John Steinbeck','Harper Lee','J.D. Salinger','Ralph Ellison','Toni Morrison','Maya Angelou','James Baldwin','Zora Neale Hurston','Langston Hughes','Virginia Woolf','James Joyce','T.S. Eliot','W.B. Yeats','Walt Whitman','Emily Dickinson','Robert Frost','Sylvia Plath','Allen Ginsberg','Jack Kerouac'];

$publishers = ['Penguin Random House','Simon & Schuster','HarperCollins','Macmillan','Hachette Book Group','Scholastic','Pearson','McGraw-Hill','Wiley','Oxford University Press','Cambridge University Press','Springer','Elsevier','Taylor & Francis','Routledge','Bloomsbury','Faber & Faber','Pan Macmillan','Vintage Books','Bantam Books','Tor Books','Gollancz','Orbit Books','Del Rey Books','Baen Books','DAW Books','Ace Books','Roc Books','Spectra','Eos','Voyager','HarperPrism','Ballantine Books','Signet Books','Berkley Books','Jove Books','Avon Books','Pocket Books','Warner Books','Bantam Spectra','Macmillan Children','Usborne','Puffin Books','Ladybird Books','Dorling Kindersley','Rough Guides','Lonely Planet','Insight Guides','Fodors','Frommers'];

$categories = ['Fantasy','Science Fiction','Horror','Thriller','Mystery','Historical Fiction','Romance','Western','Dystopian','Cyberpunk','Steampunk','Urban Fantasy','High Fantasy','Low Fantasy','Hard Sci-Fi','Soft Sci-Fi','Space Opera','Military Sci-Fi','Apocalyptic','Post-Apocalyptic','Zombie','Vampire','Werewolf','Ghost Story','Gothic Fiction','Psychological Thriller','Legal Thriller','Medical Thriller','Techno-Thriller','Spy Thriller','Cozy Mystery','Hardboiled','Police Procedural','True Crime','Biography','Autobiography','Memoir','Self-Help','Philosophy','Psychology','Sociology','History','Political Science','Economics','Business','Finance','Management','Marketing','Sales','Leadership'];

$users = ['Alice Smith','Bob Johnson','Charlie Brown','David Williams','Eve Davis','Frank Miller','Grace Wilson','Hannah Moore','Ian Taylor','Jane Anderson','Kevin Thomas','Laura Jackson','Michael White','Nina Harris','Oliver Martin','Paula Thompson','Quinn Garcia','Rachel Martinez','Sam Robinson','Tina Clark','Uma Rodriguez','Victor Lewis','Wendy Lee','Xavier Walker','Yara Hall','Zack Allen','Amy Young','Brian Hernandez','Chloe King','Daniel Wright','Ella Lopez','Felix Hill','Gina Scott','Harry Green','Isla Adams','Jack Baker','Kara Gonzalez','Liam Nelson','Mia Carter','Noah Mitchell','Olivia Perez','Peter Roberts','Ruby Turner','Sean Phillips','Tara Campbell','Ulf Parker','Vera Evans','Will Edwards','Xena Collins','Yuri Stewart'];

$locations_shelf = ['A1','A2','A3','A4','A5','B1','B2','B3','B4','B5','C1','C2','C3','C4','C5','D1','D2','D3','D4','D5','E1','E2','E3','E4','E5','F1','F2','F3','F4','F5','G1','G2','G3','G4','G5','H1','H2','H3','H4','H5','I1','I2','I3','I4','I5','J1','J2','J3','J4','J5'];
$locations_floor = ['Ground Floor','1st Floor','2nd Floor','3rd Floor','4th Floor'];

$books = ['The Lord of the Rings','Pride and Prejudice','1984','The Adventures of Tom Sawyer','A Tale of Two Cities','The Old Man and the Sea','The Great Gatsby','Hamlet','And Then There Were None','Harry Potter and the Sorcerers Stone','The Shining','The Hound of the Baskervilles','The Odyssey','War and Peace','Crime and Punishment','One Hundred Years of Solitude','Les Miserables','The Tell-Tale Heart','The Call of Cthulhu','The Time Machine','Twenty Thousand Leagues Under the Sea','Frankenstein','Dracula','The Picture of Dorian Gray','2001: A Space Odyssey','Foundation','Fahrenheit 451','Do Androids Dream of Electric Sheep?','Dune','Slaughterhouse-Five','Brave New World','The Grapes of Wrath','To Kill a Mockingbird','The Catcher in the Rye','Invisible Man','Beloved','I Know Why the Caged Bird Sings','Go Tell It on the Mountain','Their Eyes Were Watching God','The Weary Blues','Mrs Dalloway','Ulysses','The Waste Land','The Second Coming','Leaves of Grass','Selected Poems','The Road Not Taken','The Bell Jar','Howl','On the Road'];

// Insert Categories
foreach($categories as $index => $cat) {
    $conn->query("INSERT IGNORE INTO categories (name, description) VALUES ('$cat', 'Books related to $cat')");
}

// Insert Authors
foreach($authors as $index => $auth) {
    $conn->query("INSERT IGNORE INTO authors (name, bio) VALUES ('$auth', 'Famous author known for classical and modern literature.')");
}

// Insert Publishers
foreach($publishers as $index => $pub) {
    $conn->query("INSERT IGNORE INTO publishers (name, contact) VALUES ('$pub', 'contact@" . strtolower(str_replace([' ', '&', '-'], '', $pub)) . ".com')");
}

// Insert Locations
foreach($locations_shelf as $index => $shelf) {
    $floor = $locations_floor[array_rand($locations_floor)];
    $conn->query("INSERT IGNORE INTO locations (shelf_no, floor) VALUES ('$shelf', '$floor')");
}

// Insert Users
foreach($users as $index => $usr) {
    $hash = password_hash('password123', PASSWORD_DEFAULT);
    $email = strtolower(str_replace(' ', '.', $usr)) . "@example.com";
    $conn->query("INSERT IGNORE INTO users (role_id, full_name, email, password_hash, status) VALUES (3, '$usr', '$email', '$hash', 'Active')");
}

// Fetch IDs
$author_ids = array_column($conn->query("SELECT id FROM authors")->fetch_all(MYSQLI_ASSOC), 'id');
$pub_ids = array_column($conn->query("SELECT id FROM publishers")->fetch_all(MYSQLI_ASSOC), 'id');
$cat_ids = array_column($conn->query("SELECT id FROM categories")->fetch_all(MYSQLI_ASSOC), 'id');
$loc_ids = array_column($conn->query("SELECT id FROM locations")->fetch_all(MYSQLI_ASSOC), 'id');
$user_ids = array_column($conn->query("SELECT id FROM users")->fetch_all(MYSQLI_ASSOC), 'id');

// Insert Books
foreach($books as $index => $title) {
    $a_id = $author_ids[array_rand($author_ids)] ?? 1;
    $p_id = $pub_ids[array_rand($pub_ids)] ?? 1;
    $c_id = $cat_ids[array_rand($cat_ids)] ?? 1;
    $l_id = $loc_ids[array_rand($loc_ids)] ?? 1;
    
    $isbn = "978" . str_pad(rand(1, 999999999), 10, '0', STR_PAD_LEFT);
    $conn->query("INSERT IGNORE INTO books (title, isbn, author_id, publisher_id, category_id, location_id, total_copies, available_copies) 
                  VALUES ('$title', '$isbn', $a_id, $p_id, $c_id, $l_id, 10, 10)");
}

$book_ids = array_column($conn->query("SELECT id FROM books")->fetch_all(MYSQLI_ASSOC), 'id');

// Insert Borrowings
for($i=1; $i<=50; $i++) {
    $u_id = $user_ids[array_rand($user_ids)];
    $b_id = $book_ids[array_rand($book_ids)];
    $status = rand(0, 1) ? 'Active' : 'Overdue';
    $conn->query("INSERT IGNORE INTO borrowings (user_id, book_id, issue_date, due_date, status) 
                  VALUES ($u_id, $b_id, DATE_SUB(CURDATE(), INTERVAL 10 DAY), DATE_ADD(CURDATE(), INTERVAL 4 DAY), '$status')");
}

$borrowing_ids = array_column($conn->query("SELECT id FROM borrowings")->fetch_all(MYSQLI_ASSOC), 'id');

// Insert Fines
for($i=1; $i<=50; $i++) {
    $u_id = $user_ids[array_rand($user_ids)];
    $br_id = $borrowing_ids[array_rand($borrowing_ids)];
    $status = rand(0, 1) ? 'Paid' : 'Unpaid';
    $amount = rand(5, 50) . '.00';
    $conn->query("INSERT IGNORE INTO fines (borrowing_id, user_id, amount, status) 
                  VALUES ($br_id, $u_id, $amount, '$status')");
}

// Insert Reservations
for($i=1; $i<=50; $i++) {
    $u_id = $user_ids[array_rand($user_ids)];
    $b_id = $book_ids[array_rand($book_ids)];
    $conn->query("INSERT IGNORE INTO reservations (user_id, book_id, reservation_date, status) 
                  VALUES ($u_id, $b_id, CURDATE(), 'pending')");
}

echo "Successfully replaced generated data with 50+ real-world records in all tables!\n";
?>
