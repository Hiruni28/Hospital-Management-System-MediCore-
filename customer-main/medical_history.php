<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

// Include DB & Patient class (adjust paths based on actual structure)
require_once __DIR__ . '/../config/constant.php';
require_once __DIR__ . '/classes/Patient.php';

// Create patient object
$patient = new Patient($conn);

// Load patient data
$patient_data = $patient->getPatientById($_SESSION['patient_id']);
if (!$patient_data) {
    die("Patient record not found.");
}

// Function to get medical history records
function getMedicalHistory($conn, $patient_id) {
    $sql = "SELECT 
                history_id,
                patient_id,
                patient_name,
                diagnosis,
                treatment,
                doctor_id,
                doctor_name,
                visit_date,
                notes
            FROM patient_history 
            WHERE patient_id = ? 
            ORDER BY visit_date DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get medical history data
$medical_history = getMedicalHistory($conn, $_SESSION['patient_id']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title> Medical History</title>
    <link rel="stylesheet" href="profile.css" />
    <style>
        .medical-history-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            margin-top: 30px;
            margin-bottom: 0;
        }

        .section-header h2 {
            margin: 0;
            font-size: 1.4em;
            font-weight: 600;
        }

        .section-content {
            background: white;
            border: 1px solid #e1e5e9;
            border-top: none;
            border-radius: 0 0 8px 8px;
            padding: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .history-table th {
            background: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }

        .history-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: top;
        }

        .history-table tr:hover {
            background-color: #f8f9fa;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-active { background: #d4edda; color: #155724; }
        .status-completed { background: #d1ecf1; color: #0c5460; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-ongoing { background: #ffeaa7; color: #6c5ce7; }
        .status-resolved { background: #00b894; color: white; }

        .no-records {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
            font-style: italic;
        }

        .record-notes {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .date-cell {
            white-space: nowrap;
            font-weight: 500;
        }

        .page-header {
            background: white;
            padding: 20px 30px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .page-header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 2em;
            font-weight: 600;
        }

        .page-header p {
            margin: 5px 0 0 0;
            color: #7f8c8d;
            font-size: 1.1em;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #3498db;
        }

        .stat-card h3 {
            margin: 0 0 10px 0;
            font-size: 2em;
            color: #2c3e50;
            font-weight: 700;
        }

        .stat-card p {
            margin: 0;
            color: #7f8c8d;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <h2>Dashboard</h2>
        </div>

        <div class="nav-menu">
            <a href="profile.php" class="nav-item">Profile Details</a>
            <a href="appointments.php" class="nav-item">My Appointments</a>
            <a href="memberships.php" class="nav-item">My Memberships</a>
            <a href="patient_prescription.php" class="nav-item">Prescriptions</a>
            <a href="reports.php" class="nav-item">Reports</a>
            <a href="medical_history.php" class="nav-item active">Medical History</a>
        </div>

        <a href="http://localhost/medicore/index.php" class="back-btn">Back</a>
    </div>

    <div class="main-content">
        <div class="medical-history-container">
            <div class="page-header">
                <h1>Medical History</h1>
                
            </div>

            <!-- Statistics Cards -->
            <div class="stats-row">
                <div class="stat-card">
                    <h3><?php echo count($medical_history); ?></h3>
                    <p>Medical History Records</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo !empty($medical_history) ? count(array_unique(array_column($medical_history, 'doctor_name'))) : 0; ?></h3>
                    <p>Different Doctors</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo !empty($medical_history) ? date('Y') - date('Y', strtotime(min(array_column($medical_history, 'visit_date')))) + 1 : 0; ?></h3>
                    <p>Years of Records</p>
                </div>
            </div>

            <!-- Medical Conditions Section -->
            <div class="section-header">
                <h2>📋 Medical History & Diagnoses</h2>
            </div>
            <div class="section-content">
                <?php if (!empty($medical_history)): ?>
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>History ID</th>
                                <th>Diagnosis</th>
                                <th>Visit Date</th>
                                <th>Doctor</th>
                                <th>Treatment</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($medical_history as $record): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($record['history_id']); ?></strong></td>
                                    <td><strong><?php echo htmlspecialchars($record['diagnosis']); ?></strong></td>
                                    <td class="date-cell"><?php echo date('M d, Y', strtotime($record['visit_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($record['doctor_name'] ?: 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($record['treatment'] ?: 'N/A'); ?></td>
                                    <td class="record-notes" title="<?php echo htmlspecialchars($record['notes']); ?>">
                                        <?php echo htmlspecialchars($record['notes'] ?: 'No notes'); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-records">
                        <p>No medical history recorded yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>