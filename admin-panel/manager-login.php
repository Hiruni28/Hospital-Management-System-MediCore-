
<?php include('../config/constant.php'); ?>


<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<link rel="icon" href="images/side.png" type="image/x-icon">
<link rel="stylesheet" href="../admin-css/login.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="../js/script.js"></script>
</head>
<body>
<div class="container">
  <div class="left">
    <h1>Admin</h1>
    <div class="social-login">
      <button><i class="fab fa-facebook-f"></i></button>
      <button><i class="fab fa-google-plus-g"></i></button>
      <button><i class="fab fa-linkedin-in"></i></button>
    </div>
    <form action="" method="POST">
     
      <input type="text" name="username" placeholder="Username" />
      <input type="password" name="password" placeholder="Password" />
      <input type="submit" name="submit" value="Login" class="btn-primary">
      <br>
    </form>
    <a href="#">Forgot your password?</a>
    <br>
  </div>
  <div class="right">
   
    <button><a href="../staff-panel/staff-login.php">Staff Login here..</a></button>
  </div>
</div>
</body>
</html>

<?php
if (isset($_POST['submit'])) {

  $username = $_POST['username'];
  $password = md5($_POST['password']);
  $sql = "SELECT * FROM tbl_admin WHERE username = ? AND password = ? AND position = 'manager'";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password); 
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) { 
    $_SESSION['user'] = $username; 

    header('Location:' . SITEURL . '/admin-panel/admin-index.php?status=success&action=login');
  } else {
    header('Location:' . SITEURL . 'admin-panel/manager-login.php?status=error&action=login');
  }
}


?>