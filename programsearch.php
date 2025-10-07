<?php 
include('customer-main/main.php'); // Include header

// Get the search term from the URL
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

?>

<section class="searchprogram-results">
  <h2 class="program-title">Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h2>
  <div class="searchprogram-container">

    <?php
    if (!empty($search_query)) {
        // Prepare a query to search for programs based on the search term
        $sql = "SELECT p.title, p.image_name, p.id, c.title AS category_title
                FROM tbl_programs p
                JOIN tbl_programCategories c ON p.category_id = c.id
                WHERE p.title LIKE ? AND p.active = 'Yes'";
        
        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);
        $search_term = "%" . $search_query . "%"; // Adding wildcard for search
        $stmt->bind_param('s', $search_term);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $program_title = $row['title'];
                $image_name = $row['image_name'];
                $program_id = $row['id'];
                $category_title = $row['category_title'];

                // Default image if no image is provided
                $image_path = $image_name != "" ? "images/programs/" . $image_name : "images/default-program.jpg";
                ?>
                <div class="programsearch-card">
                  <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($program_title); ?>" class="program-image">
                  <h3><?php echo htmlspecialchars($program_title); ?></h3>

                  <?php
                  if (isset($_SESSION['customer_id'])) {
                      // If the customer is logged in, allow them to book
                      echo '<a href="programBoking.php?category=' . urlencode($category_title) . '&program=' . urlencode($program_title) . '" class="book-btn">Book</a>';
                  } else {
                      // If the customer is not logged in, redirect to login page
                      echo '<a href="customer-login.php" class="book-btn">Login to Book</a>';
                  }
                  ?>

                  <a href="groupclass-details.php?id=<?php echo $program_id; ?>" class="see-more">See more..</a>
                </div>
                <?php
            }
        } else {
            echo "<p>No programs found matching your search.</p>";
        }
    } else {
        echo "<p>Please enter a search term.</p>";
    }
    ?>
    
  </div>
</section>

<?php include('customer-main/footer.php'); ?>
