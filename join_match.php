<?php
// join_match.php

require("config.php");

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $match_id = $_GET['id'];
    $user_id = $POST['user_ID'];

    // Check if the match exists
    $query = "SELECT * FROM gamematch WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $match_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $match = $result->fetch_assoc();
        $current_players = $match['current_players'];
        $max_players = $match['max_players'];

        // Check if the user has already joined this match
        $checkQuery = "SELECT * FROM match_participants WHERE match_id = ? AND user_id = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param('ii', $match_id, $user_id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // If already joined, show a message with a link to cancel participation
            echo "<div class='message error'>
            <p>You have already joined this match. </p>
             <form action='cancel_match.php' method='GET'>
                    <button type='submit' name='id' value='$match_id' class='btn-cancel'>Cancel Participation</button>
                  </form>
                  </div>";
        } else {
            if ($current_players < $max_players) {
                // Update gamematch table
                $new_count = $current_players + 1;
                $updateQuery = "UPDATE gamematch SET current_players = ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param('ii', $new_count, $match_id);
                $updateStmt->execute();

                // Insert into match_participants table
                $insertQuery = "INSERT INTO match_participants (match_id, user_id) VALUES (?, ?)";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bind_param('ii', $match_id, $user_id);
                $insertStmt->execute();

                // Redirect to success page
                header("Location: join_successful.php");
                exit;
            } else {
                // If the match is full, show an error message
                echo "<div class='message error'>The match is already full.</div>";
            }
        }
    } else {
        // If match not found, show an error message
        echo "<div class='message error'>Match not found.</div>";
    }
} else {
    // If no match ID is passed in the URL, show an error message
    echo "<div class='message error'>No match selected.</div>";
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Match</title>
    <link rel="stylesheet" href="check_login.css"> <!-- Link to your CSS file -->
</head>
