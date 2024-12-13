<?php
session_start();

require("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $match_id = $_POST['match_id'];
    $match_title = $_POST['match_title'];
    $game_type = $_POST['game_type'];
    $skill_level = $_POST['skill_level'];
    $max_players = $_POST['max_players'];
    $current_players = $_POST['current_players'];
    $location = $_POST['location'];
    $start_date = $_POST['startDate'];
    $start_time = $_POST['startTime'];
    $duration = $_POST['duration'];
    $description = $_POST['description'];

    $sql = "UPDATE gamematch SET match_title = '$match_title', game_type = '$game_type', skill_level_required = '$skill_level', max_players = '$max_players', current_players = '$current_players', location = '$location', start_date = '$start_date', start_time = '$start_time', duration = '$duration', description = '$description'
            WHERE id = $match_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_query($conn, $sql)) {
        // Redirect to the successful creation page
        $_SESSION['message'] = "Update successfully!";
        header("Location: history.php");
        exit();
    } else {
        // Redirect to the failure page if something goes wrong    
        $_SESSION['message'] = "Update fail!";
        header("Location: history.php");
        exit();
    }
}
?>