<?php
session_start(); // Start the PHP session

// Check if the user is logged in
if ($_SESSION["Login"] != "YES") {
    header("Location: login.php");
    exit();
}

require("config.php");

$user_id = $_SESSION['ID']; // Get the user ID from session

// Handle form submission (insert new feedback)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and escape to prevent SQL injection
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);

    $insert_sql = "INSERT INTO feedback (user_id, title, description, rating) 
                   VALUES ('$user_id', '$title', '$description', '$rating')";

    if (mysqli_query($conn, $insert_sql)) {
        // Redirect to feedback page with success status
        header("Location: feedback.php?status=success");
        exit();
    } else {
        // Redirect to profile page with failure status
        header("Location: feedback.php?status=fail");
        exit();
    }
}

// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback & Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="feedback.css">
</head>

<body>
    <nav class="navbar">
        <a href="#" class="logo">
            <img src="IMAGE/jomteam.png" alt="Logo">
        </a>

        <ul class="menu leftmenu">
            <li><a href="main.php">Home</a></li>
            <li><a href="find_match.php">Find Match</a></li>
            <li><a href="create_match.php">Create Match</a></li>
            <li><a href="view_profile.php">Profile</a></li>
            <li><a href="#premium">Premium</a></li>
        </ul>

        <ul class="menu rightmenu">
            <li class="notification"><a href="#notification"><img src="IMAGE/NOTIFICATION.png" alt="Notification"></a>
            </li>
            <li class="logout"><a href="login.php">Log out<img src="IMAGE/LOGOUT.png" alt="Logout"></a></li>
        </ul>
    </nav>

    <?php
    if (isset($_GET['status'])) {
        $status = $_GET['status'];

        if ($status === 'success') {
            echo '<p id="message" class="message success">Thank you for your feedback!</p>';
        } elseif ($status === 'fail') {
            echo '<p id="message" class="message fail">Sorry, something went wrong. Please try again.</p>';
        }
    }
    ?>

    <h1>Submit Your Feedback</h1>

    <form method="POST" action="feedback.php">
        <label for="title">Title:</label><br>
        <input type="text" name="title" id="title" required>
        <br>
        <label for="description">Feedback Description:</label><br>
        <textarea name="description" id="description" rows="4" cols="50" required></textarea>
        <label for="rating">Rate Your Experience:</label><br>
        <div class="rating-faces">
            <label>
                <input type="radio" name="rating" value="1" required>
                <span class="face">😡</span>
            </label>
            <label>
                <input type="radio" name="rating" value="2">
                <span class="face">😟</span>
            </label>
            <label>
                <input type="radio" name="rating" value="3">
                <span class="face">😐</span>
            </label>
            <label>
                <input type="radio" name="rating" value="4">
                <span class="face">🙂</span>
            </label>
            <label>
                <input type="radio" name="rating" value="5">
                <span class="face">😃</span>
            </label>
        </div>
        <br>
        <button type="submit">Submit</button>
    </form>

    <script>
        const message = document.getElementById('message');
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 2000);
        }
    </script>
</body>

</html>