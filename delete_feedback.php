<?php
session_start();
require("config.php");

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $deleteSql = "DELETE FROM feedback WHERE id = $id";

    if ($conn->query($deleteSql) === TRUE) {
        header("Location: view_feedback.php?status=deleted");
        exit();
    } else {
        header("Location: view_feedback.php?status=fail");
        exit();
    }
}
$conn->close();
header("Location: view_feedback.php");
?>