<?php
include('partials/main.php'); // Include the main connection file

if (isset($_GET['id'])) {
    // Get the booking ID from the URL
    $booking_id = $_GET['id'];

    // Prepare the SQL query to update the status to 'Confirmed'
    $sql = "UPDATE tbl_programBooking SET status = 'Confirmed' WHERE id = ?";

    // Prepare and bind the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $booking_id);

    // Execute the query
    if ($stmt->execute()) {
        // If successful, redirect to the booking management page with a success message
       
        header('Location:' . SITEURL . 'staff-panel/manage-bookinProgram.php?status=success&action=confirm_booking');
        exit();
    } else {
        // If there's an error, redirect with an error message
        header('Location: staff-panel/manage-bookinProgram.php?status=error&action=confirm');
        exit();
    }
} else {
    // If no booking ID is found, redirect with an error
    header('Location: staff-panel/manage-bookinProgram.php?status=error&action=no_id');
    exit();
}
?>
