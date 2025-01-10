<?php
session_start();
require("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    $selectMatchSql = "SELECT match_id FROM match_participants WHERE user_id = ?";
    $selectMatchStmt = $conn->prepare($selectMatchSql);
    $selectMatchStmt->bind_param("i", $id);
    $selectMatchStmt->execute();
    $matchResult = $selectMatchStmt->get_result();

    if ($matchResult->num_rows > 0) {
        while ($row = $matchResult->fetch_assoc()) {
            $match_id = $row['match_id'];
            $selectCurrentSql = "SELECT current_players FROM gamematch WHERE id = ?";
            $selectCurrentStmt = $conn->prepare($selectCurrentSql);
            $selectCurrentStmt->bind_param("i", $match_id);
            $selectCurrentStmt->execute();
            $currentResult = $selectCurrentStmt->get_result();

            if ($currentResult->num_rows > 0) {
                $current = $currentResult->fetch_assoc();
                $new_current = $current['current_players'] - 1;

                $updateSql = "UPDATE gamematch SET current_players = ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("ii", $new_current, $match_id);
                $updateStmt->execute();
                $updateStmt->close();
            }
            $selectCurrentStmt->close();
        }
    }
    $selectMatchStmt->close();
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

 $selectMatchSql = "SELECT match_id FROM match_participants WHERE user_id = ?";
    $selectMatchStmt = $conn->prepare($selectMatchSql);
    $selectMatchStmt->bind_param("i", $id);
    $selectMatchStmt->execute();
    $matchResult = $selectMatchStmt->get_result();

    if ($matchResult->num_rows > 0) {
        while ($row = $matchResult->fetch_assoc()) {
            $match_id = $row['match_id'];

            // Get current players count
            $selectCurrentSql = "SELECT current_players FROM gamematch WHERE id = ?";
            $selectCurrentStmt = $conn->prepare($selectCurrentSql);
            $selectCurrentStmt->bind_param("i", $match_id);
            $selectCurrentStmt->execute();
            $currentResult = $selectCurrentStmt->get_result();

            if ($currentResult->num_rows > 0) {
                $current = $currentResult->fetch_assoc();
                $new_current = $current['current_players'] - 1;

                // Update players count
                $updateSql = "UPDATE gamematch SET current_players = ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("ii", $new_current, $match_id);
                $updateStmt->execute();
                $updateStmt->close();
            }
            $selectCurrentStmt->close();
        }
    }
    $selectMatchStmt->close();
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
