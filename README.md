# 📚 Smart Library Management System

A multi-role, DBMS-driven Library Management System built with **HTML5, Tailwind CSS, JavaScript, and MySQL** — supporting Admin, Librarian, and Student workflows with authentication, real-time book tracking, and automated fine calculation.

---

## 🚀 Features

- 🔐 **Role-Based Authentication** — Separate login portals for Admin, Librarian, and Student with access control
- 📖 **Book Issue & Return Tracking** — End-to-end management of book issuance, returns, and availability status
- 🪑 **Seat & Book Reservation** — Students can reserve books and seats in advance with real-time availability
- 📍 **Location Management** — Track physical shelf locations of books within the library
- 💰 **Automated Fine Calculation** — System automatically calculates overdue fines based on return dates
- 🔍 **Search & Filter Books** — Search by title, author, category, or availability with dynamic filtering
- 👤 **Author & Category Management** — Admin can manage book authors, genres, and categories
- 📋 **Reservation Management** — Full reservation lifecycle from request to approval to cancellation

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, Tailwind CSS, JavaScript |
| Backend | PHP (via XAMPP) |
| Database | MySQL |
| Server | Apache (XAMPP / WAMP / LAMP) |
| Tools | VS Code, phpMyAdmin |

---

## 📁 Project Structure

```
smart-library-management-system/
│
├── api/              # Backend API handlers and server-side logic
├── assets/           # Images, icons, and static resources
├── config/           # Database connection and configuration files
├── database/         # SQL schema and seed data files
├── pages/            # Frontend HTML pages for all roles
├── screenshots/      # Output screenshots
└── README.md
```

---

## ⚙️ How to Run Locally

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (or WAMP / LAMP) installed on your machine
- A modern web browser (Chrome / Firefox)

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/rakshithbg/smart-library-management-system.git
   ```

2. **Move project to server root**
   - Copy the project folder into `C:/xampp/htdocs/` (Windows) or `/opt/lampp/htdocs/` (Linux)

3. **Start XAMPP**
   - Open XAMPP Control Panel
   - Start **Apache** and **MySQL**

4. **Set up the database**
   - Open your browser and go to `http://localhost/phpmyadmin`
   - Create a new database named `library_db`
   - Click **Import** and select the SQL file from the `database/` folder
   - Click **Go** to import

5. **Configure database connection**
   - Open `config/` folder and update your database credentials if needed (default: host `localhost`, user `root`, password ` `)

6. **Run the project**
   - Open your browser and go to:
     ```
     http://localhost/smart-library-management-system/pages/
     ```

---

## 📸 Screenshots

<table>
  <tr>
    <td><img src="screenshots/1.png" width="400" alt="Login Page"/></td>
    <td><img src="screenshots/2.png" width="400" alt="Admin Dashboard"/></td>
  </tr>
  <tr>
    <td><img src="screenshots/3.png" width="400" alt="Book Management"/></td>
    <td><img src="screenshots/4.png" width="400" alt="Issue & Return"/></td>
  </tr>
  <tr>
    <td><img src="screenshots/5.png" width="400" alt="Reservation"/></td>
    <td><img src="screenshots/6.png" width="400" alt="Fine Calculation"/></td>
  </tr>
  <tr>
    <td><img src="screenshots/7.png" width="400" alt="Search & Filter"/></td>
    <td><img src="screenshots/8.png" width="400" alt="Student Dashboard"/></td>
  </tr>
  <tr>
    <td><img src="screenshots/9.png" width="400" alt="Librarian View"/></td>
    <td><img src="screenshots/10.png" width="400" alt="Author Management"/></td>
  </tr>
</table>

---

## 🗃️ Database Schema Overview

- `users` — stores Admin, Librarian, and Student credentials with role flags
- `books` — book catalog with title, author, category, location, and availability
- `authors` — author details linked to books
- `borrowings` — tracks issued books, due dates, and return status
- `reservations` — manages seat and book reservation records
- `fines` — stores calculated fine amounts per borrowing record
- `locations` — physical shelf/section locations within the library

---

## 👨‍💻 Author

**Rakshith B G**
- 📧 samithrakshit@gmail.com
- 💼 [LinkedIn](https://www.linkedin.com/in/rakshith-b-g-a39a26404)
- 🎓 B.E. Computer Science Engineering — Sapthagiri NPS University, Bengaluru

---

## 📄 License

This project is developed for academic purposes as part of B.E. Computer Science Engineering coursework.
