USE smart_library;

-- 1. Users
INSERT IGNORE INTO users (id, role_id, full_name, email, password_hash, status) VALUES 
(3, 3, 'Alice Smith', 'alice@example.com', '$2y$10$eWTfw7KRZW6w26fbVDbmf.neXP4p7rIaQtsfmtX08Vfe0x87c3OOa', 'Active'),
(4, 3, 'Bob Johnson', 'bob@example.com', '$2y$10$eWTfw7KRZW6w26fbVDbmf.neXP4p7rIaQtsfmtX08Vfe0x87c3OOa', 'Active'),
(5, 2, 'Librarian Sarah', 'sarah@smartlib.com', '$2y$10$eWTfw7KRZW6w26fbVDbmf.neXP4p7rIaQtsfmtX08Vfe0x87c3OOa', 'Active'),
(6, 3, 'Charlie Brown', 'charlie@example.com', '$2y$10$eWTfw7KRZW6w26fbVDbmf.neXP4p7rIaQtsfmtX08Vfe0x87c3OOa', 'Suspended');

-- 2. Categories
INSERT IGNORE INTO categories (id, name, description) VALUES 
(4, 'Mystery', 'Whodunit and crime'),
(5, 'History', 'Historical events and figures'),
(6, 'Technology', 'Computers and engineering'),
(7, 'Philosophy', 'Deep thoughts'),
(8, 'Romance', 'Love stories');

-- 3. Authors
INSERT IGNORE INTO authors (id, name, bio) VALUES 
(4, 'Arthur Conan Doyle', 'Creator of Sherlock Holmes'),
(5, 'Yuval Noah Harari', 'Historian and philosopher'),
(6, 'Linus Torvalds', 'Creator of Linux'),
(7, 'Friedrich Nietzsche', 'German philosopher'),
(8, 'Jane Austen', 'English novelist');

-- 4. Publishers
INSERT IGNORE INTO publishers (id, name, contact) VALUES 
(4, 'Vintage Books', 'vintage@books.com'),
(5, 'Manning Publications', 'support@manning.com'),
(6, 'Oxford University Press', 'info@oup.com');

-- 5. Locations
INSERT IGNORE INTO locations (id, shelf_no, floor) VALUES 
(3, 'C3', '1st Floor'),
(4, 'D4', '3rd Floor'),
(5, 'E5', 'Basement');

-- 6. Books
INSERT IGNORE INTO books (id, title, isbn, author_id, publisher_id, category_id, location_id, total_copies, available_copies) VALUES 
(4, 'The Adventures of Sherlock Holmes', '9781593080341', 4, 4, 4, 3, 4, 4),
(5, 'Sapiens', '9780062316097', 5, 4, 5, 4, 6, 6),
(6, 'Just for Fun', '9780066620732', 6, 5, 6, 5, 2, 2),
(7, 'Thus Spoke Zarathustra', '9780140441185', 7, 6, 7, 5, 3, 3),
(8, 'Pride and Prejudice', '9780141439518', 8, 4, 8, 3, 5, 5);

-- 7. Borrowings
INSERT IGNORE INTO borrowings (id, user_id, book_id, issue_date, due_date, status) VALUES 
(3, 3, 4, DATE_SUB(CURDATE(), INTERVAL 2 DAY), DATE_ADD(CURDATE(), INTERVAL 12 DAY), 'Active'),
(4, 4, 5, DATE_SUB(CURDATE(), INTERVAL 16 DAY), DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Overdue'),
(5, 3, 6, DATE_SUB(CURDATE(), INTERVAL 5 DAY), DATE_ADD(CURDATE(), INTERVAL 9 DAY), 'Active');

-- 8. Fines
INSERT IGNORE INTO fines (id, borrowing_id, user_id, amount, status) VALUES 
(2, 4, 4, 4.00, 'Unpaid');

-- 9. Reservations
INSERT IGNORE INTO reservations (id, user_id, book_id, reservation_date, status) VALUES 
(1, 3, 4, CURDATE(), 'pending'),
(2, 4, 5, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'pending');
