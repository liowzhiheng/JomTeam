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

    // Retrieve form data and sanitize
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $user_id = $_SESSION['ID'];

    // Update the database
    $update_query = "
        UPDATE profile 
        SET 
            name = '$name',
            gender = '$gender',
            age = '$age',
            status = '$status',
            phone = '$phone',
            description = '$description'
        WHERE user_id = '$user_id'
    ";

    if (mysqli_query($conn, $update_query)) {
        // Redirect to profile page with a success message
        header("Location: view_profile.php?status=success");
        exit();
    } else {
        header("Location: view_profile.php?status=fail");
        exit();
    }
}
?>