<?php
require("config.php");

// Function to check if table exists
function tableExists($conn, $tableName)
{
    $result = mysqli_query($conn, "SHOW TABLES LIKE '$tableName'");
    return mysqli_num_rows($result) > 0;
}

// Function to safely add columns to the existing table
function addColumnsToTable($conn, $tableName, $sql)
{
    if (tableExists($conn, $tableName)) {
        if (mysqli_query($conn, $sql)) {
            echo "<h3>Columns added to $tableName successfully</h3>";
        } else {
            echo "Error adding columns to $tableName: " . mysqli_error($conn);
        }
    } else {
        echo "<p>Table $tableName does not exist</p>";
    }
}

// SQL query to alter the user table by adding email_verified and verification_token
$sqlAlterUser = "ALTER TABLE user 
                 ADD COLUMN email_verified BOOLEAN DEFAULT FALSE,
                 ADD COLUMN verification_token VARCHAR(255);";

// Call the function to add columns
addColumnsToTable($conn, "user", $sqlAlterUser);

mysqli_close($conn);
?>
