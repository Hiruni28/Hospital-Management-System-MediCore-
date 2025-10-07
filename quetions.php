<?php
 include('customer-main/main.php'); 

// Fetch all the contact messages and replies from the database
$sql = "SELECT * FROM tbl_contact ORDER BY id ASC";
$res = $conn->query($sql);
?>

<div class="container_message">
    <div class="header_message">
        <h2 class="membership-title center" style="color: orangered;">Inquery Replies</h2>
    </div>

    <!-- Loop through each message and display -->
    <?php
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $full_name = htmlspecialchars($row['full_name']);
            $email = htmlspecialchars($row['email']);
            $message = htmlspecialchars($row['message']);
            $reply_message = htmlspecialchars($row['reply_message']);
    ?>
    <!-- Wrapping each message and reply in one div -->
    <div class="message-container">
        <div class="message-box">
            <div class="customer-info">
                <span>Name: <?php echo $full_name; ?></span>
                <span>Email: <?php echo $email; ?></span>
            </div>
            <div class="message-label">Message:</div>
            <div class="message-content">
                <?php echo $message; ?>
            </div>
        </div>

        <?php if (!empty($reply_message)) { ?>
        <div class="message-box staff-reply">
            <div class="message-label">Reply:</div>
            <div class="reply-content">
                <?php echo $reply_message; ?>
            </div>
        </div>
        <?php } else { ?>
        <div class="message-box staff-reply">
            <div class="reply-content">
                <em>No reply yet</em>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php
        }
    } else {
        echo "<p>No messages found.</p>";
    }
    ?>
</div>

<?php include('customer-main/footer.php'); ?>
