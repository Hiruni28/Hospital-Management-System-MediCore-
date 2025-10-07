<?php include('../config/constant.php');?> 

<?php 
$id = $_GET['id'];
$sql = "DELETE FROM doctors WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

$res = $stmt->execute();

if ($res) {
    header("Location: " . SITEURL . 'admin-panel/manage-doctor.php?status=success&action=doc_delete');
} else {
    header("Location: " . SITEURL . 'admin-panel/manage-doctor.php?status=error&action=doc_delete');
}

$stmt->close();



?>