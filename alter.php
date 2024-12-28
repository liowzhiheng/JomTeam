<?php
require("config.php");

$sql = "ALTER TABLE match_request
        ADD FOREIGN KEY (match_id)
        REFERENCES gamematch(id)
        ON DELETE CASCADE;";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "Foreign key constraint with ON DELETE CASCADE added successfully.";
} else {
    echo "Error adding foreign key constraint: " . $conn->error;
}

// Close connection
$conn->close();

?>