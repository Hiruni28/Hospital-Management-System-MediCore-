<?php
// Include the database connection and necessary functions
include('partials/main.php');

// Check if the blog ID is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the blog details based on the ID
    $sql = "SELECT * FROM tbl_blogs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the blog exists
    if ($result->num_rows == 1) {
        // Fetch the blog details
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $image_name = $row['image'];
        $description = $row['content'];
    } else {
        // Redirect if the blog does not exist
        header('Location:' . SITEURL . 'admin-panel/manage-blogs.php?status=error&action=blog_not_found');
        exit();
    }
} else {
    // Redirect if no ID is provided
    header('Location:' . SITEURL . 'admin-panel/manage-blogs.php');
    exit();
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $new_image_name = $_FILES['image']['name'];

    if ($new_image_name != "") {
        $ext = pathinfo($new_image_name, PATHINFO_EXTENSION);
        $new_image_name = "Blog_" . rand(1000, 9999) . "." . $ext;

        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../images/blogs/" . $new_image_name;

        if (move_uploaded_file($source_path, $destination_path)) {
            if ($image_name != "") {
                $remove_path = "../images/blogs/" . $image_name;
                if (file_exists($remove_path)) {
                    unlink($remove_path);
                }
            }
        } else {
            $_SESSION['upload_error'] = "Failed to upload image. Please try again.";
            header('Location: update-blog.php?id=' . $id);
            exit();
        }
    } else {
        $new_image_name = $image_name;
    }

    $sql2 = "UPDATE tbl_blogs SET title = ?, content = ?, image = ? WHERE id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('sssi', $title, $description, $new_image_name, $id);

    if ($stmt2->execute()) {
      
        header('Location:' . SITEURL . 'admin-panel/manage-blogs.php?status=success&action=update_blog');
        exit();
    } else {
      header('Location:' . SITEURL . 'admin-panel/manager-blogs.php?status=error&action=update_blog');
    }
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center " >Update Blog</h1>


        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Blog Title:</td>
                    <td><input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required></td>
                </tr>

                <tr>
                    <td>Current Image:</td>
                    <td>
                        <?php
                        if ($image_name != "") {
                            echo '<img src="../images/blogs/' . $image_name . '" width="150">';
                        } else {
                            echo '<img src="images/default-blog.jpg" width="150">';
                        }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>New Image:</td>
                    <td><input type="file" name="image"></td>
                </tr>

                <tr>
                    <td>Description:</td>
                    <td><textarea name="description" cols="30" rows="5" required><?php echo htmlspecialchars($description); ?></textarea></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Update Blog" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
