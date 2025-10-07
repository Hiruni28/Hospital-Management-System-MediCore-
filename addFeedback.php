<?php
// Include your database connection file
 include('customer-main/main.php');

if (isset($_POST['submit'])) {
    $customer_name = $_POST['name'];
    $email = $_POST['email'];
    $feedback = $_POST['feedback'];

    $sql = "INSERT INTO tbl_feedback (customer_name, email, feedback) VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $customer_name, $email, $feedback);

    if ($stmt->execute()) {
        header('Location:' . SITEURL . 'index.php?status=success&action=success_feedback');
      } else {
      
        header('Location:' . SITEURL . 'index.php?status=success&action=success_feedback');
      }

    exit();
}
?>






<!-- HTML part for the feedback form -->
<div class="feedback-container">
    <h1 style="color: black;">Add Your Feedback</h1>
    
    
    <!-- Feedback form -->
    <form action="" method="POST">
        <label for="name" style="color: black;">Your Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter your name" required>

        <label for="email" style="color: black;">Your Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>

        <label for="feedback" style="color: black;">Your Feedback:</label>
        <textarea id="feedback" name="feedback" rows="5" placeholder="Enter your feedback" required></textarea>

        <input type="submit" name="submit" value="Submit Feedback" class="btn-primary">
    </form>
</div>

<?php  include('customer-main/footer.php')?>
