<?php
ob_start();
include('partials/main.php'); 

// 1. Get the package ID from the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // 2. Fetch the package details from the database
    $sql = "SELECT * FROM tbl_packages WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Package found
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $price_local = $row['price_local'];
        $price_foreigner = $row['price_foreigner'];
        $image_name = $row['image_name'];
        $access = json_decode($row['access']);
        $features = json_decode($row['features']);
        $active = $row['active'];
    } else {
        // Redirect if the package is not found
        header('Location:' . SITEURL . 'admin-panel/manage-membership.php?status=error&action=not_found');
        exit();
    }
} else {
    // Redirect if ID is not provided
    header('Location:' . SITEURL . 'admin-panel/manage-membership.php?status=error&action=invalid_id');
    exit();
}
?>
<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Update Membership Package</h1>
        <br><br>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Package Title:</td>
                    <td>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" placeholder="Enter Package Title">
                    </td>
                </tr>

                <tr>
                    <td>Price for Locals:</td>
                    <td>
                        <input type="text" name="price_local" value="<?php echo htmlspecialchars($price_local); ?>" placeholder="Enter Price for Locals">
                    </td>
                </tr>

                <tr>
                    <td>Price for Foreigners:</td>
                    <td>
                        <input type="text" name="price_foreign" value="<?php echo htmlspecialchars($price_foreigner); ?>" placeholder="Enter Price for Foreigners">
                    </td>
                </tr>

                <tr>
                    <td>Select Image:</td>
                    <td>
                        <input type="file" name="image">
                        <br>
                        <?php if ($image_name != "") { ?>
                            <img src="../images/membership/<?php echo htmlspecialchars($image_name); ?>" width="100px" id="image-preview">
                        <?php } else { echo "No Image Uploaded"; } ?>
                    </td>
                </tr>

                <tr>
                    <td>Access:</td>
                    <td>
                        <div id="access-list">
                            <?php foreach($access as $item) { ?>
                                <input type="text" name="access[]" value="<?php echo htmlspecialchars($item); ?>"><br>
                            <?php } ?>
                        </div>
                        <button type="button" onclick="updateAccess()">Add More Access</button>
                    </td>
                </tr>

                <tr>
                    <td>Features:</td>
                    <td>
                        <div id="feature-list">
                            <?php foreach($features as $item) { ?>
                                <input type="text" name="features[]" value="<?php echo htmlspecialchars($item); ?>"><br>
                            <?php } ?>
                        </div>
                        <button type="button" onclick="updateFeature()">Add More Features</button>
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
                        <input type="submit" name="submit" value="Update Package" class="btn-primary">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="old_image" value="<?php echo $image_name; ?>">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php 
if (isset($_POST['submit'])) {
    $id = intval($_POST['id']);
    $title = $_POST['title'];
    $price_local = $_POST['price_local'];
    $price_foreign = $_POST['price_foreign'];
    $active = $_POST['active'];
    $access = json_encode($_POST['access']); 
    $features = json_encode($_POST['features']); 

  
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $image_name = $_FILES['image']['name'];
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $new_image_name = "Package_" . rand(1000, 9999) . '.' . $ext;
        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../images/membership/" . $new_image_name;
        $upload = move_uploaded_file($source_path, $destination_path);

        if ($upload && $_POST['old_image'] != "") {
            $old_image_path = "../images/membership/" . $_POST['old_image'];
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }
    } else {
        $new_image_name = $_POST['old_image']; 
    }

    $sql = "UPDATE tbl_packages SET title=?, price_local=?, price_foreigner=?, access=?, features=?, image_name=?, active=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sddssssi', $title, $price_local, $price_foreign, $access, $features, $new_image_name, $active, $id);

    if ($stmt->execute()) {
        header('Location:'.SITEURL.'admin-panel/manage-membership.php?status=success&action=update_package');

        exit();
    } else {
        header('Location:'.SITEURL.'admin-panel/add-membership.php?status=error&action=update_package');
        exit();
    }
}
?>




<?php 
include('partials/footer.php'); 
?>

