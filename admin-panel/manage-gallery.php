<?php 
include('partials/main.php'); 
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Manage Gallery</h1>

        <br><br>

        <!-- Button to add images -->
        <a href="add-gallery.php" class="btn-primary">Add Image</a>

        <br /><br />

        <!-- Displaying the gallery images in a grid format -->
        <div class="gallery-container">
            <?php
            // Query to get all images from the database
            $sql = "SELECT id, image_name FROM tbl_gallery";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if there are any images in the database
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $image_name = $row['image_name'];

                    // Set the image path
                    $image_path = "../images/gallery/" . $image_name;
            ?>
                    <!-- Display each image in a div box -->
                    <div class="image-gallerybox">
                        <img src="<?php echo $image_path; ?>" alt="Gallery Image">
                        
                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <a href="update-gallery.php?id=<?php echo $id; ?>" class="btn-secondary">Update</a>
                            <a href="delete-gallery.php?id=<?php echo $id; ?>" class="btn-danger" onclick="return confirm('Are you sure you want to delete this image?');">Delete</a>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>No images available.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>
