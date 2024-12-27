<?php
// join_match.php

require("config.php");

session_start();

if (isset($_GET['id'])) {
    $match_id = $_GET['id'];
    $user_id = $_SESSION['ID'];

    // Check if the match exists
    $query = "SELECT * FROM gamematch WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $match_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $sql="INSERT INTO match_request (match_id, request_user_id) VALUE ($match_id, $user_id)";
        if (mysqli_query($conn, $sql)) {
            // Redirect to the successful creation page
            header("Location: find_match.php");
            exit();
        } else {
            // Redirect to the failure page if something goes wrong
            header("Location: create_match.php?status=fail");
            exit();
        }
    }
}

?>