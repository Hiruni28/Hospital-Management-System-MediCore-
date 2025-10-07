<?php
// customer-main/edit-appointment.php
session_start();
include('../config/constant.php'); // adjust path if needed

$message = "";

// Get appointment ID from URL
$appointment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($appointment_id <= 0) {
    die("Invalid appointment ID.");
}

// Fetch existing appointment
$stmt = $conn->prepare("SELECT * FROM appointments WHERE appointment_id = ?");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();
$stmt->close();

if (!$appointment) {
    die("Appointment not found.");
}

// Fetch doctors from database for dropdown
$doctors = [];
$doctorResult = $conn->query("SELECT doctor_id, doctorName FROM doctors ORDER BY doctorName ASC");
if ($doctorResult->num_rows > 0) {
    while ($row = $doctorResult->fetch_assoc()) {
        $doctors[] = $row;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = isset($_POST['patient_id']) ? (int)$_POST['patient_id'] : 0;
    $doctor_id = isset($_POST['doctor_id']) ? (int)$_POST['doctor_id'] : 0;
    $patient_name = trim($_POST['patient_name']);
    $patient_phone = trim($_POST['patient_phone']);
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $appointment_type = $_POST['appointment_type'];

    if ($patient_name && $patient_phone && $appointment_date && $appointment_time && $appointment_type) {
        $stmt = $conn->prepare("UPDATE appointments SET patient_id=?, doctor_id=?, patient_name=?, patient_phone=?, appointment_date=?, appointment_time=?, appointment_type=? WHERE appointment_id=?");
        $stmt->bind_param("iisssssi", $patient_id, $doctor_id, $patient_name, $patient_phone, $appointment_date, $appointment_time, $appointment_type, $appointment_id);

        if ($stmt->execute()) {
            $message = "✅ Appointment updated successfully!";
            // Refresh appointment data
            $appointment = [
                'patient_id' => $patient_id,
                'doctor_id' => $doctor_id,
                'patient_name' => $patient_name,
                'patient_phone' => $patient_phone,
                'appointment_date' => $appointment_date,
                'appointment_time' => $appointment_time,
                'appointment_type' => $appointment_type
            ];
        } else {
            $message = "❌ Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "⚠️ Please fill all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Appointment</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7fa; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0px 0px 10px #ccc; }
        h2 { text-align: center; color: teal; }
        form { margin-top: 20px; }
        label { font-weight: bold; display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 10px; margin-top: 6px; border: 1px solid #ccc; border-radius: 6px; }
        button { margin-top: 20px; padding: 12px; width: 100%; border: none; background: teal; color: white; font-size: 16px; border-radius: 6px; cursor: pointer; }
        button:hover { background: darkslategray; }
        .message { margin-top: 15px; text-align: center; font-weight: bold; color: green; }
        .back-btn { margin-top: 10px; display: block; text-align: center; text-decoration: none; color: teal; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Appointment</h2>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="appointment_id">Appointment ID</label>
            <input type="text" name="appointment_id" id="appointment_id" value="<?php echo $appointment_id; ?>" readonly>

            <label for="patient_id">Patient ID</label>
            <input type="text" name="patient_id" id="patient_id" value="<?php echo htmlspecialchars($appointment['patient_id']); ?>" required>

            <label for="patient_name">Full Name</label>
            <input type="text" name="patient_name" id="patient_name" value="<?php echo htmlspecialchars($appointment['patient_name']); ?>" required>

            <label for="patient_phone">Phone Number</label>
            <input type="text" name="patient_phone" id="patient_phone" value="<?php echo htmlspecialchars($appointment['patient_phone']); ?>" required>

            <label for="appointment_date">Appointment Date</label>
            <input type="date" name="appointment_date" id="appointment_date" value="<?php echo $appointment['appointment_date']; ?>" required>

            <label for="appointment_time">Appointment Time</label>
            <input type="time" name="appointment_time" id="appointment_time" value="<?php echo $appointment['appointment_time']; ?>" required>

            <label for="appointment_type">Appointment Type</label>
            <select name="appointment_type" id="appointment_type" required>
                <option value="">-- Select Type --</option>
                <option value="Consultation" <?php if($appointment['appointment_type']=='Consultation') echo 'selected'; ?>>Consultation</option>
                <option value="Follow-up" <?php if($appointment['appointment_type']=='Follow-up') echo 'selected'; ?>>Follow-up</option>
                <option value="Checkup" <?php if($appointment['appointment_type']=='Checkup') echo 'selected'; ?>>Checkup</option>
            </select>

            <label for="doctor_id">Doctor (optional)</label>
            <select name="doctor_id" id="doctor_id">
                <option value="0" <?php if($appointment['doctor_id']==0) echo 'selected'; ?>>-- Not Selected --</option>
                <?php foreach ($doctors as $doc): ?>
                    <option value="<?php echo $doc['doctor_id']; ?>" <?php if($appointment['doctor_id']==$doc['doctor_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($doc['doctorName']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Update Appointment</button>
        </form>

        <a href="appointments.php" class="back-btn">← Back to Appointments</a>
    </div>
</body>
</html>
