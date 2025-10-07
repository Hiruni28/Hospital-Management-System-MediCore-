<?php include('customer-main/main.php'); ?>
<h2 class="membership-title center" style="color: orangered;">Health News</h2>
<section class="blog-container">

    <?php
    $sql = "SELECT id, title, content, image FROM tbl_blogs";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any blogs
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $title = $row['title'];
            $content = $row['content'];
            $image_name = $row['image'];

            $short_description = substr($content, 0, 100) . '...';

            $image_path = $image_name ? "images/blogs/" . $image_name : "images/default-blog.jpg";
    ?>

        <!-- Blog Box -->
        <a href="blog-details.php?id=<?php echo $id; ?>" class="blog-box"> <!-- Correct link to include blog ID -->
            <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($title); ?>" class="blog-image">
            <div class="blog-content">
                <h3 class="blog-title"><?php echo htmlspecialchars($title); ?></h3>
                <p class="blog-description"><?php echo htmlspecialchars($short_description); ?></p>
            </div>
        </a>

    <?php
        }
    } else {
        echo "<p>No blogs available at the moment.</p>";
    }
    ?>
</section>






<section id="blog4" class="blog-article">
    <h2 style="color: orangered;">Satisfactory member reviews about our efficient service.</h2>

    <?php
    // Fetch success stories from the database
    $sql = "SELECT title, description, email, image_name FROM tbl_success_stories";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are success stories in the database
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $title = $row['title'];
            $description = $row['description'];
            $email = $row['email'];
            $image_name = $row['image_name'];

            // Set the image path or use a default image
            $image_path = $image_name ? "images/success/" . $image_name : "images/default-success.jpg";
    ?>
    
    <div class="success-story">
        <img src="<?php echo $image_path; ?>" alt="<?php echo $title; ?>">
        <div class="story-content">
            <h3><?php echo $title; ?></h3>
            <h4><?php echo $email; ?></h4> <!-- Displaying the email -->
            <p><?php echo $description; ?></p>
        </div>
    </div>

    <?php
        }
    } else {
        echo "<p>No success stories available at the moment.</p>";
    }
    ?>

</section>


    <?php include('customer-main/footer.php'); ?>