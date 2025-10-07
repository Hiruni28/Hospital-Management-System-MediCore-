<?php
include('partials/main.php');

// Check if the promotion ID is provided in the URL
if (isset($_GET['id'])) {
    $promotion_id = $_GET['id'];

    $sql = "SELECT image_name FROM tbl_promotions WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $promotion_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {
        $row = $res->fetch_assoc();
        $image_name = $row['image_name'];

        $sql_delete = "DELETE FROM tbl_promotions WHERE id=?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param('i', $promotion_id);

        if ($stmt_delete->execute()) {
            if ($image_name != "") {
                $image_path = "../images/promotions/" . $image_name;
                if (file_exists($image_path)) {
                    unlink($image_path); 
                }
            }

            header("Location:".SITEURL."admin-panel/manage-promotion.php?status=success&action=delete_promotion");
            exit(); 
        } else {
            header('Location: manage-promotions.php?status=error&action=delete_failed');
            exit();
        }
    } else {
        header('Location: manage-promotions.php?status=error&action=promotion_not_found');
        exit();
    }
} else {
    header('Location: manage-promotions.php?status=error&action=invalid_id');
    exit();
}
?>
