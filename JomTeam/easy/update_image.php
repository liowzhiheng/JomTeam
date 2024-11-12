<?php
session_start();

require("config.php");

if (isset($_POST['submit'])) {
    $file_name = $_FILES['image']['name'];
    $tempname = $_FILES['image']['tmp_name'];
    $folder = 'uploads/' . $file_name;

    // Check if an image already exists for this user
    $user_id = $_SESSION['ID']; // Assuming user ID is stored in the session
    $result = mysqli_query($conn, "SELECT file FROM images WHERE user_id = '$user_id'");

    if (mysqli_num_rows($result) > 0) {
        // Get the current image file name
        $row = mysqli_fetch_assoc($result);
        $old_file = 'uploads/' . $row['file'];

        // Update the existing record
        $query = mysqli_query($conn, "UPDATE images SET file = '$file_name' WHERE user_id = '$user_id'");

        // Delete the old file if it exists
        if (file_exists($old_file)) {
            unlink($old_file);
        }
    } else {
        // Insert a new record if no existing image found
        $query = mysqli_query($conn, "INSERT INTO images (user_id, file) VALUES ('$user_id', '$file_name')");
    }

    // Move the uploaded file to the uploads directory
    if ($query && move_uploaded_file($tempname, $folder)) {
        header("Location: view_profile.php?status=success");
        exit();
    } else {
        header("Location: view_profile.php?status=fail");
        exit();
    }
}
?>