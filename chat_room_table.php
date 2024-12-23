<?php
require("config.php");

// Function to check if a table exists
function tableExists($conn, $tableName)
{
    $result = mysqli_query($conn, "SHOW TABLES LIKE '$tableName'");
    if (!$result) {
        die("Error checking table existence: " . mysqli_error($conn));
    }
    return mysqli_num_rows($result) > 0;
}

// Function to safely create table
function createTable($conn, $tableName, $sql, $dropIfExists = true)
{
    if (tableExists($conn, $tableName)) {
        if ($dropIfExists) {
            if (!mysqli_query($conn, "DROP TABLE IF EXISTS $tableName")) {
                die("Error dropping table $tableName: " . mysqli_error($conn));
            }
            echo "<p>Dropped existing table $tableName</p>";
        } else {
            echo "<p>Table $tableName already exists - skipping creation</p>";
            return;
        }
    }

    if (mysqli_query($conn, $sql)) {
        echo "<h3>Table $tableName created successfully</h3>";
    } else {
        die("Error creating table $tableName: " . mysqli_error($conn));
    }
}

// SQL to create the chat_messages table
$sqlChat = "CREATE TABLE chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    match_id INT(6) UNSIGNED NOT NULL,
    user_id INT(6) UNSIGNED NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (match_id) REFERENCES gamematch(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
)";
createTable($conn, "chat_messages", $sqlChat);

mysqli_close($conn);
?>
