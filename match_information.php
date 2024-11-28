<?php
session_start();
require("config.php");

$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['userID'];
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

    // Initialize the file_name to NULL (no image)
    $file_name = null;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // If new image is uploaded
        $file_name = $_FILES['image']['name'];
        $tempname = $_FILES['image']['tmp_name'];
        $folder = 'gamematch/' . $file_name;
        move_uploaded_file($tempname, $folder);
    }

    if (isset($_POST['create'])) {
        $sql = "INSERT INTO gamematch (user_id, match_title, game_type, skill_level_required, max_players, current_players, location, start_date, start_time, duration, status, description, file)
            VALUES ('$user_id', '$match_title', '$game_type', '$skill_level', '$max_players','$current_players', '$location', '$start_date', '$start_time', '$duration', 'open', '$description', '$file_name')";
        if (mysqli_query($conn, $sql)) {
            header("Location: create_successful.php");
            exit();
        } else {
            header("Location: create_match.php?status=fail");
            exit();
        }
    }
}
?>
