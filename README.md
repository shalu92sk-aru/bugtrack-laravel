# BugTrack — Laravel Bug Tracking System

[![CI](https://github.com/shalu92sk-aru/bugtrack-laravel/actions/workflows/ci.yml/badge.svg)](https://github.com/shalu92sk-aru/bugtrack-laravel/actions/workflows/ci.yml)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-12-red.svg)](https://laravel.com/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A portfolio-ready issue and bug tracking application built with **Laravel 12**, **PHP 8.2+**, **MySQL**, and **Bootstrap 5**.

Designed for development teams to log, track, prioritize, and resolve software issues through clear workflows and role-based permissions.

---

## 🌟 Key Features

- **Role-Based Access Control (RBAC)**: Distinct permissions for `Admin` and `Developer` roles.
- **Interactive Dashboard**: Real-time KPI metrics for total, open, in-progress, resolved, and critical bugs.
- **Project Management**: Create and configure projects with unique uppercase keys (e.g., `DEMO-XXXXXX`).
- **Full Bug Lifecycle Tracking**:
  - Detailed bug reports with steps to reproduce, environment, expected vs. actual behavior.
  - Severity (`critical`, `high`, `medium`, `low`) and Priority (`urgent`, `high`, `normal`, `low`) classifications.
  - Status transitions (`open`, `in_progress`, `resolved`, `closed`) with automated resolution timestamps.
- **Collaborative Discussion**: Comments and audit activity logging for full accountability.
- **Search & Multi-Filter Querying**: Filter issues by keyword search, status, severity, priority, and project.
- **Team Management**: Admin controls to invite, manage, activate/deactivate, and configure team members.
- **Comprehensive Automated Test Suite**: 17 feature tests covering authentication, project management, bug permissions, and user management.

---

## 🔐 Permissions Matrix

| Feature | Admin | Developer |
|---|:---:|:---:|
| View Dashboard & Metrics | ✅ | ✅ (assigned/reported bugs) |
| Create Bug Reports | ✅ | ✅ |
| View Bug Details & Comments | ✅ (all) | ✅ (assigned or reported) |
| Update Status & Edit Bug | ✅ (all) | ✅ (assigned or reported) |
| Delete Bug Reports | ✅ | ❌ |
| Create / Edit / Delete Projects | ✅ | ❌ |
| Manage Team Members | ✅ | ❌ |

---

## 💻 Tech Stack

- **Backend**: Laravel 12.x, PHP 8.2+
- **Database**: MySQL 8 / MariaDB (SQLite for fast automated testing)
- **Frontend**: Blade templates, Bootstrap 5.3, Bootstrap Icons
- **Testing**: PHPUnit 11 (Feature & Integration tests)
- **CI/CD**: GitHub Actions

---

## 🚀 Installation & Local Setup

### Prerequisites
- PHP 8.2 or later with `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, and `curl` extensions
- Composer 2.x
- MySQL 8 or MariaDB
- Local server environment such as Laragon, XAMPP, or standalone PHP/MySQL

### Setup Steps

1. **Clone the repository**:
   ```bash
   git clone https://github.com/shalu92sk-aru/bugtrack-laravel.git
   cd bugtrack-laravel
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Configure the environment**:
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

4. **Set up database credentials**:
   Open `.env` and configure your database settings:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bugtrack
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(Ensure you have created the `bugtrack` database in MySQL/phpMyAdmin)*

5. **Run database migrations and seed demo data**:
   ```bash
   php artisan migrate --seed
   ```

6. **Start the local development server**:
   ```bash
   php artisan serve
   ```
   Visit the application at: `http://127.0.0.1:8000`

---

## 👥 Demo Credentials

The database seeder provisions two default accounts for immediate testing:

| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@bugtrack.test` | `ChangeMe123!` |
| **Developer** | `developer@bugtrack.test` | `ChangeMe123!` |

> [!NOTE]
> For production environments, remember to change default passwords and set `APP_DEBUG=false` in `.env`.

---

## 🧪 Running Automated Tests

Run the automated test suite using Artisan:

```bash
php artisan test
```

Or directly via PHPUnit:

```bash
vendor/bin/phpunit
```

All feature tests run using an in-memory SQLite database, requiring no MySQL configuration.

---

## 🛠️ Helpful Artisan Commands

```bash
# Reset database and re-seed demo data
php artisan migrate:fresh --seed

# View all registered routes
php artisan route:list

# Clear application and configuration caches
php artisan optimize:clear
```

---

## 📄 License

This project is open-source software licensed under the [MIT license](LICENSE).
