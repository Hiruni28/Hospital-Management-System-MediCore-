<?php
// Include the constant.php file for SITEURL
include('config/constant.php');

// Start the session


// Unset all session variables
$_SESSION = array();

// If the session cookie is set, delete it
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to the login page
header('Location: ' . SITEURL . 'index.php');
exit();
?>