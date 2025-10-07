<?php 
include('partials/main.php');

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $program_name = $_POST['program_name']; 
    $price = $_POST['price'];
    $discount_rate = $_POST['discount_rate'];
    $discounted_price = $_POST['discounted_price'];
    $description = $_POST['description'];
    $active = $_POST['active'];

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
    } else {
        $image_name = "";
    }

    $sql = "INSERT INTO tbl_promotions (title, program_name, price, discount_rate, discounted_price, image_name, description, active) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssddssss', $title, $program_name, $price, $discount_rate, $discounted_price, $image_name, $description, $active);

    if ($stmt->execute()) {
    
        header('Location:' . SITEURL . 'admin-panel/manage-promotion.php?status=success&action=add_promotion');
        exit();
    } else {
        echo "<div class='error'>Failed to add the promotion. Please try again.</div>";
    }
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Add Promotion</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="title" placeholder="Enter Promotion Title" required>
                    </td>
                </tr>

                <tr>
                    <td>Program Name:</td>
                    <td>
                        <input type="text" name="program_name" placeholder="Enter Program Name" required> <!-- Added Program Name -->
                    </td>
                </tr>

                <tr>
                    <td>Price:</td>
                    <td>
                        <input type="number" name="price" placeholder="Enter Price" id="price" step="0.01" required>
                    </td>
                </tr>

                <tr>
                    <td>Discount Rate (%):</td>
                    <td>
                        <input type="number" name="discount_rate" placeholder="Enter Discount Rate (e.g., 20)" id="discount_rate" step="0.01">
                    </td>
                </tr>

                <tr>
                    <td>Price with Discount:</td>
                    <td>
                        <input type="number" name="discounted_price" placeholder="Price after Discount" id="discounted_price" readonly>
                    </td>
                </tr>

                <tr>
                    <td>Select New Image:</td>
                    <td>
                        <input type="file" name="image" accept="image/*" onchange="previewImage(event)">
                        <img id="image-preview" src="" alt="Image Preview" style="display:none; margin-top:10px;" width="100">
                    </td>
                </tr>

                <tr>
                    <td>Description:</td>
                    <td>
                        <textarea name="description" rows="4" placeholder="Enter Description" required></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Active:</td>
                    <td>
                        <input type="radio" name="active" value="Yes" checked> Yes
                        <input type="radio" name="active" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Promotion" class="btn-primary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>


<!-- Add your CSS here to fix the layout -->


<script>
    // Function to preview the uploaded image
    function previewImage(event) {
        const imagePreview = document.getElementById('image-preview');
        const file = event.target.files[0];
        if (file) {
            imagePreview.src = URL.createObjectURL(file);
            imagePreview.style.display = 'block';
        }
    }

    // Automatically calculate and update the price with discount
    const priceInput = document.getElementById('price');
    const discountRateInput = document.getElementById('discount_rate');
    const discountedPriceInput = document.getElementById('discounted_price');

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
