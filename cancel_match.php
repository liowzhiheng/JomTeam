<?php
// cancel_match.php
session_start();
require("config.php");


// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $match_id = $_GET['id'];
    $user_id = $_SESSION["ID"]; // Replace this with the logged-in user's ID from session

    // Check if the user is part of the match
    $checkQuery = "SELECT * FROM match_participants WHERE match_id = ? AND user_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('ii', $match_id, $user_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Remove the user from the match_participants table
        $deleteQuery = "DELETE FROM match_participants WHERE match_id = ? AND user_id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param('ii', $match_id, $user_id);
        $deleteStmt->execute();

        // Decrement current_players in gamematch table
        $updateQuery = "UPDATE gamematch SET current_players = current_players - 1 WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('i', $match_id);
        $updateStmt->execute();

// Redirect to a success HTML file
        header("Location: success.html");
        exit();
        } else {
            // Redirect to an error HTML file
            header("Location: not_in_match.html");
            exit();
        }
    } else {
        // Redirect to an error page if 'id' is not provided
        header("Location: error.html");
        exit();
    }
    }
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Match</title>
    <link rel="stylesheet" href="cancel.css">
</head>
<body>
    <script src="random_pic.js"></script>
</body>
