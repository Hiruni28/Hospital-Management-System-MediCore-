<?php 
include('partials/main.php'); 

// 1. Get the category ID from the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // 2. Fetch the category details from the database
    $sql = "SELECT * FROM tbl_programCategories WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Category found
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $current_image = $row['image_name'];
        $active = $row['active'];
    } else {
        // Redirect if the category is not found
        header('Location:' . SITEURL . 'admin-panel/manage-categoryProgram.php?status=error&action=not_found');
        exit();
    }
} else {
    // Redirect if ID is not provided
    header('Location:' . SITEURL . 'admin-panel/manage-categoryProgram.php?status=error&action=invalid_id');
    exit();
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Update Category</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" placeholder="Enter Category Title">
                    </td>
                </tr>

                <tr>
                    <td>Current Image:</td>
                    <td>
                        <?php if ($current_image != "") { ?>
                            <img src="../images/category/<?php echo htmlspecialchars($current_image); ?>" width="100px" id="image-preview">
                        <?php } else { echo "No Image"; } ?>
                    </td>
                </tr>

                <tr>
                    <td>Select New Image:</td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes" <?php if($active == "Yes") { echo "checked"; } ?>> Yes
                        <input type="radio" name="active" value="No" <?php if($active == "No") { echo "checked"; } ?>> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Update Category" class="btn-primary">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                    </td>
                </tr>
            </table>
        </form>

    </div>
</div>

<?php 
// Handle form submission for updating category
if (isset($_POST['submit'])) {
    $id = intval($_POST['id']);
    $title = $_POST['title'];
    $active = $_POST['active'];
    $current_image = $_POST['current_image'];

    // Handle image upload
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $image_name = $_FILES['image']['name'];
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $new_image_name = "Category_" . rand(1000, 9999) . '.' . $ext;
        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../images/category/" . $new_image_name;
        $upload = move_uploaded_file($source_path, $destination_path);

        // Delete old image if a new image is uploaded
        if ($upload && $current_image != "") {
            $old_image_path = "../images/category/" . $current_image;
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }
    } else {
        // Keep the current image if no new image is uploaded
        $new_image_name = $current_image;
    }

    // Update the database with the new category details
    $sql = "UPDATE tbl_programCategories SET title=?, image_name=?, active=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $title, $new_image_name, $active, $id);

    if ($stmt->execute()) {
        header('Location:' . SITEURL . 'admin-panel/manage-categoryProgram.php?status=success&action=update_category');
        exit();
    } else {
        header('Location:' . SITEURL . 'admin-panel/update-categoryProgram.php?id=' . $id . '&status=error&action=update_category');
        exit();
    }
}
?>

<?php include('partials/footer.php'); ?>
