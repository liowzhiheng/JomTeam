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

// Create user table
$sqlUser = "CREATE TABLE match_request (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    match_id INT(6) UNSIGNED,
    request_user_id INT(6) UNSIGNED,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending'
)";
createTable($conn, "match_request", $sqlUser);