<?php include('partials/main.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Add Success Story</h1>
        
        <form action="add-success-story.php" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td><input type="text" name="title" placeholder="Enter Title" required></td>
                </tr>
                
                <tr>
                    <td>Email:</td>
                    <td><input type="email" name="email" placeholder="Enter Email" required></td>
                </tr>

                <tr>
                    <td>Description:</td>
                    <td><textarea name="description" rows="5" placeholder="Enter Description" required></textarea></td>
                </tr>

                <tr>
                    <td>Select Image:</td>
                    <td><input type="file" name="image" required></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Success Story" class="btn-primary">
                    </td>
                </tr>
            </table>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            $title = $_POST['title'];
            $email = $_POST['email'];
            $description = $_POST['description'];

            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                $image_name = $_FILES['image']['name'];
                $ext = pathinfo($image_name, PATHINFO_EXTENSION);

                $image_name = "Success_Story_" . rand(1000, 9999) . "." . $ext;

                $source_path = $_FILES['image']['tmp_name'];
                $destination_path = "../images/success/" . $image_name;

                if (!move_uploaded_file($source_path, $destination_path)) {
                    echo "<div class='error'>Failed to upload image. Please try again.</div>";
                    $image_name = ""; 
                }
            } else {
                $image_name = ""; 
            }

            $sql = "INSERT INTO tbl_success_stories (title, description, email, image_name) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssss', $title, $description, $email, $image_name);

            if ($stmt->execute()) {
                header('Location:' . SITEURL . 'admin-panel/manage-success-stories.php?status=success&action=add_success_story');
                exit();
            } else {
                echo "<div class='error'>Failed to add success story. Please try again.</div>";
            }
        }
        ?>
    </div>
</div>

<?php include('partials/footer.php'); ?>
