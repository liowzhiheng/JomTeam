<?php
session_start(); // Start up your PHP Session

// If the user is not logged in send him/her to the login form
if ($_SESSION["Login"] != "YES")
    header("Location: login.php");

if ($_SESSION["LEVEL"] == 3) {

    require("config.php"); // Include the database configuration file
    $user_id = $_SESSION["ID"];
    $sql = "SELECT * FROM profile WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $rows = mysqli_fetch_assoc($result);
    } else {
        // Initialize $rows with empty values if no profile is found
        $rows = [
            'name' => '',
            'ic' => '',
            'gender' => '',
            'matric' => '',
            'phone' => '',
            'email' => ''
        ];
    }
    ?>
    <html>

    <head>
        <title>Create Your Own Match</title>
        <link rel="stylesheet" href="create_match.css">
    </head>

    <body>
        <nav class="navbar">
            <a href="#" class="logo">
                <img src="image/jomteam.png" alt="Logo">
            </a>

            <ul class="menu leftmenu">
                <li><a href="main.php">Home</a></li>
                <li><a href="find_match.php">Find Match</a></li>
                <li><a href="create_match.php">Create Match</a></li>
                <li><a href="view_profile.php">Profile</a></li>
                <li><a href="#premium">Premium</a></li>
            </ul>

            <ul class="menu rightmenu">
                <li class="notification"><a href="#notification"><img src="image/NOTIFICATION.png" alt="Notification"></a>
                </li>
                <li class="logout"><a href="login.php">Log out<img src="image/LOGOUT.png" alt="Logout"></a></li>
            </ul>
        </nav>
        <div class="header">
            <h1>Create Your Own Match</h1>
            <br>
        </div>
        <div class="wrapper">
            <div class="container">
                <form method="post" action="insert_student.php">
                    <div class="title">
                        Not finding the right match? No problem! Create your own game-changing partnership and turn any challenge into a victory. Join our network and start building your dream team today!
                    </div>
                    <div class="content">
                        <div class="personal_info">
                            <div class="input-box">
                                <span class="details">Full Name</span>
                                <input type="text" name="name" value="<?php echo ($rows['name']); ?>" required>
                            </div>
                            <div class="input-box">
                                <span class="details">UserID</span>
                                <input type="text" name="userID" value="<?php echo $_SESSION["ID"] ?>" readonly>
                            </div>
                            <div class="input-box">
                                <span class="details">Phone Number</span>
                                <input type="text" name="phone" value="<?php echo ($rows['phone']); ?>" required>
                            </div>
                            <div class="input-box">
                                <span class="details">Gender</span>
                                <input type="text" name="gender" value="<?php echo ($rows['gender']); ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="title">Game Party Information</div>
                    <div class="content">
                        <div class="personal_info">
                            <div class="input-area">
                                <span class="details">Location</span>
                                <input type="text" name="location" required>
                            </div>
                            <div class="input-box">
                                <span class="details">Start Date</span>
                                <input type="date" name="startDate" required>
                            </div>
                            <div class="input-box">
                                <span class="details">Duration of Game Match</span>
                                <input type="text" name="duration" required>
                            </div>
                        </div>
                    </div>

                    <input type="submit" class="button" value="Submit">
                </form>
            </div>
    </body>

    </html>


    <?php
    // If the user is not correct level
} else if ($_SESSION["LEVEL"] != 3) {

    echo "<p>Wrong User Level! You are not authorized to view this page</p>";

    echo "<p><a href='main.php'>Go back to main page</a></p>";

    echo "<p><a href='logout.php'>LOGOUT</a></p>";
}
