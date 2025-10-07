<?php
include(__DIR__ . '/../config/constant.php');


$patient = null;
if (isset($_SESSION['patient_id'])) {
    $patient_id = $_SESSION['patient_id'];

    // Fetch profile details from tbl_patient
    $sql = "SELECT full_name, gender, dob ,nic ,tel_no ,address ,email ,emergency_contact_name  ,emergency_contact_no ,blood_group ,allergies FROM tbl_patient 
            WHERE patient_id = '$patient_id'";
    $res = mysqli_query($conn, $sql);
    $patient = mysqli_fetch_assoc($res);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cus_css/style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Hospital System</title>
    <link rel="icon" href="images/side.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMkt8Kz0Cbr5lz0OMqIcX8w2DMEjklZjPpITUMw" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="js/script.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <style>
    .btn.profile {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 18px;
      background: #007bff;       /* blue background */
      color: #fff;              /* white text */
      text-decoration: none;
      font-size: 16px;
      font-weight: 500;
      border-radius: 8px;       /* rounded corners */
      transition: 0.3s ease;
    }
    .btn.profile i {
      font-size: 18px;          /* icon size */
    }
    .btn.profile:hover {
      background: #0056b3;      /* darker blue on hover */
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
  </style>

</head>

<body>
    <header>
        <nav class="navbar">
            <img src="images/logo1.png" style="width: 240px;height: 55px;">

            <div class="hamburger-menu" id="hamburger-menu">
                <i class="fas fa-bars"></i>
            </div>
            <ul class="nav-links" id="nav-links">
                <li><a href="index.php" style="font-size: 24px;">Home</a></li>
                <li><a href="programs.php" style="font-size: 24px;">Services</a></li>
                <li><a href="promotion.php" style="font-size: 24px;">Promotions</a></li>
                <li><a href="membership.php" style="font-size: 24px;">Membership</a></li>
                <li><a href="blog.php" style="font-size: 24px;">Blogs</a></li>
                <li><a href="quetions.php" style="font-size: 24px;">FAQ</a></li>
                <li></li>
                <button class="btn join"
                        style="display: inline-flex;
                         align-items: center;
                         gap: 10px;
                         background:rgba(245, 139, 139, 1);
                         color: white;
                         padding: 12px 30px;
                         border-radius: 50px;
                         text-decoration: none;
                         font-weight: bold;
                         margin-top: -5px;
                         transition: all 0.3s ease;">
                        <a href="programs.php" style="font-size: 18px;">Appointment</a></button>
            </ul>

            <!-- PHP to display login/signup or logout button -->
            <div class="cta-buttons">
                <?php if (isset($_SESSION['patient_id'])): ?>
                     <a href="customer-main/profile.php" class="btn profile" >
                          <i class="fa-solid fa-user"></i> Profile
                     </a>
                    <a href="logout.php" class="btn logout">Logout</a>
                <?php else: ?>
                    <a href="customer-login.php" class="btn logout">Login</a>
                    <a href="customer-signup.php" class="btn signup">Sign Up</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>



</body>

</html>