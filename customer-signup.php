<?php
include('customer-main/main.php'); // Make sure this does not output anything before processing

// Process form submission BEFORE any HTML output
if (isset($_POST['submit'])) {
    require_once('config/constant.php'); // Your DB connection and constants

    // Collect and sanitize inputs
    $full_name              = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $gender                 = isset($_POST['gender']) ? trim($_POST['gender']) : '';
    $dob                    = isset($_POST['dob']) ? trim($_POST['dob']) : '';
    $nic                    = isset($_POST['nic']) ? trim($_POST['nic']) : '';
    $tel_no                 = isset($_POST['tel_no']) ? trim($_POST['tel_no']) : '';
    $address                = isset($_POST['address']) ? trim($_POST['address']) : '';
    $email                  = isset($_POST['email']) ? trim($_POST['email']) : '';
    $emergency_contact_name = isset($_POST['emergency_contact_name']) ? trim($_POST['emergency_contact_name']) : '';
    $emergency_contact_no   = isset($_POST['emergency_contact_no']) ? trim($_POST['emergency_contact_no']) : '';
    $blood_group            = isset($_POST['blood_group']) ? trim($_POST['blood_group']) : '';
    $allergies              = isset($_POST['allergies']) ? trim($_POST['allergies']) : '';
    $username               = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password_raw           = isset($_POST['password']) ? $_POST['password'] : '';

    // Hash password securely
    $password_hash = password_hash($password_raw, PASSWORD_DEFAULT);

    // Escape all other inputs to prevent SQL injection
    $full_name              = mysqli_real_escape_string($conn, $full_name);
    $gender                 = mysqli_real_escape_string($conn, $gender);
    $dob                    = mysqli_real_escape_string($conn, $dob);
    $nic                    = mysqli_real_escape_string($conn, $nic);
    $tel_no                 = mysqli_real_escape_string($conn, $tel_no);
    $address                = mysqli_real_escape_string($conn, $address);
    $email                  = mysqli_real_escape_string($conn, $email);
    $emergency_contact_name = mysqli_real_escape_string($conn, $emergency_contact_name);
    $emergency_contact_no   = mysqli_real_escape_string($conn, $emergency_contact_no);
    $blood_group            = mysqli_real_escape_string($conn, $blood_group);
    $allergies              = mysqli_real_escape_string($conn, $allergies);
    $username               = mysqli_real_escape_string($conn, $username);

    // Note: password_hash output is safe, no need to escape
    $password = $password_hash;

    // Build SQL INSERT query
    $sql = "INSERT INTO tbl_patient SET 
        full_name='$full_name',
        gender='$gender',
        dob='$dob',
        nic='$nic',
        tel_no='$tel_no',
        address='$address',
        email='$email',
        emergency_contact_name='$emergency_contact_name',
        emergency_contact_no='$emergency_contact_no',
        blood_group='$blood_group',
        allergies='$allergies',
        username='$username',
        password='$password'";

    // Execute query
    $res = mysqli_query($conn, $sql);

    if ($res) {
    echo '<div class="notification success">✅ Registration successful!</div>';
    // Or redirect after delay:
    // header("Location: customer-login.php?status=success");
    // exit();
} else {
    echo '<div class="notification error">❌ Registration failed: ' . mysqli_error($conn) . '</div>';
}
}
?>

<style>
.notification {
    padding: 10px 15px;
    margin: 10px 0;
    width: 100%;
    border-radius: 8px;
    font-size: 18px;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    gap: 8px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    animation: fadeIn 0.4s ease-in-out;
}

.success {
    background: #e6f7e9;
    color: #2d7a32;
    border: 1px solid #2d7a32;
}

.error {
    background: #fdecea;
    color: #b91c1c;
    border: 1px solid #b91c1c;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>


<!-- HTML starts here -->
<div class="parent-container">
    <div class="container_signup">
        <form action="" method="POST">
            <h1>Patient Registration</h1>

            <div class="input-boxes">
                <input type="text" name="full_name" placeholder="Full Name" required>
                <i class='bx bx-user'></i>
            </div>

            <div class="input-boxes">
                <select name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
                <i class='bx bx-male-female'></i>
            </div>

            <div class="input-boxes">
                <input type="date" name="dob" required>
                <i class='bx bx-calendar'></i>
            </div>

            <div class="input-boxes">
                <input type="text" name="nic" placeholder="NIC/Passport No" required>
                <i class='bx bx-id-card'></i>
            </div>

            <div class="input-boxes">
                <input type="tel" name="tel_no" placeholder="Mobile" required>
                <i class='bx bx-phone-call'></i>
            </div>

            <div class="input-boxes">
                <input type="text" name="address" placeholder="Address" required>
               <i class='bx bx-current-location'></i> 
            </div>

            <div class="input-boxes">
                <input type="email" name="email" placeholder="Email">
                <i class='bx bx-envelope'></i>
            </div>

            <div class="input-boxes">
                <input type="text" name="emergency_contact_name" placeholder="Emergency Contact Name" required>
                <i class='bx bx-user-voice'></i>
            </div>

            <div class="input-boxes">
                <input type="tel" name="emergency_contact_no" placeholder="Emergency Contact Number" required>
                <i class='bx bx-phone'></i>
            </div>

            <div class="input-boxes">
                <select name="blood_group" required>
                    <option value="">Select Blood Group</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </select>
                <i class='bx bxs-droplet'></i>
            </div>

            <div class="input-boxes">
                <input type="text" name="allergies" placeholder="Known Allergies (optional)">
                <i class='bx bx-band-aid'></i>
            </div>

            <div class="input-boxes">
                <input type="text" name="username" placeholder="User Name" required>
                <i class='bx bx-user'></i>
            </div>

            <div class="input-boxes">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bx-lock-alt'></i>
            </div>

            <div class="rm">
                <label>
                    <input type="checkbox"> Remember-me
                </label>
                <a href="#">Forgot Password?</a>
            </div>

            <input type="submit" name="submit" value="Sign Up" class="btn-login">

            <div class="register">
                <p>I have an account <a href="customer-login.php">Login</a></p>
                <p>Back to <a href="index.php">Home Page</a></p>
            </div>
        </form>
    </div>
</div>