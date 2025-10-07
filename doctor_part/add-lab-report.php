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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $conn->real_escape_string($_POST['patient_id']);
    $patient = $conn->real_escape_string($_POST['patient_name']);
    $doctor_name = $conn->real_escape_string($_POST['doctor_name']);
    $type = $conn->real_escape_string($_POST['report_type']);
    $date = $_POST['report_date'];

    // Handle file upload
    $filePath = '';
    if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['report_file']['tmp_name'];
        $fileName = basename($_FILES['report_file']['name']);
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true); // create folder if not exists
        }
        $filePath = $targetDir . time() . "_" . $fileName;
        move_uploaded_file($fileTmp, $filePath);
    }

    $conn->query("INSERT INTO lab_reports (patient_id,doctor_id, patient_name,doctor_name, report_type, report_date, report_file)
                  VALUES ('$patient_id','$doctor_id', '$patient','$doctor_name', '$type', '$date', '$filePath')");

    header("Location: lab-reports.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Lab Report</title>
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
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
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
            transition: 0.3s;
        }

        input[type="text"]:focus,
        input[type="date"]:focus {
            border-color: #3498db;
            outline: none;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            margin-bottom: 10px;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .btn-secondary {
            background-color: #95a5a6;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        .back-link {
            text-align: center;
        }

        @media (max-width: 500px) {
            .form-container {
                padding: 30px 20px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Add New Lab Report</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="patient_id">Patient ID:</label>
            <input type="text" name="patient_id" id="patient_id" required>

            <label for="patient_name">Patient Name:</label>
            <input type="text" name="patient_name" id="patient_name" required>

            <label for="doctor_name">Doctor Name:</label>
            <input type="text" name="doctor_name" id="doctor_name" required>

            <label for="report_type">Report Type:</label>
            <input type="text" name="report_type" id="report_type" required>

            <label for="report_date">Report Date:</label>
            <input type="date" name="report_date" id="report_date" required>

            <label for="report_file">Upload Report (PDF or Image):</label>
            <input type="file" name="report_file" id="report_file" accept=".pdf,.jpg,.jpeg,.png,.gif" required>

            <button type="submit" class="btn">Add Report</button>
        </form>

        <div class="back-link">
            <a href="lab-reports.php"><button class="btn btn-secondary">Back</button></a>
        </div>
    </div>
</body>

</html>