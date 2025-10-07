<?php 
include('partials/main.php'); 
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Add Membership Package</h1>
        <br><br>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Package Title:</td>
                    <td><input type="text" name="title" placeholder="Enter Package Title"></td>
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
                    <td>Select Image:</td>
                    <td><input type="file" name="image_name"></td>
                </tr>

                <tr>
                    <td>Access:</td>
                    <td>
                        <div id="access-list">
                            <input type="text" name="access[]" placeholder="Enter Access Detail"><br>
                        </div>
                        <button type="button" onclick="addAccess()">Add More Access</button>
                    </td>
                </tr>

                <tr>
                    <td>Features:</td>
                    <td>
                        <div id="feature-list">
                            <input type="text" name="features[]" placeholder="Enter Feature Detail"><br>
                        </div>
                        <button type="button" onclick="addFeature()">Add More Features</button>
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
                    <td colspan="2"><input type="submit" name="submit" value="Add Package" class="btn-primary"></td>
                </tr>
            </table>
        </form>
    </div>
</div>

 
<?php 

if(isset($_POST['submit'])) {
    $title = $_POST['title'];
    $price_local = $_POST['price_local'];
    $price_foreign = $_POST['price_foreign'];
    $active = isset($_POST['active']) ? $_POST['active'] : 'No';

    $access = json_encode($_POST['access']);
    $features = json_encode($_POST['features']);
    $image_name = ""; 
    if (isset($_FILES['image_name']['name']) && $_FILES['image_name']['name'] != "") {
        $image_name = $_FILES['image_name']['name'];
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_name = "Package_" . rand(1000, 9999) . '.' . $ext;
        $source_path = $_FILES['image_name']['tmp_name'];
        $destination_path = "../images/membership/" . $image_name;
        if (!move_uploaded_file($source_path, $destination_path)) {
            header("Location:".SITEURL."admin-panel/add-membership.php?status=error&action=upload");
            exit(); 
        }
    }

    $sql = "INSERT INTO tbl_packages (title, price_local, price_foreigner, access, features, image_name, active)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sddssss', $title, $price_local, $price_foreign, $access, $features, $image_name, $active);
    if ($stmt->execute()) {
        header('Location:'.SITEURL.'admin-panel/manage-membership.php?status=success&action=add_membership');
        exit();
    } else {
        header('Location:'.SITEURL.'admin-panel/add-membership.php?status=error&action=add_membership');
        exit();
    }
}

?>

<?php include('partials/footer.php'); ?>