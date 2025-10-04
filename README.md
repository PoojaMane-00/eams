# 🧑‍💼 EAMS - Employee Attendance Management System

EAMS is a Laravel-based web application designed to streamline employee management and attendance tracking. It provides a robust admin interface for managing employee records and a user-friendly portal for employees to update their profiles, upload documents, and mark attendance.

---

## 🚀 Features

### 👨‍💼 Admin Panel
- Create and manage employee records
- View and regularize attendance logs
- Access uploaded employee documents
- Monitor employee attendance and profile

### 🙋‍♂️ Employee Portal
- Edit personal profile information
- Upload and manage documents (e.g., ID proofs, certificates)
- Mark daily attendance
- View attendance history

---

## 🛠 Tech Stack

- **Framework**: Laravel 12.29.0
- **Language**: PHP 8.3.12
- **Database**: Postgres 
- **Frontend**: Bootstrap

---

## ⚙️ Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/eams-laravel.git
   cd eams-laravel
2. **Install dependencies**
   composer install
   npm install && npm run dev
3. **Set up environment**
   cp .env.example .env
   php artisan key:generate
4. **Configure database**
    Update .env with your DB credentials:
   DB_DATABASE=eams
   DB_USERNAME=root
   DB_PASSWORD=yourpassword
5. **Run migrations**
   php artisan migrate
7. **Start the server**
   php artisan serve

## 📂 Folder Structure Highlights
app/Http/Controllers/AdminController.php – Admin logic
app/Http/Controllers/EmployeeController.php – Employee actions
resources/views/ – Blade templates for UI
routes/web.php – Route definitions


