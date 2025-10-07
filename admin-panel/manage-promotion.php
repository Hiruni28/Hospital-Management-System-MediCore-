<?php include('partials/main.php'); ?>

<div class="main-content">
  <div class="wrapper">
    <h1>Manage Promotions</h1>
    <br />

    <!-- Button to add promotion -->
    <a href="add-promotion.php" class="btn-primary">Add Promotion</a>

    <br />
    <br />

    <table class="tbl-full">
      <tr>
        <th>S.N</th>
        <th>Title</th>
        <th>Program Name</th>
        <th>Image</th>
        <th>Price</th>
        <th>Discount Rate</th>
        <th>Price with Discount</th>
        <th>Description</th>
        <th>Active</th>
        <th>Action</th>
      </tr>

      <?php
      // Fetch promotions from the database
      $sql = "SELECT * FROM tbl_promotions";
      $res = $conn->query($sql);

      if ($res->num_rows > 0) {
        $sn = 1; // Serial number
        while ($row = $res->fetch_assoc()) {
          $id = $row['id'];
          $title = $row['title'];
          $program_name = $row['program_name'];
          $image_name = $row['image_name'];
          $price = $row['price'];
          $discount_rate = $row['discount_rate'];
          $price_with_discount = $row['discounted_price'];
          $description = $row['description']; // Fetch description
          $active = $row['active'];
      ?>
        <tr>
          <td><?php echo $sn++; ?></td>
          <td><?php echo htmlspecialchars($title); ?></td>
          <td><?php echo htmlspecialchars($program_name); ?></td>
          <td>
            <?php if ($image_name != "") { ?>
              <img src="../images/promotions/<?php echo $image_name; ?>" width="100" height="100" alt="Promotion Image">
            <?php } else { ?>
              <div class="error">No Image Available</div>
            <?php } ?>
          </td>
          <td><?php echo htmlspecialchars($price); ?></td>
          <td><?php echo intval($row['discount_rate']); ?>%</td>
          <td><?php echo htmlspecialchars($price_with_discount); ?></td>
          <td><?php echo htmlspecialchars($description); ?></td> <!-- Display description -->
          <td><?php echo htmlspecialchars($active); ?></td>
          <td>
            <a href="update-promotion.php?id=<?php echo $id; ?>" class="btn-members">Update</a>
            <a href="delete-promotion.php?id=<?php echo $id; ?>" class="btn-membersDelete">Delete</a>
          </td>
        </tr>
      <?php                        

        }
      } else {
        echo "<tr><td colspan='10'><div class='error'>No promotions added yet.</div></td></tr>";
      }
      ?>

    </table>
  </div>
</div>





<?php include('partials/footer.php'); ?>
