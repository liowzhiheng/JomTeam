<?php

require("config.php");

//user database
$sqlUser = "CREATE TABLE user (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    birth_date DATE,
    verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sqlUser)) {
    echo "<h3>Table user created successfully</h3>";
} else {
    echo "Error creating table user: " . mysqli_error($conn);
}

//profile database
$sqlProfile = "CREATE TABLE profile (
    user_id INT(6) UNSIGNED PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    gender VARCHAR(50),
    age INT,
    status VARCHAR(255),
    phone VARCHAR(15),
    description TEXT,
    FOREIGN KEY (user_id) REFERENCES user(id)
)";

if (mysqli_query($conn, $sqlProfile)) {
    echo "<h3>Table profile created successfully</h3>";
} else {
    echo "Error creating table profile: " . mysqli_error($conn);
}

$sqlMatch = "CREATE TABLE gamematch (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    name VARCHAR(100),
    phone VARCHAR(15),
    email VARCHAR(100),
    gender VARCHAR(10),
    location VARCHAR(100),
    startDate DATE,
    duration INT,
    FOREIGN KEY (user_id) REFERENCES user(id)
)";

if (mysqli_query($conn, $sqlMatch)) {
    echo "<h3>Table Match created successfully</h3>";
} else {
    echo "Error creating table match: " . mysqli_error($conn);
}

//database for image
$sqlImage = "CREATE TABLE images (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    file VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id)
)";


if (mysqli_query($conn, $sqlImage)) {
    echo "<h3>Table image created successfully</h3>";
} else {
    echo "Error creating table image: " . mysqli_error($conn);
}

$sqlAds = "CREATE TABLE ads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    file VARCHAR(50) NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 0
)";

if (mysqli_query($conn, $sqlAds)) {
    echo "<h3>Table ads created successfully</h3>";
} else {
    echo "Error creating table ads: " . mysqli_error($conn);
}

mysqli_close($conn);