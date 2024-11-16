<?php
session_start(); // Start the session

require("config.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user ID is set in session
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

    // Update the user table
    $update_user_query = "
        UPDATE user 
        SET 
            first_name = '$first_name',
            last_name = '$last_name',
            gender = '$gender',
            phone = '$phone'
        WHERE id = '$user_id'
    ";

    // Update the profile table
    $update_profile_query = "
        UPDATE profile
        SET 
            status = '$status',
            description = '$description',
            location = '$location',
            interests = '$interests'
        WHERE user_id = '$user_id'
    ";

    // Execute queries and check for success
    if (mysqli_query($conn, $update_user_query) && mysqli_query($conn, $update_profile_query)) {
        // Redirect to profile page with a success message
        header("Location: view_profile.php?status=success");
        exit();
    } else {
        // Log error for debugging (optional)
        error_log("Error updating profile: " . mysqli_error($conn));

        // Redirect to profile page with a failure message
        header("Location: view_profile.php?status=fail");
        exit();
    }
}
?>
