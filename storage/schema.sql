-- ═══════════════════════════════════════════════════════════════
-- Academia University SaaS — Full Database Schema
-- Run this in phpMyAdmin or: mysql -u root academia < schema.sql
-- ═══════════════════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS academia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE academia;

SET FOREIGN_KEY_CHECKS = 0;

-- ── 1. TENANTS (Universities) ─────────────────────────────────
CREATE TABLE IF NOT EXISTS tenants (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(255) NOT NULL,
    slug       VARCHAR(100) NOT NULL UNIQUE,   -- used as login code
    email      VARCHAR(255),
    phone      VARCHAR(50),
    address    TEXT,
    logo       VARCHAR(255),
    status     ENUM('active','suspended','trial') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── 2. ADMINISTRATIVE UNITS ───────────────────────────────────
CREATE TABLE IF NOT EXISTS units (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id   INT UNSIGNED NOT NULL,
    name        VARCHAR(150) NOT NULL,           -- e.g. Registry, Bursary
    code        VARCHAR(50),                     -- e.g. REG, BUR
    description TEXT,
    head_id     INT UNSIGNED NULL,               -- FK to users (set after users table)
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tenant (tenant_id)
) ENGINE=InnoDB;

-- ── 3. USERS ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id       INT UNSIGNED NOT NULL,
    unit_id         INT UNSIGNED NULL,           -- for admin staff
    faculty_id      INT UNSIGNED NULL,
    department_id   INT UNSIGNED NULL,
    name            VARCHAR(255) NOT NULL,
    email           VARCHAR(255) NOT NULL,
    phone           VARCHAR(50),
    password        VARCHAR(255) NOT NULL,
    role            ENUM('superadmin','vc','dean','hod','lecturer','staff','student') NOT NULL DEFAULT 'student',
    matric_number   VARCHAR(50) NULL,            -- students only
    staff_id        VARCHAR(50) NULL,            -- staff/lecturers
    profile_image   VARCHAR(255) NULL,
    banner_image    VARCHAR(255) NULL,
    gender          ENUM('male','female','other') NULL,
    date_of_birth   DATE NULL,
    address         TEXT NULL,
    status          ENUM('active','inactive','suspended','pending') DEFAULT 'active',
    email_verified  TINYINT(1) DEFAULT 0,
    last_login      DATETIME NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_email_tenant (email, tenant_id),
    INDEX idx_tenant   (tenant_id),
    INDEX idx_role     (role),
    INDEX idx_dept     (department_id),
    INDEX idx_faculty  (faculty_id)
) ENGINE=InnoDB;

-- ── 4. FACULTIES ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS faculties (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id   INT UNSIGNED NOT NULL,
    dean_id     INT UNSIGNED NULL,
    name        VARCHAR(255) NOT NULL,
    code        VARCHAR(20) NOT NULL,
    description TEXT,
    established_year YEAR NULL,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tenant (tenant_id)
) ENGINE=InnoDB;

-- ── 5. DEPARTMENTS ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS departments (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id   INT UNSIGNED NOT NULL,
    faculty_id  INT UNSIGNED NOT NULL,
    hod_id      INT UNSIGNED NULL,
    name        VARCHAR(255) NOT NULL,
    code        VARCHAR(20) NOT NULL,
    description TEXT,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tenant  (tenant_id),
    INDEX idx_faculty (faculty_id)
) ENGINE=InnoDB;

-- ── 6. COURSES ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS courses (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id     INT UNSIGNED NOT NULL,
    department_id INT UNSIGNED NOT NULL,
    lecturer_id   INT UNSIGNED NULL,
    code          VARCHAR(30) NOT NULL,
    title         VARCHAR(255) NOT NULL,
    description   TEXT,
    credit_units  TINYINT UNSIGNED DEFAULT 3,
    level         ENUM('100','200','300','400','500','PG') DEFAULT '100',
    semester      ENUM('first','second','year') DEFAULT 'first',
    session       VARCHAR(20),                -- e.g. 2024/2025
    status        ENUM('active','inactive') DEFAULT 'active',
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tenant (tenant_id),
    INDEX idx_dept   (department_id)
) ENGINE=InnoDB;

-- ── 7. ENROLLMENTS ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS enrollments (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id   INT UNSIGNED NOT NULL,
    student_id  INT UNSIGNED NOT NULL,
    course_id   INT UNSIGNED NOT NULL,
    grade       VARCHAR(5) NULL,               -- A, B+, C, etc.
    score       DECIMAL(5,2) NULL,
    status      ENUM('active','dropped','completed') DEFAULT 'active',
    enrolled_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_enrollment (student_id, course_id),
    INDEX idx_student (student_id),
    INDEX idx_course  (course_id),
    INDEX idx_tenant  (tenant_id)
) ENGINE=InnoDB;

-- ── 8. ACADEMIC SESSIONS ──────────────────────────────────────
CREATE TABLE IF NOT EXISTS academic_sessions (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id   INT UNSIGNED NOT NULL,
    name        VARCHAR(50) NOT NULL,           -- e.g. 2024/2025
    start_date  DATE NOT NULL,
    end_date    DATE NOT NULL,
    is_current  TINYINT(1) DEFAULT 0,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tenant (tenant_id)
) ENGINE=InnoDB;

-- ── 9. BURSARY — FEE PAYMENTS ─────────────────────────────────
CREATE TABLE IF NOT EXISTS fee_payments (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id   INT UNSIGNED NOT NULL,
    student_id  INT UNSIGNED NOT NULL,
    session_id  INT UNSIGNED NULL,
    amount      DECIMAL(12,2) NOT NULL,
    fee_type    VARCHAR(100) NOT NULL,          -- tuition, accommodation, etc.
    reference   VARCHAR(100) UNIQUE,
    status      ENUM('pending','paid','failed','reversed') DEFAULT 'pending',
    paid_at     DATETIME NULL,
    recorded_by INT UNSIGNED NULL,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_student (student_id),
    INDEX idx_tenant  (tenant_id)
) ENGINE=InnoDB;

-- ── 10. LIBRARY — BOOKS ───────────────────────────────────────
CREATE TABLE IF NOT EXISTS books (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id    INT UNSIGNED NOT NULL,
    isbn         VARCHAR(30) NULL,
    title        VARCHAR(255) NOT NULL,
    author       VARCHAR(255),
    publisher    VARCHAR(255),
    year         YEAR,
    copies_total INT UNSIGNED DEFAULT 1,
    copies_avail INT UNSIGNED DEFAULT 1,
    category     VARCHAR(100),
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tenant (tenant_id)
) ENGINE=InnoDB;

-- ── 11. LIBRARY — BOOK LOANS ──────────────────────────────────
CREATE TABLE IF NOT EXISTS book_loans (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id   INT UNSIGNED NOT NULL,
    book_id     INT UNSIGNED NOT NULL,
    user_id     INT UNSIGNED NOT NULL,
    issued_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    due_at      DATETIME NOT NULL,
    returned_at DATETIME NULL,
    status      ENUM('active','returned','overdue') DEFAULT 'active',
    INDEX idx_book   (book_id),
    INDEX idx_user   (user_id),
    INDEX idx_tenant (tenant_id)
) ENGINE=InnoDB;

-- ── 12. RESULTS ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS results (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id    INT UNSIGNED NOT NULL,
    student_id   INT UNSIGNED NOT NULL,
    course_id    INT UNSIGNED NOT NULL,
    session_id   INT UNSIGNED NULL,
    ca_score     DECIMAL(5,2) DEFAULT 0,
    exam_score   DECIMAL(5,2) DEFAULT 0,
    total_score  DECIMAL(5,2) GENERATED ALWAYS AS (ca_score + exam_score) STORED,
    grade        VARCHAR(5) NULL,
    grade_point  DECIMAL(3,2) NULL,
    published    TINYINT(1) DEFAULT 0,
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_result (student_id, course_id, session_id),
    INDEX idx_student (student_id),
    INDEX idx_tenant  (tenant_id)
) ENGINE=InnoDB;

-- ── 13. MEETINGS & BOOKINGS ────────────────────────────────────
CREATE TABLE IF NOT EXISTS meetings (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id   INT UNSIGNED NOT NULL,
    host_id     INT UNSIGNED NOT NULL,
    guest_id    INT UNSIGNED NULL,
    title       VARCHAR(255) NOT NULL,
    description TEXT NULL,
    start_time  DATETIME NOT NULL,
    end_time    DATETIME NOT NULL,
    location    VARCHAR(255) NULL,
    status      ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_host (host_id),
    INDEX idx_guest (guest_id),
    INDEX idx_tenant (tenant_id)
) ENGINE=InnoDB;

-- ── 14. CAMPUS REPORTS ────────────────────────────────────────
CREATE TABLE IF NOT EXISTS campus_reports (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id       INT UNSIGNED NOT NULL,
    student_id      INT UNSIGNED NOT NULL,
    title           VARCHAR(255) NOT NULL,
    description     TEXT NOT NULL,
    category        VARCHAR(100) NOT NULL,          -- security, facility, academic, medical, etc.
    urgency         ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    location        VARCHAR(255) NULL,
    status          ENUM('pending', 'investigating', 'resolved', 'dismissed') DEFAULT 'pending',
    resolution_note TEXT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_student (student_id),
    INDEX idx_tenant  (tenant_id),
    INDEX idx_status  (status)
) ENGINE=InnoDB;

-- ── 15. NOTIFICATIONS ──────────────────────────────────────────
CREATE TABLE IF NOT EXISTS notifications (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id   INT UNSIGNED NOT NULL,
    user_id     INT UNSIGNED NOT NULL,
    title       VARCHAR(255) NOT NULL,
    message     TEXT NOT NULL,
    type        VARCHAR(50) DEFAULT 'info',     -- info, success, warning, error
    link        VARCHAR(255) NULL,              -- where to go when clicked
    is_read     TINYINT(1) DEFAULT 0,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_tenant (tenant_id)
) ENGINE=InnoDB;

-- ── 14. AUDIT LOGS ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS audit_logs (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id  INT UNSIGNED NOT NULL,
    user_id    INT UNSIGNED NULL,
    action     VARCHAR(100) NOT NULL,
    model      VARCHAR(100) NULL,
    model_id   INT UNSIGNED NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    ip_address VARCHAR(45) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tenant (tenant_id),
    INDEX idx_user   (user_id)
) ENGINE=InnoDB;

-- ═══════════════════════════════════════════════════════════════
-- FOREIGN KEYS
-- ═══════════════════════════════════════════════════════════════

ALTER TABLE users
    ADD CONSTRAINT fk_users_tenant     FOREIGN KEY (tenant_id)     REFERENCES tenants(id)     ON DELETE CASCADE,
    ADD CONSTRAINT fk_users_unit       FOREIGN KEY (unit_id)       REFERENCES units(id)        ON DELETE SET NULL,
    ADD CONSTRAINT fk_users_faculty    FOREIGN KEY (faculty_id)    REFERENCES faculties(id)    ON DELETE SET NULL,
    ADD CONSTRAINT fk_users_department FOREIGN KEY (department_id) REFERENCES departments(id)  ON DELETE SET NULL;

ALTER TABLE faculties
    ADD CONSTRAINT fk_faculties_tenant FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    ADD CONSTRAINT fk_faculties_dean   FOREIGN KEY (dean_id)   REFERENCES users(id)   ON DELETE SET NULL;

ALTER TABLE departments
    ADD CONSTRAINT fk_departments_tenant  FOREIGN KEY (tenant_id)  REFERENCES tenants(id)   ON DELETE CASCADE,
    ADD CONSTRAINT fk_departments_faculty FOREIGN KEY (faculty_id) REFERENCES faculties(id) ON DELETE CASCADE,
    ADD CONSTRAINT fk_departments_hod     FOREIGN KEY (hod_id)     REFERENCES users(id)     ON DELETE SET NULL;

ALTER TABLE courses
    ADD CONSTRAINT fk_courses_tenant  FOREIGN KEY (tenant_id)     REFERENCES tenants(id)     ON DELETE CASCADE,
    ADD CONSTRAINT fk_courses_dept    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE,
    ADD CONSTRAINT fk_courses_lect    FOREIGN KEY (lecturer_id)   REFERENCES users(id)       ON DELETE SET NULL;

ALTER TABLE enrollments
    ADD CONSTRAINT fk_enroll_tenant  FOREIGN KEY (tenant_id)  REFERENCES tenants(id)  ON DELETE CASCADE,
    ADD CONSTRAINT fk_enroll_student FOREIGN KEY (student_id) REFERENCES users(id)    ON DELETE CASCADE,
    ADD CONSTRAINT fk_enroll_course  FOREIGN KEY (course_id)  REFERENCES courses(id)  ON DELETE CASCADE;

ALTER TABLE units
    ADD CONSTRAINT fk_units_tenant FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE;

SET FOREIGN_KEY_CHECKS = 1;

-- ═══════════════════════════════════════════════════════════════
-- SEED DATA — Demo University + Admin User
-- ═══════════════════════════════════════════════════════════════

INSERT INTO tenants (name, slug, email, status) VALUES
('Demo University', 'demo-university', 'admin@demo.edu', 'active');

-- Units
INSERT INTO units (tenant_id, name, code) VALUES
(1, 'Registry',       'REG'),
(1, 'Bursary',        'BUR'),
(1, 'Library',        'LIB'),
(1, 'ICT Services',   'ICT');

-- VC user (password: "password")
INSERT INTO users (tenant_id, name, email, password, role, status) VALUES
(1, 'Vice Chancellor Demo', 'vc@demo.edu',
 '$2y$12$5G5M2B2zyK1tJgWvD/3j/e6hGqg3b0Y0W7P7F1F6Dp8W3J1R5bS9K',
 'vc', 'active');

-- Faculty
INSERT INTO faculties (tenant_id, dean_id, name, code) VALUES
(1, 1, 'Faculty of Engineering', 'ENG'),
(1, 1, 'Faculty of Science',     'SCI');

-- Departments
INSERT INTO departments (tenant_id, faculty_id, hod_id, name, code) VALUES
(1, 1, 1, 'Computer Engineering',   'CPE'),
(1, 1, 1, 'Electrical Engineering', 'EEE'),
(1, 2, 1, 'Computer Science',       'CSC'),
(1, 2, 1, 'Mathematics',            'MTH');

-- Academic Session
INSERT INTO academic_sessions (tenant_id, name, start_date, end_date, is_current) VALUES
(1, '2024/2025', '2024-10-01', '2025-07-31', 1);
