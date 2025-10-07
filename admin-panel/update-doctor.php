<?php include('partials/main.php'); ?>

<div class="main-content">
  <div class="wrapper">
    <h1 class="text-center">Update Doctor</h1>
    <br><br>

    <?php
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Ensure DB connection


    $sql = "SELECT * FROM doctors WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();

      $specilization = $row['specilization'];
      $doctorName = $row['doctorName'];
      $address = $row['address'];
      $docFees = $row['docFees'];
      $contactno = $row['contactno'];
      $docEmail = $row['docEmail'];
      $username = $row['username'];
      $password = $row['password'];
    } else {
      header('Location:' . SITEURL . 'admin-panel/manage-doctor.php');
      exit;
    }
    ?>

    <form action="" method="POST" class="add">
      <input type="hidden" name="id" value="<?php echo $id; ?>">

      <table class="tbl-30">
        <tr>
          <td>Specialization:</td>
          <td>
            <input type="text" name="specilization" value="<?php echo $specilization; ?>">
          </td>
        </tr>

        <tr>
          <td>Doctor Name:</td>
          <td>
            <input type="text" name="doctorName" value="<?php echo $doctorName; ?>">
          </td>
        </tr>

        <tr>
          <td>Address:</td>
          <td>
            <textarea name="address" rows="5"><?php echo $address; ?></textarea>
          </td>
        </tr>

        <tr>
          <td>Doctor Fees:</td>
          <td>
            <input type="text" name="docFees" value="<?php echo $docFees; ?>">
          </td>
        </tr>

        <tr>
          <td>Contact:</td>
          <td>
            <input type="text" name="contactno" value="<?php echo $contactno; ?>">
          </td>
        </tr>

        <tr>
          <td>Email:</td>
          <td>
            <input type="text" name="docEmail" value="<?php echo $docEmail; ?>">
          </td>
        </tr>

        <tr>
          <td>Username:</td>
          <td>
            <input type="text" name="username" value="<?php echo $username; ?>">
          </td>
        </tr>

        <tr>
          <td>Password:</td>
          <td>
            <input type="password" name="password" value="<?php echo $password; ?>">
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <input type="submit" name="submit" value="Update Doctor" class="btn-secondary">
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>

<?php
if (isset($_POST['submit'])) {
  $id = $_POST['id'];
  $specilization = $_POST['specilization'];
  $doctorName = $_POST['doctorName'];
  $address = $_POST['address'];
  $docFees = $_POST['docFees'];
  $contactno = $_POST['contactno'];
  $docEmail = $_POST['docEmail'];
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Fixed SQL - removed trailing comma before WHERE
  $sql = "UPDATE doctors SET 
    specilization = ?, 
    doctorName = ?, 
    address = ?, 
    docFees = ?, 
    contactno = ?, 
    docEmail = ?, 
    username = ?,
    password = ?
    WHERE id = ?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ssssssssi', $specilization, $doctorName, $address, $docFees, $contactno, $docEmail, $username, $password, $id);

  if ($stmt->execute()) {
    header('Location:' . SITEURL . 'admin-panel/manage-doctor.php?status=success&action=doc_update');
    exit;
  } else {
    echo "<div class='error'>Failed to Update Doctor: " . $stmt->error . "</div>";
  }
}
?>

<?php include('partials/footer.php'); ?>
