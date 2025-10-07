<?php 
include('partials/main.php'); 

if (isset($_POST['submit'])) {
    // 1. Get the form data
    $category = $_POST['category'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price_local = $_POST['price_local'];
    $price_foreign = $_POST['price_foreign'];
    $active = isset($_POST['active']) ? $_POST['active'] : 'No'; 

 
    $schedule = json_encode($_POST['schedule']); 


    $features = json_encode($_POST['features']); 

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
    } else {
        $image_name = "";
    }

    $sql = "INSERT INTO tbl_programs (category_id, title, description, price_local, price_foreign, schedule, features, image_name, active) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issddssss', $category, $title, $description, $price_local, $price_foreign, $schedule, $features, $image_name, $active);

    if ($stmt->execute()) {
        header('Location:' . SITEURL . 'admin-panel/manage-classProgram.php?status=success&action=add_program');
        exit();
    } else {
        echo "<div class='error'>Failed to add the program. Please try again.</div>";
    }
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Add Program</h1>

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

                <tr>
                    <td>Title:</td>
                    <td><input type="text" name="title" placeholder="Enter Program Title"></td>
                </tr>

                <tr>
                    <td>Description:</td>
                    <td><textarea name="description" rows="5" placeholder="Enter Program Description"></textarea></td>
                </tr>

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
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Program" class="btn-primary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script>
    // Function to dynamically add more schedule fields
    function addSchedule() {
        const scheduleList = document.getElementById('schedule-list');
        const newSchedule = document.createElement('input');
        newSchedule.setAttribute('type', 'text');
        newSchedule.setAttribute('name', 'schedule[]');
        newSchedule.setAttribute('placeholder', 'Enter Date and Time');
        scheduleList.appendChild(newSchedule);
        scheduleList.appendChild(document.createElement('br'));
    }

    // Function to dynamically add more feature fields
    function addFeature() {
        const featureList = document.getElementById('feature-list');
        const newFeature = document.createElement('input');
        newFeature.setAttribute('type', 'text');
        newFeature.setAttribute('name', 'features[]');
        newFeature.setAttribute('placeholder', 'Enter Feature');
        featureList.appendChild(newFeature);
        featureList.appendChild(document.createElement('br'));
    }
</script>

<?php include('partials/footer.php'); ?>
