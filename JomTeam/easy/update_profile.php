<?php
session_start(); // Start up your PHP Session

// Check if the user is logged in
if ($_SESSION["Login"] != "YES") {
    header("Location: index.php");
    exit();
}

require("config.php"); // Include the database configuration file

// Get the user's profile data from the form submission
$user_id = $_SESSION["ID"];
$name =  $_POST['name'];
$ic = $_POST['ic'];
$gender = $_POST['gender'];
$matric = $_POST['matric'];
$phone =  $_POST['phone'];
$email =  $_POST['email'];

// Check if the user ID exists in the user table
$user_check_query = "SELECT * FROM user WHERE id = '$user_id'";
$user_check_result = mysqli_query($conn, $user_check_query);

// Check if the user's profile already exists
$sql = "SELECT * FROM profile WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Update existing profile
    $sql_update = "UPDATE profile 
                   SET name='$name', ic='$ic', gender='$gender', matric='$matric', phone='$phone', email='$email' 
                   WHERE user_id='$user_id'";
    mysqli_query($conn, $sql_update);
} else {
    // Insert new profile
    $sql_insert = "INSERT INTO profile (user_id, name, ic, gender, matric, phone, email) 
                   VALUES ('$user_id', '$name', '$ic', '$gender', '$matric', '$phone', '$email')";
    mysqli_query($conn, $sql_insert);
}

mysqli_close($conn);

// Redirect back to the profile viewing page
header("Location: view_profile.php");
exit();
