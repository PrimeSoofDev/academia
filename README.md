# Academia — University SaaS Platform

> Production-ready, multi-tenant university management system built with **Core PHP (OOP) · MySQL · Tailwind CSS · MVC**.

---

## 🚀 Quick Start (XAMPP)

### 1. Place the project
```
C:\xampp\htdocs\academia\
```

### 2. Enable mod_rewrite
Open `C:\xampp\apache\conf\httpd.conf` and ensure:
```
LoadModule rewrite_module modules/mod_rewrite.so
```
Inside the `<Directory "...htdocs">` block set:
```
AllowOverride All
```
Restart Apache.

### 3. Create the database
In **phpMyAdmin** run `storage/schema.sql`, or via CLI:
```bash
mysql -u root < storage/schema.sql
```

### 4. Fix the demo password hash
```bash
php storage/seed.php
```

### 5. Open the app
```
http://localhost/academia/public/login
```

### 6. Demo login
| Field            | Value             |
|------------------|-------------------|
| University Code  | demo-university   |
| Email            | vc@demo.edu       |
| Password         | password          |

---

## 📁 Project Structure

```
academia/
├── app/
│   ├── controllers/        # AuthController, DashboardController …
│   ├── core/               # App, Router, Controller, Model, Auth, Middleware, Database
│   ├── models/             # User, Faculty, Department, Course
│   └── views/
│       ├── layouts/        # main.php (dashboard shell), auth.php (login shell)
│       ├── auth/           # login.php
│       └── dashboard/      # index.php (role-based)
├── config/
│   ├── app.php             # App settings, roles, units
│   └── database.php        # PDO credentials
├── public/                 # Web root — point Apache here
│   ├── index.php           # Front controller
│   ├── .htaccess           # mod_rewrite rules
│   └── assets/css|js/
├── routes/
│   └── web.php             # All GET/POST route definitions
└── storage/
    ├── schema.sql          # Full DB schema + seed data
    └── seed.php            # Password hash fixer
```

---

## 🔐 Roles & Hierarchy

| Role        | Access Level                          |
|-------------|---------------------------------------|
| superadmin  | Full system access                    |
| vc          | University-wide read/write            |
| dean        | Faculty + department management       |
| hod         | Department + course management        |
| lecturer    | Own courses + student grades          |
| staff       | Assigned admin unit (Registry, etc.)  |
| student     | Own enrolled courses + results        |

---

## 🏛️ Administrative Units

- **Registry** — student admissions, records
- **Bursary** — fee payments, financial records
- **Library** — book management, loans

---

## 🔑 Security Features

- `password_hash()` (bcrypt cost 12) for all passwords
- PDO prepared statements throughout — no raw SQL interpolation
- Session regeneration on login (prevents session fixation)
- CSRF-ready session structure
- Role-based middleware on every protected route
- Tenant isolation: every query scoped by `tenant_id`
- `.htaccess` security headers (X-Frame-Options, X-Content-Type-Options)

---

## ➕ Adding a New Module

1. **Model** → `app/models/MyModel.php` (extend `Model`)
2. **Controller** → `app/controllers/MyController.php` (extend `Controller`)
3. **View** → `app/views/mymodule/index.php`
4. **Routes** → add to `routes/web.php`

---

## 📝 License
MIT — free to use and extend.
