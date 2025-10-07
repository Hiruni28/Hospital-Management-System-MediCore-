<?php 
include('partials/main.php'); 
?>
<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Available Membership Packages</h1>

      
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
                  
                </tr>
            </thead>
            <tbody>

            <?php
            // 1. SQL query to fetch data from tbl_packages
            $sql = "SELECT * FROM tbl_packages";
            $res = mysqli_query($conn, $sql); // $conn is the database connection
            $sn = 1;

            // 2. Check if there are any packages
            if($res && mysqli_num_rows($res) > 0) {
                // 3. Loop through each package and display it
                while($row = mysqli_fetch_assoc($res)) {
                    $id = $row['id'];
                    $title = $row['title'];
                    $price_local = $row['price_local'];
                    $price_foreigner = $row['price_foreigner'];
                    $image_name = $row['image_name'];
                    $access = json_decode($row['access']); // Decoding JSON array
                    $features = json_decode($row['features']); // Decoding JSON array
                    $active = $row['active'];

                    ?>
                    <tr>
                    <td><?php  echo $sn++; ?></td>
                        <td><?php echo $title; ?></td>
                     

                        <!-- Display Image -->
                        <td>
                            <?php 
                            if($image_name != "") {
                                // Show image if it exists
                                ?>
                                <img src="../images/membership/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" style="width:100px;height:100px;">
                                <?php
                            } else {
                                echo "No Image";
                            }
                            ?>
                        </td>

                        <!-- Prices -->
                        <td><?php echo $price_local; ?></td>
                        <td><?php echo $price_foreigner; ?></td>

                        <!-- Access as a list -->
                        <td>
                            <ul>
                                <?php foreach($access as $item) { echo "<li>" . $item . "</li>"; } ?>
                            </ul>
                        </td>

                        <!-- Features as a list -->
                        <td>
                            <ul>
                                <?php foreach($features as $item) { echo "<li>" . $item . "</li>"; } ?>
                            </ul>
                        </td>

                        <!-- Active Status -->
                        <td><?php echo $active; ?></td>

                      
                    </tr>
                    <?php
                }
            } else {
                // No packages found
                echo "<tr><td colspan='9' class='error'>No Packages Found.</td></tr>";
            }
            ?>
            
            </tbody>
        </table>
        <!-- End of Table -->
    </div>
</div>

<?php include('partials/footer.php'); ?>
