<?php include('../config/constant.php');
include('login-check.php')?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../admin-css/style.css">
   
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <div class="logo" style="color:plum;">MediCore Hospital System</div>
        <a href="logout.php" class="logout-button">Logout</a>
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="admin-index.php">Home</a></li>
                <li><a href="manage-admin.php ">User</a></li>
                <li><a href="manage-doctor.php">Doctor</a></li>
                <li><a href="manage-membership.php" >Membership Package</a></li>
                <li><a href="manage-categoryProgram.php">Service Categories</a></li>
                <li><a href="manage-classProgram.php">Services</a></li>
                <li><a href="manage-promotion.php">Promotions</a></li>
                <li><a href="manage-events.php">Events</a></li>
                <li><a href="manage-inquery.php">Inquery Handle</a></li>
                <li><a href="manage-bookinPackage.php">Booking/Appoinments</a></li>
                <li><a href="manage-blogs.php">Blog Manage</a></li>
                
                <!-- <li><a href="manage-events.php">Events</a></li> -->
            </ul>
        </div>