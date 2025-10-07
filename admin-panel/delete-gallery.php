<?php
include('partials/main.php');

// Check if the 'id' is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Retrieve the image name from the database
    $sql = "SELECT image_name FROM tbl_gallery WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the image exists in the database
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $image_name = $row['image_name'];

        // 2. Delete the image file from the server
        if ($image_name != "") {
            $image_path = "../images/gallery/" . $image_name;
            if (file_exists($image_path)) {
                unlink($image_path); // Delete the image file
            }
        }

        // 3. Delete the image record from the database
        $sql = "DELETE FROM tbl_gallery WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        // Execute the query and check if it was successful
        if ($stmt->execute()) {
            // Image deleted successfully, redirect with a success message
            header('Location: manage-gallery.php?status=success&action=delete_image');
            exit();
        } else {
            // Failed to delete the image, redirect with an error message
            header('Location: manage-gallery.php?status=error&action=delete_image');
            exit();
        }
    } else {
        // Image not found, redirect with an error message
        header('Location: manage-gallery.php?status=error&action=image_not_found');
        exit();
    }
} else {
    // If the 'id' is not set in the URL, redirect with an error message
    header('Location: manage-gallery.php?status=error&action=invalid_request');
    exit();
}

?>
