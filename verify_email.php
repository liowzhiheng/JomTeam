<?php
session_start();
require('config.php');

// Get the verification token from the URL
if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    
    // Check if the token exists in the database
    $sql = "SELECT * FROM user WHERE verification_token = '$token' AND email_verified = 0";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Token is valid, verify the email
        $sql2 = "UPDATE user SET email_verified = 1 WHERE verification_token = '$token'";
        if (mysqli_query($conn, $sql2)) {
            echo "<h1>Email Verified Successfully!</h1>";
        } else {
            echo "<h1>Error verifying email. Please try again later.</h1>";
        }
    } else {
        echo "<h1>Invalid or expired verification link.</h1>";
    }
} else {
    echo "<h1>No token provided.</h1>";
}

mysqli_close($conn);
?>
