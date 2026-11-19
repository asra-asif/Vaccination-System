<?php
/**
 * Application & Database Configuration
 * 
 * Local Development (XAMPP):
 *   Host: localhost
 *   User: root
 *   Password: (empty)
 *   Database: vaccination_system
 * 
 * Live Deployment (InfinityFree):
 *   1. Log in to InfinityFree vPanel / Client Area.
 *   2. Go to "MySQL Databases" and find your database details.
 *   3. Replace the values below with your InfinityFree credentials:
 *      DB_HOST: e.g., sql123.infinityfree.com or sql123.epizy.com
 *      DB_NAME: e.g., epiz_12345678_vaccination
 *      DB_USER: e.g., epiz_12345678
 *      DB_PASS: your vPanel password
 */

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
}

// Automatically detect environment
$isLocal = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1', '::1'])
    || php_sapi_name() === 'cli'
    || (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);

if ($isLocal) {
    // Local Development (XAMPP)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'vaccination_system');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('APP_ENV', 'development');
} else {
    // Live Deployment (InfinityFree)
    define('DB_HOST', 'sql104.infinityfree.com');
    define('DB_NAME', 'if0_43484879_vms');
    define('DB_USER', 'if0_43484879');
    define('DB_PASS', 'iGK59t2LZhebKA');
    define('APP_ENV', 'production');
}

define('DB_CHARSET', 'utf8mb4');

// Session Keys
define('SESSION_ADMIN', 'admin_session');
define('SESSION_HOSPITAL', 'hospital_session');
define('SESSION_PATIENT', 'patient_session');
define('SESSION_PATIENT_NAME', 'patient_name');

if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}
?>
