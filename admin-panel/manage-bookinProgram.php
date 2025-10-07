<?php 
include('partials/main.php'); 
?>
<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Services Bookings</h1>

        <!-- Dropdown to select booking type -->
        <form action="" method="POST" class="dropdown-form">
            <select name="file-select" class="styled-select" onchange="location = this.value;">
                <option value="#">Select Booking Type...</option>
                <option value="manage-bookinPackage.php">Membership Bookings</option>
                <option value="manage-bookinProgram.php">Services Bookings</option>
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
                    <th>Category</th>
                    <th>Program Name</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch all bookings from tbl_programBooking
                $sql = "SELECT b.id, b.full_name, b.email, b.tel_no, c.title AS category_title, p.title AS program_title, b.booking_date, b.status 
                        FROM tbl_programBooking b
                        JOIN tbl_programCategories c ON b.category_id = c.id
                        JOIN tbl_programs p ON b.program_id = p.id
                        ORDER BY b.booking_date DESC";
                $res = $conn->query($sql);

                if ($res->num_rows > 0) {
                    $sn = 1;
                    while ($row = $res->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['tel_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['category_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['program_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <?php if ($row['status'] == 'Pending') { ?>
                                    <a href="confirm-booking.php?id=<?php echo $row['id']; ?>" class="btn-members">Confirm</a>
                                    <a href="cancel-booking.php?id=<?php echo $row['id']; ?>" class="btn-membersDelete ">Cancel</a>
                                <?php } else { ?>
                                    <span class="btn-disabled"><?php echo htmlspecialchars($row['status']); ?></span>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='9' class='error'>No bookings available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include('partials/footer.php'); ?>

