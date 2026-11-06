# BugTrack — Laravel Bug Tracking System

A portfolio-ready issue and bug tracking application built with Laravel 12, PHP 8.2+, MySQL and Bootstrap 5.

## Features
- Admin and developer authentication with protected roles
- Dashboard with total, open, in-progress, resolved and critical issue counts
- Project CRUD (admin only)
- Bug reporting, editing, assignment and deletion (admin only)
- Severity, priority and status management
- Bug detail page with reproduction steps, expected/actual results and environment
- Comments and activity history
- Search and filters by keyword, status, severity, priority and project
- Team member CRUD (admin only)
- Demo seed data

## Requirements
- PHP 8.2 or later
- Composer 2
- MySQL 8 / MariaDB
- XAMPP (Apache and MySQL)

## Install on XAMPP
1. Extract the project into `C:\xampp\htdocs\bugtrack`.
2. Open phpMyAdmin and create a database named `bugtrack`.
3. Open Command Prompt in the project folder and run:

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

4. Visit `http://127.0.0.1:8000`.

If you prefer Apache, point the site's document root to the project's `public` folder. Do not expose the project root.

## Demo accounts
| Role | Email | Password |
|---|---|---|
| Admin | admin@bugtrack.test | ChangeMe123! |
| Developer | developer@bugtrack.test | ChangeMe123! |

Change demo passwords before sharing a deployed instance.

## Useful commands
```bash
php artisan migrate:fresh --seed
php artisan route:list
php artisan optimize:clear
```

`migrate:fresh` deletes existing tables/data. Use only on a disposable local database.

## Notes and limitations
- This ZIP contains the Laravel application source and Composer manifest; the `vendor` directory is intentionally not included. Run `composer install` to download dependencies.
- Bootstrap and Bootstrap Icons are loaded from public CDNs, so the interface needs an internet connection for those assets.
- This is a learning/portfolio starter, not a security-audited production system. Add automated tests, rate limiting, stronger policies, file attachments, notifications, and deployment hardening before production use.
- The demo bug key uses a random suffix; collisions are extremely unlikely but should be handled with a retry strategy for high-volume production use.
- Developers can access issues they reported or are assigned. Admins can access all issues.
