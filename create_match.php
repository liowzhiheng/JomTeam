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
    <link rel="stylesheet" href="create_match_2.css">
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


    <div class="profile-content">
        <h1 class="profile-title">Create your own match</h1>
        <p class="profile-description">
            Not finding the right match? <br>No problem! Create your own game-changing partnership and turn any
            challenge
            into a victory. <br>Join our network and start building your dream team ?
        </p>
    </div>

    <div class="profile-content">
        <!-- start detail -->
        <div class="profile-container">
            <!-- left -->
            <div class="profile-left">
                <div class="uploaded-images">
                    <?php
                    $res = mysqli_query($conn, "SELECT file FROM images WHERE user_id = " . $_SESSION["ID"]);
                    while ($row = mysqli_fetch_assoc($res)) {
                        if (empty($row['file'])) {
                            // Display default image with overlay text
                            echo '<div class="image-container">
                <img src="IMAGE/default.png" alt="Default Image" class="uploaded-image" onclick="document.getElementById(\'imageInput\').click();" />
                <div class="overlay-text">Upload Image</div>
              </div>';
                        } else {
                            // Display uploaded image with overlay text
                            echo '<div class="image-container">
                <img src="uploads/' . $row['file'] . '" alt="Uploaded Image" class="uploaded-image" onclick="document.getElementById(\'imageInput\').click();" />
                <div class="overlay-text">Click to Change</div>
              </div>';
                        }
                    }
                    ?>

                    <form action="update_image.php" method="POST" enctype="multipart/form-data"
                        class="image-upload-form">
                        <input type="file" name="image" id="imageInput" style="display: none;"
                            onchange="enableSubmitButton()" />
                        <button type="submit" name="submit" class="submit-button" id="uploadButton"
                            disabled>Upload</button>
                    </form>
                </div>
            </div>
            <!-- right -->
            <div class="profile-right">
                <form method="post" action="match_information.php">

                    <div class="group">
                        <label>Full Name</label>
                        <input type="text" name="name"
                            value="<?php echo htmlspecialchars($rows['first_name'] . ' ' . $rows['last_name']); ?>"
                            required>
                    </div>
                    <div class="group">
                        <label>UserID</label>
                        <input type="text" name="userID" value="<?php echo $_SESSION['ID']; ?>" readonly>
                    </div>
                    <div class="group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($rows['phone']); ?>"
                            required>
                    </div>
                    <div class="group">
                        <label>Gender</label>
                        <input type="text" name="gender" value="<?php echo htmlspecialchars($rows['gender']); ?>"
                            required>
                    </div>

                    <div class="group">
                        <label class="details">Match Title</label>
                        <input type="text" name="match_title" required>
                    </div>
                    <div class="group">
                        <label class="details">Game Type</label>
                        <input type="text" name="game_type" required>
                    </div>
                    <div class="group">
                        <label class="details">Location</label>
                        <input type="text" name="location" required>
                    </div>
                    <div class="group">
                        <label class="details">Start Date</label>
                        <input type="date" name="startDate" required>
                    </div>
                    <div class="group">
                        <label class="details">Duration of Game Match</label>
                        <input type="text" name="duration" required>
                    </div>

                    <div class="group">
                        <label class="details">Skill Level Required</label>
                        <input type="text" name="skill_level" required>
                    </div>
                    <div class="group">
                        <label class="details">Maximum Players</label>
                        <input type="number" name="max_players" required>
                    </div>
                    <div class="group">
                        <label class="details">Current Players</label>
                        <input type="number" name="current_players" required>
                    </div>
                    <div class="group">
                        <label class="details">Description</label>
                        <textarea name="description" rows="4" required></textarea>
                    </div>


                    <div class="button-container">
                        <button type="submit" class="button" value="Submit">
                            <img src="IMAGE/red_button.png" alt="Submit Button">
                        </button>
                    </div>

                </form>
            </div>
        </div>


        <div class="players_title">
            Member 👥</div>
    </div>

    <?php
    // Default maximum and current players if none are set
    $max_players = isset($rows['max_players']) ? $rows['max_players'] : 8; // Example fallback value
    $current_players = isset($rows['current_players']) ? $rows['current_players'] : 2; // Example fallback value
    ?>

    <div class="circle_container">
        <?php for ($i = 0; $i < $max_players; $i++): ?>
            <div class="circle" style="background: <?php echo $i < $current_players ? '#EB1436' : '#AFB7C1'; ?>;"></div>
        <?php endfor; ?>
    </div>

    <div class="players_list">
        <ul>
            <?php
            for ($i = 0; $i < $max_players; $i++):
                if ($i < $current_players):
                    echo "<li>Player " . ($i + 1) . ": X</li>";
                else:
                    echo "<li>Player " . ($i + 1) . ": ?</li>";
                endif;
            endfor;
            ?>
        </ul>
    </div>






    <script src="view_profile.js"></script>
    <?php mysqli_close($conn); ?>



</body>

</html>