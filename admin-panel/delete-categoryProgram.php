<?php
// Include the main file for database connection
include('partials/main.php');

// Check if the `id` and `image_name` are provided via URL
if (isset($_GET['id']) && isset($_GET['image_name'])) {
    $id = intval($_GET['id']);
    $image_name = $_GET['image_name'];

    // 1. Delete the image file if it exists
    if ($image_name != "") {
        $image_path = "../images/category/" . $image_name;
        
        // Check if the image file exists, then delete it
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // 2. Delete the category from the database
    $sql = "DELETE FROM tbl_programCategories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    // Execute the deletion
    if ($stmt->execute()) {
        // Redirect to the manage category page with success message
        header('Location:' . SITEURL . 'admin-panel/manage-categoryProgram.php?status=success&action=delete_category');
        exit();
    } else {
        // Redirect with an error message if the query fails
        header('Location:' . SITEURL . 'admin-panel/manage-categoryProgram.php?status=error&action=delete_category');
        exit();
    }
} else {
    // Redirect if no ID or image name is provided
    header('Location:' . SITEURL . 'admin-panel/manage-categoryProgram.php?status=error&action=invalid_request');
    exit();
}
?>
