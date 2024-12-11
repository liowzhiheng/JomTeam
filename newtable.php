<?php
require("config.php");
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

$sqlFeedback = "CREATE TABLE feedback (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT(6) UNSIGNED,
title VARCHAR(50) DEFAULT NULL,
description TEXT NOT NULL,
rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
status ENUM('Unread', 'Read') DEFAULT 'Unread',
FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sqlFeedback)) {
    echo "<h3>Table feedback created successfully</h3>";
} else {
    echo "Error creating table feedback: " . mysqli_error($conn);
}

mysqli_close($conn);
?>