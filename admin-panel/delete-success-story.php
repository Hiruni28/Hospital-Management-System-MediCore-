<?php 
include('partials/main.php'); 

// Check if the ID is set in URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT image_name FROM tbl_success_stories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $image_name = $row['image_name'];

    if ($image_name != "") {
        $path = "../images/success/" . $image_name;
        if (file_exists($path)) {
            unlink($path);
        }
    }

    $sql = "DELETE FROM tbl_success_stories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: manage-success-stories.php?status=success&action=delete_success_story');
        exit();
    } else {
        echo "<div class='error'>Failed to delete success story.</div>";
    }
} else {
    header('Location: manage-success-stories.php');
}
?>
