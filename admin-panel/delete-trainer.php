<?php 
include('partials/main.php'); 

// Check if the 'id' is set in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Get the trainer's ID from the URL

    // 1. Delete the trainer record from the database
    $sql_delete = "DELETE FROM tbl_trainer WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param('i', $id);

    if ($stmt_delete->execute()) {
        // Redirect to the manage-trainers page with success message
        header('Location: ' . SITEURL . 'admin-panel/manage-persontrainer.php?status=success&action=delete_trainer');
        exit();
    } else {
        // Redirect to manage-trainers page with error if the deletion failed
        header('Location: ' . SITEURL . 'admin-panel/manage-persontrainer.php?status=error&action=delete_trainer');
        exit();
    }
} else {
    // Redirect to manage-trainers page if no ID is provided
    header('Location: ' . SITEURL . 'admin-panel/manage-persontrainer.php?status=error&action=invalid_id');
    exit();
}
?>
