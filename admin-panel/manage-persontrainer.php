<?php 
include('partials/main.php'); 
?>
<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Available Trainers</h1>

        <!-- Button to add a new trainer -->
        <a href="add-trainer.php" class="btn-primary">Add Trainer</a>
        <br><br>

        <!-- Display available trainers in table format -->
        <table class="tbl-full">
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Trainer Name</th>
                    <th>Trainer Qualification</th>
                    <th>About Trainer</th>
                    <th>Category</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php 
                // Fetch trainer details from the database
                $sql = "SELECT t.id, t.name, t.qualifications, t.about_trainer, t.active, c.title AS category_title 
                        FROM tbl_trainer t 
                        JOIN tbl_programCategories c ON t.category_id = c.id"; // Join with categories table to get category name
                $res = $conn->query($sql);

                if ($res->num_rows > 0) {
                    $sn = 1; // Serial number counter
                    while ($row = $res->fetch_assoc()) {
                        $id = $row['id'];
                        $name = $row['name'];
                        $qualifications = $row['qualifications'];
                        $about_trainer = $row['about_trainer'];
                        $category_title = $row['category_title'];
                        $active = $row['active'];
                ?>
                <tr>
                    <td><?php echo $sn++; ?></td>
                    <td><?php echo htmlspecialchars($name); ?></td>
                    <td><?php echo htmlspecialchars($qualifications); ?></td>
                    <td><?php echo htmlspecialchars($about_trainer); ?></td>
                    <td><?php echo htmlspecialchars($category_title); ?></td>
                    <td><?php echo htmlspecialchars($active); ?></td>
                    <td>
                        <a href="update-trainer.php?id=<?php echo $id; ?>" class="btn-members">Update</a>
                        <a href="delete-trainer.php?id=<?php echo $id; ?>" class="btn-membersDelete">Delete</a>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='7' class='error'>No trainers available.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
</div>
<?php include('partials/footer.php'); ?>
