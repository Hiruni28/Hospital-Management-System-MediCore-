<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define constants only if not already defined
if (!defined('SITEURL')) {
    define('SITEURL', 'http://localhost/medicore/');
}
if (!defined('LOCALHOST')) {
    define('LOCALHOST', 'localhost');
}
if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', 'root');
}
if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', '');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'medicore');
}

// Connect to the database
$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if (!$conn) {
    header('Location: ' . SITEURL . 'error.php?message=database_connection_failed');
    exit();
}



