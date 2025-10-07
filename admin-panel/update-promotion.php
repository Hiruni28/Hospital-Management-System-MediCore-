<?php 
include('partials/main.php'); 

// Check if the promotion ID is provided in the URL
if (isset($_GET['id'])) {
    $promotion_id = $_GET['id'];

    // Fetch the promotion details from the database
    $sql = "SELECT * FROM tbl_promotions WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $promotion_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {
        $row = $res->fetch_assoc();
        $title = $row['title'];
        $program_name = $row['program_name'];
        $price = $row['price'];
        $discount_rate = $row['discount_rate'];
        $price_with_discount = $row['discounted_price'];
        $description = $row['description'];
        $image_name = $row['image_name'];
        $active = $row['active'];
    } else {
        header('Location: manage-promotions.php');
        exit();
    }
} else {
    header('Location: manage-promotions.php');
    exit();
}

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $program_name = $_POST['program_name'];
    $price = $_POST['price'];
    $discount_rate = $_POST['discount_rate'];
    $price_with_discount = $_POST['price_with_discount'];
    $description = $_POST['description'];
    $active = isset($_POST['active']) ? $_POST['active'] : 'No';

    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $image_name = $_FILES['image']['name'];
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_name = "Promotion_Image_" . rand(1000, 9999) . "." . $ext;
        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../images/promotions/" . $image_name;

        if (!move_uploaded_file($source_path, $destination_path)) {
            echo "<div class='error'>Failed to upload image.</div>";
            $image_name = ""; 
        }

        if ($row['image_name'] != "" && file_exists("../images/promotions/" . $row['image_name'])) {
            unlink("../images/promotions/" . $row['image_name']);
        }
    } else {
        $image_name = $row['image_name'];
    }

    $sql = "UPDATE tbl_promotions SET title=?, program_name=?, price=?, discount_rate=?, discounted_price=?, description=?, image_name=?, active=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssddssssi', $title, $program_name, $price, $discount_rate, $price_with_discount, $description, $image_name, $active, $promotion_id);

    if ($stmt->execute()) {

        header('Location:' . SITEURL . 'admin-panel/manage-promotion.php?status=success&action=update_promotion');
        exit();

    } else {
        echo "<div class='error'>Failed to update promotion. Please try again.</div>";
    }
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Update Promotion</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td><input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required></td>
                </tr>

                <tr>
                    <td>Program Name:</td>
                    <td><input type="text" name="program_name" value="<?php echo htmlspecialchars($program_name); ?>" required></td>
                </tr>

                <tr>
                    <td>Price:</td>
                    <td><input type="number" name="price" value="<?php echo htmlspecialchars($price); ?>" step="0.01" required></td>
                </tr>

                <tr>
                    <td>Discount Rate (%):</td>
                    <td><input type="number" name="discount_rate" value="<?php echo htmlspecialchars($discount_rate); ?>" step="0.01" required></td>
                </tr>

                <tr>
                    <td>Price with Discount:</td>
                    <td><input type="number" name="price_with_discount" value="<?php echo htmlspecialchars($price_with_discount); ?>" step="0.01" readonly></td>
                </tr>

                <tr>
                    <td>Description:</td>
                    <td><textarea name="description" rows="4" required><?php echo htmlspecialchars($description); ?></textarea></td>
                </tr>

                <tr>
                    <td>Current Image:</td>
                    <td>
                        <?php if ($image_name != "") { ?>
                            <img src="../images/promotions/<?php echo $image_name; ?>" width="100px">
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
                        <input type="submit" name="submit" value="Update Promotion" class="btn-primary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script>
    // Automatically calculate and update the price with discount
    const priceInput = document.querySelector('input[name="price"]');
    const discountRateInput = document.querySelector('input[name="discount_rate"]');
    const discountedPriceInput = document.querySelector('input[name="price_with_discount"]');

    function calculateDiscountedPrice() {
        const price = parseFloat(priceInput.value) || 0;
        const discountRate = parseFloat(discountRateInput.value) || 0;
        const discountedPrice = price - (price * discountRate / 100);
        discountedPriceInput.value = discountedPrice.toFixed(2);
    }

    // Update the discounted price whenever the price or discount rate changes
    priceInput.addEventListener('input', calculateDiscountedPrice);
    discountRateInput.addEventListener('input', calculateDiscountedPrice);
</script>

<?php include('partials/footer.php'); ?>
