<?php
// match_details.php

// Include database connection
require("config.php");

// Start session to access user session data
session_start();

$user_id = $_SESSION['ID'];
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

//Check if has request
$checkQuery = "SELECT * FROM match_request WHERE match_id = ? AND request_user_id = ? AND status = 'pending'";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param('ii', $match_id, $user_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();
$has_request = $checkResult->num_rows > 0;

// host
$hostQuery = "SELECT * FROM user WHERE id = ?";
$hostStmt = $conn->prepare($hostQuery);
$hostStmt->bind_param('i', $match['user_id']);
$hostStmt->execute();
$hostResult = $hostStmt->get_result();

if ($hostResult->num_rows > 0) {
    $host = $hostResult->fetch_assoc();
} else {
    // If the host is not found, show a message
    echo "Host not found.";
    exit;
}

$ishost = 0;

if ($host['id'] == $user_id) {
    $ishost = 1;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="find_match.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="match_details.css">
    <link rel="shortcut icon" type="image/jpg" href="IMAGE/favicon.png"/>

</head>

<body>

    <?php include('navbar.php'); ?>

    <div class="profile-content">
        <h1 class="profile-title">Match Details:</h1>
    </div>

    <div class=""> <!-- TESTINGGGGGGGGGGGGG big_container-->
        <div class="profile-content">
            <section class="profile-container">

                <!-- left -->
                <div class=" profile-left">
                    <div>
                        <img src="gamematch/<?php echo $match['file']; ?>" alt="Match Image"
                            style="width: 400px; height: 400;">
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
                        <input type="text" id="max_players"
                            value="<?php echo htmlspecialchars($match['max_players']); ?>" readonly>
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

                    <div class="group">
                        <label>Email:</label>
                        <input type="text" value="<?php echo htmlspecialchars($host['email']); ?>" readonly>
                    </div>
                </div>

            </section>
            <?php if ($ishost) { ?>


                <div>
                    <form action="edit_match.php" method="POST" onSubmit="return confirm('Do you want to edit?')">
                        <input type="hidden" name="id" value="<?php echo $match_id; ?>">
                        <button class="edit_button">
                            <img src="IMAGE/edit_button_2.png" alt="Edit" style="width: 16%; height: auto;">
                        </button>
                    </form>
                </div>




                <?php
            }
            ?>
            <div class="players_title">
                Member 👥</div>
        </div>

        <div class="circle_container" id="circle_container">
            <!-- Circles will be generated here -->
        </div>

        <div class="players_list">
            <!-- Host Info -->
            <div>
                <label>Host:</label>
                <?php
                 if ($host['premium']) {
                            // Fetch the frame ID for the player using user_id from the profile table
                            $frameSql1 = "SELECT frame FROM profile WHERE user_id = " . $host['id'];
                            $frameResult1 = mysqli_query($conn, $frameSql1);

                            if ($frameResult1 && mysqli_num_rows($frameResult1) > 0) {
                                $frameRow1 = mysqli_fetch_assoc($frameResult1);
                                $frameId1 = $frameRow1['frame'];
    
                                // Fetch the frame data if a frame ID exists
                                $frameData1 = null;
                                if ($frameId1) {
                                    $frameDataSql1 = "SELECT * FROM frame WHERE id = '$frameId1'";
                                    $frameDataResult1 = mysqli_query($conn, $frameDataSql1);
                                    if ($frameDataResult1 && mysqli_num_rows($frameDataResult1) > 0) {
                                        $frameData1 = mysqli_fetch_assoc($frameDataResult1);
                                    }
                                }

                                // Display the frame if it exists
                                if ($frameData1) {
                                    echo "<div class='image-container-match'>";
                                    echo "<img src='frame/" . htmlspecialchars($frameData1['file']) . "' alt='Premium Frame' class='premium-frame-host' />";
                                    echo "</div>";
                                }
                            } else {
                                echo "frame not found";
                            }
                        }
                $res = mysqli_query($conn, "SELECT file FROM images WHERE user_id = " . $host['id']);
                $row = mysqli_fetch_assoc($res);
                if (empty($row['file'])) {
                    // Default profile picture if not available
                    echo '<img src="IMAGE/default.png" alt="Host Profile Picture" class="profile-pic-player">';
                } else {
                    // Host's profile picture
                    echo '<img src="uploads/' . $row['file'] . '" alt="Host Profile Picture" class="profile-pic-player">';
                }
                ?>
                <a href="player_profile.php?id=<?php echo $host['id']; ?>&match_id=<?php echo $match_id; ?>"
                    class="host-name">
                    <?php echo htmlspecialchars($host['first_name'] . ' ' . $host['last_name']); ?>
                </a>
            </div>

            <ul id="playersList">
                <?php
                // Query to get players who joined the match
                $playersQuery = "
            SELECT user.id, user.first_name, user.last_name,user.premium 
            FROM match_participants
            INNER JOIN user ON match_participants.user_id = user.id
            WHERE match_participants.match_id = ? 
            ORDER BY match_participants.join_date ASC";

                // Prepare and execute the query
                $stmt = $conn->prepare($playersQuery);
                $stmt->bind_param('i', $match_id);
                $stmt->execute();
                $playersResult = $stmt->get_result();

                $players = [];
                while ($row = $playersResult->fetch_assoc()) {
                    $players[] = $row;
                }

                $currentPlayerIndex = 0; // To track the index of players joining
                $maxPlayers = $max_players; // Max players allowed in the game
                $currentPlayersCount = count($players); // Get the count of current players in the match
                $displayedCurrentPlayers = $current_players; // Track how many current players are there
                
                // Calculate how many "X" to display (the difference between current_players and actual players in DB)
                $X = $displayedCurrentPlayers - $currentPlayersCount;

                // Loop to display all player slots
                for ($i = 1; $i <= $maxPlayers; $i++) {
                    if ($X > 0) {
                        // Show "X" for the remaining current players
                        echo "<li id='player{$i}'>Player {$i}: Reserved</li>";
                        $X--; // Decrease the count of "X" shown
                    } elseif ($currentPlayerIndex < $currentPlayersCount) {
                        // After "X", show the names of joined players
                        $player = $players[$currentPlayerIndex];
                        $playerName = htmlspecialchars($player['first_name'] . " " . $player['last_name']);

                        $profilePicRes = mysqli_query($conn, "SELECT file FROM images WHERE user_id = " . $player['id']);
                        $profilePicRow = mysqli_fetch_assoc($profilePicRes);

                        if ($player['premium']) {
                            // Fetch the frame ID for the player using user_id from the profile table
                            $frameSql = "SELECT frame FROM profile WHERE user_id = " . $player['id'];
                            $frameResult = mysqli_query($conn, $frameSql);

                            if ($frameResult && mysqli_num_rows($frameResult) > 0) {
                                $frameRow = mysqli_fetch_assoc($frameResult);
                                $frameId = $frameRow['frame'] ?? null;

                                // Fetch the frame data if a frame ID exists
                                $frameData = null;
                                if ($frameId) {
                                    $frameDataSql = "SELECT * FROM frame WHERE id = '$frameId'";
                                    $frameDataResult = mysqli_query($conn, $frameDataSql);
                                    if ($frameDataResult && mysqli_num_rows($frameDataResult) > 0) {
                                        $frameData = mysqli_fetch_assoc($frameDataResult);
                                    }
                                }

                                // Display the frame if it exists
                                if ($frameData) {
                                    echo "<div class='image-container-match'>";
                                    echo "<img src='frame/" . htmlspecialchars($frameData['file']) . "' alt='Premium Frame' class='premium-frame' />";
                                    echo "</div>";
                                }
                            } else {

                            }
                        }
                        echo "<li id='player{$i}'>Player {$i}: ";

                        if (empty($profilePicRow['file'])) {
                            echo '<img src="IMAGE/default.png" alt="Profile Picture" class="profile-pic-player">';
                        } else {
                            echo '<img src="uploads/' . $profilePicRow['file'] . '" alt="Profile Picture" class="profile-pic-player">';
                        }

                        echo "<a href='player_profile.php?id={$player['id']}&match_id={$match_id}' 
                            style='color: black; 
                            text-decoration: none; 
                            font-weight: 500; 
                            display: inline-block;
                            transition: transform 0.3s ease, box-shadow 0.3s ease;'
                            onmouseover=\"this.style.color='#EB1436'; 
                                        this.style.transform='translateY(-3px)'\" 
                            onmouseout=\"this.style.color='black'; 
                                        this.style.transform='none' 
                                        \">$playerName</a></li>";

                        $currentPlayerIndex++; // Move to the next participant
                    } else {
                        // Show "?" for any remaining empty slots
                        echo "<li id='player{$i}'>Player {$i}: ?</li>";
                    }
                }
                ?>
            </ul>
        </div>





        <!-- Join Match Section -->
        <div style="text-align: center;
            font-family:'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 25px;
            margin-top:3%; ">
            <div style="display: flex; justify-content: center; flex-wrap: wrap;">
                <?php if ($has_joined): ?>
                    <!-- If user has already joined, show "Joined" button -->
                    <div>
                        <p style="color: black;">You have joined the match.</p>
                        <p style="color: black;">Do you wish to cancel?</p>
                        <form action="cancel_match.php" method="GET" style="text-align: center;">
                            <input type="hidden" name="id" value="<?php echo $match_id; ?>">
                            <button class="cancel-button">
                                Cancel
                            </button>
                        </form>
                        <style>
                            /* Default style for the cancel button */
                            .cancel-button {
                                width: 300px;
                                height: 100px;
                                font-size: 30px;
                                font-weight: 700;
                                color: white;
                                background: linear-gradient(202deg, #EB1436 0%, rgba(235, 20, 54, 0.9) 71%);
                                border: none;
                                border-radius: 50px;
                                cursor: pointer;
                                margin-top: 1%;
                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                                transition: all 0.3s ease;
                                /* Smooth transition for all properties */
                            }

                            /* Hover effect for the cancel button */
                            .cancel-button:hover {
                                transform: scale(1.1);
                                /* Slightly enlarges the button */
                                background: linear-gradient(202deg, #EB1436 0%, rgba(235, 20, 54, 0.9) 71%);
                                /* Darkens the gradient */
                            }
                        </style>
                    </div>

                <?php elseif ($has_request && $current_players == $max_players): ?>
                    <div>
                        <p style="color: black;">You have request the match but the match is already full now.</p>
                        <p style="color: black;">Do you wish to cancel the request?</p>
                         <button style="width: 300px; 
                        height: 100px; 
                        font-size: 30px; 
                        font-weight: 700; 
                        color: white; 
                        background: black;
                        border: none; 
                        border-radius: 50px; 
                        cursor: pointer; 
                        transition: background-color 0.3s ease; 
                        margin-top:1%" box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                            Match Full
                        </button>
                        <form action="cancel_request.php" method="GET" style="text-align: center;">
                            <input type="hidden" name="id" value="<?php echo $match_id; ?>">
                            <button class="cancel-button">
                                Cancel
                            </button>
                        </form>
                        <style>
                            /* Default style for the cancel button */
                            .cancel-button {
                                width: 300px;
                                height: 100px;
                                font-size: 30px;
                                font-weight: 700;
                                color: white;
                                background: linear-gradient(202deg, #EB1436 0%, rgba(235, 20, 54, 0.9) 71%);
                                border: none;
                                border-radius: 50px;
                                cursor: pointer;
                                margin-top: 1%;
                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                                transition: all 0.3s ease;
                                /* Smooth transition for all properties */
                            }

                            /* Hover effect for the cancel button */
                            .cancel-button:hover {
                                transform: scale(1.1);
                                /* Slightly enlarges the button */
                                background: linear-gradient(202deg, #EB1436 0%, rgba(235, 20, 54, 0.9) 71%);
                                /* Darkens the gradient */
                            }
                        </style>
                    </div>


                <?php elseif ($has_request): ?>
                    <div>
                        <p style="color: black;">You have request the match.</p>
                        <p style="color: black;">Do you wish to cancel the request?</p>
                        <form action="cancel_request.php" method="GET" style="text-align: center;">
                            <input type="hidden" name="id" value="<?php echo $match_id; ?>">
                            <button class="cancel-button">
                                Cancel
                            </button>
                        </form>
                        <style>
                            /* Default style for the cancel button */
                            .cancel-button {
                                width: 300px;
                                height: 100px;
                                font-size: 30px;
                                font-weight: 700;
                                color: white;
                                background: linear-gradient(202deg, #EB1436 0%, rgba(235, 20, 54, 0.9) 71%);
                                border: none;
                                border-radius: 50px;
                                cursor: pointer;
                                margin-top: 1%;
                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                                transition: all 0.3s ease;
                                /* Smooth transition for all properties */
                            }

                            /* Hover effect for the cancel button */
                            .cancel-button:hover {
                                transform: scale(1.1);
                                /* Slightly enlarges the button */
                                background: linear-gradient(202deg, #EB1436 0%, rgba(235, 20, 54, 0.9) 71%);
                                /* Darkens the gradient */
                            }
                        </style>
                    </div>

                <?php elseif ($current_players < $max_players): ?>
                    <!-- If match is not full and user has not joined -->
                    <div>
                        <p style="color: black;">Are you interested in the match?</p>
                        <p style="color: black;">Join now and have fun!</p>
                        <?php if ($ishost) { ?>
                            <form action="join_match.php" method="POST" style="text-align: center;">
                                <input type="hidden" name="match_id" value="<?php echo $match_id; ?>">
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                <button class="join-button">
                                    Join Match
                                </button>
                            </form>
                            <?php
                        } else {
                            ?>
                            <form action="request_match.php" method="GET" style="text-align: center;">
                                <input type="hidden" name="id" value="<?php echo $match_id; ?>">
                                <button class="join-button">
                                    Join Match
                                </button>
                            </form>
                            <?php
                        }
                        ?>
                        <style>
                            /* Default style for the button */
                            .join-button {
                                width: 300px;
                                height: 100px;
                                font-size: 30px;
                                font-weight: 700;
                                color: white;
                                background: linear-gradient(202deg, #EB1436 0%, rgba(235, 20, 54, 0.9) 71%);
                                border: none;
                                border-radius: 50px;
                                cursor: pointer;
                                margin-top: 1%;
                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                                transition: all 0.3s ease;
                                /* Smooth transition for all properties */
                            }

                            /* Hover effect for the button */
                            .join-button:hover {
                                transform: scale(1.1);
                                /* Slightly enlarges the button */
                                background: linear-gradient(202deg, #EB1436 0%, rgba(235, 20, 54, 0.9) 71%);
                            }
                        </style>
                    </div>
            
                <?php else: ?>
                    <!-- If match is full -->
                    <div>
                        <p style="color: black;">It seems the match is full.</p>
                        <p style="color: black;">Try to look for another one!</p>
                        <button style="width: 300px; 
                        height: 100px; 
                        font-size: 30px; 
                        font-weight: 700; 
                        color: white; 
                        background: black;
                        border: none; 
                        border-radius: 50px; 
                        cursor: pointer; 
                        transition: background-color 0.3s ease; 
                        margin-top:1%" box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                            Match Full
                        </button>
                    </div>
                <?php endif; ?>

                <?php if ($ishost) { ?>


                    <div>
                        <a href="#" onclick="openModal('request')"><img src="IMAGE/request.png" alt="Delete"
                                class="delete-img"
                                style="width: 100px; height: 100px; margin-top: 85%; margin-left: 40%;"></a>
                    </div>

                    <form action="delete_match.php" method="POST" onSubmit="return confirm('Do you want to delete?')">
                        <input type="hidden" name="id" value="<?php echo $match_id; ?>">
                        <button class="delete-button"
                            style="background: none; border: none; cursor: pointer; margin-top: 89%; margin-left: 80%;">
                            <img src="IMAGE/delete_button.png" alt="Delete" class="delete-img"
                                style="width: 100px; height: 100px;">
                        </button>
                    </form>

                    <style>
                        /* Hover effect for the delete button image */
                        .delete-img:hover {
                            transform: scale(1.1);
                            /* Slightly enlarges the image */
                            transition: all 0.3s ease;
                            /* Smooth transition */
                        }
                    </style>
                </div>

            </div>
            <?php
                }
                ?>
    </div>

    </div>

    <div class="chatroom">
        <h2>Chat Room💬</h2>
        <div id="chatMessages" class="chat-messages">
            <!-- Messages will be dynamically loaded here -->
        </div>
        <form id="chatForm">
            <input type="hidden" id="match_id" value="<?php echo $match_id; ?>">
            <input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
            <input id="chatInput" placeholder="Type your message here..." required />
            <button class="chat_button" type="submit">➤</button>
        </form>
    </div>





    <script src="chat_room.js"></script>
    <script src="footer.js"></script>

</body>

<footer>
    <div class="footer-container">
        <div class="footer-links">
            <a href="#" onclick="openModal('terms')">Terms of Service</a> |
            <a href="#" onclick="openModal('privacy')">Privacy Policy</a>
        </div>
        <div class="footer-info">
            <p1>&copy; 2024 JomTeam. All rights reserved.</p1>
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

<div id="requestModal" class="modal">
    <div class="match-request" style="color:black;">
        <span class="close" onclick="closeModal('request')">&times;</span>


        <?php
        $query2 = "SELECT * from match_request WHERE match_id = $match_id AND status='pending'";
        $result2 = mysqli_query($conn, $query2);

        if (mysqli_num_rows($result2) > 0) {
            $requests = mysqli_fetch_all($result2, MYSQLI_ASSOC);

        } else {

            $requests = [];
        }
        ?>
        <h1>Match Request:</h1>
        <ul>

            <?php if (!empty($requests)): ?>

                <?php foreach ($requests as $request):
                    $request_id = $request['request_user_id'];
                    $query3 = "SELECT * from user WHERE id = $request_id";
                    $result3 = mysqli_query($conn, $query3);

                    if (mysqli_num_rows($result3) > 0) {
                        $row2 = mysqli_fetch_assoc($result3);
                        echo '<p class="detail">';
                        echo '<a class="friend-name" href="player_profile.php?id=' . htmlspecialchars($row2['id']) . '">';
                        echo htmlspecialchars($row2['first_name'] . ' ' . $row2['last_name']);
                        echo '</a>';
                        //profile picture
                        $profilePicRes = mysqli_query($conn, "SELECT file FROM images WHERE user_id = " . $row2['id']);
                        $profilePicRow = mysqli_fetch_assoc($profilePicRes);
                        if (empty($profilePicRow['file'])) {
                            echo '<img src="IMAGE/default.png" alt="Profile Picture" class="profile-pic">';
                        } else {
                            echo '<img src="uploads/' . $profilePicRow['file'] . '" alt="Profile Picture" class="profile-pic">';
                        } ?>
                        <form method="POST" class="action-form" action="match_request_action.php">
                            <input type="hidden" name="request_user_id" value="<?php echo $row2['id']; ?>">
                            <input type="hidden" name="request_match_id" value="<?php echo $request['match_id']; ?>">
                            <button type="submit" name="accept_request_match" class="action-button accept-button">Accept</button>
                        </form>

                        <form method="POST" class="action-form" action="match_request_action.php">
                            <input type="hidden" name="request_user_id" value="<?php echo $row2['id']; ?>">
                            <input type="hidden" name="request_match_id" value="<?php echo $request['match_id']; ?>">
                            <button type="submit" name="reject_request_match" class="action-button reject-button">Reject</button>
                        </form>
                        </p>
                        <?php
                    }
                endforeach;
                ?>
                </p>
            </ul>
        <?php else: ?>
            </p>

            <p class="detail3">
            <p class="friend-name3">No requests found.</p>
            </p>

        <?php endif; ?>
    </div>
</div>


<script src="footer.js"></script>


</html>

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
