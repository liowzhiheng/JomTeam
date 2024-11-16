<?php
session_start();

if ($_SESSION["Login"] != "YES") {
    header("Location: login.php");
    exit();
}

require("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $user_id = mysqli_real_escape_string($conn, $_POST['userID']);
    $match_title = mysqli_real_escape_string($conn, $_POST['match_title']);
    $game_type = mysqli_real_escape_string($conn, $_POST['game_type']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $start_date = mysqli_real_escape_string($conn, $_POST['startDate']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $skill_level = mysqli_real_escape_string($conn, $_POST['skill_level']);
    $max_players = mysqli_real_escape_string($conn, $_POST['max_players']);
    $current_players = mysqli_real_escape_string($conn, $_POST['current_players']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Prepare and bind the SQL query to prevent SQL injection
    $query = $conn->prepare("INSERT INTO gamematch (user_id, match_title, game_type, location, start_date, duration, skill_level_required, max_players, current_players, description) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $query->bind_param("isssssiiss", $user_id, $match_title, $game_type, $location, $start_date, $duration, $skill_level, $max_players, $current_players, $description);

    if ($query->execute()) {
        echo "Match created successfully.";
    } else {
        echo "Error: " . $query->error;
    }
}
?>
