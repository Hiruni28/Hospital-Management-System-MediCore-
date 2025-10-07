<?php
//include constanr.php for siteurl
include('../config/constant.php');
//1.destroy the session 
session_destroy();//unset $_session['user']

//redirect to the login page
header('Location:'.SITEURL.'staff-panel/staff-login.php');