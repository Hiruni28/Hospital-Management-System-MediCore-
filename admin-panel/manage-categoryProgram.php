<?php include('partials/main.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Service Categories</h1>
        <br />
        <a href="add-categoryProgram.php" class="btn-primary">Add Service Category</a>
        <br><br>

        <table class="tbl-full">
            <tr>
                <th>S.N</th>
                <th>Title</th>
                <th>Image</th>
                <th>Active</th>
                <th>Action</th>
            </tr>

            <?php
            // Fetch categories from the database
            $sql = "SELECT * FROM tbl_programCategories";
            $res = $conn->query($sql); // Assuming $conn is the database connection

            if ($res && $res->num_rows > 0) {
                $sn = 1; // Create a serial number variable

                // Loop through all rows
                while ($row = $res->fetch_assoc()) {
                    $id = $row['id'];
                    $title = $row['title'];
                    $image_name = $row['image_name'];
                    $active = $row['active'];
            ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td><?php echo htmlspecialchars($title); ?></td>

                        <!-- Image -->
                        <td>
                            <?php
                            if ($image_name != "") {
                                // Display the image if it's available
                            ?>
                                <img src="../images/category/<?php echo htmlspecialchars($image_name); ?>" alt="<?php echo htmlspecialchars($title); ?>" style="width: 100px; height: 100px;">
                            <?php
                            } else {
                                // Display a placeholder image if no image is uploaded
                                echo "<div class='error'>Image not available</div>";
                            }
                            ?>
                        </td>

                        <!-- Active Status -->
                        <td><?php echo htmlspecialchars($active); ?></td>

                        <!-- Action Buttons -->
                        <td>
                            <a href="update-categoryProgram.php?id=<?php echo $id; ?>" class="btn-members">Update</a><br>
                            <a href="delete-categoryProgram.php?id=<?php echo $id; ?>&image_name=<?php echo $image_name; ?>" class="btn-membersDelete">Delete</a>
                        </td>
                    </tr>
            <?php
                }
            } else {
                // No categories available
                echo "<tr><td colspan='6' class='error'>No categories available.</td></tr>";
            }
            ?>
        </table>

    </div>
</div>

<?php include('partials/footer.php'); ?>
