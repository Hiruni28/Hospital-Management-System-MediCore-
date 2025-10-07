<?php
include('customer-main/main.php');

// Initialize variables
$full_name = $email = $tel_no = '';

// Check if user is logged in
if (isset($_SESSION['patient_id'])) {
    $patient_id = $_SESSION['patient_id'];

    // Fetch customer details from tbl_patient
    $stmt = $conn->prepare("SELECT full_name, email, tel_no FROM tbl_patient WHERE patient_id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $stmt->bind_result($full_name, $email, $tel_no);
    $stmt->fetch();
    $stmt->close();
}

// ✅ Handle form submission BEFORE sending HTML
if (isset($_POST['submit'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $tel_no = $_POST['tel_no'];
    $package = $_POST['package'];
    $message = $_POST['message'];

    $sql = "INSERT INTO tbl_membershipBookings (full_name, email, tel_no, package, message) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $full_name, $email, $tel_no, $package, $message);

    if ($stmt->execute()) {
        // Redirect before output
        header("Location: " . SITEURL . "index.php?status=success&action=Membershipbooking");
        exit();
    } else {
        header("Location: " . SITEURL . "membershipBoking.php?status=error&action=Membershipbooking");
        exit();
    }
}
?>

<section class="membershipBook">
    <div class="booking">
        <form action="" method="POST" enctype="multipart/form-data">
            <h1 class="main">Booking</h1>

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

            <!-- Retrieve package from URL query string -->
            <div class="input-box">
                <input type="text" name="package" value="<?php echo isset($_GET['package']) ? htmlspecialchars($_GET['package']) : ''; ?>" readonly>
                <i class='bx bx-package'></i>
            </div>

            <div class="input-box">
                <textarea name="message" placeholder="Additional Details"></textarea>
            </div>

            <input type="submit" name="submit" value="Booking" class="btn-member">
        </form>

        <p class="booking-note">(After your booking, one of our staff members will contact you soon. If there's any delay, please send a message to our mobile number: 0773274601)</p>
    </div>
</section>

<?php include('customer-main/footer.php'); ?>

