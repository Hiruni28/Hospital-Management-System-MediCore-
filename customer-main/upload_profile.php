<?php
// upload_profile.php
session_start();
include('../config/constant.php'); // Adjust path if needed

// Check if patient is logged in
if (!isset($_SESSION['patient_id'])) {
    die("You must be logged in to upload a profile picture.");
}

$patient_id = $_SESSION['patient_id'];
$message = "";

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["profile_image"])) {
    $targetDir = "../uploads/"; // folder to save images
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // create folder if it doesn't exist
    }

    $fileName = basename($_FILES["profile_image"]["name"]);
    $targetFile = $targetDir . time() . "_" . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validate image
    $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
    if ($check === false) {
        $message = "File is not an image.";
    } elseif ($_FILES["profile_image"]["size"] > 2 * 1024 * 1024) { // 2MB limit
        $message = "Sorry, your file is too large. Max 2MB.";
    } elseif (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        $message = "Only JPG, JPEG, PNG & GIF files are allowed.";
    } else {
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
            // Save file path in database
            $filePathDB = "uploads/" . time() . "_" . $fileName;
            $sql = "UPDATE tbl_patient SET profile_image='$filePathDB' WHERE patient_id='$patient_id'";
            if (mysqli_query($conn, $sql)) {
                $message = "Profile picture updated successfully!";
            } else {
                $message = "Database update failed: " . mysqli_error($conn);
            }
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Profile Picture</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            text-align: center;
            padding: 30px;
        }
        .upload-box {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        input[type="file"] {
            padding: 8px;
            margin: 10px 0;
        }
        input[type="submit"] {
            background: #28a745;
            border: none;
            padding: 10px 20px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .message {
            margin-top: 15px;
            font-weight: bold;
            color: #333;
        }
        img {
            margin-top: 10px;
            border-radius: 50%;
            max-width: 150px;
        }
    </style>
</head>
<body>

<div class="upload-box">
    <h2>Upload Your Profile Picture</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="profile_image" accept="image/*" required>
        <br>
        <input type="submit" value="Upload">
    </form>

    <?php
    if (!empty($message)) {
        echo "<div class='message'>$message</div>";
    }

    // Display current profile image
    $result = mysqli_query($conn, "SELECT profile_image FROM tbl_patient WHERE patient_id='$patient_id'");
    if ($row = mysqli_fetch_assoc($result)) {
        if (!empty($row['profile_image'])) {
            echo "<img src='../" . htmlspecialchars($row['profile_image']) . "' alt='Profile Picture'>";
        }
    }
    ?>
</div>

</body>
</html>
