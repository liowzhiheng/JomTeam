<?php
// join_successful.php

require("config.php");

if (isset($_GET['id'])) {
    $match_id = $_GET['id'];
}

// This page will be displayed after a user successfully joins a match
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="join_succesful.css"> <!-- Link to your CSS file if you have one -->
    <meta http-equiv="refresh" content="3;url=find_match.php"> <!-- Redirect after 3 seconds -->
    <link rel="stylesheet" href="animation.css">
    <style>
        .background {
            position: fixed;
            /* Stays fixed in place behind content */
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            /* Pushes it behind everything else */
            pointer-events: none;
            /* Prevent interaction with shapes */
        }

        .container {
            position: relative;
            z-index: 1;
            /* Ensures content stays above the background */
            padding: 20px;
        }
    </style>


</head>

<body>
    <div class="background"></div>
    <div class="container">
        <h1>Congratulations!</h1>
        <h1>You have successfully joined the match!</h1>

        <img id="randomImage" alt="Join Successful" class="login-image" />
    </div>
</body>

</html>
<script src="background_effect.js" defer></script>
<script src="random_pic.js"></script>
