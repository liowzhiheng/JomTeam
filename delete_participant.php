<?php
session_start();

require("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $match_id = $_POST['match_id'];
    $real = $_POST['real'];

    if ($real == 1) {
        $delete = "DELETE FROM match_participants WHERE user_id = ?";
        $Stmt = $conn->prepare($delete);
        $Stmt->bind_param("i", $id);
        if ($Stmt->execute()) {
            $query = "SELECT current_players FROM gamematch WHERE id = $match_id";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                $current = $result->fetch_assoc();
            } else {
                echo "User not found.";
                exit();
            }
            $new_current = $current['current_players'] - 1;
            $update = "UPDATE gamematch SET current_players = $new_current WHERE id = $match_id";
            $result = mysqli_query($conn, $update);

            $_SESSION['message'] = "Participant removed successfully!";
        } else {
            $_SESSION['message'] = "Error deleting participant: " . $Stmt->error;
        }

        $Stmt->close();
    } else if ($real == 0) {
        $query = "SELECT current_players FROM gamematch WHERE id = $match_id";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $current = $result->fetch_assoc();
        } else {
            echo "User not found.";
            exit();
        }
        $new_current = $current['current_players'] - 1;
        $update = "UPDATE gamematch SET current_players = $new_current WHERE id = $match_id";
        $result = mysqli_query($conn, $update);
        $_SESSION['message'] = "Participant removed successfully!";
    }
}

$conn->close();

header("Location: history.php");
exit();