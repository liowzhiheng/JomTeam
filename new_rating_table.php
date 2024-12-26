<?php
require("config.php");

// Drop existing table
$dropTable = "DROP TABLE IF EXISTS player_ratings";
if (mysqli_query($conn, $dropTable)) {
    echo "<h3>Successfully dropped player_ratings table</h3>";
} 

// Create new player_ratings table
$sqlPlayerRatings = "CREATE TABLE player_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rater_id INT(6) UNSIGNED NOT NULL,
    rated_user_id INT(6) UNSIGNED NOT NULL,
    rating DECIMAL(3,2) NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    match_id INT(6) UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (rater_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (rated_user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (match_id) REFERENCES gamematch(id) ON DELETE SET NULL,
    UNIQUE KEY unique_rating (rater_id, rated_user_id)
)";

if (mysqli_query($conn, $sqlPlayerRatings)) {
    echo "<h3>New player_ratings table created successfully</h3>";
} 

mysqli_close($conn);
?>
