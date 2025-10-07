<?php
include('customer-main/main.php');
// Include your database connection
?>

<!-- <section class="hero">
  <div class="hero-content">
    <div class="hero-text">
      <h1>"Transform Your Health, Build Your Confidence!"</h1>
      <p>Join FitZone Fitness Center today and take the first step towards a healthier, happier you.</p>
      <button class="btn join">Join Us</button>
    </div>
    <div class="side-image">
      <img src="images/side1.png" alt="Side image here">
    </div>
  </div>
</section> -->

<section class="membership">
  <h2 class="membership-title" style="color: orangered;">Membership Packages</h2>
  <div class="membership-container">

    <?php
    // Fetch the membership packages from the database
    $sql = "SELECT * FROM tbl_packages WHERE active='Yes'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // Loop through the results and display each package
      while ($row = $result->fetch_assoc()) {
        $title = $row['title'];
        $price_local = $row['price_local'];
        $price_foreign = $row['price_foreigner'];
        $access = json_decode($row['access']); 
        $features = json_decode($row['features']); 
        $image_name = $row['image_name'];
    ?>

        <div class="membership-card">
          <div class="membership-image">
            <?php if ($image_name != "") { ?>
              <img src="images/membership/<?php echo htmlspecialchars($image_name); ?>" alt="<?php echo htmlspecialchars($title); ?>">
            <?php } else { ?>
              <img src="images/default-package.jpg" alt="Default Image">
            <?php } ?>
          </div>
          <div class="membership-content">
            <h3><?php echo htmlspecialchars($title); ?></h3>
            <p><strong>Price for Local:</strong> <?php echo htmlspecialchars($price_local); ?>/month</p>
            <p><strong>Price for Foreigners:</strong> $<?php echo htmlspecialchars($price_foreign); ?>/month</p>
            <p><strong>Access:</strong>
            <ul>
              <?php foreach ($access as $item) { ?>
                <li><?php echo htmlspecialchars($item); ?></li>
              <?php } ?>
            </ul>
            </p>
            <p><strong>Features:</strong>
            <ul>
              <?php foreach ($features as $item) { ?>
                <li><?php echo htmlspecialchars($item); ?></li>
              <?php } ?>
            </ul>
            </p>

   <?php


if (isset($_SESSION['patient_id'])) {
   
    echo '<a href="membershipBoking.php?package=' . urlencode($title) . '" class="book-btn">Request</a>';
} else {
    // If the customer is not logged in, redirect to login page
    echo '<a href="customer-login.php" class="book-btn">Login to Request</a>';
}
?>


          </div>
        </div>

    <?php
      }
    } else {
      echo "<p>No membership packages available at the moment.</p>";
    }
    ?>

  </div>
</section>

<?php include('customer-main/footer.php'); ?>