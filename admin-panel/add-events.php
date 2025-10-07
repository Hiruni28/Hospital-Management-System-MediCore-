<?php
include('partials/main.php'); // Include your header and database connection

// PHP logic for handling the form submission
if (isset($_POST['submit'])) {
    $event_name = $_POST['event_name'];
    $event_datetime = $_POST['event_datetime'];
    $gifts = $_POST['gifts'];
    $description = $_POST['description'];
    $active = $_POST['active']; 
    
    // 2. Handle image upload
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $image_name = $_FILES['image']['name'];
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);

        $image_name = "Event_" . rand(1000, 9999) . "." . $ext;
        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../images/events/" . $image_name;

        if (!move_uploaded_file($source_path, $destination_path)) {
            $_SESSION['upload_error'] = "Failed to upload image. Please try again.";
            $image_name = ""; 
        }
    } else {
        $image_name = ""; 
    }

    $sql = "INSERT INTO tbl_events (event_name, event_datetime, gifts, description, image_name, active) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss', $event_name, $event_datetime, $gifts, $description, $image_name, $active);

    if ($stmt->execute()) {
        header('Location:'.SITEURL.'admin-panel/manage-events.php?status=success&action=add_events');
        exit();  
    } else {
        header('Location:'.SITEURL.'admin-panel/add-events.php?status=error&action=add_events');
        exit();  
    }
}
?>


<!-- HTML part for adding an event -->
<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Add Special Event</h1>
        <br />

  
        <!-- Form for adding event -->
        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Event Name:</td>
                    <td><input type="text" name="event_name" placeholder="Enter Event Name" required></td>
                </tr>

                <tr>
                    <td>Event Date & Time:</td>
                    <td><input type="datetime-local" name="event_datetime" required></td>
                </tr>

                <tr>
                    <td>Gifts:</td>
                    <td><input type="text" name="gifts" placeholder="Enter Gifts"></td>
                </tr>

                <tr>
                    <td>Description:</td>
                    <td><textarea name="description" cols="30" rows="5" placeholder="Enter Event Description"></textarea></td>
                </tr>

                <tr>
                    <td>Image:</td>
                    <td><input type="file" name="image"></td>
                </tr>

                <!-- Add the Active Field -->
                <tr>
                    <td>Active:</td>
                    <td>
                        <select name="active">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Event" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
