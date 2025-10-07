<?php include('partials/main.php'); ?>

<div class="main-content">
  <div class="wrapper">
    <h1 class="text-center">Update Admin</h1>
    <br><br>


    <?php

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    $sql = "SELECT * FROM tbl_admin WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();

      $full_name = $row['full_name'];
      $username = $row['username'];
      $email = $row['email'];
      $position = $row['position'];
    } else {
      header('Location:' . SITEURL . 'admin/manage-admin.php');
    }


    ?>

    <form action="" method="POST" class="add">
      <table class="tbl-30">
        <tr>
          <td>Full Name:</td>
          <td>
            <input type="text" name="full_name" value="<?php echo $full_name; ?>">
          </td>
        </tr>

        <tr>
          <td>User Name</td>
          <td>
            <input type="text" name="username" value="<?php echo $username; ?>">
          </td>
        </tr>

        <tr>
          <td>Email</td>
          <td>
            <input type="text" name="email" value="<?php echo $email; ?>">
          </td>
        </tr>

        <tr>
          <td>Position:</td>
          <td>
            <select name="position">
              <option value="staff">Staff</option>
              <option value="manager">Manager</option>
            </select>
          </td>
        </tr>


        <tr>
          <td colspan="2">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="submit" name="submit" value="Update Admin" class="btn-secondary">
          </td>
        </tr>



      </table>
    </form>
  </div>
</div>

<?php

if (isset($_POST['submit'])) {

  $id = $_POST['id'];
  $full_name = $_POST['full_name'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $position = $_POST['position'];
  
  $sql = "UPDATE tbl_admin SET 
full_name = ?,
username = ?,
email = ?,
position = ?
WHERE id =?
";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ssssi',$full_name,$username,$email,$position,$id);


  if ($stmt->execute()) {

    header('Location:' . SITEURL . 'admin-panel/manage-admin.php?status=success&action=update');

  } else {
    header('Location:' . SITEURL . 'admin-panel/manage-admin.php?status=error&action=update');
  }

 
}


?>





<?php include('partials/footer.php'); ?>