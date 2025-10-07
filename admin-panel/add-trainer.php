<?php 
include('partials/main.php'); 

// Fetch the 'Personalized Training Programs' category from the database
$sql = "SELECT id, title FROM tbl_programCategories WHERE title='Personalized Training Programs' AND active='Yes'";
$res = $conn->query($sql);

// Check if the category was found
if ($res->num_rows > 0) {
    $category = $res->fetch_assoc(); // Get the category details
    $category_id = $category['id'];
    $category_title = $category['title'];
} else {
    // Handle the case when the category is not found
    $category_id = null;
    $category_title = "Category not available";
}

if (isset($_POST['submit'])) {
    // Get form data
    $name = $_POST['name'];
    $qualifications = $_POST['qualifications'];
    $about_trainer = $_POST['about_trainer'];
    $active = isset($_POST['active']) ? $_POST['active'] : 'No';

    // Insert data into the database
    $sql = "INSERT INTO tbl_trainer (category_id, name, qualifications, about_trainer, active) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issss', $category_id, $name, $qualifications, $about_trainer, $active);

    if ($stmt->execute()) {
        // Redirect to manage trainers page with success message
        header('Location: ' . SITEURL . 'admin-panel/manage-persontrainer.php?status=success&action=add_trainer');
        exit();
    } else {
        echo "<div class='error'>Failed to add the trainer. Please try again.</div>";
    }
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Add Trainer</h1>

        <form action="" method="POST">
            <table class="tbl-30">
                <tr>
                    <td>Selected Category:</td>
                    <td><?php echo $category_title; ?></td>
                </tr>

                <tr>
                    <td>Name:</td>
                    <td><input type="text" name="name" placeholder="Enter Trainer Name" required></td>
                </tr>

                <tr>
                    <td>Qualifications:</td>
                    <td><input type="text" name="qualifications" placeholder="Enter Trainer Qualifications" required></td>
                </tr>

                <tr>
                    <td>About Trainer:</td>
                    <td><textarea name="about_trainer" rows="5" placeholder="Enter Brief Description About Trainer" required></textarea></td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes" checked> Yes
                        <input type="radio" name="active" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2"><input type="submit" name="submit" value="Add Trainer" class="btn-primary"></td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
