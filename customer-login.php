<?php
// Start session BEFORE any output
session_start();

require_once('config/constant.php');  // DB connection and constants


if (isset($_POST['submit'])) {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = $_POST['password'] ?? '';

    // Prepare statement
    $sql = "SELECT * FROM tbl_patient WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL prepare error: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password using password_verify()
        if (password_verify($password, $user['password'])) {
            $_SESSION['patient_id'] = $user['patient_id']; // Adjust to your actual PK column name
            header('Location: ' . SITEURL . 'index.php?status=success&action=login_cus');
            exit();
        } else {
            header('Location: ' . SITEURL . 'index.php?status=error&action=login_cus');
            exit();
        }
    } else {
        header('Location: ' . SITEURL . 'customer-login.php?status=error&action=login_cus');
        exit();
    }
}
include('customer-main/main.php');
?>

<section class="logings">

    <div class="container-login">
        <form action="" method="POST">
            <h1 style="font-size: 36px;">Login</h1>
            <div class="input-boxes">
                <input type="text" name="username" placeholder="Username" required>
                <i class='bx bx-user'></i>
            </div>
            <div class="input-boxes">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bx-lock-alt'></i>
            </div>
            <div class="rm">
                <label>
                    <input type="checkbox"> Remember-me
                </label>
                <a href="#">Forgot Password?</a>
            </div>
            <input type="submit" name="submit" value="Login" class="btn-login">
            <div class="register">
                <p>Don't have an account? <a href="customer-signup.php">Register</a></p>
                <p>Back to <a href="index.php">Home Page</a></p>
            </div>
        </form>
    </div>
</section>
