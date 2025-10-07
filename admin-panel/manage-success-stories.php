<?php 
include('partials/main.php'); 
?>
<div class="main-content">
    <div class="wrapper">
        <h1>Manage Success Stories</h1>
        <br />

        <!-- Button to add a new success story -->
        <a href="add-success-story.php" class="btn-primary">Add Success Story</a>

        <br /><br />

        <table class="tbl-full">
            <tr>
                <th>S.N</th>
                <th>Title</th>
                <th>Image</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>

            <?php
            // Query to get all success stories from the database
            $sql = "SELECT * FROM tbl_success_stories";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if there are any success stories
            if ($result->num_rows > 0) {
                $sn = 1; // Serial number variable
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $title = $row['title'];
                    $email = $row['email'];
                    $image_name = $row['image_name'];

                    // Set default image if no image is provided
                    $image_path = $image_name ? "../images/success/" . $image_name : "images/default-story.jpg";
            ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td><?php echo htmlspecialchars($title); ?></td>
                        <td>
                            <img src="<?php echo $image_path; ?>" width="100" height="100" alt="Success Story Image" onerror="this.src='images/default-story.jpg';">
                        </td>
                        <td><?php echo htmlspecialchars($email); ?></td>
                       
                        <td>
                            <a href="update-success-story.php?id=<?php echo $id; ?>" class="btn-secondary">Update</a>
                            <a href="delete-success-story.php?id=<?php echo $id; ?>" class="btn-danger" onclick="return confirm('Are you sure you want to delete this success story?');">Delete</a>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='5' class='error'>No success stories added yet!</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<?php include('partials/footer.php'); ?>
