<?php
session_start();

// 🔐 Auth guard
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "medicore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate prescription ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    die("Invalid request.");
}

$prescription_id = (int) $_GET['id'];
$patient_id      = (int) $_SESSION['patient_id'];

// Fetch prescription record (only for this patient)
$sql  = "SELECT image FROM prescriptions WHERE prescription_id = ? AND patient_id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $prescription_id, $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    http_response_code(404);
    die("Prescription not found.");
}

$row       = $result->fetch_assoc();
$filename  = $row['image'];

// Upload directory (adjust if needed)
$UPLOAD_DIR = realpath(__DIR__ . '/../doctor_part/uploads/prescriptions');
$filepath   = $UPLOAD_DIR . DIRECTORY_SEPARATOR . $filename;

// Validate file
if (!$filename || !is_file($filepath)) {
    http_response_code(404);
    die("File not found.");
}

// Allow only safe file types
$allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

if (!in_array($ext, $allowed_ext, true)) {
    http_response_code(400);
    die("Invalid file type.");
}

// Set MIME type
$mime = [
    'pdf'  => 'application/pdf',
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
][$ext] ?? 'application/octet-stream';

// Send headers
if (ob_get_level()) ob_end_clean();

header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
header('Content-Length: ' . filesize($filepath));
header('Cache-Control: private, must-revalidate, max-age=0');
header('Pragma: public');
header('Expires: 0');

// Output file
readfile($filepath);
exit();
