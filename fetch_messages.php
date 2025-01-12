<?php
session_start(); // Ensure the session is started

require("config.php");

if (isset($_GET['match_id'])) {
    $match_id = $_GET['match_id'];
    $query = "SELECT chat_messages.*, user.first_name, user.last_name 
              FROM chat_messages
              INNER JOIN user ON chat_messages.user_id = user.id
              WHERE match_id = ? ORDER BY timestamp ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $match_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        // Check if the message is from the current user
        $messageClass = ($row['user_id'] == $_SESSION['ID']) ? 'my-message' : 'other-message';

        // Fetch profile picture
        $profilePicRes = mysqli_query($conn, "SELECT file FROM images WHERE user_id = " . $row['user_id']);
        $profilePicRow = mysqli_fetch_assoc($profilePicRes);
          if (empty($profilePicRow['file'])) {
            $profilePic = 'default.png';
        } else {
            $profilePic =$profilePicRow['file'];
        }

        echo "<div class='chat-message $messageClass'>
                <img src='uploads/$profilePic' alt='Profile Picture' class='profile-pic-chat'>
                <p><strong>" . htmlspecialchars($row['first_name'] . " " . $row['last_name']) . ":</strong>
                " . htmlspecialchars($row['message']) . "</p>
              </div>";
    }
}
?>
