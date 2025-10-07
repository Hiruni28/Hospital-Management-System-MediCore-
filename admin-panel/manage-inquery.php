<?php 
include('partials/main.php'); 

if (isset($_POST['submit'])) {
    $query_id = $_POST['query_id']; 
    $reply_message = $_POST['reply']; 

    $sql = "UPDATE tbl_contact SET reply_message = ?, status = 'Replied' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $reply_message, $query_id);

    if ($stmt->execute()) {
        

        header("Location:".SITEURL."admin-panel/manage-inquery.php?status=success&action=success_sent");
        exit(); 

    } else {
        echo "<div class='error'>Failed to send the reply. Please try again.</div>";
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $query_id = $_GET['id']; 

    $sql = "DELETE FROM tbl_contact WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $query_id);

    if ($stmt->execute()) {
        header('Location: manage-queries.php?status=deleted');
        exit();
    } else {
        echo "<div class='error'>Failed to delete the query. Please try again.</div>";
    }
}

// Fetch all the customer queries from the database
$sql = "SELECT * FROM tbl_contact ORDER BY id ASC";
$res = $conn->query($sql);
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Manage Queries</h1>

        <table class="tbl-full">
    <thead>
        <tr>
            <th>S.N</th>
            <th>Customer Name</th>
            <th>Customer Email</th>
            <th>Message (Query)</th>
            <th>Status</th>
            <th>Reply & Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($res->num_rows > 0) {
            $sn = 1;
            while ($row = $res->fetch_assoc()) {
                $id = $row['id'];
                $full_name = $row['full_name'];
                $email = $row['email'];
                $message = $row['message'];
                $status = $row['status'];
                $reply = $row['reply_message'];
                ?>
                <tr>
                    <td><?php echo $sn++; ?></td>
                    <td><?php echo $full_name; ?></td>
                    <td><?php echo $email; ?></td>
                    <td><?php echo $message; ?></td>
                    <td><?php echo $status; ?></td>
                    <td>
                        <?php if ($status == 'Pending') { ?>
                            <form action="" method="POST">
                                <textarea name="reply" placeholder="Type reply here" rows="3" required></textarea><br>
                                <input type="hidden" name="query_id" value="<?php echo $id; ?>">
                                <input type="submit" name="submit" value="Send Message" class="btn-secondary">
                            </form>
                        <?php } else { ?>
                            <div><strong>Reply:</strong> <?php echo $reply; ?></div>
                        <?php } ?>
                        
                        <a href="delete-queries.php?action=delete&id=<?php echo $id; ?>" class="btn-membersDelete" onclick="return confirm('Are you sure you want to delete this query?');">Delete</a>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='6' class='error'>No queries available.</td></tr>";
        }
        ?>
    </tbody>
</table>

    </div>
</div>

<?php include('partials/footer.php'); ?>
