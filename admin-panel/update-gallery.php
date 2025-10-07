<?php
include('partials/main.php');

// Get the image ID from URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query to get the image data from the database
    $sql = "SELECT image_name FROM tbl_gallery WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the image exists
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $image_name = $row['image_name'];
    }
}

// Handle form submission to update image
if (isset($_POST['submit'])) {
    // Handle image upload
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $new_image = $_FILES['image']['name'];
        $ext = pathinfo($new_image, PATHINFO_EXTENSION);
        $new_image_name = "Gallery_" . rand(1000, 9999) . "." . $ext;

        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../images/gallery/" . $new_image_name;

        if (move_uploaded_file($source_path, $destination_path)) {
            // Delete old image
            if ($image_name != "") {
                unlink("../images/gallery/" . $image_name);
            }

            // Update image in database
            $sql = "UPDATE tbl_gallery SET image_name = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_image_name, $id);
            $stmt->execute();

            header('Location:'.SITEURL.'admin-panel/manage-gallery.php?status=success&action=update_image');
            exit();
        } else {
            echo "<div class='error'>Failed to upload image.</div>";
        }
    }
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Update Image</h1>
        
        <!-- Form to upload new image -->
        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-full">
                <tr>
                    <td>Select New Image:</td>
                    <td><input type="file" name="image" required></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Update Image" class="btn-primary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
