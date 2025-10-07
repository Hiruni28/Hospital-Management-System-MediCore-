<?php
include('partials/main.php');
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Dental Emergency</h1>

        <!-- Dropdown to select file type -->
        <form action="" method="POST" class="dropdown-form">
            <select name="file-select" class="styled-select" onchange="location = this.value;">
                <option value="#">Select Program Category here ...</option>
                <option value="manage-classProgram.php">Manage Operation</option>
                <option value="manage-laboratory.php">Manage Testing</option>
                <option value="manage-nutration.php">Manage OPD/ETU</option>
                <option value="manage-consultation.php">Manage Consultation</option>
                <option value="manage-babycare.php">Manage Baby Care</option>
                <option value="manage-dental.php"selected>Manage Dental Emergency</option>
                <option value="manage-neurology.php">Manage Neurology</option>
                <option value="manage-orthopedics.php">Manage Orthopedics</option>
                <option value="manage-dermatology.php">Manage Dermatology</option>
            </select>
        </form>

        <br><br>

        <!-- Button to add a new nutrition program -->
        <a href="add-program.php" class="btn-primary">Add Dental Emergency</a>
        <br><br>

        <div class="programs-grid">
            <?php
            // Fetch nutrition programs from tbl_programs
            $sql = "SELECT * FROM tbl_programs WHERE category_id = (SELECT id FROM tbl_programCategories WHERE title = 'Dental Emergency')";
            $res = $conn->query($sql);

            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    $program_id = $row['id'];
                    $title = $row['title'];
                    $price_local = $row['price_local'];
                    $price_foreign = $row['price_foreign'];
                    $schedule = json_decode($row['schedule'], true);
                    $features = json_decode($row['features'], true);
                    $image_name = $row['image_name'];
                    $active = $row['active'];

                    // Set default image if no image is provided
                    $image_path = ($image_name != "") ? "../images/programs/" . $image_name : "../images/default-program.jpg";
            ?>

                    <div class="program-box">
                        <div class="program-img">
                            <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($title); ?>" style="width:100%; height:auto;">
                        </div>
                        <div class="program-details">
                            <h4><?php echo htmlspecialchars($title); ?></h4>
                            <p><span class="label">Program ID:</span> <?php echo htmlspecialchars($program_id); ?></p>
                            <p><span class="label">Price (Local):</span> Rs. <?php echo htmlspecialchars($price_local); ?></p>
                            <p><span class="label">Price (Foreign):</span> $<?php echo htmlspecialchars($price_foreign); ?></p>
                            <p><span class="label">Time Schedule:</span></p>
                            <ul>
                                <?php foreach ($schedule as $time) { ?>
                                    <li><?php echo htmlspecialchars($time); ?></li>
                                <?php } ?>
                            </ul>
                            <p><span class="label">Features:</span></p>
                            <ul>
                                <?php foreach ($features as $feature) { ?>
                                    <li><?php echo htmlspecialchars($feature); ?></li>
                                <?php } ?>
                            </ul>
                            <p><span class="label">Active:</span> <?php echo htmlspecialchars($active); ?></p>
                        </div>
                        <div class="program-actions">
                            <a href="update-dental.php?id=<?php echo $program_id; ?>" class="btn-secondary">Update</a>
                            <a href="delete-program.php?id=<?php echo $program_id; ?>" class="btn-danger">Delete</a>
                        </div>
                    </div>

            <?php
                }
            } else {
                echo "<p>No nutrition programs available at the moment.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>