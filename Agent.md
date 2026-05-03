You are a senior software architect and PHP engineer.

I want you to scaffold a production-ready, scalable university SaaS system called "Academia" using the following stack:

* Backend: Core PHP (OOP, no framework)
* Database: MySQL
* Frontend: Tailwind CSS
* Architecture: MVC (Model-View-Controller)
* Environment: XAMPP (Apache)

The system must support a multi-tenant university structure and reflect a real university hierarchy similar to:

Council → Senate → Vice Chancellor → Deans → HODs → Lecturers → Students
And administrative units like Registry, Bursary, Library, etc.

---

## 🎯 OBJECTIVE

Generate a clean, scalable project structure with:

* Proper folder organization
* Base classes (Controller, Model, Router)
* Multi-tenant support using tenant_id
* Role-based access system
* Unit-based structure (Registry, Bursary, etc.)

---

## 📁 PROJECT STRUCTURE

Create this folder structure:

academia/
│
├── app/
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │
│   ├── models/
│   │   ├── User.php
│   │   ├── Faculty.php
│   │   ├── Department.php
│   │   ├── Course.php
│   │
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── main.php
│   │   │   ├── auth.php
│   │   ├── auth/
│   │   │   ├── login.php
│   │   ├── dashboard/
│   │   │   ├── index.php
│   │
│   ├── core/
│   │   ├── App.php
│   │   ├── Controller.php
│   │   ├── Model.php
│   │   ├── Router.php
│   │   ├── Database.php
│   │   ├── Auth.php
│   │   ├── Middleware.php
│
├── config/
│   ├── database.php
│   ├── app.php
│
├── public/
│   ├── index.php
│   ├── .htaccess
│   ├── assets/
│       ├── css/
│       ├── js/
│
├── routes/
│   ├── web.php
│
├── storage/
│
└── vendor/ (optional for future use)

---

## ⚙️ CORE REQUIREMENTS

### 1. FRONT CONTROLLER

* All requests must go through `/public/index.php`
* Use `.htaccess` to route requests

---

### 2. ROUTER SYSTEM

* Build a simple Router class
* Support GET and POST routes
* Example:
  Route::get('/login', 'AuthController@login');

---

### 3. BASE CLASSES

#### Controller.php

* Load views
* Handle requests

#### Model.php

* Connect to database
* Provide query methods

#### Database.php

* Use PDO
* Secure prepared statements

---

### 4. MULTI-TENANT SUPPORT

* Every table must include `tenant_id`
* Store tenant_id in session after login

---

### 5. AUTHENTICATION SYSTEM

Create Auth class that:

* Handles login/logout
* Stores session:

  * user_id
  * role
  * tenant_id

---

### 6. ROLE SYSTEM

Roles:

* vc
* dean
* hod
* lecturer
* student
* staff

Create middleware like:
authorize(['admin', 'dean'])

---

### 7. UNIT SYSTEM (IMPORTANT)

Support administrative units:

* Registry
* Bursary
* Library

Each user can belong to a unit:

* unit_id in users table

---

### 8. DATABASE CONNECTION

Use config file:
config/database.php

---

### 9. TAILWIND CSS SETUP

* Create a base layout file
* Include Tailwind via CDN (for now)
* Build a simple dashboard UI:

  * Sidebar
  * Navbar
  * Content area

---

### 10. SAMPLE PAGES

Generate:

* Login page
* Dashboard page (role-based)
* Basic navigation

---

## 🔐 SECURITY

* Use password_hash()
* Use prepared statements (PDO)
* Validate inputs

---

## 🧠 CODING STYLE

* Use OOP principles
* Keep code clean and modular
* Separate logic from views

---

## 🚀 OUTPUT FORMAT

* Generate all necessary PHP files with working code
* Include comments explaining each part
* Ensure project runs on XAMPP after setup

---

Do NOT skip any core file. Build this like a real production foundation, not a demo.
