<?php
// Include database connection and header
include('partials/main.php');
?>
<div class="main-content">
    <div class="wrapper">
        <h1>Manage Blogs</h1>
        <br />

        <!-- Button to add blogs -->
        <a href="add-blog.php" class="btn-primary">Add Blog</a>
        <br /><br />

        <table class="tbl-full">
            <tr>
                <th>S.N</th>
                <th>Title</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>

            <?php
            $sql = "SELECT * FROM tbl_blogs";
            $stmt = $conn->prepare($sql);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $sn = 1; 
                    while ($row = $result->fetch_assoc()) {
                        $id = $row['id'];
                        $title = $row['title'];
                        $image_name = $row['image'];

                        $image_path = $image_name ? "../images/blogs/" . $image_name : "images/default-blog.jpg";
            ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo htmlspecialchars($title); ?></td>
                            <td>
                                <img src="<?php echo $image_path; ?>" width="100" height="100" alt="Blog Image" onerror="this.src='images/default-blog.jpg';">
                            </td>
                            <td>
                                <a href="update-blog.php?id=<?php echo $id; ?>" class="btn-secondary">Update</a>
                                <a href="delete-blog.php?id=<?php echo $id; ?>" class="btn-danger" onclick="return confirm('Are you sure you want to delete this blog?');">Delete</a>
                            </td>
                        </tr>
            <?php
                    }
                } else {
                    echo "<tr><td colspan='4' class='error'>No blogs added yet!</td></tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='error'>Failed to fetch blogs. Please try again later.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>


<?php
// Include footer
include('partials/footer.php');
?>
