<?php
session_start();
if (!isset($_SESSION['doctor'])) {
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['doctor']['id'];
$conn = new mysqli("localhost", "root", "", "medicore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$report_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch report to edit
$result = $conn->query("SELECT * FROM lab_reports WHERE id = $report_id AND doctor_id = $doctor_id");
if ($result->num_rows === 0) {
    echo "Report not found.";
    exit();
}
$report = $result->fetch_assoc();

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $patient_id = $conn->real_escape_string($_POST['patient_id']);
    $patient_name = $conn->real_escape_string($_POST['patient_name']);
    $doctor_name = $conn->real_escape_string($_POST['doctor_name']);
    $report_type = $conn->real_escape_string($_POST['report_type']);
    $report_date = $conn->real_escape_string($_POST['report_date']);

    $updateFileSQL = "";

    if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['report_file']['tmp_name'];
        $fileName = $_FILES['report_file']['name'];
        $fileSize = $_FILES['report_file']['size'];
        $fileType = $_FILES['report_file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedExtensions = ['pdf', 'docx', 'jpg', 'png'];
        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = uniqid("report_", true) . '.' . $fileExtension;
            $uploadFileDir = 'uploads/reports/';
            $dest_path = $uploadFileDir . $newFileName;

            // Create directory if not exists
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Optional: delete old file
                if (!empty($report['report_file']) && file_exists($report['report_file'])) {
                    unlink($report['report_file']);
                }

                $updateFileSQL = ", report_file = '$dest_path'";
            }
        }
    }

    $conn->query("UPDATE lab_reports SET 
        patient_id = '$patient_id',
        patient_name = '$patient_name',
        doctor_name = '$doctor_name',
        report_type = '$report_type',
        report_date = '$report_date'
        $updateFileSQL
        WHERE id = $report_id AND doctor_id = $doctor_id");

    header("Location: lab-reports.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Lab Report</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #34495e;
        }

        input[type="text"],
        input[type="date"],
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="file"]:focus {
            border-color: #3498db;
            outline: none;
        }

        .btn {
            display: inline-block;
            width: 48%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            text-align: center;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .btn-cancel {
            background-color: #95a5a6;
        }

        .btn-cancel:hover {
            background-color: #7f8c8d;
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        @media (max-width: 500px) {
            .btn-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Edit Lab Report</h2>
        <form method="post" enctype="multipart/form-data">

            <label>Patient ID:</label>
            <input type="text" name="patient_id" value="<?= htmlspecialchars($report['patient_id']) ?>" required>

            <label>Patient Name:</label>
            <input type="text" name="patient_name" value="<?= htmlspecialchars($report['patient_name']) ?>" required>

            <label>Doctor Name:</label>
            <input type="text" name="doctor_name" value="<?= htmlspecialchars($report['doctor_name']) ?>" required>

            <label>Report Type:</label>
            <input type="text" name="report_type" value="<?= htmlspecialchars($report['report_type']) ?>" required>

            <label>Report Date:</label>
            <input type="date" name="report_date" value="<?= htmlspecialchars($report['report_date']) ?>" required>

            <label>Upload New Report File:</label>
            <input type="file" name="report_file">

            <div class="btn-group">
                <button type="submit" class="btn">Update</button>
                <a href="lab-reports.php" class="btn btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>