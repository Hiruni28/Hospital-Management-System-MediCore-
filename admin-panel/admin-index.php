<?php 
include('partials/main.php'); 
?>

<!-- Main Content -->
<div class="main-content">
    <div class="content-grid">
        <a class="content-button" href="manage-bookinPackage.php">Available Bookings</a>
        <a class="content-button" href="manage-inquery.php">Manage Inqueries</a>
        <a class="content-button" href="manage-gallery.php">Gallery</a>
        <a class="content-button" href="manage-success-stories.php">success story</a>
    </div>
    

    <br><br><br><br>


    <!-- Scrollable Table Container -->
    <?php

// Query to fetch promotion details
$sql = "SELECT title, program_name, price, discount_rate FROM tbl_promotions";
$result = $conn->query($sql);

?>

<h3>Promotion Details</h3>
<br><br>
<!-- Scrollable Table Container -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>S.N</th>
                <th>Title</th>
                <th>Program Name</th>
                <th>Price</th>
                <th>Discount Rate (%)</th>
                <th>Price with Discount</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $sn = 1; // Initialize serial number
                while ($row = $result->fetch_assoc()) {
                    // Calculate price with discount
                    $price_with_discount = $row['price'] - ($row['price'] * ($row['discount_rate'] / 100));
            ?>
                <tr>
                    <td><?php echo $sn++; ?></td> <!-- Display serial number -->
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['program_name']); ?></td>
                    <td><?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo number_format($row['discount_rate'], 2); ?>%</td>
                    <td><?php echo number_format($price_with_discount, 2); ?></td>
                </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='6'>No promotion details found.</td></tr>"; // Update colspan to 6
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('partials/footer.php'); ?>

