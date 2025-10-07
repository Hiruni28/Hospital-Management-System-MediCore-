
<?php 
//authorisation-access control
// session_start();
//check whether the user is logged in or not
if(!isset($_SESSION['user'])){ //if user session is not set

 //user is not logged in

//  $_SESSION['no-login-message'] = "<div class='error text-center'> Please login to access admin panel </div>";

  //redirect to login page with message
  header('Location:'.SITEURL.'staff-panel/staff-login.php?status=error&action=no-login-message');



}