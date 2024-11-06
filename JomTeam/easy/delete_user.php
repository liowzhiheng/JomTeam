<?php
session_start(); // Start up your PHP Session

require("config.php"); // Include the database configuration file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // Delete user by id
    $sql = "DELETE FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User removed successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

// Redirect back to the view users page without any prior output
header("Location: view_user.php");
exit();
