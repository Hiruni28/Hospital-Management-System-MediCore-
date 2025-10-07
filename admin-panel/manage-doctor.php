<?php
include('partials/main.php');
?>
<div class="main-content">
    <div class="wrapper">
        <h1>Manage Doctor</h1>
        <br />

        <!-- Button to add admins -->
        <a href="add-doctor.php" class="btn-primary">Add Doctor</a>

        <br />
        <br />

        <table class="tbl-full">
            <tr>
                <th>S.N</th>
                <th>Specialization</th>
                <th>Doctor Name</th>
                <th>Email</th>
                <th>Doc-Fees</th>
                <th>Actions</th>
            </tr>
             <?php
            $sql = "SELECT * FROM doctors";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $count = $result->num_rows;

                $sn = 1;
                if ($count > 0) {
                    while ($rows = $result->fetch_assoc()) {

                        $id = $rows['id'];
                        $specilization = $rows['specilization'];
                        $doctorName = $rows['doctorName'];
                        $docEmail = $rows['docEmail'];
                        $docFees = $rows['docFees'];
            ?>

                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo $specilization; ?></td>
                            <td><?php echo $doctorName; ?></td>
                            <td><?php echo $docEmail; ?></td>
                            <td><?php echo $docFees; ?></td>
                            <td>

                                <a href="<?php echo SITEURL; ?>admin-panel/update-doctor.php?id=<?php
                                                                                                echo $id; ?>" class="btn-secondary">Update Doctor</a>


                                <a href="<?php echo SITEURL; ?>admin-panel/delete-doctor.php?id=<?php
                                                                                                echo $id; ?>" class="btn-danger">Detele Doctor</a>
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

        <?php include('partials/footer.php'); ?>