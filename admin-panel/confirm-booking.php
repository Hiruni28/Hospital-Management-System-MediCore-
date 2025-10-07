<?php
include('partials/main.php'); // Include the main connection file

if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    $sql = "UPDATE tbl_programBooking SET status = 'Confirmed' WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $booking_id);

    if ($stmt->execute()) {
       
        header('Location:' . SITEURL . 'admin-panel/manage-bookinProgram.php?status=success&action=confirm_booking');
        exit();
    } else {
        header('Location: manage-bookinProgram.php?status=error&action=confirm');
        exit();
    }
} else {
    header('Location: manage-bookinProgram.php?status=error&action=no_id');
    exit();
}
?>
