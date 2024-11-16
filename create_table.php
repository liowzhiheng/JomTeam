<?php
require("config.php");

// Function to check if table exists
function tableExists($conn, $tableName) {
    $result = mysqli_query($conn, "SHOW TABLES LIKE '$tableName'");
    return mysqli_num_rows($result) > 0;
}

// Function to safely create table
function createTable($conn, $tableName, $sql, $dropIfExists = true) {
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
$sqlUser = "CREATE TABLE user (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    birth_date DATE,
    level INT DEFAULT 3,
    verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
createTable($conn, "user", $sqlUser);

// Create profile table
$sqlProfile = "CREATE TABLE profile (
    user_id INT(6) UNSIGNED PRIMARY KEY,
    status VARCHAR(255),
    description TEXT,
    location VARCHAR(100),
    interests TEXT,
    preferred_game_types VARCHAR(255),
    skill_level VARCHAR(50),
    availability TEXT,
    last_active TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
)";
createTable($conn, "profile", $sqlProfile);

// Create gamematch table
$sqlMatch = "CREATE TABLE gamematch (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    match_title VARCHAR(100) NOT NULL,
    game_type VARCHAR(100) NOT NULL,
    skill_level_required VARCHAR(50),
    max_players INT,
    current_players INT DEFAULT 1,
    location VARCHAR(100),
    start_date DATETIME,
    end_date DATETIME,
    duration INT,
    status VARCHAR(20) DEFAULT 'open',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
)";
createTable($conn, "gamematch", $sqlMatch);

// Create images table
$sqlImage = "CREATE TABLE images (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    file VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_profile_picture TINYINT(1) DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
)";
createTable($conn, "images", $sqlImage);

// Create match_participants table
$sqlMatchParticipants = "CREATE TABLE match_participants (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    match_id INT(6) UNSIGNED,
    user_id INT(6) UNSIGNED,
    join_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) DEFAULT 'pending',
    FOREIGN KEY (match_id) REFERENCES gamematch(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    UNIQUE KEY unique_participant (match_id, user_id)
)";
createTable($conn, "match_participants", $sqlMatchParticipants);

mysqli_close($conn);
?>
