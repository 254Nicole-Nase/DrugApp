# 💊 Drug Info Center

A PHP web application for managing and displaying information about different drug categories. Built for educational purposes.

![PHP](https://img.shields.io/badge/PHP-8.x-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.x-orange)
![License](https://img.shields.io/badge/License-MIT-green)

## ✨ Features

- **🔐 Secure Authentication** - Password hashing with PHP's `password_hash()`
- **📊 Dashboard** - View all drugs organized by category
- **➕ CRUD Operations** - Add, Edit, View, and Delete drugs
- **🖼️ Image Upload** - Upload drug images with validation
- **🔒 SQL Injection Protection** - Prepared statements throughout
- **📱 Responsive Design** - Modern UI that works on all devices
- **🔑 Password Management** - Change password functionality

## 🚀 Quick Start

### Prerequisites
- PHP 8.x
- MySQL 8.x or MariaDB
- Web browser

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/254Nicole-Nase/DrugApp.git
   cd DrugApp
   ```

2. **Set up the database**
   ```bash
   mysql -u root -p < db/drug_app.sql
   ```

3. **Configure database connection**
   
   Edit `db_connection.php`:
   ```php
   $hostname = "127.0.0.1";
   $username = "root";
   $password = "your_password";  // Update this
   $database = "drug_app";
   ```

4. **Start the server**
   ```bash
   php -S localhost:8000
   ```

5. **Open in browser**
   ```
   http://localhost:8000
   ```

### Default Login
- **Username:** `joke`
- **Password:** `12345`

## 📁 Project Structure

```
DrugApp/
├── css/
│   └── style.css          # Modern responsive styles
├── db/
│   └── drug_app.sql       # Database schema & seed data
├── img/                   # Drug images
├── index.php              # Login page
├── dashboard.php          # Main dashboard
├── addDrug.php            # Add new drug form
├── editDrug.php           # Edit drug form
├── deleteDrug.php         # Delete drug handler
├── view_details.php       # Drug details page
├── drugCategories.php     # Categories listing
├── changePassword.php     # Password change form
├── header.php             # Common header/nav
├── footer.php             # Common footer
├── db_connection.php      # Database configuration
├── functions.php          # Helper functions
└── logout.php             # Session logout
```

## 🗄️ Database Schema

| Table | Description |
|-------|-------------|
| `administrators` | Admin login credentials |
| `drug_categories` | 6 drug categories |
| `drug_details` | Drug info with images |
| `admin_sessions` | Session tracking |

### Drug Categories
1. CNS Depressants
2. CNS Stimulants
3. Hallucinogens
4. Dissociative Anesthetics
5. Narcotic Analgesics
6. Inhalants

## 🔒 Security Features

- ✅ Password hashing (bcrypt)
- ✅ Prepared statements (SQL injection prevention)
- ✅ Input sanitization (XSS prevention)
- ✅ Session-based authentication
- ✅ File upload validation

## 📸 Screenshots

### Login Page
Modern login interface with secure authentication.

### Dashboard
Grid view of all drugs organized by category with edit/delete options.

### Add Drug Form
Easy-to-use form with image preview and category selection.

## 🛠️ Technologies Used

- **Backend:** PHP 8.x
- **Database:** MySQL 8.x / MariaDB
- **Frontend:** HTML5, CSS3, JavaScript
- **Fonts:** Google Fonts (Outfit, Space Mono)

## 📝 License

This project is for educational purposes.

## 👤 Author

**Nicole Nase**
- GitHub: [@254Nicole-Nase](https://github.com/254Nicole-Nase)

---

⭐ Star this repo if you found it helpful!
