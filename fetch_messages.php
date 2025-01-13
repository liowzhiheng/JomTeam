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
            $profilePic = $profilePicRow['file'];
        }
        // Fetch frame for the user
        $frameQuery = "SELECT frame FROM profile WHERE user_id = " . $row['user_id'];
        $frameRes = mysqli_query($conn, $frameQuery);

        if ($frameRes && mysqli_num_rows($frameRes) > 0) {
            $frameRow = mysqli_fetch_assoc($frameRes);
            $frameId = $frameRow['frame'];

            // Fetch the frame file
            $frameFileQuery = "SELECT file FROM frame WHERE id = '$frameId'";
            $frameFileRes = mysqli_query($conn, $frameFileQuery);

            if ($frameFileRes && mysqli_num_rows($frameFileRes) > 0) {
                $frameFileRow = mysqli_fetch_assoc($frameFileRes);
                $frameFile = $frameFileRow['file'];
            } 
        } 
        echo "<div class='chat-message $messageClass'>
                <div>
                <img src='uploads/$profilePic' alt='Profile Picture' class='profile-pic-chat'>
                </div>
                <div class='image-container-match-chat'>
                <img src='frame/$frameFile' alt='Premium Frame' class='premium-frame-chat' />
                </div>
                <p><strong>" . htmlspecialchars($row['first_name'] . " " . $row['last_name']) . ":</strong>
                " . htmlspecialchars($row['message']) . "</p>
              </div>";
    }
}
?>