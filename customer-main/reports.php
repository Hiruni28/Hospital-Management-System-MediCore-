<?php
// customer-main/reports.php
session_start();
include('../config/constant.php'); // $conn

// Check patient login
if (!isset($_SESSION['patient_id'])) {
    header("Location: ../login.php");
    exit;
}

$patient_id = (int)$_SESSION['patient_id'];

// Fetch reports for this patient
$sql = "SELECT id, report_type, report_date, report_file FROM lab_reports WHERE patient_id=? ORDER BY report_date DESC";
$stmt = $conn->prepare($sql);
if (!$stmt) die("DB error: ".$conn->error);

$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// Helper for safe output
function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MediCore - Report</title>
    <link rel="stylesheet" href="profile.css" />
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body { 
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    background: linear-gradient(135deg, #adb9ebff 0%, #d3bbebff 100%);
    color: #2c3e50;
    min-height: 100vh;
    padding: 20px;
}

.wrap { 
    max-width: 1000px; 
    margin: 0 auto; 
}

.header {
    text-align: center;
    margin-bottom: 30px;
    color: white;
}

.header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 10px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.header p {
    font-size: 1.1rem;
    opacity: 0.9;
}

.card { 
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.2);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f1f5f9;
}

.card-header h2 {
    font-size: 1.8rem;
    font-weight: 600;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header h2::before {
    content: "📋";
    font-size: 1.5rem;
}

table { 
    width: 100%; 
    border-collapse: collapse; 
    margin-top: 20px;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

th, td { 
    padding: 16px 20px; 
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

th { 
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

tr:hover {
    background: #f8fafc;
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

tr:last-child td {
    border-bottom: none;
}

.btn-download { 
    padding: 8px 16px; 
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white; 
    text-decoration: none; 
    border-radius: 8px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
}

.btn-download:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.btn-download::before {
    content: "⬇️";
}

.btn-back {
    padding: 10px 20px;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    text-decoration: none;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
}

.btn-back:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

.no-reports {
    text-align: center;
    padding: 60px 20px;
    color: #64748b;
}

.no-reports::before {
    content: "📄";
    font-size: 4rem;
    display: block;
    margin-bottom: 20px;
    opacity: 0.5;
}

.no-reports p {
    font-size: 1.2rem;
    font-weight: 500;
}

.report-type {
    font-weight: 600;
    color: #4f46e5;
}

.report-date {
    color: #6b7280;
    font-weight: 500;
}

.report-id {
    background: #e0f2fe;
    color: #0277bd;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .wrap {
        padding: 0 10px;
    }
    
    .card {
        padding: 20px;
        border-radius: 15px;
    }
    
    .card-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    table {
        font-size: 0.9rem;
    }
    
    th, td {
        padding: 12px 10px;
    }
    
    .header h1 {
        font-size: 2rem;
    }
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
            <a href="appointments.php" class="nav-item">My Appointments</a>
            <a href="memberships.php" class="nav-item">My Memberships</a>
            <a href="patient_prescription.php" class="nav-item">Prescriptions</a>
            <a href="reports.php" class="nav-item active">Reports</a>
        </div>

        <a href="http://localhost/medicore/index.php" class="back-btn">Back</a>
    </div>

<div class="header">
    <h1>Reports</h1>
    <p>Access and download your medical test results</p>
</div>

<div class="wrap">
    <div class="card">
        <div class="card-header">
            <h2>My Reports</h2>
            <a href="profile.php" class="btn-back">⬅ Back to Profile</a>
        </div>
        
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Report Type</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1; while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><span class="report-id"><?php echo $i++; ?></span></td>
                        <td><span class="report-type"><?php echo e($row['report_type']); ?></span></td>
                        <td><span class="report-date"><?php echo $row['report_date'] ? e(date("d M Y", strtotime($row['report_date']))) : '-'; ?></span></td>
                        <td>
                            <a class="btn-download" href="download_report.php?id=<?php echo e($row['id']); ?>">Download</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-reports">
                <p>No reports available yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>