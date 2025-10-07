<?php include('customer-main/main.php'); ?>


<section id="hero">
  <div class="main-image"></div>
  <div class="heroMain-content" style="margin-top: -200px;">
    <img src="images/side2.png" alt="Left Side Image" class="side-img left-image" style="width: 400px; height: 500px; margin-top: 100px;">
    <div class="hero-search">
      <h1>Search the services Here</h1>
      <p>Make your life fantastic</p>
      <form action="programsearch.php" method="get">
    <input type="text" name="search" placeholder="Search Here">
    <button type="submit">Search Here</button>
</form>
    </div>
    <img src="images/side1.png" alt="Right Side Image" class="side-img right-image" style="width: 400px; height: 500px; margin-top: 100px;">
  </div>
</section>


<section class="programs">
  <h2 class="program-title" style="color: black;">::Operation Theatre::</h2>
  <div class="program-container">

    <?php
    // Fetch classes from tbl_programs where category name is 'Group Classes'
    $sql = "SELECT p.title, p.image_name, p.id, c.title AS category_title
            FROM tbl_programs p 
            JOIN tbl_programCategories c ON p.category_id = c.id 
            WHERE c.title = 'Operation Theatre' AND p.active = 'Yes'";

    $res = $conn->query($sql);

    if ($res) {
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $program_title = $row['title'];
                $image_name = $row['image_name'];
                $program_id = $row['id'];
                $category_title = $row['category_title'];

                // Default image if no image is provided
                $image_path = !empty($image_name) ? "images/programs/" . $image_name : "images/default-program.jpg";
                ?>
                
                <div class="program-card">
                  <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($program_title); ?>" class="program-image">
                  <h3><?php echo htmlspecialchars($program_title); ?></h3>
                <?php
                  if (isset($_SESSION['patient_id'])) {
                      // If the customer is logged in, allow them to book
                      echo '<a href="http://localhost/medicore/customer-main/add-appointment.php?category=' . urlencode($category_title) . '&program=' . urlencode($program_title) . '" class="book-btn">Request</a>';
                  } else {
                      // If the customer is not logged in, redirect to login page
                      echo '<a href="customer-login.php" class="book-btn">Login to Request</a>';
                  }
                  ?>

                  <a href="groupclass-details.php?id=<?php echo $program_id; ?>" class="see-more">See more..</a>
                </div>

                <?php
            }
        } else {
            echo "<p>No classes available in this category.</p>";
        }
    } else {
        echo "Error: " . $conn->error; // Output any SQL errors
    }
    ?>
    
  </div>                
</section>


<section class="programs">
  <h2 class="program-title" style="color:black;">::Laboratory Service::</h2>
  <div class="program-container">

    <?php
    // Fetch nutrition counseling programs from tbl_programs where category is 'Nutrition Counselling'
    $sql = "SELECT p.title, p.image_name, p.id
            FROM tbl_programs p
            JOIN tbl_programCategories c ON p.category_id = c.id
            WHERE c.title = 'Laboratory Service' AND p.active = 'Yes'";

    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $program_title = $row['title'];
            $image_name = $row['image_name'];
            $program_id = $row['id']; // Keep program ID for dynamic link
            $category_title = 'Laboratory Service'; // Define the category title

            // Default image if no image is provided
            $image_path = ($image_name != "") ? "images/programs/" . $image_name : "images/default-program.jpg";
            ?>

            <div class="program-card">
              <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($program_title); ?>" class="program-image">
              <h3><?php echo htmlspecialchars($program_title); ?></h3>
              
              <?php
              // Check if the customer is logged in
              if (isset($_SESSION['patient_id'])) {
                  // Allow booking and pass the category and program as URL parameters
                  echo '<a href="http://localhost/medicore/customer-main/add-appointment.php?category=' . urlencode($category_title) . '&program=' . urlencode($program_title) . '" class="book-btn">Request</a>';
              } else {
                  // If not logged in, redirect to login page
                  echo '<a href="customer-login.php" class="book-btn">Login to Request</a>';
              }
              ?>
              
              <a href="groupclass-details.php?id=<?php echo $program_id; ?>" class="see-more">See more..</a>
            </div>

            <?php
        }
    } else {
        echo "<p>No nutrition counseling programs available at the moment.</p>";
    }
    ?>
    
  </div>
</section>


<section class="programs">
  <h2 class="program-title" style="color:black;">::OPD/ETU::</h2>
  <div class="program-container">

    <?php
    // Fetch nutrition counseling programs from tbl_programs where category is 'Nutrition Counselling'
    $sql = "SELECT p.title, p.image_name, p.id
            FROM tbl_programs p
            JOIN tbl_programCategories c ON p.category_id = c.id
            WHERE c.title = 'OPD/ETU' AND p.active = 'Yes'";

    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $program_title = $row['title'];
            $image_name = $row['image_name'];
            $program_id = $row['id']; // Keep program ID for dynamic link
            $category_title = 'OPD/ETU'; // Define the category title

            // Default image if no image is provided
            $image_path = ($image_name != "") ? "images/programs/" . $image_name : "images/default-program.jpg";
            ?>

            <div class="program-card">
              <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($program_title); ?>" class="program-image">
              <h3><?php echo htmlspecialchars($program_title); ?></h3>
              
              <?php
              // Check if the customer is logged in
              if (isset($_SESSION['patient_id'])) {
                  // Allow booking and pass the category and program as URL parameters
                  echo '<a href="http://localhost/medicore/customer-main/add-appointment.php?category=' . urlencode($category_title) . '&program=' . urlencode($program_title) . '" class="book-btn">Request</a>';
              } else {
                  // If not logged in, redirect to login page
                  echo '<a href="customer-login.php" class="book-btn">Login to Request</a>';
              }
              ?>
              
              <a href="groupclass-details.php?id=<?php echo $program_id; ?>" class="see-more">See more..</a>
            </div>

            <?php
        }
    } else {
        echo "<p>No nutrition counseling programs available at the moment.</p>";
    }
    ?>
    
  </div>
</section>

<section class="programs">
  <h2 class="program-title" style="color: black;">::Specialist Consultation::</h2>
  <div class="program-container">

    <?php
    // Fetch nutrition counseling programs from tbl_programs where category is 'Nutrition Counselling'
    $sql = "SELECT p.title, p.image_name, p.id
            FROM tbl_programs p
            JOIN tbl_programCategories c ON p.category_id = c.id
            WHERE c.title = 'Specialist Consultation' AND p.active = 'Yes'";

    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $program_title = $row['title'];
            $image_name = $row['image_name'];
            $program_id = $row['id']; // Keep program ID for dynamic link
            $category_title = 'Specialist Consultation'; // Define the category title

            // Default image if no image is provided
            $image_path = ($image_name != "") ? "images/programs/" . $image_name : "images/default-program.jpg";
            ?>

            <div class="program-card">
              <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($program_title); ?>" class="program-image">
              <h3><?php echo htmlspecialchars($program_title); ?></h3>
              
              <?php
              // Check if the customer is logged in
              if (isset($_SESSION['patient_id'])) {
                  // Allow booking and pass the category and program as URL parameters
                  echo '<a href="http://localhost/medicore/customer-main/add-appointment.php?category=' . urlencode($category_title) . '&program=' . urlencode($program_title) . '" class="book-btn">Request</a>';
              } else {
                  // If not logged in, redirect to login page
                  echo '<a href="customer-login.php" class="book-btn">Login to Request</a>';
              }
              ?>
              
              <a href="groupclass-details.php?id=<?php echo $program_id; ?>" class="see-more">See more..</a>
            </div>

            <?php
        }
    } else {
        echo "<p>No nutrition counseling programs available at the moment.</p>";
    }
    ?>
    
  </div>
</section>

<section class="programs">
  <h2 class="program-title" style="color: black;">::Mother & Baby Care::</h2>
  <div class="program-container">

    <?php
    // Fetch nutrition counseling programs from tbl_programs where category is 'Nutrition Counselling'
    $sql = "SELECT p.title, p.image_name, p.id
            FROM tbl_programs p
            JOIN tbl_programCategories c ON p.category_id = c.id
            WHERE c.title = 'Mother & Baby Care' AND p.active = 'Yes'";

    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $program_title = $row['title'];
            $image_name = $row['image_name'];
            $program_id = $row['id']; // Keep program ID for dynamic link
            $category_title = 'Mother & Baby Care'; // Define the category title

            // Default image if no image is provided
            $image_path = ($image_name != "") ? "images/programs/" . $image_name : "images/default-program.jpg";
            ?>

            <div class="program-card">
              <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($program_title); ?>" class="program-image">
              <h3><?php echo htmlspecialchars($program_title); ?></h3>
              
              <?php
              // Check if the customer is logged in
              if (isset($_SESSION['patient_id'])) {
                  // Allow booking and pass the category and program as URL parameters
                  echo '<a href="http://localhost/medicore/customer-main/add-appointment.php?category=' . urlencode($category_title) . '&program=' . urlencode($program_title) . '" class="book-btn">Request</a>';
              } else {
                  // If not logged in, redirect to login page
                  echo '<a href="customer-login.php" class="book-btn">Login to Request</a>';
              }
              ?>
              
              <a href="groupclass-details.php?id=<?php echo $program_id; ?>" class="see-more">See more..</a>
            </div>

            <?php
        }
    } else {
        echo "<p>No nutrition counseling programs available at the moment.</p>";
    }
    ?>
    
  </div>
</section>


<section class="programs">
  <h2 class="program-title" style="color: black;">::Dental Emergency::</h2>
  <div class="program-container">

    <?php
    // Fetch nutrition counseling programs from tbl_programs where category is 'Nutrition Counselling'
    $sql = "SELECT p.title, p.image_name, p.id
            FROM tbl_programs p
            JOIN tbl_programCategories c ON p.category_id = c.id
            WHERE c.title = 'Dental Emergency' AND p.active = 'Yes'";

    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $program_title = $row['title'];
            $image_name = $row['image_name'];
            $program_id = $row['id']; // Keep program ID for dynamic link
            $category_title = 'Dental Emergency'; // Define the category title

            // Default image if no image is provided
            $image_path = ($image_name != "") ? "images/programs/" . $image_name : "images/default-program.jpg";
            ?>

            <div class="program-card">
              <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($program_title); ?>" class="program-image">
              <h3><?php echo htmlspecialchars($program_title); ?></h3>
              
              <?php
              // Check if the customer is logged in
              if (isset($_SESSION['patient_id'])) {
                  // Allow booking and pass the category and program as URL parameters
                  echo '<a href="http://localhost/medicore/customer-main/add-appointment.php?category=' . urlencode($category_title) . '&program=' . urlencode($program_title) . '" class="book-btn">Request</a>';
              } else {
                  // If not logged in, redirect to login page
                  echo '<a href="customer-login.php" class="book-btn">Login to Request</a>';
              }
              ?>
              
              <a href="groupclass-details.php?id=<?php echo $program_id; ?>" class="see-more">See more..</a>
            </div>

            <?php
        }
    } else {
        echo "<p>No nutrition counseling programs available at the moment.</p>";
    }
    ?>
    
  </div>
</section>

<section class="programs">
  <h2 class="program-title" style="color: black;">::Neurology::</h2>
  <div class="program-container">

    <?php
    // Fetch nutrition counseling programs from tbl_programs where category is 'Nutrition Counselling'
    $sql = "SELECT p.title, p.image_name, p.id
            FROM tbl_programs p
            JOIN tbl_programCategories c ON p.category_id = c.id
            WHERE c.title = 'Neurology' AND p.active = 'Yes'";

    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $program_title = $row['title'];
            $image_name = $row['image_name'];
            $program_id = $row['id']; // Keep program ID for dynamic link
            $category_title = 'Neurology'; // Define the category title

            // Default image if no image is provided
            $image_path = ($image_name != "") ? "images/programs/" . $image_name : "images/default-program.jpg";
            ?>

            <div class="program-card">
              <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($program_title); ?>" class="program-image">
              <h3><?php echo htmlspecialchars($program_title); ?></h3>
              
              <?php
              // Check if the customer is logged in
              if (isset($_SESSION['patient_id'])) {
                  // Allow booking and pass the category and program as URL parameters
                  echo '<a href="http://localhost/medicore/customer-main/add-appointment.php?category=' . urlencode($category_title) . '&program=' . urlencode($program_title) . '" class="book-btn">Request</a>';
              } else {
                  // If not logged in, redirect to login page
                  echo '<a href="customer-login.php" class="book-btn">Login to Request</a>';
              }
              ?>
              
              <a href="groupclass-details.php?id=<?php echo $program_id; ?>" class="see-more">See more..</a>
            </div>

            <?php
        }
    } else {
        echo "<p>No nutrition counseling programs available at the moment.</p>";
    }
    ?>
    
  </div>
</section>


<section class="programs">
  <h2 class="program-title" style="color: black;">::Orthopedics::</h2>
  <div class="program-container">

    <?php
    // Fetch nutrition counseling programs from tbl_programs where category is 'Nutrition Counselling'
    $sql = "SELECT p.title, p.image_name, p.id
            FROM tbl_programs p
            JOIN tbl_programCategories c ON p.category_id = c.id
            WHERE c.title = 'Orthopedics' AND p.active = 'Yes'";

    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $program_title = $row['title'];
            $image_name = $row['image_name'];
            $program_id = $row['id']; // Keep program ID for dynamic link
            $category_title = 'Orthopedics'; // Define the category title

            // Default image if no image is provided
            $image_path = ($image_name != "") ? "images/programs/" . $image_name : "images/default-program.jpg";
            ?>

            <div class="program-card">
              <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($program_title); ?>" class="program-image">
              <h3><?php echo htmlspecialchars($program_title); ?></h3>
              
              <?php
              // Check if the customer is logged in
              if (isset($_SESSION['patient_id'])) {
                  // Allow booking and pass the category and program as URL parameters
                  echo '<a href="http://localhost/medicore/customer-main/add-appointment.php?category=' . urlencode($category_title) . '&program=' . urlencode($program_title) . '" class="book-btn">Request</a>';
              } else {
                  // If not logged in, redirect to login page
                  echo '<a href="customer-login.php" class="book-btn">Login to Request</a>';
              }
              ?>
              
              <a href="groupclass-details.php?id=<?php echo $program_id; ?>" class="see-more">See more..</a>
            </div>

            <?php
        }
    } else {
        echo "<p>No nutrition counseling programs available at the moment.</p>";
    }
    ?>
    
  </div>
</section>


<section class="programs">
  <h2 class="program-title" style="color: black;">::Dermatology::</h2>
  <div class="program-container">

    <?php
    // Fetch nutrition counseling programs from tbl_programs where category is 'Nutrition Counselling'
    $sql = "SELECT p.title, p.image_name, p.id
            FROM tbl_programs p
            JOIN tbl_programCategories c ON p.category_id = c.id
            WHERE c.title = 'Dermatology' AND p.active = 'Yes'";

    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $program_title = $row['title'];
            $image_name = $row['image_name'];
            $program_id = $row['id']; // Keep program ID for dynamic link
            $category_title = 'Dermatology'; // Define the category title

            // Default image if no image is provided
            $image_path = ($image_name != "") ? "images/programs/" . $image_name : "images/default-program.jpg";
            ?>

            <div class="program-card">
              <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($program_title); ?>" class="program-image">
              <h3><?php echo htmlspecialchars($program_title); ?></h3>
              
              <?php
              // Check if the customer is logged in
              if (isset($_SESSION['patient_id'])) {
                  // Allow booking and pass the category and program as URL parameters
                  echo '<a href="http://localhost/medicore/customer-main/add-appointment.php?category=' . urlencode($category_title) . '&program=' . urlencode($program_title) . '" class="book-btn">Request</a>';
              } else {
                  // If not logged in, redirect to login page
                  echo '<a href="customer-login.php" class="book-btn">Login to Request</a>';
              }
              ?>
              
              <a href="groupclass-details.php?id=<?php echo $program_id; ?>" class="see-more">See more..</a>
            </div>

            <?php
        }
    } else {
        echo "<p>No nutrition counseling programs available at the moment.</p>";
    }
    ?>
    
  </div>
</section>

<?php include('customer-main/footer.php'); ?>