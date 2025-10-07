<?php
include('partials/main.php');

// Check if the ID of the booking is passed
if (isset($_GET['id'])) {
    // Get the ID of the booking
    $booking_id = $_GET['id'];

    // SQL query to update the status of the booking to 'Cancelled'
    $sql = "UPDATE tbl_membershipBookings SET status = 'Cancelled' WHERE id = ?";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $booking_id);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to the manage-booking page with success message
        header('Location:' . SITEURL . 'staff-panel/manage-bookinPackage.php?status=success&action=cancel_booking');
        exit();
    } else {
        // Redirect to the manage-booking page with error message
        header('Location:'.SITEURL.'staff-panel/manage-bookinPackage.php?status=error&action=cancel_booking');
        exit();
    }

    // Close the statement

} else {
    // Redirect to manage booking page if no ID is provided
    $_SESSION['message'] = "<div class='error'>Invalid Booking ID.</div>";
    header('Location: manage-bookinProgram.php');
}

include('partials/footer.php');
?>
