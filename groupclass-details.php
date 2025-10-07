<?php
include('customer-main/main.php');

// Check if the program ID is provided in the URL
if (isset($_GET['id'])) {
    $program_id = intval($_GET['id']); 

    // Fetch the program details including the category_id and category title from the database
    $sql = "SELECT p.title, p.price_local, p.price_foreign, p.description, p.features, p.schedule, p.image_name, c.title AS category_title
            FROM tbl_programs p
            JOIN tbl_programCategories c ON p.category_id = c.id
            WHERE p.id = ? AND p.active = 'Yes'"; 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $program_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {
        // Fetch the program details
        $row = $res->fetch_assoc();
        $title = $row['title'];
        $price_local = $row['price_local'];
        $price_foreign = $row['price_foreign'];
        $description = $row['description'];
        $features = json_decode($row['features'], true); 
        $schedule = json_decode($row['schedule'], true);
        $image_name = $row['image_name'];
        $category_title = $row['category_title']; 

        $image_path = ($image_name != "") ? "images/programs/" . $image_name : "images/default-program.jpg";
    } else {
        header('Location: programs.php');
        exit();
    }
} else {
    header('Location: programs.php');
    exit();
}
?>

<section class="details-container">
    <div class="details-header">
        <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($title); ?>">
        <div class="details-info">
            <h2><?php echo htmlspecialchars($title); ?></h2>
            <p><strong>Price for Local:</strong> <?php echo htmlspecialchars($price_local); ?> per month</p>
            <p><strong>Price for Foreigners:</strong> $<?php echo htmlspecialchars($price_foreign); ?> per month</p>
            <?php
            if (isset($_SESSION['patient_id'])) {
                // If the customer is logged in, allow them to book
                echo '<a href="http://localhost/medicore/customer-main/add-appointment.php?category=' . urlencode($category_title) . '&program=' . urlencode($title) . '" class="book-btn">Request</a>';
            } else {
                // If the customer is not logged in, redirect to login page
                echo '<a href="customer-login.php" class="book-btn">Login to Request</a>';
            }
            ?>
        </div>
    </div>

    <div class="details-content">
        <h3 style="color: #059665dc;">Services Details Description</h3>
        <p><?php echo htmlspecialchars($description); ?></p>

        <h3 style="color: #059665dc;">Features</h3>
        <ul>
            <?php foreach ($features as $feature) { ?>
                <li><?php echo htmlspecialchars($feature); ?></li>
            <?php } ?>
        </ul>

        <h3 style="color: #059665dc;">Dates and Time Schedule</h3>
        <ul>
            <?php foreach ($schedule as $time) { ?>
                <li><?php echo htmlspecialchars($time); ?></li>
            <?php } ?>
        </ul>

        <a href="programs.php" class="back-button">Back to Services</a>
    </div>
</section>

<?php include('customer-main/footer.php'); ?>
