<?php
include('partials/main.php');
?>
<div class="main-content">
    <div class="wrapper">
        <h1>Manage Admin</h1>
        <br />

        <!-- Button to add admins -->
        <a href="add-admin.php" class="btn-primary">Add Admin</a>

        <br />
        <br />

        <table class="tbl-full">
            <tr>
                <th>S.N</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Position</th>
                <th>Actions</th>
            </tr>
            <?php
            $sql = "SELECT * FROM tbl_admin";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $count = $result->num_rows;

                $sn = 1;
                if ($count > 0) {
                    while ($rows = $result->fetch_assoc()) {

                        $id = $rows['id'];
                        $full_name = $rows['full_name'];
                        $username = $rows['username'];
                        $email = $rows['email'];
                        $position = $rows['position'];
            ?>

                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo $full_name; ?></td>
                            <td><?php echo $username; ?></td>
                            <td><?php echo $email; ?></td>
                            <td><?php echo $position; ?></td>
                            <td>

                                <a href="<?php echo SITEURL; ?>admin-panel/update-admin.php?id=<?php
                                                                                                echo $id; ?>" class="btn-secondary">Update Admin</a>


                                <a href="<?php echo SITEURL; ?>admin-panel/delete-admin.php?id=<?php
                                                                                                echo $id; ?>" class="btn-danger">Detele admin</a>
                            </td>
                        </tr>


            <?php

                    }
                } else {
                    //we do not have dayta in database
                }
            }

            ?>
        </table>

        <br><br>
        <h1>Patient Details</h1>
        <table class="tbl-full">
            <tr>
                <th>S.N</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Contacts</th>
                <th>Actions</th>
            </tr>

            <!-- Fetch customer data from tbl_patient -->
            <?php
            // Query to get all customers
            $sql = "SELECT * FROM tbl_patient";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if there are any customers in the database
            if ($result->num_rows > 0) {
                $sn = 1; // Serial number
                while ($row = $result->fetch_assoc()) {
                    $patient_id = $row['patient_id'];
                    $full_name = $row['full_name'];
                    $email = $row['email'];
                    $username = $row['username'];
                    $contact = $row['tel_no'];
            ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td><?php echo htmlspecialchars($full_name); ?></td>
                        <td><?php echo htmlspecialchars($email); ?></td>
                        <td><?php echo htmlspecialchars($username); ?></td>
                        <td><?php echo htmlspecialchars($contact); ?></td>
                        <td>
                            <a href="delete-customer.php?id=<?php echo $id; ?>" class="btn-danger">Delete Patient</a>
                        </td>
                    </tr>
            <?php
                }
            } else {
                // If no customers are found
                echo "<tr><td colspan='6' class='error'>No customers found.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>
<?php include('partials/footer.php'); ?>