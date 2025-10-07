<?php 
include('partials/main.php'); 
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Add Gallery Image</h1>

        <br><br>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Select Image:</td>
                    <td><input type="file" name="image_name" required></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Image" class="btn-primary">
                    </td>
                </tr>
            </table>
        </form>

        <?php 
        // Check if the form is submitted
        if (isset($_POST['submit'])) {
            // 1. Handle Image Upload
            if (isset($_FILES['image_name']['name']) && $_FILES['image_name']['name'] != "") {
                $image_name = $_FILES['image_name']['name'];

                // Get the file extension (e.g., 'jpg', 'jpeg', 'png')
                $ext = pathinfo($image_name, PATHINFO_EXTENSION);

                // Rename the image to avoid duplicates
                $image_name = "Gallery_Image_" . rand(1000, 9999) . '.' . $ext;

                // Image upload path
                $source_path = $_FILES['image_name']['tmp_name'];
                $destination_path = "../images/gallery/" . $image_name;

                // Upload image
                if (!move_uploaded_file($source_path, $destination_path)) {
                    echo "<div class='error'>Failed to upload image.</div>";
                    $image_name = ""; // Reset image name if upload fails
                }
            } else {
                // If no image is uploaded, set image name to an empty string
                $image_name = "";
            }

            // 2. Insert the image into the database
            if ($image_name != "") {
                $sql = "INSERT INTO tbl_gallery (image_name) VALUES (?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $image_name);

                // 3. Execute query and redirect to manage gallery page with success or error message
                if ($stmt->execute()) {
                    header('Location:'.SITEURL.'admin-panel/manage-gallery.php?status=success&action=add_image');
                    exit();  
                } else {
                    echo "<div class='error'>Failed to add image. Please try again.</div>";
                }
            }
        }
        ?>
    </div>
</div>

<?php include('partials/footer.php'); ?>
