# Vaccination System

A modern PHP & MySQL vaccination scheduling and COVID-19 testing management platform.

---

## Architecture Overview

The application is structured for direct deployment to shared hosting (such as InfinityFree, cPanel, or LAMP/WAMP stacks):

```text
Vaccination-System/
|-- admin/                   # Administrator dashboard and hospital/patient management
|-- assets/                  # Centralized stylesheets, JavaScript, fonts, and template images
|-- hospital/                # Hospital dashboard, appointments, and COVID test results
|-- includes/                # Configuration, database handlers, and global functions
|   |-- config.php           # Central environment settings and database credentials
|   |-- db.php               # MySQLi and PDO database connection handler
|   |-- functions.php        # Helper utilities and data sanitization
|   |-- header.php           # Public navigation bar
|   `-- init.php             # Session initialization and bootstrapping
|-- uploads/                 # Storage for user-uploaded media (patients, hospitals, admin)
|-- Website/                 # Compatibility 301 redirects for legacy URLs
|-- .htaccess                # Apache security rules, UTF-8 charset, and route protections
|-- index.php                # Homepage with vaccine information and hospital locator
|-- appointment.php          # Vaccination booking workflow
|-- covid-test.php           # COVID-19 test application workflow
|-- download-report.php      # Printable report view
|-- login.php                # Patient authentication
|-- logout.php               # Patient session termination
|-- profile.php              # Patient profile, appointments, and test records
|-- user-register.php        # Patient registration
`-- vaccination_system.sql   # Complete database schema and seed data
```

---

## Local Development Setup (XAMPP)

1. Place the project folder inside your web server directory (e.g., `C:/xampp/htdocs/Vaccination-System/`).
2. Start Apache and MySQL services in the XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin/` and create a database named `vaccination_system`.
4. Import `vaccination_system.sql`.
5. Check `includes/config.php` to ensure local settings match:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'vaccination_system');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('APP_ENV', 'development');
   ```
6. Open your browser and navigate to:
   - Patient Portal: `http://localhost/Vaccination-System/index.php`
   - Hospital Portal: `http://localhost/Vaccination-System/hospital/login.php`
   - Admin Portal: `http://localhost/Vaccination-System/admin/login.php`

---

## Deployment to InfinityFree

For complete, step-by-step instructions on deploying this project to InfinityFree free web hosting, see the [InfinityFree Deployment Guide](file:///c:/xampp/htdocs/Vaccination-System/INFINITYFREE_GUIDE.md).

---

## Default Access Credentials

| Role | Portal URL | Email | Password |
| :--- | :--- | :--- | :--- |
| **Administrator** | `/admin/login.php` | `taha@gmail.com` | `taha1234` |
| **Hospital** | `/hospital/login.php` | `ziauddin@gmail.com` | `zia123` |
| **Patient** | `/login.php` | `taha@gmail.com` | `12345` |

---

## Key Features

- **Centralized Database Layer**: All connections and settings managed in `includes/config.php` and `includes/db.php`.
- **Case-Insensitive Compatibility**: All system directories use lowercase naming (`admin/`, `hospital/`, `assets/`, `uploads/`, `includes/`) to eliminate Linux case-sensitivity issues on live hosting.
- **Unified Uploads Directory**: Uploads are partitioned cleanly into `uploads/patients/`, `uploads/hospitals/`, and `uploads/admin/`.
- **Password Security**: Standard `password_hash` with BCRYPT encryption, supporting seamless migration from legacy plaintext credentials.
- **Apache Hardening**: Root `.htaccess` disables directory indexing, protects sensitive files (`.sql`, `.log`, `.ini`, `.md`), and shields the `includes/` folder from direct HTTP requests.
