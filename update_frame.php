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
    $user_id = $_SESSION['ID'];
    $frame = mysqli_real_escape_string($conn, $_POST['frame']);

    $update_query = "UPDATE profile SET frame = '$frame' WHERE user_id = '$user_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: view_profile.php?status=success");
        exit();
    } else {
        header("Location: view_profile.php?status=fail");
        exit();
    }
}