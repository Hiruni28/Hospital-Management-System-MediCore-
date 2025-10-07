<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

// Include DB connection
require_once __DIR__ . '/../config/constant.php';

// Function to get patient data from session or database
function getPatientData($conn, $patient_id) {
    $stmt = $conn->prepare("SELECT email, full_name FROM tbl_patient WHERE patient_id = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        $stmt->close();
    }
    return null;
}

// Function to get patient membership details
function getPatientMemberships($conn, $patient_id, $patient_email = null) {
    // Approach 1: If we have patient email, use it
    if ($patient_email) {
        $stmt = $conn->prepare("
            SELECT id, full_name, email, tel_no, package, message, status, booking_date 
            FROM tbl_membershipbookings 
            WHERE email = ?
            ORDER BY booking_date DESC
        ");
        if ($stmt) {
            $stmt->bind_param("s", $patient_email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                return $result->fetch_all(MYSQLI_ASSOC);
            }
        }
    }

    // Approach 2: Try to match by patient_id (if your membership table has that column)
    $stmt = $conn->prepare("
        SELECT id, full_name, email, tel_no, package, message, status, booking_date 
        FROM tbl_membershipbookings 
        WHERE patient_id = ?
        ORDER BY booking_date DESC
    ");
    if ($stmt) {
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    // Approach 3: fallback — get all memberships
    $stmt = $conn->prepare("
        SELECT id, full_name, email, tel_no, package, message, status, booking_date 
        FROM tbl_membershipbookings 
        ORDER BY booking_date DESC
    ");
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    return [];
}

// Get patient data
$patient_data = getPatientData($conn, $_SESSION['patient_id']);
$patient_email = $patient_data['email'] ?? ($_SESSION['email'] ?? null);

// Get membership bookings
$memberships = getPatientMemberships($conn, $_SESSION['patient_id'], $patient_email);

// If we couldn't match by email/patient_id and got all memberships, mark message
if (!$patient_email && !empty($memberships)) {
    $show_contact_message = true;
}

// Function to get status badge class
function getStatusBadge($status) {
    switch(strtolower($status)) {
        case 'confirmed': return 'status-confirmed';
        case 'pending':   return 'status-pending';
        case 'cancelled': return 'status-cancelled';
        default:          return 'status-pending';
    }
}

// Function to format date
function formatDate($date) {
    return date('M d, Y g:i A', strtotime($date));
}

// Debug information (remove in production)
$debug_info = [
    'patient_id' => $_SESSION['patient_id'],
    'patient_email' => $patient_email,
    'memberships_count' => count($memberships),
    'session_data' => $_SESSION
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title> Memberships details</title>
    <link rel="stylesheet" href="profile.css" />
    <style>
        .memberships-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .memberships-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }

        .memberships-header h1 {
            color: #333;
            margin: 0;
            font-size: 2.5rem;
        }

        .membership-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .memberships-grid {
            display: grid;
            gap: 20px;
        }

        .membership-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-left: 5px solid #667eea;
            transition: all 0.3s ease;
        }

        .membership-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .membership-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .membership-package {
            font-size: 1.4rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .membership-id {
            font-size: 0.9rem;
            color: #666;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .membership-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 3px;
            font-weight: 500;
        }

        .detail-value {
            font-size: 1rem;
            color: #333;
            font-weight: 500;
        }

        .membership-message {
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 3px solid #007bff;
        }

        .message-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .message-text {
            color: #333;
            line-height: 1.5;
        }

        .no-memberships {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .no-memberships-icon {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .no-memberships h3 {
            color: #666;
            margin-bottom: 10px;
        }

        .no-memberships p {
            color: #999;
            margin-bottom: 20px;
        }

        .book-membership-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            display: inline-block;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .book-membership-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .debug-info {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            color: #666;
        }

        .debug-info h4 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            border-left: 4px solid #f39c12;
            background: #fef9e7;
            color: #8a6d3b;
        }

        .alert h4 {
            margin-top: 0;
        }

        @media (max-width: 768px) {
            .membership-header {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .membership-details {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 15px;
            }

            .membership-card {
                padding: 20px;
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
            <a href="profile.php" class="nav-item">Profile Details</a>
            <a href="appointments.php" class="nav-item">My Appointments</a>
            <a href="memberships.php" class="nav-item active">My Memberships</a>
            <a href="patient_prescription.php" class="nav-item">Prescriptions</a>
            <a href="reports.php" class="nav-item">Reports</a>
        </div>

        <a href="http://localhost/medicore/index.php" class="back-btn">Back</a>
    </div>

    <div class="main-content">
        <div class="memberships-container">
            <div class="memberships-header">
                <h1> Memberships</h1>
            </div>

            <!-- Debug Information (Remove in Production) -->
            <?php if (isset($_GET['debug'])): ?>
            <div class="debug-info">
                <h4>Debug Information:</h4>
                <pre><?php echo htmlspecialchars(json_encode($debug_info, JSON_PRETTY_PRINT)); ?></pre>
            </div>
            <?php endif; ?>

            <!-- Alert for database issues -->
            <?php if (!$patient_email && empty($memberships)): ?>
            <div class="alert">
                <h4>Unable to Load Membership Data</h4>
                <p>We're having trouble connecting your account to your membership bookings. This might be due to:</p>
                <ul>
                    <li>Database configuration issues</li>
                    <li>Missing patient information</li>
                    <li>Account setup incomplete</li>
                </ul>
                <p>Please contact the administrator or try logging out and back in.</p>
            </div>
            <?php endif; ?>

            <?php if (!empty($memberships)): ?>
                <!-- Membership Statistics -->
                <div class="membership-stats">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($memberships); ?></div>
                        <div class="stat-label">Total Bookings</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">
                            <?php echo count(array_filter($memberships, function($m) { return strtolower($m['status']) == 'confirmed'; })); ?>
                        </div>
                        <div class="stat-label">Active Memberships</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">
                            <?php echo count(array_filter($memberships, function($m) { return strtolower($m['status']) == 'pending'; })); ?>
                        </div>
                        <div class="stat-label">Pending Approval</div>
                    </div>
                </div>
                <!-- Membership Cards -->
                <div class="memberships-grid">
                    <?php foreach ($memberships as $membership): ?>
                        <div class="membership-card">
                            <div class="membership-header">
                                <div>
                                    <div class="membership-package"><?php echo htmlspecialchars($membership['package']); ?></div>
                                    <div class="membership-id">Booking ID: <?php echo $membership['id']; ?></div>
                                </div>
                                <span class="status-badge <?php echo getStatusBadge($membership['status']); ?>">
                                    <?php echo htmlspecialchars($membership['status']); ?>
                                </span>
                            </div>

                            <div class="membership-details">
                                <div class="detail-item">
                                    <span class="detail-label">Full Name</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($membership['full_name']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Email</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($membership['email']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Phone Number</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($membership['tel_no']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Booking Date</span>
                                    <span class="detail-value"><?php echo formatDate($membership['booking_date']); ?></span>
                                </div>
                            </div>

                            <?php if (!empty($membership['message'])): ?>
                                <div class="membership-message">
                                    <div class="message-label">Additional Message:</div>
                                    <div class="message-text"><?php echo htmlspecialchars($membership['message']); ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <!-- No Memberships Found -->
                <div class="no-memberships">
                    <div class="no-memberships-icon">🏆</div>
                    <h3>No Membership Bookings Found</h3>
                    <p>You haven't booked any memberships yet. Start your membership journey today!</p>
                    <a href="http://localhost/medicore/membership.php" class="book-membership-btn">Book a Membership</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>