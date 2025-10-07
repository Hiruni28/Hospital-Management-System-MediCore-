<?php
 include('customer-main/main.php'); 

if (isset($_POST['submit'])) {
    // 1. Get data from the form
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $message = $_POST['message'];

    // 2. Prepare SQL query to insert data into the contact table
    $sql = "INSERT INTO tbl_contact (full_name, email, mobile, message) 
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $full_name, $email, $mobile, $message);

    if ($stmt->execute()) {
        header('Location:' . SITEURL . 'index.php?status=success&action=success_sent');
        exit();
    } else {
        echo "<div class='error'>Failed to send message. Please try again.</div>";
    }
}


include('customer-main/footer.php'); 
?> 

