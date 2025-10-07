<?php
include('../config/constant.php'); // adjust path if needed

if (isset($_GET['id'])) {
    $appointment_id = (int)$_GET['id'];

    // Update status to Confirmed
    $sql = "UPDATE appointments SET status = 'confirmed' WHERE id = $appointment_id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Appointment confirmed successfully.";
    } else {
        $_SESSION['message'] = "Failed to confirm appointment.";
    }
}
header("Location: manage-appointment.php");
exit;
?>
