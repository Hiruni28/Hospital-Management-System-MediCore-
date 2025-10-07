<?php
include('partials/main.php');
?>
<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Appointments</h1>

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
                    <th>SN</th>
                    <th>Doctor ID</th>
                    <th>Patient Name</th>
                    <th>Phone No</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status </th>
                    <th> Action </th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch all bookings from appointments
                $sql = "SELECT * FROM appointments";
                $res = $conn->query($sql);

                if ($res->num_rows > 0) {
                    $sn = 1;
                    while ($row = $res->fetch_assoc()) {
                        $id = $row['id'];
                        $appointment_id = $row['id'];
                        $doctor_id = $row['doctor_id'];
                        $patient_name = $row['patient_name'];
                        $patient_phone = $row['patient_phone'];
                        $appointment_date = $row['appointment_date'];
                        $appointment_time = $row['appointment_time'];
                        $status = $row['status'];
                ?>
                        <tr>
                            <td><?php echo $sn; ?></td>
                            <td><?php echo $doctor_id; ?></td>
                            <td><?php echo $patient_name; ?></td>
                            <td><?php echo $patient_phone; ?></td>
                            <td><?php echo $appointment_date; ?></td>
                            <td><?php echo $appointment_time; ?></td>
                            <td><?php echo $status; ?></td>

                            <td>
                                <?php if (strtolower($status) == 'pending') { ?>
                                    <a href="confirm-appointment.php?id=<?php echo $appointment_id; ?>" class="btn-secondary">Confirm</a>
                                    <a href="cancel-appointment.php?id=<?php echo $appointment_id; ?>" class="btn-danger">Cancel</a>
                                <?php } else { ?>
                                    <?php if (strtolower($status) == 'confirmed') { ?>
                                        <span class="badge badge-success">Confirmed</span>
                                    <?php } elseif (strtolower($status) == 'cancelled') { ?>
                                        <span class="badge badge-danger">Cancelled</span>
                                    <?php } else { ?>
                                        <span class="badge badge-warning"><?php echo ucfirst($status); ?></span>
                                    <?php } ?>
                                <?php } ?>
                            </td>

                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='9' class='error'>No bookings found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include('partials/footer.php'); ?>