<?php 
include('partials/main.php'); 
?>
<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Membership Bookings</h1>

        <!-- Dropdown to select booking type -->
        <form action="" method="POST" class="dropdown-form">
        <select name="file-select" class="styled-select" onchange="location = this.value;">
                <option value="#">Select Booking Type...</option>
                <option value="manage-bookinPackage.php">Membership Bookings</option>
                <option value="manage-appointment.php">Appointments</option>
            </select>
        </form>

        <br><br>

        <!-- Display bookings in table -->
        <table class="tbl-full">
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Package</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch bookings from tbl_membershipBookings
                $sql = "SELECT * FROM tbl_membershipBookings";
                $res = $conn->query($sql);

                if ($res->num_rows > 0) {
                    $sn = 1;
                    while ($row = $res->fetch_assoc()) {
                        $id = $row['id'];
                        $full_name = $row['full_name'];
                        $email = $row['email'];
                        $tel_no = $row['tel_no'];
                        $package = $row['package'];
                        $status = $row['status'];
                        ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo htmlspecialchars($full_name); ?></td>
                            <td><?php echo htmlspecialchars($email); ?></td>
                            <td><?php echo htmlspecialchars($tel_no); ?></td>
                            <td><?php echo htmlspecialchars($package); ?></td>
                            <td><?php echo htmlspecialchars($status); ?></td>
                            <td>
                                <?php if ($status == 'Pending') { ?>
                                    <a href="confirm-membershipbooking.php?id=<?php echo $id; ?>" class="btn-secondary">Confirm</a>
                                    <a href="cancel-membershipbooking.php?id=<?php echo $id; ?>" class="btn-danger">Cancel</a>
                                <?php } else { ?>
                                    <span><?php echo $status; ?></span>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='7' class='error'>No bookings found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include('partials/footer.php'); ?>
