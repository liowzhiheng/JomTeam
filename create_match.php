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
    <link rel="stylesheet" href="footer.css">

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
                        <input type="number" name="max_players" id="max_players" value="0" required
                            oninput="updatePlayers()">
                    </div>


                    <div class="group">
                        <label class="details">Current Players</label>
                        <input type="number" name="current_players" id="current_players" value="0" required
                            oninput="updatePlayers()">
                    </div>


                    <div class="group">
                        <label class="details">Description</label>
                        <textarea name="description" rows="4" required></textarea>
                    </div>


                    <div class="button-container">
                        <button type="submit" class="button" value="Submit">
                            <img src="IMAGE/button_3.png" alt="Submit Button">
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
    $max_players = isset($rows['max_players']) ? $rows['max_players'] : 0; // Example fallback value
    $current_players = isset($rows['current_players']) ? $rows['current_players'] : 0; // Example fallback value
    ?>

    <div class="circle_container" id="circle_container">
        <!-- Circles will be generated here -->
    </div>

    <div class="players_list">
        <ul id="playersList">
            <?php
            // Ensure max_players and current_players are properly set
            $max_players = isset($rows['max_players']) ? $rows['max_players'] : 0;
            $current_players = isset($rows['current_players']) ? $rows['current_players'] : 0;

            for ($i = 0; $i < $max_players; $i++):
                if ($i < $current_players):
                    echo "<li id='player" . ($i + 1) . "'>Player " . ($i + 1) . ": X</li>";
                else:
                    echo "<li id='player" . ($i + 1) . "'>Player " . ($i + 1) . ": ?</li>";
                endif;
            endfor;
            ?>
        </ul>
    </div>








    <script src="view_profile.js"></script>
    <?php mysqli_close($conn); ?>



</body>

<footer>
    <div class="footer-container">
        <div class="footer-links">
            <a href="#" onclick="openModal('terms')">Terms of Service</a> |
            <a href="#" onclick="openModal('privacy')">Privacy Policy</a>
        </div>
        <div class="footer-info">
            <p>&copy; 2024 JomTeam. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Modal for Terms of Service -->
<div id="termsModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('terms')">&times;</span>
        <h2>Terms of Service</h2>
        <p>
            Welcome to JomTeam! By using our platform, you agree to these Terms of Service. Please read them carefully.
            If you do not agree with any part of these terms, you may not use our services.
        </p>
        <h3>1. User Accounts</h3>
        <p>Users must provide accurate and up-to-date information during registration.</p>
        <h3>2. Privacy</h3>
        <p>Your privacy is important to us. We are committed to protecting your personal information.</p>
        <h3>3. Acceptable Use</h3>
        <p>You agree not to use the platform for illegal, harmful, or disruptive purposes.
            Harassment, hate speech, or inappropriate content is strictly prohibited.</p>
        <h3>4. Match Creation and Participation</h3>
        <p>Users creating matches must ensure the information provided (e.g., location, time) is accurate.
            Users participating in matches must adhere to the agreed-upon rules and schedules.</p>
        <h3>5. Payment and Premium Services</h3>
        <p>Premium features may be offered with a subscription. Fees are non-refundable unless specified otherwise.</p>

    </div>
</div>

<!-- Modal for Privacy Policy -->
<div id="privacyModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('privacy')">&times;</span>
        <h2>Privacy Policy</h2>
        <p>At JomTeam, we respect your privacy. This policy outlines how we handle your personal data when you use our
            platform.</p>

        <h3>1. Information Collection</h3>
        <p>We collect information you provide when you register, interact with our platform, and use our services.</p>

        <h3>2. Data Usage</h3>
        <p>Your data is used to improve our services and provide a personalized experience.</p>

        <h3>3. How We Use Your Information<br></h3>
        <ul>
            <li>To provide and improve our services.</li>
            <li>To personalize your experience and match recommendations.</li>
            <li>To communicate updates, promotions, or changes to the platform.</li>
        </ul>

        <h3>4. Data Sharing</h3>
        <ul>
            <li>We do not sell your personal information.</li>
            <li>Data may be shared with third-party providers (e.g., payment processors) necessary to deliver our
                services.</li>
        </ul>

        <h3>5. Security</h3>
        <p>We use advanced encryption and security measures to protect your data. However, no system is completely
            secure.</p>

        <h3>6. Your Rights</h3>
        <ul>
            <li>You can access, modify, or delete your personal information by contacting support.</li>
            <li>You can opt out of promotional communications at any time.</li>
        </ul>

        <h3>7. Cookies</h3>
        <p>Our platform uses cookies to enhance your browsing experience. You can manage cookie preferences in your
            browser settings.</p>

        <h3>8. Changes to Privacy Policy</h3>
        <p>We may update this Privacy Policy periodically. Changes will be posted on this page with the revised date.
        </p>
    </div>
</div>


<script src="footer.js"></script>
<<<<<<< HEAD

</html>

<script>
    // Update the circles based on max and current players
    function updatePlayers() {
        const maxPlayers = parseInt(document.getElementById('max_players').value);
        const currentPlayers = parseInt(document.getElementById('current_players').value);
        const circleContainer = document.getElementById('circle_container');
        circleContainer.innerHTML = ''; // Clear existing circles

        // Generate circles based on the number of max players
        for (let i = 0; i < maxPlayers; i++) {
            const circle = document.createElement('div');
            circle.classList.add('circle');
            // Set circle color based on whether it's filled or not
            if (i < currentPlayers) {
                circle.style.backgroundColor = '#EB1436'; // Filled circle (player added)
            } else {
                circle.style.backgroundColor = '#AFB7C1'; // Empty circle (vacant spot)
            }
            circleContainer.appendChild(circle);
        }
    }

    // Initialize the circles on page load
    document.addEventListener('DOMContentLoaded', function () {
        updatePlayers();
    });
</script>

<script>
    // Update the player list in real-time
    function updatePlayersList() {
        const maxPlayers = parseInt(document.getElementById('max_players').value);
        const currentPlayers = parseInt(document.getElementById('current_players').value);
        const playersList = document.getElementById('playersList');

        // Clear the current list
        playersList.innerHTML = '';

        // Generate the updated player list
        for (let i = 0; i < maxPlayers; i++) {
            const playerItem = document.createElement('li');
            playerItem.id = 'player' + (i + 1);

            // Set text based on current players
            if (i < currentPlayers) {
                playerItem.textContent = 'Player ' + (i + 1) + ': X';
            } else {
                playerItem.textContent = 'Player ' + (i + 1) + ': ?';
            }

            // Append to the list
            playersList.appendChild(playerItem);
        }
    }

    // Attach event listeners for changes in max_players and current_players inputs
    document.getElementById('max_players').addEventListener('input', updatePlayersList);
    document.getElementById('current_players').addEventListener('input', updatePlayersList);

    // Initialize the player list on page load
    document.addEventListener('DOMContentLoaded', updatePlayersList);

</script>
=======

</html>
>>>>>>> 3654b8bf6969e1a7091d0981b0fabc8b149495a6
