<?php
require("config.php");

// Function to check if table exists (reusing existing function)
function tableExists($conn, $tableName)
{
    $result = mysqli_query($conn, "SHOW TABLES LIKE '$tableName'");
    return mysqli_num_rows($result) > 0;
}

// Function to safely create table (reusing existing function)
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

// Create pending_changes table
$sqlPendingChanges = "CREATE TABLE pending_changes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED NOT NULL,
    change_type ENUM('email', 'password') NOT NULL,
    new_value VARCHAR(255) NOT NULL,
    verification_token VARCHAR(32) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP DEFAULT (CURRENT_TIMESTAMP + INTERVAL 24 HOUR),
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    INDEX idx_token (verification_token),
    INDEX idx_expires (expires_at)
)";

createTable($conn, "pending_changes", $sqlPendingChanges);

// Add a cleanup event to automatically remove expired tokens
$sqlCleanupEvent = "
CREATE EVENT IF NOT EXISTS cleanup_pending_changes
ON SCHEDULE EVERY 1 DAY
DO DELETE FROM pending_changes WHERE expires_at < NOW()";

if (mysqli_query($conn, $sqlCleanupEvent)) {
    echo "<h3>Cleanup event created successfully</h3>";
} else {
    echo "Error creating cleanup event: " . mysqli_error($conn);
}

// Make sure event scheduler is running
mysqli_query($conn, "SET GLOBAL event_scheduler = ON");

mysqli_close($conn);
?>