<?php
require("config.php");
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['ID'];

// Fetch friends
$friendsQuery = "SELECT u.id, u.first_name, u.last_name
                 FROM friends f
                 JOIN user u ON (f.user_id = u.id OR f.friend_id = u.id)
                 WHERE (f.user_id = ? OR f.friend_id = ?)
                   AND u.id != ?";
$friendsStmt = $conn->prepare($friendsQuery);
$friendsStmt->bind_param("iii", $current_user_id, $current_user_id, $current_user_id);
$friendsStmt->execute();
$friendsResult = $friendsStmt->get_result();

if ($friendsResult->num_rows == 0) {
    echo "You have no friends.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Friends</title>
</head>
<body>
    <h1>Your Friends</h1>
    <ul>
        <?php while ($row = $friendsResult->fetch_assoc()): ?>
        <li><?php echo $row['first_name'] . " " . $row['last_name']; ?></li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
