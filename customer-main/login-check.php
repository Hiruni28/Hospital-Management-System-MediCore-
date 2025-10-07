<?php
// session_start(); // Start the session

if (isset($_SESSION['user_id'])) {
    // If logged in, show the logout button
    echo '   <a href="logout.php" class="btn logout">Logout</a>';
} else {
    // If not logged in, show login and sign-up buttons
    echo ' <a href="customer-login.php" class="btn logout">Login</a>';
    echo '<a href="customer-signup.php" class="btn signup">Sign Up</a>';
}
?>
