# Smart Library Management System 📚

A production-grade, administrative dashboard for library management built with PHP, MySQL, and Vanilla JavaScript.

## 📁 Project Structure

This project follows a clean, modular architecture:

```text
smart_library/
├── 📁 api/               # Server-side RESTful API endpoints
│   ├── auth.php          # Session & Authentication checks
│   ├── authors.php       # Author management
│   ├── books.php         # Book Catalog & Search (CRUD)
│   ├── borrow.php        # Borrowing & Returning logic (with Fines)
│   ├── categories.php    # Category management
│   ├── fine.php          # Fine payment logic
│   ├── locations.php     # Shelf & Floor location management
│   ├── login.php         # User authentication
│   ├── publishers.php    # Publisher management
│   ├── users.php         # User management (CRUD)
│   └── ...               # Additional metadata APIs
├── 📁 assets/            # Static resources
│   ├── 📁 css/           
│   │   └── style.css     # Global premium styling & animations
│   └── 📁 js/            
│       └── app.js        # Core SPA Frontend Controller
├── 📁 config/            # System configuration
│   └── db.php            # MySQL Database connection (Port 3307)
├── 📁 database/          # Database management scripts
│   ├── database.sql      # Core Schema (Tables, Triggers, Roles)
│   └── seeders/          # Data seeding scripts (Real-world characters)
├── 📁 pages/             # Frontend UI Pages
│   ├── dashboard.html    # Main Admin Dashboard (SPA)
│   ├── login.html        # Secure Login portal
│   └── signup.html       # User Registration
└── README.md             # Project documentation
```

## 🚀 Getting Started

1. **XAMPP Setup**:
   - Ensure MySQL is running on port **3307**.
   - Place this folder in `C:\xampp\htdocs\smart_library`.
2. **Database**:
   - Import `database/database.sql` into phpMyAdmin.
3. **Access**:
   - Open `http://localhost/smart_library/pages/login.html` in your browser.

## 🛠️ Features
- **Google-like Search**: Real-time suggestions for books, authors, and categories.
- **Full CRUD**: Manage Books, Users, and Authors directly from the dashboard.
- **Smart Stock**: Automated inventory management via database triggers.
- **Fine System**: Automatic late fine calculation upon book return.
- **SPA Architecture**: Smooth navigation without page reloads using the History API.
