<?php
include('customer-main/main.php'); // Include the header


// Get category and program from the URL if available
$selected_category = isset($_GET['category']) ? $_GET['category'] : '';
$selected_program = isset($_GET['program']) ? $_GET['program'] : '';

// Initialize user data
$full_name = $email = $tel_no = '';
$customer_id = $_SESSION['customer_id'] ?? null;

// Fetch customer details if logged in
if ($customer_id) {
    $stmt = $conn->prepare("SELECT full_name, email, tel_no FROM tbl_customer WHERE id = ?");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $stmt->bind_result($full_name, $email, $tel_no);
    $stmt->fetch();
    $stmt->close();
}

// Booking form submission logic
if (isset($_POST['submit'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $tel_no = $_POST['tel_no'];
    $weight = $_POST['weight'];
    $category = $_POST['category']; // From hidden input
    $program = $_POST['Program'];   // From hidden input
    $message = $_POST['message'];

    // Insert booking into the tbl_programBooking table
    $sql = "INSERT INTO tbl_programBooking (customer_id, full_name, email, tel_no, category_id, program_id, weight, message) 
            VALUES (?, ?, ?, ?, 
                    (SELECT id FROM tbl_programCategories WHERE title = ?), 
                    (SELECT id FROM tbl_programs WHERE title = ?), ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isssssss', $customer_id, $full_name, $email, $tel_no, $category, $program, $weight, $message);

    if ($stmt->execute()) {
        header('Location: ' . SITEURL . 'index.php?status=success&action=programBooking');
        exit();
    } else {
        echo "<div class='error'>Failed to book the program. Please try again.</div>";
    }
}
?>

<section class="membershipBook">
    <div class="booking">
        <form action="" method="POST" enctype="multipart/form-data">
            <h1 class="main">Request</h1>

            <div class="input-box">
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" placeholder="Full Name" required readonly>
                <i class='bx bx-user'></i>
            </div>

            <div class="input-box">
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Email" required readonly>
                <i class='bx bx-envelope'></i>
            </div>

            <div class="input-box">
                <input type="tel" name="tel_no" value="<?php echo htmlspecialchars($tel_no); ?>" placeholder="Mobile" required readonly>
                <i class='bx bx-phone-call'></i>
            </div>

            <div class="input-box">
                <input type="text" name="weight" placeholder="Weight (e.g. 55kg)" required>
                <i class='bx bx-body'></i>
            </div>

            <!-- Hidden category and program fields from URL -->
            <input type="hidden" name="category" value="<?php echo htmlspecialchars($selected_category); ?>">
            <input type="hidden" name="Program" value="<?php echo htmlspecialchars($selected_program); ?>">

            <!-- Display-only category and program -->
            <div class="input-box">
                <input type="text" value="<?php echo htmlspecialchars($selected_category); ?>" placeholder="Category" readonly>
            </div>

            <div class="input-box">
                <input type="text" value="<?php echo htmlspecialchars($selected_program); ?>" placeholder="Program" readonly>
            </div>

            <div class="input-box">
                <textarea name="message" placeholder="Additional Details"></textarea>
            </div>

            <input type="submit" name="submit" value="Request" class="btn-member">
        </form>

        <p class="booking-note">(After your booking, one of our staff members will contact you soon. If there's any delay, please send a message to our mobile number: 0773274601)</p>
    </div>
</section>

<?php include('customer-main/footer.php'); ?>
