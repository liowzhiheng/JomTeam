<?php
// cancel_successful.php

require("config.php");

if (isset($_GET['id'])) {
    $match_id = $_GET['id'];
}

// This page will be displayed after a user successfully cancels a match
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="join_succesful.css"> <!-- Link to your CSS file if you have one -->
    <meta http-equiv="refresh" content="3;url=find_match.php"> <!-- Redirect after 3 seconds -->

   
       
</head>

<body>
    <div class="container">
        <h1>You have successfully canceled the match.</h1>
        <h1>Let's look forward for another match!</h1>

        <img id="randomImage"alt="Login Successful" class="login-image" />
    </div>
</body>

</html>

<script src="random_pic.js"></script>
