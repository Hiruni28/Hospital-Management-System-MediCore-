<?php
session_start();

/* =========================
   DB CONNECTION
   ========================= */
$conn = new mysqli("localhost", "root", "", "medicore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* =========================
   AUTH GUARD
   ========================= */
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = (int) $_SESSION['patient_id'];

/* =========================
   PATHS (SERVER + PUBLIC)
   ========================= */
$UPLOAD_DIR = rtrim(__DIR__, '/\\') . 'doctor_part/uploads/prescriptions/';
$PUBLIC_BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . 'doctor_part/uploads/prescriptions/';
if (substr($PUBLIC_BASE, -1) !== '/') $PUBLIC_BASE .= '/';

/* =========================
   DOWNLOAD HANDLER
   ========================= */
if (isset($_GET['download']) && $_GET['download'] !== '') {
    $filename = basename($_GET['download']);
    $filepath = $UPLOAD_DIR . $filename;

    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_ext, true)) {
        http_response_code(400);
        die("Invalid file type.");
    }

    if (!is_file($filepath) || !file_exists($filepath)) {
        http_response_code(404);
        die("File not found.");
    }

    $mime = [
        'pdf'  => 'application/pdf',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
    ][$ext] ?? 'application/octet-stream';

    if (ob_get_level()) ob_end_clean();

    header('Content-Type: ' . $mime);
    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename="' . rawurlencode($filename) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize($filepath));
    header('Cache-Control: private, must-revalidate, max-age=0');
    header('Pragma: public');
    header('Expires: 0');

    readfile($filepath);
    exit();
}

/* =========================
   FETCH PRESCRIPTIONS
   ========================= */
$sql = "SELECT `prescription_id`, `patient_name`, `doctor_name`, `date`, `medicine`, `dosage`, `duration`, `notes`, `image`
        FROM `prescriptions`
        WHERE `patient_id` = ?
        ORDER BY `prescription_id` DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("i", $patient_id);
$stmt->execute();
$prescriptions = $stmt->get_result();

/* Status options for display */
$statusOptions = ['Pending', 'Completed', 'Cancelled'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MediCore - My Prescriptions</title>
    <link rel="stylesheet" href="profile.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f9f9f9;
            margin: 0;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .top-bar {
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-end;
        }

        .top-bar a button {
            background-color: #3498db;
            color: #fff;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .top-bar a button:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #e8f4f8;
        }

        .image-cell {
            text-align: center;
        }

        .image-cell img {
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            object-fit: cover;
            width: 60px;
            height: 60px;
            display: block;
            margin: 0 auto;
        }

        .download-link {
            display: inline-block;
            margin-top: 6px;
            color: #3498db;
            text-decoration: none;
            font-size: 12px;
        }

        .download-link:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
        }

        .badge-pdf {
            width: 60px;
            height: 60px;
            background: #3498db;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 12px;
            border-radius: 4px;
            margin: 0 auto;
        }

        .file-missing {
            color: #e74c3c;
            font-size: 12px;
        }

        .file-none {
            color: #7f8c8d;
            font-size: 12px;
        }

        .status {
            font-weight: bold;
            padding: 6px 12px;
            border-radius: 20px;
            color: #fff;
            font-size: 12px;
            text-transform: uppercase;
            display: inline-block;
        }

        .status.Pending {
            background-color: #f39c12;
        }

        .status.Completed {
            background-color: #27ae60;
        }

        .status.Cancelled {
            background-color: #e74c3c;
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
            <a href="appointments.php" class="nav-item ">My Appointments</a>
            <a href="memberships.php" class="nav-item">My Memberships</a>
            <a href="patient_prescription.php" class="nav-item active">Prescriptions</a>
            <a href="reports.php" class="nav-item">Reports</a>
        </div>

        <a href="http://localhost/medicore/index.php" class="back-btn">Back</a>
    </div>

    <div class="container" style="margin-left: 270px;">
        <h2>My Prescriptions</h2>


        <?php if ($prescriptions && $prescriptions->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Medicine</th>
                        <th>Dosage</th>
                        <th>Duration</th>
                        <th>Notes</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $prescriptions->fetch_assoc()): ?>
                        <?php $status = $statusOptions[array_rand($statusOptions)]; ?>
                        <tr>
                            <td><?= htmlspecialchars($row['prescription_id']) ?></td>
                            <td><?= htmlspecialchars($row['patient_name']) ?></td>
                            <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                            <td><?= htmlspecialchars($row['date']) ?></td>
                            <td><?= htmlspecialchars($row['medicine']) ?></td>
                            <td><?= htmlspecialchars($row['dosage']) ?></td>
                            <td><?= htmlspecialchars($row['duration']) ?></td>
                            <td><?= htmlspecialchars($row['notes']) ?></td>
                            <td>
                                <a class="btn-download" href="download_prescription.php?id=<?= htmlspecialchars($row['prescription_id']) ?>"style="color: #0510a8ff;">Download</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <p>No prescriptions found.</p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
<?php
if (isset($stmt) && $stmt instanceof mysqli_stmt) {
    $stmt->close();
}
$conn->close();
