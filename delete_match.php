<?php
session_start();

require("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    $deleteSql = "DELETE FROM gamematch WHERE id = ?";
    $matchStmt = $conn->prepare($deleteSql);
    $matchStmt->bind_param("i", $id);
    if ($matchStmt->execute()) {
        header("Location: view_match.php?status=deleted");
        exit();
    } else {
        header("Location: view_match.php?status=fail");
        exit();
    }
}

$conn->close();

header("Location: view_match.php");
exit();