# InfinityFree Deployment Guide

This guide details the step-by-step process for deploying the Vaccination Management System to InfinityFree shared hosting.

---

## Directory Structure Overview

The project is structured specifically for Apache web roots (such as `htdocs/` on InfinityFree and cPanel):

```text
htdocs/
|-- admin/                   # Administrator portal
|-- assets/                  # CSS, JS, fonts, and template images
|   |-- css/
|   |-- fonts/
|   |-- images/
|   `-- js/
|-- hospital/                # Hospital management portal
|-- includes/                # Centralized configuration and database logic
|   |-- config.php           # Database credentials and environment settings
|   |-- db.php               # Database connection handler (MySQLi and PDO)
|   |-- functions.php        # Helper utilities
|   |-- header.php           # Patient portal header navigation
|   `-- init.php             # Core session and bootstrap loader
|-- uploads/                 # User-uploaded files and media
|   |-- admin/
|   |-- hospitals/
|   `-- patients/
|-- Website/                 # Backward-compatibility 301 redirects
|-- .htaccess                # Security and Apache routing directives
|-- appointment.php          # Patient vaccine booking
|-- covid-test.php           # Patient COVID-19 test booking
|-- download-report.php      # Printable report generator
|-- index.php                # Public homepage
|-- login.php                # Patient login
|-- logout.php               # Patient logout
|-- profile.php              # Patient profile and appointment history
|-- user-register.php        # Patient registration
`-- vaccination_system.sql   # Complete database schema and seed data
```

---

## Step 1: Create a MySQL Database in InfinityFree

1. Log in to the [InfinityFree Client Area](https://dash.infinityfree.com/).
2. Select your hosting account and click **Control Panel** (vPanel).
3. Under the **Databases** section, click **MySQL Databases**.
4. In the **Create New Database** section, enter a database name (for example, `vaccination`) and click **Create Database**.
5. Once created, note the following details displayed in the table:
   - **MySQL Hostname**: Typically looks like `sql123.infinityfree.com` or `sql123.epizy.com`.
   - **MySQL Database Name**: Full name including prefix, e.g., `epiz_12345678_vaccination`.
   - **MySQL Username**: Account username, e.g., `epiz_12345678`.
   - **MySQL Password**: Found in your Client Area under Account Details.

---

## Step 2: Import the Database via phpMyAdmin

1. In the InfinityFree vPanel under **MySQL Databases**, click the **phpMyAdmin** button next to your newly created database.
2. In phpMyAdmin, click the database name on the left sidebar.
3. Click the **Import** tab in the top navigation bar.
4. Click **Choose File** and select `vaccination_system.sql` from your project folder.
5. Ensure the character set is set to **utf-8**.
6. Click **Go** at the bottom to execute the import.
7. Verify that all 8 tables are present:
   - `tbl_admin`
   - `tbl_appointment`
   - `tbl_city`
   - `tbl_feedback`
   - `tbl_hospital`
   - `tbl_patient`
   - `tbl_test`
   - `tbl_vaccine`

---

## Step 3: Configure Database Credentials

Open `includes/config.php` in a text editor and update the database parameters with your InfinityFree details:

```php
// Database Credentials for InfinityFree
define('DB_HOST', 'sql123.infinityfree.com');      // Replace with your MySQL Hostname
define('DB_NAME', 'epiz_12345678_vaccination');    // Replace with your full Database Name
define('DB_USER', 'epiz_12345678');                // Replace with your MySQL Username
define('DB_PASS', 'your_account_password');        // Replace with your vPanel password
define('DB_CHARSET', 'utf8mb4');

// Set environment to production for live deployment
define('APP_ENV', 'production');
```

Save the file.

---

## Step 4: Upload Files to InfinityFree

You can upload the project using FTP (recommended) or the online File Manager.

### Option A: Using FileZilla (Recommended)

1. Download and open [FileZilla Client](https://filezilla-project.org/).
2. Enter your FTP connection details from your InfinityFree Client Area:
   - **Host**: `ftpupload.net`
   - **Username**: Your account username (`epiz_12345678`)
   - **Password**: Your hosting account password
   - **Port**: `21`
3. Click **Quickconnect**.
4. In the right pane (Remote site), open the `htdocs` directory.
   - If there is an existing `index2.html` or default welcome file, delete it.
5. In the left pane (Local site), select all files and directories inside your `Vaccination-System` folder.
6. Drag and drop all items into the remote `htdocs` directory.
7. Wait for all files to complete transferring without failed transfers.

### Option B: Using vPanel Online File Manager

1. In vPanel, open **Online File Manager**.
2. Navigate into `htdocs/`.
3. Upload your files and directories maintaining the structure shown above.

---

## Step 5: Verify Directory Permissions

Ensure that the upload directories are writable by the web server:
- `uploads/`
- `uploads/admin/`
- `uploads/hospitals/`
- `uploads/patients/`

In FileZilla, right-click the `uploads` folder, select **File permissions...**, set numeric value to `755` (or `777` if required by the host), check **Recurse into subdirectories**, and apply.

---

## Default Login Credentials

### 1. Administrator Portal
- **URL**: `http://your-domain.infinityfreeapp.com/admin/login.php`
- **Email**: `taha@gmail.com`
- **Password**: `taha1234`

### 2. Hospital Portal
- **URL**: `http://your-domain.infinityfreeapp.com/hospital/login.php`
- **Email**: `ziauddin@gmail.com`
- **Password**: `zia123`
- *(Alternative Hospital: `jinnah@gmail.com` / `jinnah123`)*

### 3. Patient Portal
- **URL**: `http://your-domain.infinityfreeapp.com/login.php`
- **Email**: `taha@gmail.com`
- **Password**: `12345`
- *New patients can register directly via `/user-register.php`.*

---

## Hosting Notes and Troubleshooting

1. **Case Sensitivity**: InfinityFree runs on Linux. All directory names have been normalized to lowercase (`admin/`, `hospital/`, `assets/`, `uploads/`, `includes/`). Always use lowercase paths when referencing internal assets.
2. **Database Host**: Never use `localhost` on InfinityFree. Always use the MySQL hostname provided in your vPanel (e.g., `sqlXXX.infinityfree.com`).
3. **Session Cookies**: The system uses standard PHP sessions. Ensure your browser allows cookies for your custom domain or subdomain.
4. **Security**: The included `.htaccess` file prevents directory browsing, blocks direct browser access to `includes/`, and denies HTTP access to sensitive files (`.sql`, `.log`, `.ini`, `.md`).

