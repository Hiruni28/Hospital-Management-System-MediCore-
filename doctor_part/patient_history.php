<?php
session_start();

// Check if doctor is logged in
if (!isset($_SESSION['doctor'])) {
    header("Location: login.php");
    exit();
}

$doctor_id = (int) $_SESSION['doctor']['id']; // force integer

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medicore";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search term
$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM patient_history 
                            WHERE doctor_id = ? 
                            AND (patient_name LIKE ? OR diagnosis LIKE ?)
                            ORDER BY visit_date DESC");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $likeSearch = "%$search%";
    $stmt->bind_param("iss", $doctor_id, $likeSearch, $likeSearch);
} else {
    $stmt = $conn->prepare("SELECT * FROM patient_history 
                            WHERE doctor_id = ?
                            ORDER BY visit_date DESC");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("i", $doctor_id);
}

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient History</title>
    <link rel="icon" href="../images/doctor_portal.png" type="image/x-icon">
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            padding: 20px;
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            min-height: calc(100vh - 40px);
        }

        /* Header */
        h2 {
            color: #2c3e50;
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(45deg, #3498db, #2980b9);
            border-radius: 2px;
        }

        /* Top bar */
        .top-bar {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .top-bar a {
            text-decoration: none;
        }

        .top-bar button {
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .top-bar button:hover {
            background: linear-gradient(45deg, #2980b9, #1f5f99);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }

        .top-bar button:active {
            transform: translateY(0);
        }

        /* Search box */
        .search-box {
            margin-bottom: 30px;
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }

        .search-box form {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-box input[type="text"] {
            flex: 1;
            min-width: 300px;
            padding: 12px 16px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .search-box input[type="text"]:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .search-box button {
            background: linear-gradient(45deg, #27ae60, #2ecc71);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        }

        .search-box button:hover {
            background: linear-gradient(45deg, #229954, #27ae60);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        }

        /* Table styles */
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background: linear-gradient(45deg, #34495e, #2c3e50);
            color: white;
            padding: 16px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        td {
            padding: 14px 12px;
            border-bottom: 1px solid #f1f1f1;
            font-size: 14px;
            transition: background-color 0.2s ease;
        }

        tr:hover td {
            background-color: #f8f9fa;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        tr:nth-child(even):hover {
            background-color: #f0f0f0;
        }

        /* Specific column styling */
        td:first-child, th:first-child {
            font-weight: 600;
            color: #2c3e50;
        }

        td:nth-child(4), td:nth-child(5) { /* Diagnosis and Treatment */
            max-width: 200px;
            word-wrap: break-word;
        }

        td:nth-child(8) { /* Notes */
            max-width: 250px;
            word-wrap: break-word;
        }

        /* No records message */
        .no-records {
            text-align: center;
            color: #7f8c8d;
            font-size: 18px;
            margin-top: 50px;
            padding: 40px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px dashed #bdc3c7;
        }

        /* Results info */
        .results-info {
            margin-bottom: 15px;
            color: #666;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .results-count {
            background: #e8f4fd;
            padding: 6px 12px;
            border-radius: 20px;
            border: 1px solid #bee5eb;
        }

        /* Clear search button */
        .clear-search {
            background: #6c757d !important;
            margin-left: 10px;
            padding: 8px 16px !important;
            font-size: 12px !important;
        }

        .clear-search:hover {
            background: #545b62 !important;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .container {
                padding: 20px;
                margin: 0;
                border-radius: 0;
                min-height: 100vh;
            }
            
            h2 {
                font-size: 1.8rem;
            }
            
            .top-bar {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box input[type="text"] {
                min-width: 100%;
            }
            
            .table-container {
                margin: 0 -20px;
                border-radius: 0;
            }
            
            table {
                font-size: 12px;
            }
            
            th, td {
                padding: 8px 6px;
            }
            
            th {
                font-size: 11px;
            }

            .results-info {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            table {
                font-size: 10px;
            }
            
            th, td {
                padding: 6px 4px;
            }
            
            .search-box {
                padding: 15px;
            }
        }

        /* Accessibility improvements */
        button:focus,
        input:focus {
            outline: 2px solid #3498db;
            outline-offset: 2px;
        }

        /* Print styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .container {
                box-shadow: none;
                padding: 0;
            }
            
            .top-bar,
            .search-box {
                display: none;
            }
            
            table {
                border: 1px solid #000;
            }
            
            th, td {
                border: 1px solid #000;
                padding: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Patient History</h2>

        <div class="top-bar">
            <a href="newdoctor-dashboard.php">
                <button>← Back to Dashboard</button>
            </a>
        </div>

        <div class="search-box">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by patient name or diagnosis..." value="<?= htmlspecialchars($search) ?>" />
                <button type="submit">🔍 Search</button>
                <?php if ($search !== ''): ?>
                    <a href="?" style="text-decoration: none;">
                        <button type="button" class="clear-search">✕ Clear</button>
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
            <div class="results-info">
                <span class="results-count">
                    <?= $result->num_rows ?> record(s) found
                    <?php if ($search !== ''): ?>
                        for "<?= htmlspecialchars($search) ?>"
                    <?php endif; ?>
                </span>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>History ID</th>
                            <th>Patient ID</th>
                            <th>Patient Name</th>
                            <th>Diagnosis</th>
                            <th>Treatment</th>
                            <th>Doctor</th>
                            <th>Visit Date</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['history_id']) ?></td>
                                <td><?= htmlspecialchars($row['patient_id']) ?></td>
                                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                                <td><?= htmlspecialchars($row['diagnosis']) ?></td>
                                <td><?= htmlspecialchars($row['treatment']) ?></td>
                                <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                                <td><?= htmlspecialchars($row['visit_date']) ?></td>
                                <td><?= htmlspecialchars($row['notes']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-records">
                <?php if ($search !== ''): ?>
                    No patient history records found for "<?= htmlspecialchars($search) ?>".
                    <br><br>
                    <a href="?" style="text-decoration: none;">
                        <button style="background: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                            Show All Records
                        </button>
                    </a>
                <?php else: ?>
                    No patient history records found.
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>