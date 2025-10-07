<?php
include('partials/main.php');
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Laboratory Service</h1>

        <!-- Dropdown to select file type -->
        <form action="" method="POST" class="dropdown-form">
            <select name="file-select" class="styled-select" onchange="location = this.value;">
                <option value="#">Select Program Category here ...</option>
                <option value="manage-classProgram.php">Manage Operation</option>
                <option value="manage-training.php"selected>Manage Testing</option>
                <option value="manage-nutration.php">Manage OPD/ETU</option>
                <option value="manage-consultation.php">Manage Consultation</option>
                <option value="manage-babycare.php">Manage Baby Care</option>
                <option value="manage-dental.php">Manage Dental Emergency</option>
                <option value="manage-neurology.php">Manage Neurology</option>
                <option value="manage-orthopedics.php">Manage Orthopedics</option>
                <option value="manage-dermatology.php">Manage Dermatology</option>
            </select>
        </form>

        <br><br>

        <!-- Button to add a new training program -->
        <a href="add-training.php" class="btn-primary">Add Testing</a>
        <br><br>

        <div class="programs-grid">
            <?php
            // Fetch training programs from tbl_programs with trainer information
            $sql = "SELECT p.id, p.title, p.price_local, p.price_foreign, p.schedule, p.features, p.image_name, p.active, t.name AS trainer_name 
                    FROM tbl_programs p 
                    LEFT JOIN tbl_trainer t ON p.trainer_id = t.id 
                    WHERE p.category_id = (SELECT id FROM tbl_programCategories WHERE title = 'Laboratory Service')";

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
                    $trainer_name = $row['trainer_name'];

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
                            <a href="update-training.php?id=<?php echo $program_id; ?>" class="btn-secondary">Update</a>
                            <a href="delete-program.php?id=<?php echo $program_id; ?>" class="btn-danger">Delete</a>
                        </div>
                    </div>

            <?php
                }
            } else {
                echo "<p>No training programs available at the moment.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>