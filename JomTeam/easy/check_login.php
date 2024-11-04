<?php
session_start(); // Start up your PHP Session

require('config.php'); // Include the database configuration file

// username and password sent from form
$myusername = $_POST["username"];
$mypassword = $_POST["password"];

// Sanitize user inputs to prevent SQL injection
$myusername = mysqli_real_escape_string($conn, $myusername);
$mypassword = mysqli_real_escape_string($conn, $mypassword);

$sql = "SELECT * FROM user WHERE username='$myusername' AND password='$mypassword'";

$result = mysqli_query($conn, $sql);

$rows = mysqli_fetch_assoc($result);

$user_name = $rows["username"];
$user_id = $rows["id"];  // Assuming `id` is the unique identifier in the `user` table
$user_level = $rows["level"];

// mysqli_num_rows is counting table rows
$count = mysqli_num_rows($result);

// If result matched $myusername and $mypassword, table row must be 1 row
if ($count == 1) {

    // Add user information to the session (global session variables)        
    $_SESSION["Login"] = "YES";
    $_SESSION["USER"] = $user_name;
    $_SESSION["ID"] = $user_id;
    $_SESSION["LEVEL"] = $user_level;

    // Check if the profile already exists
    $check_profile_sql = "SELECT * FROM profile WHERE user_id = '$user_id'";
    $check_profile_result = mysqli_query($conn, $check_profile_sql);

    if (mysqli_num_rows($check_profile_result) == 0) {
        // Insert a new record into the profile table
        $insert_profile_sql = "INSERT INTO profile (user_id) VALUES ('$user_id')";
        mysqli_query($conn, $insert_profile_sql);
    }
    echo '<html>
    <head>
        <link rel="stylesheet" href="check_login.css">
    </head>
    <body>
        <div class="container">
            <h2>You are now logged in as <strong>' . htmlspecialchars($_SESSION["USER"]) . '</strong> <br>
            <h2>with access level <strong>' . htmlspecialchars($_SESSION["LEVEL"]) . '</strong></h2>
           
            <div>
                <a href="main.php" class="button">Enter site</a>
                <a href="index.php" class="button">Back to login page</a>
            </div>
        </div>
    </body>
    </html>';

    // If wrong username and password
} else {
    $_SESSION["Login"] = "NO";
    header("Location: index.php");
}

mysqli_close($conn);
