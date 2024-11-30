<?php
session_start(); // Start the session

require("config.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user ID is set in the session
    if (!isset($_SESSION['ID'])) {
        echo "User ID is not set in the session.";
        exit();
    }

    // Retrieve and sanitize form data
    $first_name = mysqli_real_escape_string($conn, $_POST['fname']);
    $last_name = mysqli_real_escape_string($conn, $_POST['lname']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $interests = mysqli_real_escape_string($conn, $_POST['interests']);
    $user_id = $_SESSION['ID'];

    // Handle the image upload if there's a file
    $file_name = null;  // Initialize as null, so it doesn't overwrite the current image if no new image is selected
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file_name = $_FILES['image']['name'];
        $tempname = $_FILES['image']['tmp_name'];
        $folder = 'uploads/' . $file_name;

        // Check if an image already exists for this user
        $result = mysqli_query($conn, "SELECT file FROM images WHERE user_id = '$user_id'");

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $old_file = 'uploads/' . $row['file'];

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
        if (!$query || !move_uploaded_file($tempname, $folder)) {
            // If the image upload fails, redirect with failure status
            header("Location: view_profile.php?status=fail");
            exit();
        }
    }

    $update_query = "
        UPDATE user u
        JOIN profile p ON u.id = p.user_id
        SET 
            u.first_name = '$first_name',
            u.last_name = '$last_name',
            u.gender = '$gender',
            u.phone = '$phone',
            p.status = '$status',
            p.description = '$description',
            p.location = '$location',
            p.interests = '$interests'
        WHERE u.id = '$user_id'
    ";

    if (mysqli_query($conn, $update_query)) {
        header("Location: view_profile.php?status=success");
        exit();
    } else {
        header("Location: view_profile.php?status=fail");
        exit();
    }
}
?>