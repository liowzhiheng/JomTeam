<?php

require("config.php");

$sqlUser = "CREATE TABLE user (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  level INT(3) NOT NULL
)";

if (mysqli_query($conn, $sqlUser)) {
    echo "<h3>Table user created successfully</h3>";
} else {
    echo "Error creating table user: " . mysqli_error($conn);
}

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

$sqlStudent = "CREATE TABLE student (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    name VARCHAR(100),
    ic VARCHAR(12),
    matric VARCHAR(8) NOT NULL,
    phone VARCHAR(15),
    email VARCHAR(100),
    gender VARCHAR(10),
    address TEXT,
    course VARCHAR(50),
    yearOfStudy INT,
    cgpa FLOAT(3,2),
    industry VARCHAR(50),
    location VARCHAR(100),
    startDate DATE,
    duration INT,
    relevantCourse TEXT,
    skill TEXT,
    experience TEXT,
    project TEXT,
    add1 TEXT,
    add2 TEXT,
    status VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES user(id)
)";

if (mysqli_query($conn, $sqlStudent)) {
    echo "<h3>Table student created successfully</h3>";
} else {
    echo "Error creating table student: " . mysqli_error($conn);
}

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

mysqli_close($conn);
