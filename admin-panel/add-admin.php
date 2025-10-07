<?php include('partials/main.php'); ?>


<div class="main-content">
  <div class="wrapper">
    <h1 class="text-center">Add Admin</h1>

    <br />
    <br />
    <form action="" method="POST" class="add">
      <table class="tbl-30">
        <tr>
          <td>Full Name:</td>
          <td>
            <input type="text" name="full_name" placeholder="Enter Your name">
          </td>
        </tr>

        <tr>
          <td>User Name:</td>
          <td>
            <input type="text" name="username" placeholder="Enter Your Username">
          </td>
        </tr>

        <tr>
          <td>Email:</td>
          <td>
            <input type="text" name="email" placeholder="Enter Your email">
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
          <td>Password:</td>
          <td>
            <input type="password" name="password" placeholder="Enter Your Password">
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <input type="submit" name="submit" value="Add Admin" class="btn-secondary">
          </td>
        </tr>

      </table>
    </form>
  </div>
</div>



<?php

if (isset($_POST['submit'])) {

  $full_name = $_POST['full_name'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $position = $_POST['position'];
  $password = md5($_POST['password']);


  $sql = "INSERT INTO tbl_admin SET 
 full_name=?,
 username=?,
 email=?,
 position=?,
 password=?
 ";

  $sql = "INSERT INTO tbl_admin (full_name, username, email, position, password) VALUES (?, ?, ?, ?, ? )";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sssss', $full_name, $username, $email, $position, $password);



  if ($stmt->execute()) {
    header("Location: " . SITEURL . 'admin-panel/manage-admin.php?status=success&action=add');
  } else {
    header("Location: " . SITEURL . 'admin-panel/add-admin.php?status=error&action=add');
  }


  exit();
}


?>