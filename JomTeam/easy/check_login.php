<?php
session_start(); // Start up your PHP Session

require('config.php'); // Include the database configuration file

// email and password sent from form
$myemail = $_POST["email"];
$mypassword = $_POST["password"];

// Sanitize user inputs to prevent SQL injection
$myemail = mysqli_real_escape_string($conn, $myemail);
$mypassword = mysqli_real_escape_string($conn, $mypassword);

$sql = "SELECT * FROM user WHERE email='$myemail' AND password='$mypassword'";

$result = mysqli_query($conn, $sql);

$rows = mysqli_fetch_assoc($result);

$user_name = $rows["email"];
$user_id = $rows["id"];  // Assuming `id` is the unique identifier in the `user` table
$user_level = $rows["level"];

// mysqli_num_rows is counting table rows
$count = mysqli_num_rows($result);

// If result matched $myemail and $mypassword, table row must be 1 row
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
        // Insert a new record into the profile and image tables
        $insert_sql = "
            INSERT INTO profile (user_id) VALUES ('$user_id');
            INSERT INTO images (user_id) VALUES ('$user_id');
        ";
        mysqli_multi_query($conn, $insert_sql);
    }
    
    echo '<html>
    <head>
        <link rel="stylesheet" href="check_login.css">
    </head>
    <body>
        <div class="container">
            <h2>You are now logged in as <strong>' . htmlspecialchars($_SESSION["USER"]) . '</strong></h2>
            <h2>With access level <strong>' . htmlspecialchars($_SESSION["LEVEL"]) . '</strong></h2>';

    if ($_SESSION["LEVEL"] == 1) {
        echo '<a href="view_user.php" class="button">Enter site</a>';
    } else {
        echo '<a href="main.php" class="button">Enter site</a>';
    }

    echo '<br><a href="login.php" class="button">Back to login page</a>
        </div>
    </body>
</html>';


    // If wrong email and password
} else {
    $_SESSION["Login"] = "NO";
    header("Location: login.php");
}

mysqli_close($conn);
