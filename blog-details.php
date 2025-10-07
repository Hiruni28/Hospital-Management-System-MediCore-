<?php include('customer-main/main.php'); ?>

<h1 class="center" style="color: orangered;">Exploring the blogs</h1>

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id']; 

    $sql = "SELECT title, content FROM tbl_blogs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // Bind the blog ID
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the blog exists
    if ($result->num_rows > 0) {
        $blog = $result->fetch_assoc();
        $title = $blog['title'];
        $content = $blog['content'];
?>

<!-- Displaying full blog content -->
<section id="blog<?php echo $id; ?>" class="blog-article">
    <h2><?php echo htmlspecialchars($title); ?></h2>

    <!-- Full Description -->
    <p><?php echo nl2br(htmlspecialchars($content)); ?></p> <!-- Display the full content -->
</section>

<?php
    } else {
        echo "<p>Blog not found.</p>";
    }
} else {
    echo "<p>No blog selected.</p>"; // Error message if no blog ID is present in the URL
}
?>

<?php include('customer-main/footer.php'); ?>
