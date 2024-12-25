<?php
require("config.php");
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['ID'];

// Fetch pending friend requests
$requestQuery = "SELECT fr.id, u.first_name, u.last_name, u.id AS sender_id
                 FROM friend_requests fr
                 JOIN user u ON fr.sender_id = u.id
                 WHERE fr.receiver_id = ? AND fr.status = 'pending'";
$requestStmt = $conn->prepare($requestQuery);
$requestStmt->bind_param("i", $current_user_id);
$requestStmt->execute();
$requestResult = $requestStmt->get_result();

if ($requestResult->num_rows == 0) {
    echo "You have no pending friend requests.";
    exit();
}

// Handle acceptance or rejection
if (isset($_POST['accept_request'])) {
    $request_id = $_POST['request_id'];
    $updateQuery = "UPDATE friend_requests SET status = 'accepted' WHERE id = ? AND receiver_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ii", $request_id, $current_user_id);
    $updateStmt->execute();

    // Add to friends table
    $requestData = "SELECT sender_id FROM friend_requests WHERE id = ?";
    $requestDataStmt = $conn->prepare($requestData);
    $requestDataStmt->bind_param("i", $request_id);
    $requestDataStmt->execute();
    $requestDataResult = $requestDataStmt->get_result();
    $sender = $requestDataResult->fetch_assoc();
    
    if ($sender) {
        $sender_id = $sender['sender_id'];
        $insertFriendQuery = "INSERT INTO friends (user_id, friend_id) VALUES (?, ?), (?, ?)";
        $insertFriendStmt = $conn->prepare($insertFriendQuery);
        $insertFriendStmt->bind_param("iiii", $current_user_id, $sender_id, $sender_id, $current_user_id);
        $insertFriendStmt->execute();
    }

    echo "Friend request accepted!";
}

if (isset($_POST['reject_request'])) {
    $request_id = $_POST['request_id'];
    $updateQuery = "UPDATE friend_requests SET status = 'rejected' WHERE id = ? AND receiver_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ii", $request_id, $current_user_id);
    $updateStmt->execute();

    echo "Friend request rejected!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friend Requests</title>
</head>
<body>
    <h1>Pending Friend Requests</h1>
    <table border="1">
        <tr>
            <th>Sender</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $requestResult->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="accept_request">Accept</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="reject_request">Reject</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
