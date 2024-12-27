<?php
// cancel_match.php
session_start();
require("config.php");


// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $match_id = $_GET['id'];
    $user_id = $_SESSION["ID"]; // Replace this with the logged-in user's ID from session

    // Check if the user is part of the match
    $checkQuery = "SELECT * FROM match_request WHERE match_id = ? AND request_user_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('ii', $match_id, $user_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Remove the user from the match_participants table
        $deleteQuery = "DELETE FROM match_request WHERE match_id = ? AND request_user_id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param('ii', $match_id, $user_id);
        $deleteStmt->execute();
        
        header("Location: find_match.php");

    }
}
?>