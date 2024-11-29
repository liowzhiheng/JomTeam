<?php
// match_details.php

// Include database connection
require("config.php");

// Start session to access user session data
session_start();

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $match_id = $_GET['id'];

    // Prepare and execute query to fetch match details
    $query = "SELECT * FROM gamematch WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $match_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the match exists
    if ($result->num_rows > 0) {
        $match = $result->fetch_assoc();
    } else {
        // If match doesn't exist, show error message
        echo "Match not found.";
        exit;
    }
} else {
    // If 'id' is not provided in URL, show error message
    echo "No match selected.";
    exit;
}

// Fetch current number of players and max players
$current_players = $match['current_players'];
$max_players = $match['max_players'];
$user_id = $_SESSION['ID']; // Get user ID from session

// Check if the user has already joined the match
$checkQuery = "SELECT * FROM match_participants WHERE match_id = ? AND user_id = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param('ii', $match_id, $user_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();
$has_joined = $checkResult->num_rows > 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="find_match.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="match_details.css">
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
            <li class="logout"><a href="index.php">Log out<img src="IMAGE/LOGOUT.png" alt="Logout"></a></li>
        </ul>
    </nav>

    <div class="profile-content">
        <h1 class="profile-title">Match Details:</h1>
    </div>

    <div class="profile-content">
        <section class="profile-container">

            <!-- left -->
            <div class=" profile-left">
                <div>
                    <img src="gamematch/<?php echo $match['file']; ?>" alt="Match Image"
                        style="width: 200px; height: auto;">
                </div>
            </div>
            <!-- right -->
            <div class="profile-right">
                <div class="group">
                    <label>Title:</label>
                    <input type="text" value="<?php echo htmlspecialchars($match['match_title']); ?>" readonly>
                </div>
                <div class="group">
                    <label>Game Type:</label>
                    <input type="text" value="<?php echo htmlspecialchars($match['game_type']); ?>" readonly>
                </div>
                <div class="group">
                    <label>Location:</label>
                    <input type="text" value="<?php echo htmlspecialchars($match['location']); ?>" readonly>
                </div>
                <div class="group">
                    <label>Skill Level Required:</label>
                    <textarea readonly><?php echo htmlspecialchars($match['skill_level_required']); ?></textarea>
                </div>
                <div class="group">
                    <label>Start Date:</label>
                    <input type="text" value="<?php echo date($match['start_date']); ?>" readonly>
                </div>

                <div class="group">
                    <label>Start Time:</label>
                    <input type="text" value="<?php echo $match['start_time'] ?>" readonly>
                </div>

                <div class="group">
                    <label>Max Players:</label>
                    <input type="text" id="max_players" value="<?php echo htmlspecialchars($match['max_players']); ?>"
                        readonly>
                </div>
                <div class="group">
                    <label>Current Players:</label>
                    <input type="text" id="current_players"
                        value="<?php echo htmlspecialchars($match['current_players']); ?>" readonly>
                </div>

                <div class="group">
                    <label>Status:</label>
                    <input type="text" value="<?php echo htmlspecialchars($match['status']); ?>" readonly>
                </div>
                <div class="group">
                    <label>Description:</label>
                    <textarea readonly><?php echo nl2br(htmlspecialchars($match['description'])); ?></textarea>
                </div>
            </div>

        </section>
        <div class="players_title">
            Member 👥</div>
    </div>

    <div class="circle_container" id="circle_container">
        <!-- Circles will be generated here -->
    </div>

    <div class="players_list">
        <ul id="playersList">
            <?php
            // Query to get players who joined the match
            $playersQuery = "
                SELECT user.first_name, user.last_name 
                FROM match_participants
                INNER JOIN user ON match_participants.user_id = user.id
                WHERE match_participants.match_id = ? 
                ORDER BY match_participants.join_date ASC";

            $stmt = $conn->prepare($playersQuery);
            $stmt->bind_param('i', $match_id);
            $stmt->execute();
            $playersResult = $stmt->get_result();

            $players = [];
            while ($row = $playersResult->fetch_assoc()) {
                $players[] = $row;
            }

            // Display player names or placeholders
            for ($i = 0; $i < $max_players; $i++):
                if ($i < count($players)):
                    $playerName = htmlspecialchars($players[$i]['first_name'] . " " . $players[$i]['last_name']);
                    echo "<li id='player" . ($i + 1) . "'>Player " . ($i + 1) . ": $playerName</li>";
                else:
                    echo "<li id='player" . ($i + 1) . "'>Player " . ($i + 1) . ": ?</li>";
                endif;
            endfor;
            ?>
        </ul>
    </div>

    <div style="text-align: center;"> <!-- Join Match Section -->
    <?php if ($has_joined): ?>
        <!-- If user has already joined, show "Joined" button -->
        <div class="button">
            <p>You have joined the match.</p>
            <p>Do you wish to cancel?</p>
            <a href="join_match.php?id=<?php echo $match_id; ?>">
                <button style="width: 75%; height: 70px; font-size: 16px; font-weight: 700; color: white; background: linear-gradient(202deg, #EB1436 0%, rgba(235, 20, 54, 0.66) 71%); border: none; border-radius: 50px; cursor: pointer; transition: background-color 0.3s ease; margin-top: 10%;">
                    Cancel
                </button>
            </a>
        </div>
        <?php elseif ($current_players < $max_players): ?>
            <!-- If match is not full and user has not joined -->
            <div class="button">
                <a href="join_match.php?id=<?php echo $match_id; ?>">
                    <img src="IMAGE/join_match_button.png" alt="Join Match">
                </a>
            </div>
        <?php else: ?>
            <!-- If match is full -->
            <div class="button">
                <img src="IMAGE/match_full_button.png" alt="Match Full">
            </div>
        <?php endif; ?>
    </div>

    <script src="footer.js"></script>
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


</html>


<script>
    // Update the circles based on max and current players
    function updatePlayers() {
        const maxPlayers = parseInt(document.getElementById('max_players').value);
        const currentPlayers = parseInt(document.getElementById('current_players').value);
        const circleContainer = document.getElementById('circle_container');
        circleContainer.innerHTML = ''; // Clear existing circles

        // Get the pop sound element
        const popSound = document.getElementById('popSound');

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
            // Play the sound for each circle added
            popSound.play();

        }
    }

    // Initialize the circles on page load
    document.addEventListener('DOMContentLoaded', function () {
        updatePlayers();
    });
</script>

<script>
    function updatePlayers() {
        const maxPlayers = parseInt(document.getElementById('max_players').value) || 0;
        const currentPlayers = parseInt(document.getElementById('current_players').value) || 0;
        const circleContainer = document.getElementById('circle_container');
        circleContainer.innerHTML = ''; // Clear existing circles

        // Ensure the pop sound exists
        const popSound = document.getElementById('popSound');

        for (let i = 0; i < maxPlayers; i++) {
            const circle = document.createElement('div');
            circle.classList.add('circle');
            circle.style.backgroundColor = i < currentPlayers ? '#EB1436' : '#AFB7C1'; // Set color
            circleContainer.appendChild(circle);

            // Play sound if the pop sound element exists
            if (popSound) {
                popSound.play();
            }
        }
    }

    // Initialize the circles on page load
    document.addEventListener('DOMContentLoaded', updatePlayers);

</script>
