<?php 
include('partials/main.php'); 
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Add Service Category</h1>

        <br><br>


        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Category Title:</td>
                    <td><input type="text" name="title" placeholder="Enter Category Title"></td>
                </tr>

                <tr>
                    <td>Select Image:</td>
                    <td><input type="file" name="image_name"></td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes" checked> Yes
                        <input type="radio" name="active" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Category" class="btn-primary">
                    </td>
                </tr>
            </table>
        </form>

        <?php 
        // Check if the form is submitted
        if (isset($_POST['submit'])) {
            // 1. Get the form data
            $title = $_POST['title'];
            $active = isset($_POST['active']) ? $_POST['active'] : 'No'; // Default to 'No'

            // 2. Handle Image Upload
            if (isset($_FILES['image_name']['name']) && $_FILES['image_name']['name'] != "") {
                $image_name = $_FILES['image_name']['name'];

                // Get the extension (e.g., 'jpg', 'jpeg', 'png')
                $ext = pathinfo($image_name, PATHINFO_EXTENSION);

                // Rename the image to avoid duplicates
                $image_name = "Program_Category_" . rand(1000, 9999) . '.' . $ext;

                // Image upload path
                $source_path = $_FILES['image_name']['tmp_name'];
                $destination_path = "../images/category/" . $image_name;

                // Upload image
                if (!move_uploaded_file($source_path, $destination_path)) {
                    echo "<div class='error'>Failed to upload image.</div>";
                    $image_name = ""; // Reset image name if upload fails
                }
            } else {
                // If no image is uploaded, set image name to an empty string
                $image_name = "";
            }

            // 3. Insert category into the database
            $sql = "INSERT INTO tbl_programCategories (title, image_name, active) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $title, $image_name, $active);

            // 4. Execute query and redirect to manage category page with success or error message
            if ($stmt->execute()) {
                header('Location:'.SITEURL.'admin-panel/manage-categoryProgram.php?status=success&action=add_category');
                exit();  
            } else {
                header('Location:'.SITEURL.'admin-panel/add-membership.php?status=error&action=add_category');
                exit();        
                }
        }
        ?>
    </div>
</div>

<?php include('partials/footer.php'); ?>
