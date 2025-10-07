<?php 
include('partials/main.php'); 

// 1. Get the trainer ID from the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 2. Fetch the trainer details from the database
    $sql = "SELECT * FROM tbl_trainer WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Trainer found
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $qualifications = $row['qualifications'];
        $about_trainer = $row['about_trainer'];
        $active = $row['active'];
    } else {
        // Redirect if the trainer is not found
        header('Location:' . SITEURL . 'admin-panel/manage-persontrainer.php?status=error&action=not_found');
        exit();
    }
} else {
    // Redirect if ID is not provided
    header('Location:' . SITEURL . 'admin-panel/manage-persontrainer.php?status=error&action=invalid_id');
    exit();
}

// Fetch the 'Personalized Training Programs' category from the database
$sql_category = "SELECT id, title FROM tbl_programCategories WHERE title='Personalized Training Programs' AND active='Yes'";
$res_category = $conn->query($sql_category);
if ($res_category->num_rows > 0) {
    $category = $res_category->fetch_assoc();
    $category_title = $category['title'];
} else {
    $category_title = "Category not available";
}

if (isset($_POST['submit'])) {
    // 1. Get the form data
    $name = $_POST['name'];
    $qualifications = $_POST['qualifications'];
    $about_trainer = $_POST['about_trainer'];
    $active = isset($_POST['active']) ? $_POST['active'] : 'No';

    // 2. Update the trainer details in the database (without image handling)
    $sql_update = "UPDATE tbl_trainer SET name=?, qualifications=?, about_trainer=?, active=? WHERE id=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param('ssssi', $name, $qualifications, $about_trainer, $active, $id);

    // 3. Execute the query and check for success or error
    if ($stmt_update->execute()) {
        header('Location:' . SITEURL . 'admin-panel/manage-persontrainer.php?status=success&action=update_trainer');
        exit();
    } else {
        echo "<div class='error'>Failed to update the trainer. Please try again.</div>";
    }
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Update Trainer</h1>

        <form action="" method="POST">
            <table class="tbl-30">
                <tr>
                    <td>Selected Category:</td>
                    <td><?php echo $category_title; ?></td>
                </tr>

                <tr>
                    <td>Name:</td>
                    <td><input type="text" name="name" value="<?php echo $name; ?>" required></td>
                </tr>

                <tr>
                    <td>Qualifications:</td>
                    <td><input type="text" name="qualifications" value="<?php echo $qualifications; ?>" required></td>
                </tr>

                <tr>
                    <td>About Trainer:</td>
                    <td><textarea name="about_trainer" rows="5" required><?php echo $about_trainer; ?></textarea></td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes" <?php if($active == "Yes") echo "checked"; ?>> Yes
                        <input type="radio" name="active" value="No" <?php if($active == "No") echo "checked"; ?>> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2"><input type="submit" name="submit" value="Update Trainer" class="btn-primary"></td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
