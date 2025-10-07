<?php
session_start();
if (!isset($_SESSION['doctor'])) {
    header("Location: login.php");
    exit();
}

$doctor = $_SESSION['doctor'];
$doctor_id = $doctor['id'];

$report_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($report_id === 0) {
    echo "❌ Invalid report selected.";
    exit();
}

// DB Connection
$conn = new mysqli("localhost", "root", "", "medicore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get report by ID and doctor
$sql = "SELECT * FROM lab_reports WHERE id = ? AND doctor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $report_id, $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$report = $result->fetch_assoc();

if (!$report) {
    echo "❌ Report not found or you don't have permission to view it.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Lab Report</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            padding: 40px;
        }

        .container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .report-detail {
            margin-bottom: 20px;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .file-display {
            margin-top: 20px;
            text-align: center;
        }

        img, iframe {
            max-width: 100%;
            max-height: 400px;
        }

        .btn-back {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 16px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .btn-back:hover {
            background: #2980b9;
        }

        .download-link {
            display: block;
            margin-top: 10px;
            color: #27ae60;
            text-decoration: none;
        }

        .download-link:hover {
            text-decoration: underline;
        }

        .message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Lab Report Details</h2>
    <div class="report-detail"><span class="label">Patient ID:</span> <?= htmlspecialchars($report['patient_id']) ?></div>
    <div class="report-detail"><span class="label">Patient:</span> <?= htmlspecialchars($report['patient_name']) ?></div>
    <div class="report-detail"><span class="label">Doctor:</span> <?= htmlspecialchars($report['doctor_name']) ?></div>
    <div class="report-detail"><span class="label">Report Type:</span> <?= htmlspecialchars($report['report_type']) ?></div>
    <div class="report-detail"><span class="label">Report Date:</span> <?= htmlspecialchars($report['report_date']) ?></div>

    <div class="file-display">
        <?php
        $filePath = $report['report_file'];
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $fullPath = __DIR__ . '/' . $filePath;
        ?>

        <?php if (!empty($filePath) && file_exists($fullPath)): ?>
            <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                <img src="<?= $filePath ?>" alt="Report Image">
            <?php elseif ($ext === 'pdf'): ?>
                <iframe src="<?= $filePath ?>"></iframe>
            <?php else: ?>
                <p class="message">Unsupported file format (<?= htmlspecialchars($ext) ?>)</p>
            <?php endif; ?>

            <a href="<?= $filePath ?>" download class="download-link">⬇ Download Report</a>
        <?php else: ?>
            <p class="message">No file uploaded or file missing.</p>
        <?php endif; ?>
    </div>

    <a href="lab-reports.php" class="btn-back">← Back to All Reports</a>
</div>

</body>
</html>
