<?php
include('partials/main.php');

if (isset($_GET['id']) && isset($_GET['image_name'])) {
    $id = $_GET['id'];
    $image_name = $_GET['image_name'];

    if ($image_name != "") {
        $path = "../images/membership/" . $image_name;

        if (file_exists($path)) {
            unlink($path); 
        }
    }

    $sql = "DELETE FROM tbl_packages WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); 

    if ($stmt->execute()) {
        header("Location:".SITEURL."admin-panel/manage-membership.php?status=success&action=delete_package");
        exit(); 
    } else {
        // 5. If query execution failed, redirect with an error message
        header("Location:".SITEURL."admin-panel/manage-membership.php?status=error&action=delete_package");
        exit(); // Ensure no further script execution
    }
} else {
    // If ID or image name is not set, redirect back with an error
    header("Location:".SITEURL."admin-panel/manage-membership.php?status=error&action=delete_invalid");
    exit();
}
?>
