<?php
session_start();
require("config.php");

if ($_SESSION["LEVEL"] == 1) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];

        $deleteSql = "DELETE FROM gamematch WHERE id = $id";
        if ($conn->query($deleteSql) === TRUE) {
            header("Location: view_match.php?status=deleted");
            exit();
        } else {
            header("Location: view_match.php?status=fail");
            exit();
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id = $_GET['id'];

        $deleteSql = "DELETE FROM gamematch WHERE id = $id";
        if ($conn->query($deleteSql) === TRUE) {
            header("Location: view_match.php?status=deleted");
            exit();
        } else {
            header("Location: view_match.php?status=fail");
            exit();
        }
    }
    $conn->close();
    header("Location: view_match.php");
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];

        $deleteSql = "DELETE FROM gamematch WHERE id = ?";
        $matchStmt = $conn->prepare($deleteSql);
        $matchStmt->bind_param("i", $id);
        if ($matchStmt->execute()) {
            header("Location: history.php");
            exit();
        } else {
            header("Location: match_details.php?id=<?php $id ?>");
            exit();
        }
    }
    $conn->close();
}
exit();
