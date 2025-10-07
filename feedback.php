<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | sL Code Hub</title>
    <link rel="stylesheet" href="cus_css/style-customer-login.css">
    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="js/script.js"></script>
</head>

<body>

    <div class="contaner">
        <form action="" method="POST">
            <h1>Login</h1>
            <div class="input-box">
                <input type="text" name="full_name" placeholder="Full Name">
                <i class='bx bx-user'></i>
            </div>
            <div class="input-box">
                <input type="email" name="email" placeholder="Email">
                <i class='bx bx-lock-alt'></i>
            </div>
            <div class="input-box">
                <textarea name="text" rows="8" cols="32" placeholder="Message"></textarea>
                <i class='bx bx-lock-alt'></i>   
            </div>
            <input type="submit" name="submit" value="Submit Feedback" class="btn">

            <div class="register">
                <p>Go Back <a href="index.php">Click Here</a></p>
            </div>
        </form>
    </div>
</body>

</html>
