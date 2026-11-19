<?php
require_once __DIR__ . '/config.php';

// Disable default strict exceptions in PHP 8.1+ to preserve procedural compatibility
mysqli_report(MYSQLI_REPORT_OFF);

// MySQLi Connection
$connection = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$connection) {
    if (APP_ENV === 'development') {
        die("Database Connection Error: " . mysqli_connect_error());
    } else {
        die("Unable to connect to the database. Error: " . mysqli_connect_error() . "<br>Please verify your credentials in includes/config.php.");
    }
}

mysqli_set_charset($connection, DB_CHARSET);

// PDO Connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (\PDOException $e) {
    $pdo = null;
}
?>
