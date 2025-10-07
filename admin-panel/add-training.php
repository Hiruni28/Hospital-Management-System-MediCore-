<?php 
include('partials/main.php'); 

if (isset($_POST['submit'])) {
    // 1. Get the form data
    $category = $_POST['category'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price_local = $_POST['price_local'];
    $price_foreign = $_POST['price_foreign'];
    $active = isset($_POST['active']) ? $_POST['active'] : 'No'; // Set to 'No' if not selected
    $trainer_id = $_POST['trainer']; // Get the trainer id from the form

    // 2. Handle Date and Time Schedule
    $schedule = json_encode($_POST['schedule']); // Store the schedule as a JSON string

    // 3. Handle Features
    $features = json_encode($_POST['features']); // Store the features as a JSON string

    // 4. Handle Image Upload
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $image_name = $_FILES['image']['name'];
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_name = "Program_Image_" . rand(1000, 9999) . "." . $ext;
        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../images/programs/" . $image_name;
        if (!move_uploaded_file($source_path, $destination_path)) {
            echo "<div class='error'>Failed to upload image.</div>";
            $image_name = ""; // Reset image name
        }
    } else {
        $image_name = "";
    }

    // 5. Insert the data into the database
    $sql = "INSERT INTO tbl_programs (category_id, title, description, price_local, price_foreign, schedule, features, image_name, active, trainer_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issddssssi', $category, $title, $description, $price_local, $price_foreign, $schedule, $features, $image_name, $active, $trainer_id);

    if ($stmt->execute()) {
        header('Location:' . SITEURL . 'admin-panel/manage-training.php?status=success&action=add_trainerprogram');
        exit();
    } else {
        echo "<div class='error'>Failed to add the program. Please try again.</div>";
    }
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Add Training Program</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Select Category:</td>
                    <td>
                      <select name="category">
                            <?php 
                            // Fetch categories from the database
                            $sql = "SELECT * FROM tbl_programCategories WHERE active='Yes'";
                            $res = $conn->query($sql);
                            if ($res->num_rows > 0) {
                                while ($row = $res->fetch_assoc()) {
                                    echo "<option value='{$row['id']}'>{$row['title']}</option>";
                                }
                            } else {
                                echo "<option value=''>No categories available</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                

                <!-- Rest of the fields like Title, Description, etc. -->

                <tr>
                    <td>Title:</td>
                    <td><input type="text" name="title" placeholder="Enter Program Title"></td>
                </tr>

                <tr>
                    <td>Description:</td>
                    <td><textarea name="description" rows="5" placeholder="Enter Program Description"></textarea></td>
                </tr>

                <!-- Remaining form fields for schedule, features, and other details -->
                <!-- ... -->
                <tr>
                    <td>Price for Locals:</td>
                    <td><input type="text" name="price_local" placeholder="Enter Price for Locals"></td>
                </tr>

                <tr>
                    <td>Price for Foreigners:</td>
                    <td><input type="text" name="price_foreign" placeholder="Enter Price for Foreigners"></td>
                </tr>

                <tr>
                    <td>Date and Time Schedule:</td>
                    <td>
                        <div id="schedule-list" class="schedule-list">
                            <input type="text" name="schedule[]" placeholder="Enter Date and Time" class="schedule-input">
                        </div>
                        <button type="button" onclick="addSchedule()" class="btn-add-schedule">Add More Schedule</button>
                    </td>
                </tr>

                <tr>
                    <td>Select Image:</td>
                    <td><input type="file" name="image"></td>
                </tr>

                <tr>
                    <td>Features:</td>
                    <td>
                        <div id="feature-list" class="feature-list">
                            <input type="text" name="features[]" placeholder="Enter Feature" class="feature-input">
                        </div>
                        <button type="button" onclick="addFeature()" class="btn-add-feature">Add More Features</button>
                    </td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes"> Yes
                        <input type="radio" name="active" value="No"> No
                    </td>
                </tr>
                

                <tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Program" class="btn-primary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<?php include('partials/footer.php'); ?>
