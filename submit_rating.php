<?php
require("config.php");
session_start();

// Check if user is logged in
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit();
}

// Validate input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rater_id = $_SESSION['ID'];
    $rated_user_id = intval($_POST['rated_user_id']);
    
    // If no rating selected, just return to profile
    if (!isset($_POST['rating'])) {
        header("Location: player_profile.php?id=$rated_user_id");
        exit();
    }
    
    $rating = floatval($_POST['rating']);


    // Validate rating
    if ($rating < 1 || $rating > 5) {
        echo "Invalid rating.";
        exit();
    }

    // If invalid rating, return to profile
    if ($rating < 1 || $rating > 5) {
        header("Location: player_profile.php?id=$rated_user_id");
        exit();
    }

    // Check if they've participated in a match together
    $matchQuery = "
    SELECT mp1.match_id 
    FROM match_participants mp1 
    JOIN match_participants mp2 ON mp1.match_id = mp2.match_id
    WHERE mp1.user_id = ? AND mp2.user_id = ?
    LIMIT 1
    ";
    
    $matchStmt = $conn->prepare($matchQuery);
    $matchStmt->bind_param("ii", $rater_id, $rated_user_id);
    $matchStmt->execute();
    $matchResult = $matchStmt->get_result();
    $matchRow = $matchResult->fetch_assoc();

    $match_id = $matchRow ? $matchRow['match_id'] : null;

    // Prepare and execute rating insertion
    $insertQuery = "
        INSERT INTO player_ratings 
        (rater_id, rated_user_id, rating, match_id) 
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        rating = VALUES(rating)
    ";

    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("iidi", $rater_id, $rated_user_id, $rating, $match_id);

    if ($insertStmt->execute()) {
        header("Location: player_profile.php?id=$rated_user_id");
        header("Location: player_profile.php?id=$rated_user_id&match_id=" . $_POST['match_id']);
        exit();
    } else {
        header("Location: player_profile.php?id=$rated_user_id");
        header("Location: player_profile.php?id=$rated_user_id&match_id=" . $_POST['match_id']);
        exit();
    }
}

header("Location: index.php");
exit();