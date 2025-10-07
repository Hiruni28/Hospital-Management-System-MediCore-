<?php  
include('partials/main.php');  

// Check if the program ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $program_id = intval($_GET['id']);

    // Fetch the program image name to delete the file
    $sql = "SELECT image_name FROM tbl_programs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $program_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        $image_name = $row['image_name'];

        // Delete the program from the database
        $sql = "DELETE FROM tbl_programs WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $program_id);

        if ($stmt->execute()) {
            // Delete the image file if it exists
            $image_path = "../images/programs/" . $image_name;
            if (!empty($image_name) && file_exists($image_path)) {
                unlink($image_path);
            }

            header('Location: manage-classProgram.php?status=success&action=delete_program');
            exit();
        } else {
            echo "<div class='error'>Failed to delete the class. Please try again.</div>";
        }
    } else {
        header('Location: manage-classProgram.php?status=error&action=not_found');
        exit();
    }
} else {
    header('Location: manage-classProgram.php?status=error&action=invalid_id');
    exit();
}

include('partials/footer.php');  
?>

