<?php
// Include the necessary files
include('partials/main.php');

// Check if the blog ID is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the blog to retrieve the image file name (if any)
    $sql = "SELECT image FROM tbl_blogs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $image_name = $row['image'];

        if ($image_name != "") {
            $image_path = "../images/blogs/" . $image_name;
            if (file_exists($image_path)) {
                unlink($image_path); 
            }
        }

        $sql2 = "DELETE FROM tbl_blogs WHERE id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param('i', $id);

        if ($stmt2->execute()) {
         
            header('Location:' . SITEURL . 'admin-panel/manage-blogs.php?status=success&action=delete_blog');
            exit();
        } else {
            header('Location:' . SITEURL . 'admin-panel/manage-blogs.php?status=error&action=delete_blog');
            exit();
        }
    } else {
        header('Location:' . SITEURL . 'admin-panel/manage-blogs.php?status=error&action=blog_not_found');
        exit();
    }
} else {
    // Redirect if no ID is provided
    header('Location:' . SITEURL . 'admin-panel/manage-blogs.php');
    exit();
}



