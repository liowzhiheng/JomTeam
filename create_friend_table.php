<?php
require("config.php");

// Function to check if table exists
function tableExists($conn, $tableName)
{
    $result = mysqli_query($conn, "SHOW TABLES LIKE '$tableName'");
    return mysqli_num_rows($result) > 0;
}

// Function to safely create table
function createTable($conn, $tableName, $sql, $dropIfExists = true)
{
    if (tableExists($conn, $tableName)) {
        if ($dropIfExists) {
            mysqli_query($conn, "DROP TABLE IF EXISTS $tableName");
            echo "<p>Dropped existing table $tableName</p>";
        } else {
            echo "<p>Table $tableName already exists - skipping creation</p>";
            return;
        }
    }

    if (mysqli_query($conn, $sql)) {
        echo "<h3>Table $tableName created successfully</h3>";
    } else {
        echo "Error creating table $tableName: " . mysqli_error($conn);
    }
}

// Create friends table
$sqlFriends = "CREATE TABLE friends (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    friend_id INT(6) UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (friend_id) REFERENCES user(id) ON DELETE CASCADE,
    UNIQUE KEY unique_friendship (user_id, friend_id)
);
";


createTable($conn, "friends", $sqlFriends);

$sqlFriendRequests = "CREATE TABLE friend_requests (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sender_id INT(6) UNSIGNED,
    receiver_id INT(6) UNSIGNED,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES user(id) ON DELETE CASCADE
);
";

createTable($conn, "friend_requests", $sqlFriendRequests);



mysqli_close($conn);
?>
