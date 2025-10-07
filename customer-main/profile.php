<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

// Include DB & Patient class (adjust paths based on actual structure)
require_once __DIR__ . '/../config/constant.php';
require_once __DIR__ . '/classes/Patient.php';

// Create patient object
$patient = new Patient($conn);

// Load patient data
$patient_data = $patient->getPatientById($_SESSION['patient_id']);
if (!$patient_data) {
    die("Patient record not found.");
}

$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient->patient_id = $_SESSION['patient_id'];
    $patient->full_name = $_POST['full_name'] ?? '';
    $patient->gender = $_POST['gender'] ?? '';
    $patient->dob = $_POST['dob'] ?? '';
    $patient->nic = $_POST['nic'] ?? '';
    $patient->tel_no = $_POST['tel_no'] ?? '';
    $patient->address = $_POST['address'] ?? '';
    $patient->email = $_POST['email'] ?? '';
    $patient->emergency_contact_name = $_POST['emergency_contact_name'] ?? '';
    $patient->emergency_contact_no = $_POST['emergency_contact_no'] ?? '';
    $patient->blood_group = $_POST['blood_group'] ?? '';
    $patient->allergies = $_POST['allergies'] ?? '';
    $patient->username = $_POST['username'] ?? '';
    $patient->profile_image = $patient_data['profile_image'] ?? ''; // default

    // Handle image upload
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = __DIR__ . "/uploads/patients/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $imageFileType = strtolower(pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION));
        $new_filename = "patient_" . $patient->patient_id . "." . $imageFileType;
        $target_file = $target_dir . $new_filename;

        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $patient->profile_image = $new_filename;
            }
        }
    }

    // Validate
    $validation_errors = $patient->validate();
    if (empty($validation_errors)) {
        $check_patient = new Patient($conn);
        $existing = $check_patient->getPatientByEmail($patient->email);
        if ($existing && isset($existing['patient_id']) && $existing['patient_id'] != $patient->patient_id) {
            $message = "Email already exists for another patient.";
            $message_type = "error";
        } else {
            if ($patient->update()) {
                $message = "Profile updated successfully!";
                $message_type = "success";
            } else {
                $message = "Unable to update profile. Please try again.";
                $message_type = "error";
            }
        }
    } else {
        $message = implode('<br>', $validation_errors);
        $message_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MediCore - My Profile</title>
    <link rel="stylesheet" href="profile.css" />
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <h2>Dashboard</h2>
        </div>

        <div class="nav-menu">
            <a href="profile.php" class="nav-item active">Profile Details</a>
            <a href="appointments.php" class="nav-item">My Appointments</a>
            <a href="memberships.php" class="nav-item">My Memberships</a>
            <a href="patient_prescription.php" class="nav-item">Prescriptions</a>
            <a href="reports.php" class="nav-item">Reports</a>
        </div>

        <a href="http://localhost/medicore/index.php" class="back-btn">Back</a>
    </div>

    <div class="main-content">
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-avatar">
                    <?php
                    $image_path = __DIR__ . "/uploads/patients/" . $patient->profile_image;
                    if (!empty($patient->profile_image) && file_exists($image_path)): ?>
                        <img src="uploads/patients/<?php echo htmlspecialchars($patient->profile_image); ?>" alt="Profile Image" style="width:100px; height:100px; border-radius:50%;">
                    <?php else: ?>
                        <?php
                        $names = explode(' ', $patient->full_name);
                        echo strtoupper(substr($names[0] ?? '', 0, 1));
                        if (count($names) > 1) echo strtoupper(substr($names[1] ?? '', 0, 1));
                        ?>
                    <?php endif; ?>
                </div>
                <h1>My Profile</h1>
            </div>

            <?php if ($message): ?>
                <div class="<?php echo $message_type; ?>-message">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Profile Image</label>
                        <input type="file" name="profile_image" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($patient->full_name); ?>" required />
                    </div>

                    <div class="form-group">
                        <label>Gender <span class="required">*</span></label>
                        <select name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male" <?php echo ($patient->gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($patient->gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                            <option value="Other" <?php echo ($patient->gender == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Date of Birth <span class="required">*</span></label>
                        <input type="date" name="dob" value="<?php echo htmlspecialchars($patient->dob); ?>" required />
                    </div>

                    <div class="form-group">
                        <label>NIC <span class="required">*</span></label>
                        <input type="text" name="nic" value="<?php echo htmlspecialchars($patient->nic); ?>" required />
                    </div>

                    <div class="form-group">
                        <label>Phone Number <span class="required">*</span></label>
                        <input type="tel" name="tel_no" value="<?php echo htmlspecialchars($patient->tel_no); ?>" required />
                    </div>

                    <div class="form-group">
                        <label>Address <span class="required">*</span></label>
                        <input type="text" name="address" value="<?php echo htmlspecialchars($patient->address); ?>" required />
                    </div>

                    <div class="form-group">
                        <label>Email Address <span class="required">*</span></label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($patient->email); ?>" required />
                    </div>

                    <div class="form-group">
                        <label>Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name" value="<?php echo htmlspecialchars($patient->emergency_contact_name); ?>" />
                    </div>

                    <div class="form-group">
                        <label>Emergency Contact Number</label>
                        <input type="tel" name="emergency_contact_no" value="<?php echo htmlspecialchars($patient->emergency_contact_no); ?>" />
                    </div>

                    <div class="form-group">
                        <label>Blood Group</label>
                        <select name="blood_group">
                            <option value="">Select Blood Group</option>
                            <?php
                            $bloodGroups = ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"];
                            foreach ($bloodGroups as $bg) {
                                echo '<option value="' . $bg . '" ' . ($patient->blood_group == $bg ? 'selected' : '') . '>' . $bg . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($patient->username); ?>" />
                    </div>

                    <div class="form-group full-width">
                        <label>Allergies & Medical Conditions</label>
                        <textarea name="allergies"><?php echo htmlspecialchars($patient->allergies); ?></textarea>
                    </div>
                </div>
                <button type="submit" class="update-btn">Update Profile</button>
            </form>
        </div>
    </div>
</body>

</html>