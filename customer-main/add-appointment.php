<?php
session_start();
include('../config/constant.php'); 

// Initialize variables
$patient_id = $full_name  = $tel_no = '';

// Check if user is logged in and fetch details
if (isset($_SESSION['patient_id'])) {
    $pid = $_SESSION['patient_id'];
    $stmt = $conn->prepare("SELECT patient_id, full_name, tel_no FROM tbl_patient WHERE patient_id = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $stmt->bind_result($patient_id, $full_name, $tel_no);
    $stmt->fetch();
    $stmt->close();
}

// Auto-generate Appointment ID
$nextAppointmentId = 1;
$result = $conn->query("SELECT MAX(appointment_id) AS max_id FROM appointments");
if ($result && $row = $result->fetch_assoc()) {
    $nextAppointmentId = $row['max_id'] + 1;
}

$selected_category = isset($_GET['category']) ? $_GET['category'] : '';
$selected_program  = isset($_GET['program']) ? $_GET['program'] : '';
$message = "";
$success = false;

// Fetch doctors for dropdown
$doctors = [];
$doctorResult = $conn->query("SELECT doctor_id, doctorName FROM doctors ORDER BY doctorName ASC");
if ($doctorResult->num_rows > 0) {
    while ($row = $doctorResult->fetch_assoc()) {
        $doctors[] = $row;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id   = (int)$_POST['appointment_id'];
    $patient_id       = (int)$_POST['patient_id'];
    $doctor_id        = (int)$_POST['doctor_id'];
    $patient_name     = trim($_POST['patient_name']);
    $patient_phone    = trim($_POST['patient_phone']);
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    if ($patient_name && $patient_phone && $appointment_date && $appointment_time) {
        $stmt = $conn->prepare("INSERT INTO appointments 
            (appointment_id, patient_id, doctor_id, patient_name, patient_phone, appointment_date, appointment_time, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");

        $stmt->bind_param("iiissss", 
            $appointment_id, 
            $patient_id, 
            $doctor_id, 
            $patient_name, 
            $patient_phone, 
            $appointment_date, 
            $appointment_time
        );

        if ($stmt->execute()) {
            $message = "✅ Appointment request submitted successfully!";
            $success = true;
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
    <title>Book Appointment</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #d9e6f8ff;
            padding: 40px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 6px 16px rgba(0, 0, 0, 0.15);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 26px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-top: 14px;
            color: #34495e;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            font-size: 15px;
            background: #fafafa;
        }

        input[readonly] {
            background: #eef2f7;
            font-weight: bold;
            color: #555;
        }

        button[type="submit"] {
            width: 100%;
            background: linear-gradient(135deg, #16a085, #1abc9c);
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 17px;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 25px;
            transition: all 0.3s ease;
        }

        button[type="submit"]:hover {
            background: linear-gradient(135deg, #1abc9c, #16a085);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(22, 160, 133, 0.3);
        }

        .back-btn {
            display: block;
            text-align: center;
            text-decoration: none;
            width: 100%;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            margin-top: 15px;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: linear-gradient(135deg, #2980b9, #3498db);
        }

        .message {
            margin-top: 15px;
            padding: 14px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 15px;
        }

        .message.success {
            background: #e9f9ee;
            color: #27ae60;
        }

        .message.error {
            background: #fdecea;
            color: #c0392b;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>📅 Book an Appointment</h2>

        <?php if ($message): ?>
            <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Appointment ID</label>
            <input type="text" name="appointment_id" value="<?php echo $nextAppointmentId; ?>" readonly>

            <label>Patient ID</label>
            <input type="text" name="patient_id" value="<?php echo $patient_id; ?>" readonly>

            <label>Full Name</label>
            <input type="text" name="patient_name" value="<?php echo htmlspecialchars($full_name); ?>" readonly>

            <label>Phone Number</label>
            <input type="text" name="patient_phone" value="<?php echo htmlspecialchars($tel_no); ?>" readonly>

            <label>Appointment Date</label>
            <input type="date" name="appointment_date" required>

            <label>Appointment Time</label>
            <input type="time" name="appointment_time" required>

            <label>Category</label>
            <input type="text" value="<?php echo htmlspecialchars($selected_category); ?>" readonly>
            <input type="hidden" name="category" value="<?php echo htmlspecialchars($selected_category); ?>">

            <label>Program</label>
            <input type="text" value="<?php echo htmlspecialchars($selected_program); ?>" readonly>
            <input type="hidden" name="program" value="<?php echo htmlspecialchars($selected_program); ?>">

            <label for="doctor_id">Doctor (optional)</label>
            <select name="doctor_id" id="doctor_id">
                <option value="0">-- Not Selected --</option>
                <?php foreach ($doctors as $doc): ?>
                    <option value="<?php echo $doc['doctor_id']; ?>">
                        <?php echo htmlspecialchars($doc['doctorName']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Submit Appointment</button>
        </form>

        <a href="http://localhost/medicore/programs.php" class="back-btn">← Back to Programs</a>
    </div>
</body>
</html>