<?php
session_start();
// Add admin authentication check here if needed
// if (!isset($_SESSION['admin'])) {
//     header("Location: admin_login.php");
//     exit();
// }

$conn = new mysqli("localhost", "root", "", "fitzone");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
$messageType = '';

// Handle appointment confirmation/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $appointmentId = $_POST['appointment_id'] ?? '';

    if ($action === 'confirm' && $appointmentId) {
        $stmt = $conn->prepare("UPDATE appointments SET status = 'confirmed' WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $appointmentId);
            if ($stmt->execute()) {
                $message = "Appointment confirmed successfully!";
                $messageType = "success";
            } else {
                $message = "Error confirming appointment.";
                $messageType = "error";
            }
            $stmt->close();
        }
    } elseif ($action === 'reject' && $appointmentId) {
        $stmt = $conn->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $appointmentId);
            if ($stmt->execute()) {
                $message = "Appointment rejected successfully!";
                $messageType = "success";
            } else {
                $message = "Error rejecting appointment.";
                $messageType = "error";
            }
            $stmt->close();
        }
    } elseif ($action === 'add_appointment') {
        // Add new appointment
        $doctorId = $_POST['doctor_id'];
        $patientName = $_POST['patient_name'];
        $patientPhone = $_POST['patient_phone'];
        $appointmentDate = $_POST['appointment_date'];
        $appointmentTime = $_POST['appointment_time'];
        $appointmentType = $_POST['appointment_type'];

        $stmt = $conn->prepare("INSERT INTO appointments (doctor_id, patient_name, patient_phone, appointment_date, appointment_time, appointment_type, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
        if ($stmt) {
            $stmt->bind_param("isssss", $doctorId, $patientName, $patientPhone, $appointmentDate, $appointmentTime, $appointmentType);
            if ($stmt->execute()) {
                $message = "Appointment added successfully! Waiting for confirmation.";
                $messageType = "success";
            } else {
                $message = "Error adding appointment.";
                $messageType = "error";
            }
            $stmt->close();
        }
    }
}

// Fetch all doctors for dropdown
$doctorsQuery = "SELECT id, username, specilization FROM doctors ORDER BY username";
$doctorsResult = $conn->query($doctorsQuery);
$doctors = $doctorsResult->fetch_all(MYSQLI_ASSOC);

// Fetch pending appointments
$pendingQuery = "SELECT a.*, d.username as doctor_name, d.specilization 
                FROM appointments a 
                JOIN doctors d ON a.doctor_id = d.id 
                WHERE a.status = 'pending' 
                ORDER BY a.appointment_date, a.appointment_time";
$pendingResult = $conn->query($pendingQuery);
$pendingAppointments = $pendingResult->fetch_all(MYSQLI_ASSOC);

// Fetch confirmed appointments
$confirmedQuery = "SELECT a.*, d.username as doctor_name, d.specilization 
                  FROM appointments a 
                  JOIN doctors d ON a.doctor_id = d.id 
                  WHERE a.status = 'confirmed' 
                  ORDER BY a.appointment_date DESC, a.appointment_time DESC 
                  LIMIT 20";
$confirmedResult = $conn->query($confirmedQuery);
$confirmedAppointments = $confirmedResult->fetch_all(MYSQLI_ASSOC);

// Fetch cancelled appointments
$cancelledQuery = "SELECT a.*, d.username as doctor_name, d.specilization 
                  FROM appointments a 
                  JOIN doctors d ON a.doctor_id = d.id 
                  WHERE a.status = 'cancelled' 
                  ORDER BY a.appointment_date DESC, a.appointment_time DESC 
                  LIMIT 10";
$cancelledResult = $conn->query($cancelledQuery);
$cancelledAppointments = $cancelledResult->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Appointment Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }

        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .section h2 {
            color: #667eea;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background-color: #5a6fd8;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
            margin: 2px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #555;
        }

        .table tr:hover {
            background-color: #f5f5f5;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 20px;
        }

        .tab {
            padding: 12px 24px;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 14px;
            font-weight: 600;
            color: #666;
            border-bottom: 3px solid transparent;
        }

        .tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Admin Panel - Appointment Management</h1>
            <p>Manage and confirm doctor appointments</p>
        </div>

        <?php if ($message): ?>
            <div class="message <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= count($pendingAppointments) ?></div>
                <div class="stat-label">Pending Appointments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count($confirmedAppointments) ?></div>
                <div class="stat-label">Confirmed Today</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count($cancelledAppointments) ?></div>
                <div class="stat-label">Cancelled</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count($doctors) ?></div>
                <div class="stat-label">Total Doctors</div>
            </div>
        </div>

        <!-- Add New Appointment -->
        <div class="section">
            <h2>Add New Appointment</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add_appointment">
                <div class="form-row">
                    <div class="form-group">
                        <label>Doctor</label>
                        <select name="doctor_id" required>
                            <option value="">Select Doctor</option>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?= $doctor['id'] ?>">
                                    Dr. <?= htmlspecialchars($doctor['username']) ?> - <?= htmlspecialchars($doctor['specilization']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Patient Name</label>
                        <input type="text" name="patient_name" required>
                    </div>
                </div>
                <div class="form-row-3">
                    <div class="form-group">
                        <label>Patient Phone</label>
                        <input type="tel" name="patient_phone" required>
                    </div>
                    <div class="form-group">
                        <label>Appointment Date</label>
                        <input type="date" name="appointment_date" required>
                    </div>
                    <div class="form-group">
                        <label>Appointment Time</label>
                        <input type="time" name="appointment_time" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Appointment Type</label>
                    <select name="appointment_type" required>
                        <option value="Consultation">Consultation</option>
                        <option value="Follow-up">Follow-up</option>
                        <option value="Regular Checkup">Regular Checkup</option>
                        <option value="Emergency">Emergency</option>
                        <option value="Surgery">Surgery</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Add Appointment</button>
            </form>
        </div>

        <!-- Appointment Management Tabs -->
        <div class="section">
            <div class="tabs">
                <button class="tab active" onclick="showTab('pending')">Pending Approval (<?= count($pendingAppointments) ?>)</button>
                <button class="tab" onclick="showTab('confirmed')">Confirmed (<?= count($confirmedAppointments) ?>)</button>
                <button class="tab" onclick="showTab('cancelled')">Cancelled (<?= count($cancelledAppointments) ?>)</button>
            </div>

            <!-- Pending Appointments -->
            <div id="pending" class="tab-content active">
                <h2>Pending Appointments - Waiting for Confirmation</h2>
                <?php if (count($pendingAppointments) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Patient</th>
                                <th>Phone</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingAppointments as $appointment): ?>
                                <tr>
                                    <td>
                                        <strong>Dr. <?= htmlspecialchars($appointment['doctor_name']) ?></strong><br>
                                        <small><?= htmlspecialchars($appointment['specilization']) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
                                    <td><?= htmlspecialchars($appointment['patient_phone']) ?></td>
                                    <td><?= date('M j, Y', strtotime($appointment['appointment_date'])) ?></td>
                                    <td><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></td>
                                    <td><?= htmlspecialchars($appointment['appointment_type']) ?></td>
                                    <td><span class="status-badge status-pending">Pending</span></td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="confirm">
                                            <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                            <button type="submit" class="btn btn-success btn-small" onclick="return confirm('Confirm this appointment?')">
                                                Confirm
                                            </button>
                                        </form>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="reject">
                                            <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('Reject this appointment?')">
                                                Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; color: #666; padding: 40px;">No pending appointments</p>
                <?php endif; ?>
            </div>

            <!-- Confirmed Appointments -->
            <div id="confirmed" class="tab-content">
                <h2>Confirmed Appointments</h2>
                <?php if (count($confirmedAppointments) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Patient</th>
                                <th>Phone</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($confirmedAppointments as $appointment): ?>
                                <tr>
                                    <td>
                                        <strong>Dr. <?= htmlspecialchars($appointment['doctor_name']) ?></strong><br>
                                        <small><?= htmlspecialchars($appointment['specilization']) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
                                    <td><?= htmlspecialchars($appointment['patient_phone']) ?></td>
                                    <td><?= date('M j, Y', strtotime($appointment['appointment_date'])) ?></td>
                                    <td><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></td>
                                    <td><?= htmlspecialchars($appointment['appointment_type']) ?></td>
                                    <td><span class="status-badge status-confirmed">Confirmed</span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; color: #666; padding: 40px;">No confirmed appointments</p>
                <?php endif; ?>
            </div>

            <!-- Cancelled Appointments -->
            <div id="cancelled" class="tab-content">
                <h2>Cancelled Appointments</h2>
                <?php if (count($cancelledAppointments) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Patient</th>
                                <th>Phone</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cancelledAppointments as $appointment): ?>
                                <tr>
                                    <td>
                                        <strong>Dr. <?= htmlspecialchars($appointment['doctor_name']) ?></strong><br>
                                        <small><?= htmlspecialchars($appointment['specilization']) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
                                    <td><?= htmlspecialchars($appointment['patient_phone']) ?></td>
                                    <td><?= date('M j, Y', strtotime($appointment['appointment_date'])) ?></td>
                                    <td><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></td>
                                    <td><?= htmlspecialchars($appointment['appointment_type']) ?></td>
                                    <td><span class="status-badge status-cancelled">Cancelled</span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; color: #666; padding: 40px;">No cancelled appointments</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab content
            document.getElementById(tabName).classList.add('active');

            // Add active class to clicked tab
            event.target.classList.add('active');
        }

        // Auto-refresh every 30 seconds to check for new appointments
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>

</html>