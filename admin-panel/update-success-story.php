<?php 
include('partials/main.php'); 

// Check if ID is set in URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the current success story details
    $sql = "SELECT * FROM tbl_success_stories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $title = $row['title'];
    $description = $row['description'];
    $email = $row['email'];
    $current_image = $row['image_name'];
} else {
    // Redirect if no ID is found
    header('Location: manage-success-stories.php');
    exit();
}

// Handle form submission for updating success story
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $email = $_POST['email'];
    
    // Handle new image upload if a new one is provided
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $image_name = $_FILES['image']['name'];
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_name = "Success_Story_" . rand(1000, 9999) . '.' . $ext;
        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../images/success/" . $image_name;
        
        // Upload the new image
        if (move_uploaded_file($source_path, $destination_path)) {
            // Remove old image if it exists
            if ($current_image != "") {
                unlink("../images/success/" . $current_image);
            }
        } else {
            $image_name = $current_image; // Keep the old image if upload fails
        }
    } else {
        $image_name = $current_image; // Keep the old image if no new one is uploaded
    }

    // Update the database
    $sql = "UPDATE tbl_success_stories SET title = ?, description = ?, email = ?, image_name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $description, $email, $image_name, $id);

    if ($stmt->execute()) {
        header('Location: manage-success-stories.php?status=success&action=update_success_story');
        exit();
    } else {
        echo "<div class='error'>Failed to update success story.</div>";
    }
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Update Success Story</h1>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td><input type="text" name="title" value="<?php echo $title; ?>" required></td>
                </tr>

                <tr>
                    <td>Description:</td>
                    <td><textarea name="description" rows="5"><?php echo $description; ?></textarea></td>
                </tr>

                <tr>
                    <td>Email:</td>
                    <td><input type="email" name="email" value="<?php echo $email; ?>" required></td>
                </tr>

                <tr>
                    <td>Current Image:</td>
                    <td>
                        <?php if ($current_image != "") { ?>
                            <img src="../images/success/<?php echo $current_image; ?>" width="100px">
                        <?php } else { ?>
                            <p>No image available.</p>
                        <?php } ?>
                    </td>
                </tr>

                <tr>
                    <td>New Image:</td>
                    <td><input type="file" name="image"></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Update Success Story" class="btn-primary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
