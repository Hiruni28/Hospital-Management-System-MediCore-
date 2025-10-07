<?php
include('partials/main.php'); // Include your header and database connection

// Check if event ID is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the event details based on the ID
    $sql = "SELECT * FROM tbl_events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Event found, fetch the details
        $row = $result->fetch_assoc();
        $event_name = $row['event_name'];
        $event_datetime = $row['event_datetime'];
        $gifts = $row['gifts'];
        $description = $row['description'];
        $image_name = $row['image_name'];
        $active = $row['active'];
    } else {
        // Redirect if event not found
        $_SESSION['no_event_found'] = "Event not found.";
        header('Location: manage-events.php');
        exit();
    }
} else {
    // Redirect if no ID is provided
    header('Location: manage-events.php');
    exit();
}

if (isset($_POST['submit'])) {
    $event_name = $_POST['event_name'];
    $event_datetime = $_POST['event_datetime'];
    $gifts = $_POST['gifts'];
    $description = $_POST['description'];
    $active = $_POST['active'];

    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $image_name = $_FILES['image']['name'];
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_name = "Event_" . rand(1000, 9999) . "." . $ext;
        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../images/events/" . $image_name;

        if (move_uploaded_file($source_path, $destination_path)) {
            if ($row['image_name'] != "") {
                $remove_path = "../images/events/" . $row['image_name'];
                if (file_exists($remove_path)) {
                    unlink($remove_path);
                }
            }
        } else {
            $_SESSION['upload_error'] = "Failed to upload image.";
            $image_name = $row['image_name']; // Keep old image if upload fails
        }
    } else {
        $image_name = $row['image_name'];
    }

    $sql = "UPDATE tbl_events SET event_name = ?, event_datetime = ?, gifts = ?, description = ?, image_name = ?, active = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssi', $event_name, $event_datetime, $gifts, $description, $image_name, $active, $id);

    if ($stmt->execute()) {
      header('Location:'.SITEURL.'admin-panel/manage-events.php?status=success&action=update_events');
      exit();
    } else {
      header('Location:' . SITEURL . 'admin-panel/manage-events.php?status=error&action=update_events');
      exit();
    }
}
?>

<!-- HTML part for updating event -->
<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center ">Update Special Event</h1>
        <br />

      

        <!-- Form for updating the event -->
        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Event Name:</td>
                    <td><input type="text" name="event_name" value="<?php echo htmlspecialchars($event_name); ?>" required></td>
                </tr>

                <tr>
                    <td>Event Date & Time:</td>
                    <td><input type="datetime-local" name="event_datetime" value="<?php echo date('Y-m-d\TH:i', strtotime($event_datetime)); ?>" required></td>
                </tr>

                <tr>
                    <td>Gifts:</td>
                    <td><input type="text" name="gifts" value="<?php echo htmlspecialchars($gifts); ?>"></td>
                </tr>

                <tr>
                    <td>Description:</td>
                    <td><textarea name="description" cols="30" rows="5" placeholder="Enter Event Description"><?php echo htmlspecialchars($description); ?></textarea></td>
                </tr>

                <tr>
                    <td>Current Image:</td>
                    <td>
                        <?php if ($image_name != "") { ?>
                            <img src="../images/events/<?php echo $image_name; ?>" width="100" height="100">
                        <?php } else { ?>
                            <p>No Image Available</p>
                        <?php } ?>
                    </td>
                </tr>

                <tr>
                    <td>New Image:</td>
                    <td><input type="file" name="image"></td>
                </tr>

                <!-- Add the Active Field -->
                <tr>
                    <td>Active:</td>
                    <td>
                        <select name="active">
                            <option value="Yes" <?php if ($active == "Yes") echo "selected"; ?>>Yes</option>
                            <option value="No" <?php if ($active == "No") echo "selected"; ?>>No</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Update Event" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
