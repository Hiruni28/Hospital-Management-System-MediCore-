<?php
session_start();
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/constant.php';
require_once __DIR__ . '/classes/Patient.php';

// Patient object
$patient = new Patient($conn);

// Load patient data
$patient_data = $patient->getPatientById($_SESSION['patient_id']);
if (!$patient_data) {
    die("Patient record not found.");
}

$patient_id = $_SESSION['patient_id'];

// --- Delete appointment ---
if (isset($_GET['delete'])) {
    $appointment_id = intval($_GET['delete']);
    $conn->query("DELETE FROM appointments WHERE appointment_id = $appointment_id AND patient_id = $patient_id");
    header("Location: appointments.php");
    exit();
}

// --- Fetch appointments for this patient ---
$sql = "SELECT * FROM appointments WHERE patient_id = ? ORDER BY appointment_date DESC, appointment_time DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MediCore - Appointments</title>
    <link rel="stylesheet" href="profile.css" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            padding: 40px;
            margin: 0;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 30px;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 20px;
        }
        .top-bar a button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: 0.3s;
        }
        .top-bar a button:hover {
            background-color: #2980b9;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #ecf0f1;
            color: #333;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .btn {
            padding: 8px 12px;
            font-size: 13px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
            transition: 0.2s;
        }
        .btn-view { background-color: #2ecc71; color: white; }
        .btn-update { background-color: #f39c12; color: white; }
        .btn-delete { background-color: #e74c3c; color: white; }
        .btn:hover { opacity: 0.9; }
        @media (max-width: 768px) {
            .top-bar { flex-direction: column; align-items: flex-start; gap: 15px; }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <h2>Dashboard</h2>
        </div>

        <div class="nav-menu">
            <a href="profile.php" class="nav-item ">Profile Details</a>
            <a href="appointments.php" class="nav-item active">My Appointments</a>
            <a href="memberships.php" class="nav-item">My Memberships</a>
            <a href="patient_prescription.php" class="nav-item">Prescriptions</a>
            <a href="reports.php" class="nav-item">Reports</a>
        </div>

        <a href="http://localhost/medicore/index.php" class="back-btn">Back</a>
    </div>

    <table style="margin-left: 250px; width: calc(100% - 270px);">
        <tr>
            <th>ID</th>
            <th>Doctor</th>
            <th>Patient</th>
            <th>Phone</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['appointment_id']) ?></td>
                    <td><?= htmlspecialchars($row['doctor_id']) ?></td>
                    <td><?= htmlspecialchars($row['patient_name']) ?></td>
                    <td><?= htmlspecialchars($row['patient_phone']) ?></td>
                    <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                    <td><?= htmlspecialchars($row['appointment_time']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        
                        <a href="edit-appointment.php?id=<?= $row['appointment_id'] ?>"><button class="btn btn-update">Update</button></a>
                        <a href="?delete=<?= $row['appointment_id'] ?>" onclick="return confirm('Are you sure to delete this appointment?');">
                            <button class="btn btn-delete">Delete</button>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        <?php else: ?>
            <tr><td colspan="9" style="text-align:center; padding:20px;">No appointments found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
