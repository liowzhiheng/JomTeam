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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Successful</title>
    <link rel="stylesheet" href="check_login.css"> <!-- Link to your CSS file if you have one -->
</head>

<body>
    <div class="container">
        <h1>Congratulations!</h1>
        <p>You have successfully joined the match!</p>

        <p><a href="main.php">Go back to your dashboard</a></p> <!-- Link to redirect user to their dashboard -->
        <p><a href="match_details.php?id=<?php echo $match_id; ?>">View Match Details</a></p> <!-- Link to view match details -->
    </div>
</body>

</html>
