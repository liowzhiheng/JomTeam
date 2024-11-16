<?php
session_start(); // Start the PHP session

// Check if the user is logged in
if ($_SESSION["Login"] != "YES") {
    header("Location: login.php");
    exit();
}

// Check if USER_ID is set
if (!isset($_SESSION["ID"])) {
    echo "User ID is not set in the session.";
    exit();
}

require("config.php");

// Fetch user and profile data
$user_id = $_SESSION['ID'];

$query = "
    SELECT 
        u.first_name, 
        u.last_name, 
        u.gender, 
        u.email, 
        u.phone, 
        p.status, 
        p.description, 
        p.location, 
        p.interests, 
        p.preferred_game_types, 
        p.skill_level, 
        p.availability 
    FROM 
        user u 
    LEFT JOIN 
        profile p 
    ON 
        u.id = p.user_id 
    WHERE 
        u.id = '$user_id'
";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "No profile data found.";
    exit();
}

$rows = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Own Match</title>
    <link rel="stylesheet" href="create_match.css">
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

    <div class="header">
        <h1>Create Your Own Match</h1>
        <br>
    </div>

    <div class="wrapper">
        <div class="container">
            <form method="post" action="match_information.php">
                <div class="title">
                    Not finding the right match? No problem! Create your own game-changing partnership and turn any
                    challenge into a victory. Join our network and start building your dream team today!
                </div>
                <div class="content">
                    <div class="personal_info">
                        <div class="input-box">
                            <span class="details">Full Name</span>
                            <input type="text" name="name"
                                value="<?php echo htmlspecialchars($rows['first_name'] . ' ' . $rows['last_name']); ?>"
                                required>
                        </div>
                        <div class="input-box">
                            <span class="details">UserID</span>
                            <input type="text" name="userID" value="<?php echo $_SESSION['ID']; ?>" readonly>
                        </div>
                        <div class="input-box">
                            <span class="details">Phone Number</span>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($rows['phone']); ?>"
                                required>
                        </div>
                        <div class="input-box">
                            <span class="details">Gender</span>
                            <input type="text" name="gender" value="<?php echo htmlspecialchars($rows['gender']); ?>"
                                required>
                        </div>
                    </div>
                </div>

                <div class="title">Game Party Information</div>
                <div class="content">
                    <div class="personal_info">
                        <div class="input-box">
                            <span class="details">Match Title</span>
                            <input type="text" name="match_title" required>
                        </div>
                        <div class="input-box">
                            <span class="details">Game Type</span>
                            <input type="text" name="game_type" required>
                        </div>
                        <div class="input-box">
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
                        <!-- New Fields -->
                        <div class="input-box">
                            <span class="details">Skill Level Required</span>
                            <input type="text" name="skill_level" required>
                        </div>
                        <div class="input-box">
                            <span class="details">Maximum Players</span>
                            <input type="number" name="max_players" required>
                        </div>
                        <div class="input-box">
                            <span class="details">Current Players</span>
                            <input type="number" name="current_players" required>
                        </div>
                        <div class="input-box">
                            <span class="details">Description</span>
                            <textarea name="description" rows="4" required></textarea>
                        </div>
                    </div>
                </div>

                <input type="submit" class="button" value="Submit">
            </form>


        </div>
    </div>
</body>

</html>