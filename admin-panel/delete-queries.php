<?php 
include('partials/main.php'); 

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $query_id = $_GET['id'];

    // Prepare the DELETE query
    $sql = "DELETE FROM tbl_contact WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $query_id);

    // Execute the query and check if the deletion was successful
    if ($stmt->execute()) {
        // Redirect back to the manage-queries page with a success message
    
        header("Location:".SITEURL."admin-panel/manage-inquery.php?status=success&action=delete_message");
        exit(); 
    } else {
        // Display an error message if the deletion failed
        echo "<div class='error'>Failed to delete the query. Please try again.</div>";
    }
} else {
    // Redirect back to the manage-queries page if no 'id' is provided
    header('Location: manage-queries.php');
    exit();
}

?>
