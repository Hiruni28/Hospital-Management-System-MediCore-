<?php
include('../config/constant.php'); // adjust path if needed

if (isset($_GET['id'])) {
    $appointment_id = (int)$_GET['id'];

    // Update status to Cancelled
    $sql = "UPDATE appointments SET status = 'cancelled' WHERE id = $appointment_id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Appointment cancelled successfully.";
    } else {
        $_SESSION['message'] = "Failed to cancel appointment.";
    }
}
header("Location: manage-appointment.php");
exit;
?>
