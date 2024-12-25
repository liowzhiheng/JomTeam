<?php
require("config.php");
session_start();

// Check if the user is logged in
if (!isset($_SESSION['ID'])) {
    echo "You need to log in to send a friend request.";
    exit;
}

$user_id = $_SESSION['ID']; // Get the logged-in user ID

// Check if the friend ID is passed
if (isset($_POST['friend_id'])) {
    $friend_id = $_POST['friend_id'];

    // Prevent users from sending a request to themselves
    if ($user_id == $friend_id) {
        echo "You cannot send a friend request to yourself.";
        exit;
    }

    // Check if a friendship already exists
    $checkQuery = "SELECT * FROM friends WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('iiii', $user_id, $friend_id, $friend_id, $user_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "Friend request already sent or friendship exists.";
    } else {
        // Insert a new friend request with status 'pending'
        $insertQuery = "INSERT INTO friends (user_id, friend_id, status) VALUES (?, ?, 'pending')";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param('ii', $user_id, $friend_id);
        if ($insertStmt->execute()) {
            echo "Friend request sent!";
        } else {
            echo "Error sending friend request.";
        }
    }
} else {
    echo "Friend ID is required.";
}

$conn->close();
?>
