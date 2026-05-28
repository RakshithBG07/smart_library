USE smart_library;

-- Authors (IDs 9 to 23)
INSERT IGNORE INTO authors (id, name, bio) VALUES 
(9, 'J.K. Rowling', 'British author, creator of Harry Potter.'),
(10, 'George R.R. Martin', 'American novelist, creator of A Song of Ice and Fire.'),
(11, 'Agatha Christie', 'English writer known for her detective novels.'),
(12, 'Stephen King', 'American author of horror, supernatural fiction.'),
(13, 'Neil Gaiman', 'English author of short fiction, novels.'),
(14, 'Terry Pratchett', 'English author of fantasy novels.'),
(15, 'Frank Herbert', 'American science fiction writer.'),
(16, 'Isaac Asimov', 'Prolific American writer.'),
(17, 'H.P. Lovecraft', 'American writer of weird and horror fiction.'),
(18, 'Mark Twain', 'American writer, humorist.'),
(19, 'Charles Dickens', 'English writer and social critic.'),
(20, 'Leo Tolstoy', 'Russian writer.'),
(21, 'Fyodor Dostoevsky', 'Russian novelist and essayist.'),
(22, 'Virginia Woolf', 'English modernist author.'),
(23, 'Gabriel Garcia Marquez', 'Colombian novelist and journalist.');

-- Publishers (IDs 7 to 16)
INSERT IGNORE INTO publishers (id, name, contact) VALUES 
(7, 'Macmillan Publishers', 'contact@macmillan.com'),
(8, 'Simon & Schuster', 'info@simonandschuster.com'),
(9, 'Hachette Livre', 'hello@hachette.com'),
(10, 'Scholastic', 'support@scholastic.com'),
(11, 'Pearson Education', 'info@pearson.com'),
(12, 'McGraw-Hill', 'support@mcgraw-hill.com'),
(13, 'Bloomsbury Publishing', 'info@bloomsbury.com'),
(14, 'Bantam Spectra', 'contact@bantamspectra.com'),
(15, 'Tor Books', 'hello@tor.com'),
(16, 'Gollancz', 'info@gollancz.co.uk');

-- Categories (IDs 9 to 17)
INSERT IGNORE INTO categories (id, name, description) VALUES 
(9, 'Horror', 'Scary and terrifying stories'),
(10, 'Thriller', 'Suspenseful and exciting'),
(11, 'Biography', 'Stories of real people'),
(12, 'Self-Help', 'Books for personal improvement'),
(13, 'Science', 'Scientific concepts and theories'),
(14, 'Poetry', 'Rhythmic and aesthetic writing'),
(15, 'Comics', 'Graphic novels and comics'),
(16, 'Cookbooks', 'Recipes and culinary arts'),
(17, 'Travel', 'Guides and stories about places');

-- Locations (IDs 6 to 13)
INSERT IGNORE INTO locations (id, shelf_no, floor) VALUES 
(6, 'A2', '1st Floor'),
(7, 'B3', '2nd Floor'),
(8, 'C4', '3rd Floor'),
(9, 'D5', 'Basement'),
(10, 'E1', '4th Floor'),
(11, 'F2', 'Ground Floor'),
(12, 'G3', 'Ground Floor'),
(13, 'H4', '1st Floor');

-- Books (IDs 9 to 28)
INSERT IGNORE INTO books (id, title, isbn, author_id, publisher_id, category_id, location_id, total_copies, available_copies) VALUES 
(9, 'Harry Potter and the Sorcerers Stone', '9780747532699', 9, 13, 3, 6, 10, 8),
(10, 'A Game of Thrones', '9780553103540', 10, 14, 3, 7, 5, 2),
(11, 'Murder on the Orient Express', '9780007119318', 11, 7, 4, 8, 4, 4),
(12, 'The Shining', '9780385121675', 12, 8, 9, 9, 3, 1),
(13, 'American Gods', '9780380789030', 13, 9, 3, 10, 6, 5),
(14, 'Good Omens', '9780575046903', 14, 10, 3, 11, 2, 2),
(15, 'Dune', '9780441172719', 15, 7, 1, 6, 7, 7),
(16, 'The Call of Cthulhu', '9781503265882', 17, 8, 9, 7, 3, 3),
(17, 'The Adventures of Tom Sawyer', '9780143039561', 18, 9, 5, 8, 4, 4),
(18, 'A Tale of Two Cities', '9780141439600', 19, 10, 5, 9, 8, 8),
(19, 'War and Peace', '9780140447934', 20, 11, 5, 10, 2, 2),
(20, 'Crime and Punishment', '9780140449136', 21, 12, 5, 11, 5, 5),
(21, 'To the Lighthouse', '9780156907392', 22, 7, 5, 6, 3, 3),
(22, 'One Hundred Years of Solitude', '9780060883287', 23, 8, 3, 7, 6, 6),
(23, 'It', '9780451169518', 12, 9, 9, 8, 4, 1),
(24, 'The Stand', '9780307743688', 12, 10, 9, 9, 5, 5),
(25, 'Coraline', '9780380807345', 13, 11, 3, 10, 7, 7),
(26, 'Neverwhere', '9780380789016', 13, 12, 3, 11, 4, 4),
(27, 'The Colour of Magic', '9780552124751', 14, 7, 3, 6, 3, 3),
(28, 'Mort', '9780552131063', 14, 8, 3, 7, 5, 5);

-- Some Users (IDs 7 to 9)
INSERT IGNORE INTO users (id, role_id, full_name, email, password_hash, status) VALUES 
(7, 3, 'Emily Davis', 'emily@example.com', '$2y$10$eWTfw7KRZW6w26fbVDbmf.neXP4p7rIaQtsfmtX08Vfe0x87c3OOa', 'Active'),
(8, 3, 'Michael Wilson', 'michael@example.com', '$2y$10$eWTfw7KRZW6w26fbVDbmf.neXP4p7rIaQtsfmtX08Vfe0x87c3OOa', 'Active'),
(9, 3, 'Sarah Taylor', 'sarah.t@example.com', '$2y$10$eWTfw7KRZW6w26fbVDbmf.neXP4p7rIaQtsfmtX08Vfe0x87c3OOa', 'Active');

-- Some Borrowings (IDs 6 to 9)
INSERT IGNORE INTO borrowings (id, user_id, book_id, issue_date, due_date, status) VALUES 
(6, 7, 9, DATE_SUB(CURDATE(), INTERVAL 3 DAY), DATE_ADD(CURDATE(), INTERVAL 11 DAY), 'Active'),
(7, 8, 10, DATE_SUB(CURDATE(), INTERVAL 20 DAY), DATE_SUB(CURDATE(), INTERVAL 6 DAY), 'Overdue'),
(8, 9, 12, DATE_SUB(CURDATE(), INTERVAL 5 DAY), DATE_ADD(CURDATE(), INTERVAL 9 DAY), 'Active'),
(9, 7, 23, DATE_SUB(CURDATE(), INTERVAL 15 DAY), DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Overdue');

-- Some Fines
INSERT IGNORE INTO fines (id, borrowing_id, user_id, amount, status) VALUES 
(4, 7, 8, 12.00, 'Unpaid'),
(5, 9, 7, 2.00, 'Unpaid');

-- Some Reservations
INSERT IGNORE INTO reservations (id, user_id, book_id, reservation_date, status) VALUES 
(3, 8, 11, CURDATE(), 'pending'),
(4, 9, 14, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'pending');
