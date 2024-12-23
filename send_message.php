<?php
require("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $match_id = $_POST['match_id'];
    $user_id = $_POST['user_id'];
    $message = $_POST['message'];

    $query = "INSERT INTO chat_messages (match_id, user_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iis', $match_id, $user_id, $message);

    if ($stmt->execute()) {
        echo "Message sent.";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>