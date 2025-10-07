<?php include('partials/main.php'); ?>

<div class="main-content">
  <div class="wrapper">
    <h1 class="text-center">Add Doctor</h1>
    <br />
    <br />

    <form action="" method="POST" class="add">
      <table class="tbl-30">
        <tr>
          <td>Specialization:</td>
          
            
            <td>
                      <select name="specilization">
                            <?php 
                            // Fetch categories from the database
                            $sql = "SELECT * FROM tbl_programCategories WHERE active='Yes'";
                            $res = $conn->query($sql);
                            if ($res->num_rows > 0) {
                                while ($row = $res->fetch_assoc()) {
                                    echo "<option value='{$row['title']}'>{$row['title']}</option>";
                                }
                            } else {
                                echo "<option value=''>No categories available</option>";
                            }
                            ?>
                        </select>
                    </td>
          </td>
        </tr>

        <tr>
          <td>Doctor Name:</td>
          <td>
            <input type="text" name="doctorName" placeholder="Enter Doctor Name">
          </td>
        </tr>

        <tr>
                    <td>Address:</td>
                    <td><textarea name="address" rows="5" placeholder="Enter Doctor Address"></textarea></td>
        </tr>

        <tr>
          <td>Doctor Fees:</td>
          <td>
            <input type="text" name="docFees" placeholder="Enter Doctor Fees">
          </td>
        </tr>

        <tr>
          <td>Contact:</td>
          <td>
            <input type="text" name="contactno" placeholder="Enter Doctor Contact">
          </td>
        </tr>

        <tr>
          <td>Email:</td>
          <td>
            <input type="text" name="docEmail" placeholder="Enter Doctor Email">
          </td>
        </tr>

        <tr>
          <td>UserName:</td>
          <td>
            <input type="text" name="username" placeholder="Enter Doctor UserName">
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
            <input type="submit" name="submit" value="Add Doctor" class="btn-secondary">
          </td>
        </tr>

      </table>
    </form>
  </div>
</div>

<?php

if (isset($_POST['submit'])) {
  $specilization = $_POST['specilization']; 
  $doctorName = $_POST['doctorName'];
  $address = $_POST['address'];
  $docFees = $_POST['docFees'];
  $contactno = $_POST['contactno'];
  $docEmail = $_POST['docEmail'];
  $username = $_POST['username'];
  $password = md5($_POST['password']); // Password Encryption
  
    $sql = "INSERT INTO doctors SET 
  specilization=?,
  doctorName=?,
  address=?,
  docFees=?,
  contactno=?,
  docEmail=?,
  username=?,
  password=?
 ";

  $sql = "INSERT INTO doctors (specilization, doctorName, address, docFees, contactno, docEmail, username, password) VALUES (?, ?, ?, ?, ?, ?,?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ssssssss', $specilization, $doctorName, $address, $docFees, $contactno, $docEmail, $username, $password);

  if ($stmt->execute()) {
    header("Location: " . SITEURL . 'admin-panel/manage-doctor.php?status=success&action=doc_add');
  } else {
    header("Location: " . SITEURL . 'admin-panel/add-doctor.php?status=error&action=doc_add');
  }

  exit();

}


?>
