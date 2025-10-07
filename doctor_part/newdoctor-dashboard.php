<?php
session_start();
if (!isset($_SESSION['doctor'])) {
    header("Location: login.php");
    exit();
}

$doctor = $_SESSION['doctor'];

// Database connection
$conn = new mysqli("localhost", "root", "", "medicore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle profile image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $uploadDir = __DIR__ . '/uploads/doctors/'; // ✅ FIXED
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $file = $_FILES['profile_image'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024;

    if ($file['error'] === UPLOAD_ERR_OK) {
        if (in_array($file['type'], $allowedTypes)) {
            if ($file['size'] <= $maxSize) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newFileName = 'doctor_' . $doctor['id'] . '_' . time() . '.' . $ext;
                $destination = $uploadDir . $newFileName;

                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $stmt = $conn->prepare("UPDATE doctors SET profile_image = ? WHERE id = ?");
                    if ($stmt) {
                        $stmt->bind_param("si", $newFileName, $doctor['id']);
                        $stmt->execute();
                        $stmt->close();

                        $_SESSION['doctor']['profile_image'] = $newFileName;
                        $doctor['profile_image'] = $newFileName;

                        $uploadSuccess = "Profile image updated successfully.";
                    }
                } else {
                    $uploadError = "Failed to move uploaded file.";
                }
            } else {
                $uploadError = "File size must be 2MB or less.";
            }
        } else {
            $uploadError = "Only JPG, PNG, and GIF files are allowed.";
        }
    } else {
        $uploadError = "Error uploading file.";
    }
}

// Fetch today's confirmed appointments
$today = date("Y-m-d");

$appointmentsQuery = "SELECT * FROM appointments 
                     WHERE doctor_id = ? 
                     AND appointment_date >= ? 
                     AND status = 'confirmed'
                     ORDER BY appointment_date ASC, appointment_time ASC";

$stmt = $conn->prepare($appointmentsQuery);
$stmt->bind_param("is", $doctor['id'], $today);
$stmt->execute();
$result = $stmt->get_result();
$appointments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Doctor Dashboard</title>
  <link rel="icon" href="../images/doctor_portal.png" type="image/x-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    * {
      margin: 0; padding: 0; box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }
    body {
      display: flex;
      min-height: 100vh;
      background: #f5f7fb;
    }

    /* Sidebar */
    .sidebar {
      width: 260px;
      background: linear-gradient(180deg, #3a7d60, #2a5c45);
      color: #fff;
      display: flex;
      flex-direction: column;
      padding: 25px 20px;
    }
    .logo {
      text-align: center;
      margin-bottom: 30px;
    }
    .logo h2 {
      font-size: 22px;
      font-weight: 700;
      letter-spacing: 1px;
    }
    .nav-menu {
      list-style: none;
    }
    .nav-item {
      margin-bottom: 18px;
    }
    .nav-link {
      text-decoration: none;
      color: #e0e0e0;
      font-size: 15px;
      padding: 12px 15px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: 0.3s;
    }
    .nav-link:hover,
    .nav-link.active {
      background: rgba(255,255,255,0.15);
      color: #fff;
    }

    /* Header */
    .header {
      background: linear-gradient(135deg, #3d6db5, #6fb89c);
      padding: 20px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #fff;
    }
    .welcome {
      font-size: 20px;
      font-weight: 600;
    }
    .user-profile {
      display: flex; align-items: center; gap: 12px;
    }
    .profile-img {
      width: 48px; height: 48px; border-radius: 50%;
      background: #fff; color: #333;
      display: flex; align-items: center; justify-content: center;
      font-weight: bold;
    }

    /* Main */
    .main-content {
      flex: 1;
      padding: 30px;
    }

    /* Cards */
    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
      gap: 20px;
    }
    .card {
      background: #fff;
      padding: 25px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transition: 0.3s;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .card h3 {
      font-size: 16px;
      margin-bottom: 8px;
      color: #666;
    }
    .card p {
      font-size: 22px;
      font-weight: 700;
      color: #2a5c45;
    }

    /* Table */
    .data-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    }
    .data-table th, .data-table td {
      padding: 14px 18px;
      border-bottom: 1px solid #eee;
      font-size: 14px;
      text-align: left;
    }
    .data-table th {
      background: #f0f4f8;
      font-weight: 600;
    }
    .status-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 500;
    }
    .status-confirmed { background: #e8f5e8; color: #2e7d32; }
    .status-pending { background: #fff3e0; color: #f57c00; }
    .status-cancelled { background: #ffebee; color: #c62828; }

    /* Logout Button */
    .logout {
      margin-top: 25px;
      text-align: center;
    }
    .logout button {
      background: #c62828;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }
    .logout button:hover {
      background: #b71c1c;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="logo"><h2>Doctor Panel</h2></div>
    <ul class="nav-menu">
      <li class="nav-item"><a href="doctor-appointments.php" class="nav-link">👥 My Patients</a></li>
      <li class="nav-item"><a href="prescriptions.php" class="nav-link">💊 Prescriptions</a></li>
      <li class="nav-item"><a href="lab-reports.php" class="nav-link">🧪 Reports</a></li>
    </ul>
  </aside>

  <!-- Main Section -->
  <div style="flex:1; display:flex; flex-direction:column;">
    <!-- Header -->
    <header class="header">
      <div class="welcome">Welcome, Dr. <?= htmlspecialchars($doctor['username']) ?> 👋</div>
      <div class="user-profile">
        <?php
        if (!empty($doctor['profile_image'])) {
          echo '<img src="uploads/doctors/'.htmlspecialchars($doctor['profile_image']).'" class="profile-img" style="object-fit:cover;">';
        } else {
          echo '<div class="profile-img">'.strtoupper(substr($doctor['username'],0,2)).'</div>';
        }
        ?>
        <span><?= htmlspecialchars($doctor['specilization']) ?></span>
      </div>
    </header>

    <main class="main-content">
            <!-- About MediCore Hospital Section -->
            <div style="margin-top: 50px; max-width: 1000px; margin-left: auto; margin-right: auto; background: linear-gradient(135deg, #667eea 0%, #764ba2); padding: 40px; border-radius: 20px; box-shadow: 0 6px 18px rgba(0,0,0,0.12); font-family: Arial, sans-serif;">
                <h2 style="color:black; font-size: 28px; margin-bottom: 20px; text-align:center;">
                    Welcome to <span style="color:black;">MediCore Hospital</span>
                </h2>
                <p style="font-size:17px; line-height:1.8; color:#222; text-align:center; max-width:850px; margin: 0 auto 30px;">
                    MediCore Hospital is more than just a healthcare institution — we are a trusted
                    partner in your journey toward wellness. With world-class facilities, a
                    compassionate team, and patient-centered care, we bring healing and innovation
                    together under one roof.
                </p>
            </div>

      <!-- Logout -->
      <div class="logout">
        <form action="logout.php" method="post">
          <button type="submit">Logout</button>
        </form>
      </div>
    </main>
  </div>
</body>
</html>