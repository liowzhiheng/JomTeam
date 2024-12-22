<?php
require("config.php");

$sql = "ALTER TABLE user
        CHANGE COLUMN verified last_activity DATETIME";

if (mysqli_query($conn, $sql)) {
    echo "Column renamed and datatype updated successfully.";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>