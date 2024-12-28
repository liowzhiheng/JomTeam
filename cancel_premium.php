<?php
session_start();

// Check if the user is logged in
if ($_SESSION["Login"] != "YES") {
    header("Location: index.php");
    exit();
}

// Check if USER_ID is set
if (!isset($_SESSION["ID"])) {
    echo "User ID is not set in the session.";
    exit();
}

$user_id = $_SESSION["ID"];

require("config.php");

// Update the user's premium status to 0
$query = "UPDATE user SET premium = 0 WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);

// Execute the statement
if ($stmt->execute()) {
    
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();

?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="check_login.css">
    <link rel="stylesheet" href="animation.css">
    <meta http-equiv="refresh" content="3;url=<?php echo ($_SESSION["LEVEL"] == 1) ? 'dashboard.php' : 'premium.php'; ?>">
    <style>
        .background {
            position: fixed;
            /* Stays fixed in place behind content */
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            /* Pushes it behind everything else */
            pointer-events: none;
            /* Prevent interaction with shapes */
        }

        .container {
            position: relative;
            z-index: 1;
            /* Ensures content stays above the background */
            padding: 20px;
        }
    </style>

</head>

<body>
    <div class="background"></div>
    <div class="container">
        <h2>Hi! <strong><?php echo htmlspecialchars($_SESSION["USER"]); ?></strong><br>You are not premium now babe.💔</h2>
        <img id="randomImage" alt="Login Successful" class="login-image" />
    </div>
</body>
<script src="random_pic.js"></script>
<script src="background_effect.js" defer></script>

</html>
<?php
mysqli_close($conn);
?>
