<?php include('../config/constant.php');?> 

<?php 
$id = $_GET['id'];
$sql = "DELETE FROM tbl_admin WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

$res = $stmt->execute();

if ($res) {
    header("Location: " . SITEURL . 'admin-panel/manage-admin.php?status=success&action=delete');
} else {
    header("Location: " . SITEURL . 'admin-panel/manage-admin.php?status=error&action=delete');
}

$stmt->close();



?>