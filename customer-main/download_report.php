<?php
// customer-main/download_report.php

session_start();
include('../config/constant.php'); // Database connection ($conn)

// Check if patient is logged in
if (!isset($_SESSION['patient_id'])) {
    die("Unauthorized access.");
}
$patient_id = (int)$_SESSION['patient_id'];

// Validate report ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid report ID.");
}
$report_id = intval($_GET['id']);

// Fetch the report record for this patient
$sql = "SELECT report_file FROM lab_reports WHERE id = ? AND patient_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param("ii", $report_id, $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// Check if report exists
if ($result->num_rows === 0) {
    die("Report not found or access denied.");
}

$report = $result->fetch_assoc();
$file_name = basename($report['report_file']); // prevent directory traversal

// Build absolute file path inside doctor_part/uploads/reports
$file_path = realpath(__DIR__ . '/../doctor_part/uploads/reports/' . $file_name);

// Check if file exists
if (!$file_path || !file_exists($file_path)) {
    die("File not found on server: " . htmlspecialchars($file_name));
}

// Force file download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $file_name . '"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($file_path));

readfile($file_path);
exit;
?>
