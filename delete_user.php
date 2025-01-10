<?php
session_start();
require("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    $selectMatch = "SELECT * FROM match_participants WHERE user_id = ?";
    $result = mysqli_query($conn, $selectMatch);
    if(mysqli_num_rows($result) > 0){
        while ($row = $result->fetch_assoc()) {
            $match_id = $row['match_id'];
            $selectCurent = "SELECT current_players FROM gamematch WHERE id = $match_id";
            $result2 = mysqli_query($conn, $selectCurent);

            if (mysqli_num_rows($result2) > 0) {
                $current = $result2->fetch_assoc();
            } else {
                echo "User not found.";
                exit();
            }
            $new_current = $current['current_players'] - 1;
            $update = "UPDATE gamematch SET current_players = $new_current WHERE id = $match_id";
            $result3 = mysqli_query($conn, $update);
        }
    }else{
        echo "Match not found";
    }
    $deleteProfileSql = "DELETE FROM profile WHERE user_id = ?";
    $profileStmt = $conn->prepare($deleteProfileSql);
    $profileStmt->bind_param("i", $id);
    if ($profileStmt->execute()) {
        $deleteUserSql = "DELETE FROM user WHERE id = ?";
        $userStmt = $conn->prepare($deleteUserSql);
        $userStmt->bind_param("i", $id);

        if ($userStmt->execute()) {
            $_SESSION['message'] = "User and related profile removed successfully!";
        } else {
            $_SESSION['message'] = "Error deleting user: " . $userStmt->error;
        }

        $userStmt->close();
    } else {
        $_SESSION['message'] = "Error deleting profile: " . $profileStmt->error;
    }

    $profileStmt->close();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $selectMatch = "SELECT * FROM match_participants WHERE user_id = ?"
    $result = mysqli_query($conn, $selectMatch);
    if(mysqli_num_rows($result) > 0){
        while ($row = $result->fetch_assoc()) {
            $match_id = $row['match_id'];
            $selectCurent = "SELECT current_players FROM gamematch WHERE id = $match_id";
            $result2 = mysqli_query($conn, $selectCurent);

            if (mysqli_num_rows($result2) > 0) {
                $current = $result2->fetch_assoc();
            } else {
                echo "User not found.";
                exit();
            }
            $new_current = $current['current_players'] - 1;
            $update = "UPDATE gamematch SET current_players = $new_current WHERE id = $match_id";
            $result3 = mysqli_query($conn, $update);
        }
    }else{
        echo "Match not found";
    }

    $deleteProfileSql = "DELETE FROM profile WHERE user_id = ?";
    $profileStmt = $conn->prepare($deleteProfileSql);
    $profileStmt->bind_param("i", $id);
    if ($profileStmt->execute()) {
        $deleteUserSql = "DELETE FROM user WHERE id = ?";
        $userStmt = $conn->prepare($deleteUserSql);
        $userStmt->bind_param("i", $id);

        if ($userStmt->execute()) {
            $_SESSION['message'] = "User and related profile deleted successfully!";
        } else {
            $_SESSION['message'] = "Error deleting user: " . $userStmt->error;
        }

        $userStmt->close();
    } else {
        $_SESSION['message'] = "Error deleting profile: " . $profileStmt->error;
    }

    $profileStmt->close();
}

$conn->close();

header("Location: view_user.php");
exit();
