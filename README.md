# Smart Library Management System

A production-grade, multi-role Library Management System built as a **Single Page Application (SPA)** using **PHP, MySQL, and Vanilla JavaScript** — featuring RESTful API architecture, database triggers, real-time search, and automated fine management.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3, Vanilla JavaScript (ES6+), SPA via History API |
| Backend | PHP (RESTful API endpoints) |
| Database | MySQL — triggers, relational schema, role management |
| Server | Apache via XAMPP (MySQL on port 3307) |
| Tools | VS Code, phpMyAdmin |

---

## Features

- **Role-Based Authentication** — Secure login and signup with session management and access control per user role
- **SPA Architecture** — Smooth, reload-free navigation using the Browser History API for a seamless dashboard experience
- **Google-like Real-Time Search** — Instant search suggestions across books, authors, and categories as you type
- **Full CRUD Operations** — Complete Create, Read, Update, Delete management for Books, Users, Authors, Publishers, and Categories
- **RESTful API Layer** — Clean, modular PHP API endpoints handling all client-server communication
- **Smart Stock Management** — Automated inventory tracking via MySQL database triggers on borrow and return events
- **Borrow & Return System** — End-to-end book issuance and return workflows with availability status updates
- **Automated Fine Calculation** — System automatically computes overdue fines on book return based on due dates
- **Fine Payment Tracking** — Fine payment lifecycle management from generation to settlement
- **Location Management** — Shelf and floor-level physical location tracking for every book
- **Publisher Management** — Full publisher catalog linked to book records
- **Data Seeding** — Real-world seed scripts for realistic development and testing

---

## Project Structure

```
smart_library/
│
├── api/                        # RESTful PHP API endpoints
│   ├── auth.php                # Session validation & authentication checks
│   ├── authors.php             # Author management (CRUD)
│   ├── books.php               # Book catalog, search & inventory (CRUD)
│   ├── borrow.php              # Borrow/return logic with fine triggers
│   ├── categories.php          # Category management
│   ├── fine.php                # Fine payment processing
│   ├── locations.php           # Shelf & floor location management
│   ├── login.php               # User authentication & session creation
│   ├── publishers.php          # Publisher management
│   ├── users.php               # User management (CRUD)
│   └── ...                     # Additional metadata API handlers
│
├── assets/
│   ├── css/
│   │   └── style.css           # Global styling & animations
│   └── js/
│       └── app.js              # Core SPA frontend controller (History API)
│
├── config/
│   └── db.php                  # MySQL database connection (port 3307)
│
├── database/
│   ├── database.sql            # Core schema — tables, triggers, roles
│   └── seeders/                # Real-world data seeding scripts
│
├── pages/
│   ├── dashboard.html          # Main admin SPA dashboard
│   ├── login.html              # Secure login portal
│   └── signup.html             # User registration page
│
└── README.md
```

---

## Getting Started

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) installed with Apache and MySQL
- MySQL configured to run on **port 3307**
- A modern browser (Chrome / Firefox)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/rakshithbg/smart-library-management-system.git
   ```

2. **Move to XAMPP root**
   ```
   Copy the smart_library/ folder into: C:\xampp\htdocs\smart_library
   ```

3. **Import the database**
   - Start **Apache** and **MySQL** from the XAMPP Control Panel
   - Open `http://localhost/phpmyadmin`
   - Create a new database named `smart_library`
   - Click **Import** → select `database/database.sql` → click **Go**

4. **Configure the connection**
   - Open `config/db.php`
   - Confirm host is `localhost`, port is `3307`, and credentials match your XAMPP setup

5. **Launch the app**
   ```
   http://localhost/smart_library/pages/login.html
   ```

---

---

## Database Highlights

- **Triggers** — Auto-update stock count on borrow and return events without manual intervention
- **Relational Schema** — Normalized tables with foreign key constraints across books, users, borrowings, fines, authors, publishers, locations, and categories
- **Role Management** — User roles defined and enforced at the database level
- **Seeders** — Pre-populated realistic data for development and demo purposes

---

## Author

**Rakshith B G**
- Email: samithrakshit@gmail.com
- LinkedIn: [linkedin.com/in/rakshith-b-g-a39a26404](https://www.linkedin.com/in/rakshith-b-g-a39a26404)
- B.E. Computer Science Engineering — Sapthagiri NPS University, Bengaluru

---

## License

Developed for academic purposes as part of B.E. Computer Science Engineering coursework.
