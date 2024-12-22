<?php
session_start();
require("config.php");

date_default_timezone_set("Asia/Kuala_Lumpur");
if (isset($_SESSION['ID'])) {
    $userId = $_SESSION['ID'];
    $logoutTime = date("Y-m-d H:i:s");

    $sql = "UPDATE user SET last_activity = '$logoutTime' WHERE id = $userId";
    if ($conn->query($sql) === TRUE) {
    } else {
        error_log("Error updating last_activity: " . $conn->error);
    }

    session_destroy();
    header("Location: index.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>