<?php
session_start();

require("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

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

$conn->close();

header("Location: view_user.php");
exit();