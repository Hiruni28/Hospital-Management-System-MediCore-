<?php 
include('partials/main.php'); 
?>
<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Available Membership Packages</h1>

        <a href="add-membership.php" class="btn-primary">Add Packages</a>
        <br><br>

        <!-- Start of Table -->
        <table border="1">
            <thead>
                <tr>
                    <th>Package Id</th>
                    <th>Package Title</th>                 
                    <th>Image</th>
                    <th>Price for Locals</th>
                    <th>Price for Foreigners</th>
                    <th>Access</th>
                    <th>Features</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            <?php
            $sql = "SELECT * FROM tbl_packages";
            $res = mysqli_query($conn, $sql); 
            $sn = 1;

            if($res && mysqli_num_rows($res) > 0) {
                while($row = mysqli_fetch_assoc($res)) {
                    $id = $row['id'];
                    $title = $row['title'];
                    $price_local = $row['price_local'];
                    $price_foreigner = $row['price_foreigner'];
                    $image_name = $row['image_name'];
                    $access = json_decode($row['access']); 
                    $features = json_decode($row['features']); 
                    $active = $row['active'];
                    ?>
                    <tr>
                    <td><?php  echo $sn++; ?></td>
                        <td><?php echo $title; ?></td>                   
                        <td>
                            <?php 
                            if($image_name != "") {
                                ?>
                                <img src="../images/membership/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" style="width:100px;height:100px;">
                                <?php
                            } else {
                                echo "No Image";
                            }
                            ?>
                        </td>
                        <td><?php echo $price_local; ?></td>
                        <td><?php echo $price_foreigner; ?></td>
                        <td>
                            <ul>
                                <?php foreach($access as $item) { echo "<li>" . $item . "</li>"; } ?>
                            </ul>
                        </td>
                        <td>
                            <ul>
                                <?php foreach($features as $item) { echo "<li>" . $item . "</li>"; } ?>
                            </ul>
                        </td>
                        <td><?php echo $active; ?></td>
                      <td>
                            <a href="update-membership.php?id=<?php echo $id; ?>" class="btn-members">Update Package</a><br>
                            <a href="delete-membership.php?id=<?php echo $id; ?>&image_name=<?php echo $image_name; ?>" class="btn-membersDelete">Delete Package</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='9' class='error'>No Packages Found.</td></tr>";
            }
            ?>
            
            </tbody>
        </table>
        <!-- End of Table -->
    </div>
</div>

<?php include('partials/footer.php'); ?>
