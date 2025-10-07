<?php
include('partials/main.php'); // Include your header and database connection

// Check if the event ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT image_name FROM tbl_events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $image_name = $row['image_name'];

        $sql = "DELETE FROM tbl_events WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            if ($image_name != "") {
                $image_path = "../images/events/" . $image_name;
                if (file_exists($image_path)) {
                    unlink($image_path); // Delete the image file
                }
            }

            header('Location:' . SITEURL . 'admin-panel/manage-events.php?status=success&action=delete_events');
            exit();
        } else {
            header('Location:' . SITEURL . 'admin-panel/manage-events.php?status=error&action=delete_events');
            exit();
        }
    } else {
      
        header('Location:' . SITEURL . 'admin-panel/manage-events.php?status=error&action=no_event_found');
        exit();
    }
} else {
    header('Location: manage-events.php');
    exit();
}
?>
