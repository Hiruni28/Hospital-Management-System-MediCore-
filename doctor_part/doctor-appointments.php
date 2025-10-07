<?php
session_start();

// Check login
if (!isset($_SESSION['doctor'])) {
    header("Location: login.php");
    exit();
}

$doctor = $_SESSION['doctor'];

// Database connection
$conn = new mysqli("localhost", "root", "", "medicore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get selected date (default = today)
$today = date("Y-m-d");
$selected_date = isset($_GET['date']) && $_GET['date'] !== '' ? $_GET['date'] : $today;

// Query: Only confirmed appointments for this doctor on selected date
$query = "SELECT * FROM appointments 
          WHERE doctor_id = ? AND status = 'confirmed' AND appointment_date = ?
          ORDER BY appointment_time ASC";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("SQL Prepare Error: " . $conn->error);
}

$stmt->bind_param("is", $doctor['id'], $selected_date);
$stmt->execute();
$result = $stmt->get_result();
$appointments = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Approved Appointments</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 40px;
            background-color: #f9f9fb;
            color: #333;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .top-bar {
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }


        .top-bar a button {
            background-color: #3498db;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;

        }

        .top-bar a button:hover {
            background-color: #2980b9;
        }

        .date-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .date-form input[type="date"] {
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .date-form button {
            background-color: #2ecc71;
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .date-form button:hover {
            background-color: #27ae60;
        }

        .debug-info {
            background-color: #eef5fb;
            padding: 12px 16px;
            border-left: 4px solid #3498db;
            border-radius: 5px;
            font-size: 14px;
            margin-bottom: 25px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 14px 16px;
            border-bottom: 1px solid #e1e1e1;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f4f6f8;
        }

        tr:hover {
            background-color: #eef3f7;
        }

        .error {
            background-color: #ffe5e5;
            color: #c0392b;
            padding: 16px;
            border-radius: 6px;
            border-left: 4px solid #e74c3c;
            font-size: 14px;
            margin-top: 20px;
        }

        .error ul {
            margin: 10px 0 0 20px;
        }

        .error p {
            margin: 0;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h2>Approved Appointments</h2>
    <div class="top-bar">
        <a href="newdoctor-dashboard.php">
            <button>← Back to Dashboard</button>
        </a>

        <form method="get" class="date-form">
            <label for="date">Select Date:</label>
            <input type="date" name="date" id="date" value="<?= htmlspecialchars($selected_date) ?>">
            <button type="submit">View</button>
        </form>
    </div>

    <?php if (count($appointments) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient ID</th>
                    <th>Patient Name</th>
                    <th>Contact</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $a): ?>
                    <tr>
                        <td><?= htmlspecialchars($a['id'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($a['patient_id'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($a['patient_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($a['patient_phone'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($a['appointment_date'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($a['appointment_time'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($a['status'] ?? 'N/A') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="error">
            <p>No approved appointments found for <?= htmlspecialchars($selected_date) ?>.</p>
        </div>
    <?php endif; ?>
</body>
</html>
