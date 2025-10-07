<?php 
include('partials/main.php'); // Include your header and database connection
?>

<div class="main-content">
  <div class="wrapper">
    <h1>Manage Special Events</h1>
    <br />

    <!-- Button to add special events -->
    <a href="add-events.php" class="btn-primary">Add Event</a>

    <br /><br />

    <table class="tbl-full">
      <tr>
        <th>S.N</th>
        <th>Event Name</th>
        <th>Image</th>
        <th>Event Date & Time</th>
        <th>Gifts</th>
        <th>Description</th>
        <th>Active</th>
        <th>Action</th>
      </tr>

      <?php
// Fetch events from the database
$sql = "SELECT * FROM tbl_events";
$res = $conn->query($sql);

if ($res->num_rows > 0) {
  $sn = 1; 

  while ($row = $res->fetch_assoc()) {
    $id = $row['id'];
    $event_name = $row['event_name'];
    $event_datetime = $row['event_datetime'];
    $gifts = $row['gifts'];
    $description = $row['description'];
    $image_name = $row['image_name'];
    $active = $row['active']; 
    $image_path = ($image_name != "") ? "../images/events/" . $image_name : "../images/placeholder.png";
    ?>

    <tr>
      <td><?php echo $sn++; ?></td>
      <td><?php echo $event_name; ?></td>
      <td>
        <img src="<?php echo $image_path; ?>" width="100" height="100" alt="Event Image" onerror="this.src='../images/placeholder.png';">
      </td>
      <td><?php echo $event_datetime; ?></td>
      <td><?php echo $gifts; ?></td>
      <td><?php echo $description; ?></td>
      <td><?php echo $active; ?></td> 
      <td>
        <a href="update-events.php?id=<?php echo $id; ?>" class="btn-members">Update Event</a>
        <a href="delete-events.php?id=<?php echo $id; ?>" class="btn-membersDelete" onclick="return confirm('Are you sure you want to delete this event?');">Delete Event</a>
      </td>
    </tr>

    <?php
  }
} else {
  // No events found
  echo "<tr><td colspan='8' class='error'>No events added yet!</td></tr>"; // Updated colspan to 8
}
?>

      
    </table>
  </div>
</div>

<?php include('partials/footer.php'); ?>
