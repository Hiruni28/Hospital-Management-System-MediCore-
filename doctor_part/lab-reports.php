<?php
session_start();
if (!isset($_SESSION['doctor'])) {
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['doctor']['id'];

// Database connection
$conn = new mysqli("localhost", "root", "", "medicore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$safeSearch = $conn->real_escape_string($search);

// Delete report
if (isset($_GET['delete'])) {
    $reportId = intval($_GET['delete']);
    $conn->query("DELETE FROM lab_reports WHERE id = $reportId AND doctor_id = $doctor_id");
    header("Location: lab-reports.php");
    exit();
}

// Fetch reports
$sql = "SELECT * FROM lab_reports WHERE doctor_id = '$doctor_id'";
if (!empty($safeSearch)) {
    $sql .= " AND patient_id LIKE '%$safeSearch%'";
}
$sql .= " ORDER BY report_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lab Reports</title>
    <link rel="icon" href="../images/doctor_portal.png" type="image/x-icon">
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

        .search-bar {
            display: flex;
            gap: 10px;
            margin: 10px 0;
        }

        .search-bar input[type="text"] {
            padding: 8px;
            width: 240px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .search-bar button {
            padding: 8px 14px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .search-bar button:hover {
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

        .btn-view {
            background-color: #2ecc71;
            color: white;
        }

        .btn-update {
            background-color: #f39c12;
            color: white;
        }

        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .top-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .search-bar {
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>
</head>
<body>
    <h2>Welcome, Dr. <?= htmlspecialchars($_SESSION['doctor']['doctorName']) ?></h2>

    <div class="top-bar">
        <a href="newdoctor-dashboard.php">
            <button>← Back to Dashboard</button>
        </a>

        <form class="search-bar" method="get">
            <input type="text" name="search" placeholder="Search by patient ID..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>

        <a href="add-lab-report.php">
            <button>+ Add New Report</button>
        </a>
    </div>

    <table>
        <tr>
            <th>Patient ID</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Report Type</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                 <td><?= htmlspecialchars($row['patient_id']) ?></td>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                 <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                <td><?= htmlspecialchars($row['report_type']) ?></td>
                <td><?= htmlspecialchars($row['report_date']) ?></td>
                <td>
                    <a href="view-lab-report.php?id=<?= $row['id'] ?>"><button class="btn btn-view">View</button></a>
                    <a href="edit-lab-report.php?id=<?= $row['id'] ?>"><button class="btn btn-update">Update</button></a>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure to delete?');">
                        <button class="btn btn-delete">Delete</button>
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
