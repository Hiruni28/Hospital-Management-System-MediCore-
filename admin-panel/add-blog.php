<?php 
include('partials/main.php'); 

if (isset($_POST['submit'])) {
    // Get the form data
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Handle the image upload
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        // Image name and extension
        $image_name = $_FILES['image']['name'];
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
      // Rename the image to avoid duplicate names
        $image_name = "Blog_" . rand(1000, 9999) . "." . $ext;
        // Define the source and destination path
        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../images/blogs/" . $image_name;
        // Upload the image
        if (!move_uploaded_file($source_path, $destination_path)) {
            // If image upload fails, show an error message
            $_SESSION['upload_error'] = "Failed to upload image. Please try again.";
            $image_name = ""; // Set image to an empty string
        }
    } else {
        $image_name = ""; 
    }
    $sql = "INSERT INTO tbl_blogs (title, content, image) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $title, $content, $image_name);

    if ($stmt->execute()) {
      
        header('Location:'.SITEURL.'admin-panel/manage-blogs.php?status=success&action=add_blog_success');
        exit();  
    } else {
        header('Location:'.SITEURL.'admin-panel/add-blog.php?status=error&action=add_blog_success');
        exit();    
    }
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Add Blog</h1>
        <br />

        <!-- Form to add blog -->
        <form action="add-blog.php" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td><input type="text" name="title" placeholder="Enter Blog Title" required></td>
                </tr>

                <tr>
                    <td>Content:</td>
                    <td><textarea name="content" cols="30" rows="10" placeholder="Enter Blog Content" required></textarea></td>
                </tr>

                <tr>
                    <td>Image:</td>
                    <td><input type="file" name="image"></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Blog" class="btn-primary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
