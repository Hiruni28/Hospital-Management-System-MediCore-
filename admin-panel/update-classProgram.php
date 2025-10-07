<?php 
include('partials/main.php'); 

// Check if the class ID is provided in the URL
if (isset($_GET['id'])) {
    $program_id = $_GET['id'];

    // Fetch the program details from the database
    $sql = "SELECT * FROM tbl_programs WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $program_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {
        $row = $res->fetch_assoc();
        $title = $row['title'];
        $category_id = $row['category_id']; // Fetch category id
        $price_local = $row['price_local'];
        $price_foreign = $row['price_foreign']; // Fetch price for foreigners
        $schedule = json_decode($row['schedule'], true);
        $features = json_decode($row['features'], true);
        $description = $row['description']; // Fetch description
        $image_name = $row['image_name'];
        $active = $row['active'];
    } else {
        header('Location: manage-classProgram.php');
        exit();
    }
} else {
    header('Location: manage-classProgram.php');
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $price_local = $_POST['price_local'];
    $price_foreign = $_POST['price_foreign'];
    $schedule = json_encode($_POST['schedule']);
    $features = json_encode($_POST['features']);
    $description = $_POST['description'];
    $active = isset($_POST['active']) ? $_POST['active'] : 'No'; 

    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $image_name = $_FILES['image']['name'];
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_name = "Program_Image_" . rand(1000, 9999) . "." . $ext;
        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../images/programs/" . $image_name;

        if (!move_uploaded_file($source_path, $destination_path)) {
            echo "<div class='error'>Failed to upload image.</div>";
            $image_name = ""; 
        }

        if ($row['image_name'] != "" && file_exists("../images/programs/" . $row['image_name'])) {
            unlink("../images/programs/" . $row['image_name']);
        }
    } else {
        $image_name = $row['image_name'];
    }

    $sql = "UPDATE tbl_programs SET title=?, category_id=?, price_local=?, price_foreign=?, schedule=?, features=?, description=?, image_name=?, active=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sisssssssi', $title, $category, $price_local, $price_foreign, $schedule, $features, $description, $image_name, $active, $program_id);

    if ($stmt->execute()) {
        header('Location:'.SITEURL.'admin-panel/manage-classProgram.php?status=success&action=update_class');
        exit();
    } else {
        echo "<div class='error'>Failed to update class. Please try again.</div>";
    }
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Update Class Program</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td><input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required></td>
                </tr>

                <tr>
                    <td>Select Category:</td>
                    <td>
                        <select name="category">
                            <?php 
                            // Fetch all categories
                            $sql = "SELECT * FROM tbl_programCategories WHERE active='Yes'";
                            $res = $conn->query($sql);
                            if ($res->num_rows > 0) {
                                while ($category = $res->fetch_assoc()) {
                                    $selected = ($category['id'] == $category_id) ? "selected" : "";
                                    echo "<option value='{$category['id']}' $selected>{$category['title']}</option>";
                                }
                            } else {
                                echo "<option value=''>No categories available</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Price (Local):</td>
                    <td><input type="text" name="price_local" value="<?php echo htmlspecialchars($price_local); ?>" required></td>
                </tr>

                <tr>
                    <td>Price (Foreign):</td>
                    <td><input type="text" name="price_foreign" value="<?php echo htmlspecialchars($price_foreign); ?>" required></td>
                </tr>

                <tr>
                    <td>Description:</td>
                    <td><textarea name="description" rows="5" required><?php echo htmlspecialchars($description); ?></textarea></td>
                </tr>

                <tr>
                    <td>Date and Time Schedule:</td>
                    <td>
                        <div id="schedule-list" class="schedule-list">
                            <?php 
                            if (!empty($schedule)) {
                                foreach ($schedule as $time) {
                                    echo '<input type="text" name="schedule[]" value="' . htmlspecialchars($time) . '" class="schedule-input"><br>';
                                }
                            } else {
                                echo '<input type="text" name="schedule[]" placeholder="Enter Date and Time" class="schedule-input">';
                            }
                            ?>
                        </div>
                        <button type="button" onclick="addSchedule()" class="btn-add-schedule">Add More Schedule</button>
                    </td>
                </tr>

                <tr>
                    <td>Features:</td>
                    <td>
                        <div id="feature-list" class="feature-list">
                            <?php 
                            if (!empty($features)) {
                                foreach ($features as $feature) {
                                    echo '<input type="text" name="features[]" value="' . htmlspecialchars($feature) . '" class="feature-input"><br>';
                                }
                            } else {
                                echo '<input type="text" name="features[]" placeholder="Enter Feature" class="feature-input">';
                            }
                            ?>
                        </div>
                        <button type="button" onclick="addFeature()" class="btn-add-feature">Add More Features</button>
                    </td>
                </tr>

                <tr>
                    <td>Current Image:</td>
                    <td>
                        <?php if ($image_name != "") { ?>
                            <img src="../images/programs/<?php echo $image_name; ?>" width="100px">
                        <?php } else { echo "No image uploaded."; } ?>
                    </td>
                </tr>

                <tr>
                    <td>Upload New Image:</td>
                    <td><input type="file" name="image"></td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes" <?php if ($active == "Yes") echo "checked"; ?>> Yes
                        <input type="radio" name="active" value="No" <?php if ($active == "No") echo "checked"; ?>> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Update Program" class="btn-primary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
