<?php
// match_details.php

// Include database connection
require("config.php");

// Start session to access user session data
session_start();

$user_id = $_SESSION['ID'];
// Check if 'id' is passed in the URL
if (isset($_POST['id'])) {
    $match_id = $_POST['id'];

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

    <style>
        .table-container {
            border-radius: 15px;
            padding: 30px;
            margin: 20px 100px;
            width: 100%;
            overflow: hidden;
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: #dbdbdb;
            color: rgb(0, 0, 0);
            margin: 0 auto;
        }

        .table th,
        .table td {
            padding: 12px 15px;
            text-align: center;
        }

        .table th {
            background-color: #888888;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>

    <?php include('navbar.php'); ?>

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
            <form action="update_gamematch.php" method="POST">
                <div class="profile-right">
                    <input type="hidden" name="match_id" value="<?php echo $match_id; ?>">
                    <div class="group">
                        <label>Title:</label>
                        <input type="text" name="match_title"
                            value="<?php echo htmlspecialchars($match['match_title']); ?>" required>
                    </div>
                    <div class="group">
                        <label>Game Type:</label>
                        <?php $selected_game = $match['game_type'] ?>
                        <select name="game_type" required>
                            <option value="" hidden>Select Game Type</option>
                            <option value="basketball" <?php if ($selected_game == "basketball") {
                                echo ("selected");
                            } ?>>
                                Basketball</option>
                            <option value="football" <?php if ($selected_game == "football") {
                                echo ("selected");
                            } ?>>Football
                            </option>
                            <option value="badminton" <?php if ($selected_game == "badminton") {
                                echo ("selected");
                            } ?>>Badminton
                            </option>
                            <option value="volleyball" <?php if ($selected_game == "volleyball") {
                                echo ("selected");
                            } ?>>
                                Volleyball</option>
                            <option value="tennis" <?php if ($selected_game == "tennis") {
                                echo ("selected");
                            } ?>>Tennis</option>
                            <option value="futsal" <?php if ($selected_game == "fustal") {
                                echo ("selected");
                            } ?>>Futsal</option>
                            <option value="others" <?php if ($selected_game == "others") {
                                echo ("selected");
                            } ?>>Others</option>
                        </select>
                    </div>
                    <div class="group">
                        <label>Location:</label>
                        <?php $selected_location = $match['location'] ?>
                        <select name="location" required>
                            <option value="" hidden>Select Location</option>
                            <!-- Johor -->
                            <optgroup label="Johor">
                                <option value="Johor Bahru" <?php if ($selected_location == "Johor Bahru")
                                    echo "selected"; ?>>Johor Bahru</option>
                                <option value="Skudai" <?php if ($selected_location == "Skudai")
                                    echo "selected"; ?>>
                                    Skudai</option>
                                <option value="Kulai" <?php if ($selected_location == "Kulai")
                                    echo "selected"; ?>>Kulai
                                </option>
                                <option value="Muar" <?php if ($selected_location == "Muar")
                                    echo "selected"; ?>>Muar
                                </option>
                                <option value="Batu Pahat" <?php if ($selected_location == "Batu Pahat")
                                    echo "selected"; ?>>Batu Pahat</option>
                                <option value="Kota Tinggi" <?php if ($selected_location == "Kota Tinggi")
                                    echo "selected"; ?>>Kota Tinggi</option>
                                <option value="Pontian" <?php if ($selected_location == "Pontian")
                                    echo "selected"; ?>>
                                    Pontian</option>
                            </optgroup>
                            <!-- Kedah -->
                            <optgroup label="Kedah">
                                <option value="Alor Setar" <?php if ($selected_location == "Alor Setar")
                                    echo "selected"; ?>>Alor Setar</option>
                                <option value="Sungai Petani" <?php if ($selected_location == "Sungai Petani")
                                    echo "selected"; ?>>Sungai Petani</option>
                                <option value="Kulim" <?php if ($selected_location == "Kulim")
                                    echo "selected"; ?>>Kulim
                                </option>
                                <option value="Langkawi" <?php if ($selected_location == "Langkawi")
                                    echo "selected"; ?>>
                                    Langkawi</option>
                            </optgroup>
                            <!-- Kelantan -->
                            <optgroup label="Kelantan">
                                <option value="Kota Bharu" <?php if ($selected_location == "Kota Bharu")
                                    echo "selected"; ?>>Kota Bharu</option>
                                <option value="Tanah Merah" <?php if ($selected_location == "Tanah Merah")
                                    echo "selected"; ?>>Tanah Merah</option>
                                <option value="Gua Musang" <?php if ($selected_location == "Gua Musang")
                                    echo "selected"; ?>>Gua Musang</option>
                            </optgroup>
                            <!-- Malacca -->
                            <optgroup label="Malacca">
                                <option value="Malacca City" <?php if ($selected_location == "Malacca City")
                                    echo "selected"; ?>>Malacca City</option>
                                <option value="Ayer Keroh" <?php if ($selected_location == "Ayer Keroh")
                                    echo "selected"; ?>>Ayer Keroh</option>
                                <option value="Jasin" <?php if ($selected_location == "Jasin")
                                    echo "selected"; ?>>Jasin
                                </option>
                            </optgroup>
                            <!-- Negeri Sembilan -->
                            <optgroup label="Negeri Sembilan">
                                <option value="Seremban" <?php if ($selected_location == "Seremban")
                                    echo "selected"; ?>>
                                    Seremban</option>
                                <option value="Port Dickson" <?php if ($selected_location == "Port Dickson")
                                    echo "selected"; ?>>Port Dickson</option>
                                <option value="Nilai" <?php if ($selected_location == "Nilai")
                                    echo "selected"; ?>>Nilai
                                </option>
                            </optgroup>
                            <!-- Pahang -->
                            <optgroup label="Pahang">
                                <option value="Kuantan" <?php if ($selected_location == "Kuantan")
                                    echo "selected"; ?>>
                                    Kuantan</option>
                                <option value="Temerloh" <?php if ($selected_location == "Temerloh")
                                    echo "selected"; ?>>
                                    Temerloh</option>
                                <option value="Bentong" <?php if ($selected_location == "Bentong")
                                    echo "selected"; ?>>
                                    Bentong</option>
                                <option value="Cameron Highlands" <?php if ($selected_location == "Cameron Highlands")
                                    echo "selected"; ?>>Cameron Highlands</option>
                            </optgroup>
                            <!-- Penang -->
                            <optgroup label="Penang">
                                <option value="George Town" <?php if ($selected_location == "George Town")
                                    echo "selected"; ?>>George Town</option>
                                <option value="Bayan Lepas" <?php if ($selected_location == "Bayan Lepas")
                                    echo "selected"; ?>>Bayan Lepas</option>
                                <option value="Butterworth" <?php if ($selected_location == "Butterworth")
                                    echo "selected"; ?>>Butterworth</option>
                            </optgroup>
                            <!-- Perak -->
                            <optgroup label="Perak">
                                <option value="Ipoh" <?php if ($selected_location == "Ipoh")
                                    echo "selected"; ?>>Ipoh
                                </option>
                                <option value="Taiping" <?php if ($selected_location == "Taiping")
                                    echo "selected"; ?>>
                                    Taiping</option>
                                <option value="Lumut" <?php if ($selected_location == "Lumut")
                                    echo "selected"; ?>>Lumut
                                </option>
                            </optgroup>
                            <!-- Perlis -->
                            <optgroup label="Perlis">
                                <option value="Kangar" <?php if ($selected_location == "Kangar")
                                    echo "selected"; ?>>
                                    Kangar</option>
                                <option value="Arau" <?php if ($selected_location == "Arau")
                                    echo "selected"; ?>>Arau
                                </option>
                            </optgroup>
                            <!-- Sabah -->
                            <optgroup label="Sabah">
                                <option value="Kota Kinabalu" <?php if ($selected_location == "Kota Kinabalu")
                                    echo "selected"; ?>>Kota Kinabalu</option>
                                <option value="Sandakan" <?php if ($selected_location == "Sandakan")
                                    echo "selected"; ?>>
                                    Sandakan</option>
                                <option value="Tawau" <?php if ($selected_location == "Tawau")
                                    echo "selected"; ?>>Tawau
                                </option>
                            </optgroup>
                            <!-- Sarawak -->
                            <optgroup label="Sarawak">
                                <option value="Kuching" <?php if ($selected_location == "Kuching")
                                    echo "selected"; ?>>
                                    Kuching</option>
                                <option value="Miri" <?php if ($selected_location == "Miri")
                                    echo "selected"; ?>>Miri
                                </option>
                                <option value="Sibu" <?php if ($selected_location == "Sibu")
                                    echo "selected"; ?>>Sibu
                                </option>
                            </optgroup>
                            <!-- Selangor -->
                            <optgroup label="Selangor">
                                <option value="Shah Alam" <?php if ($selected_location == "Shah Alam")
                                    echo "selected"; ?>>Shah Alam</option>
                                <option value="Petaling Jaya" <?php if ($selected_location == "Petaling Jaya")
                                    echo "selected"; ?>>Petaling Jaya</option>
                                <option value="Subang Jaya" <?php if ($selected_location == "Subang Jaya")
                                    echo "selected"; ?>>Subang Jaya</option>
                            </optgroup>
                            <!-- Terengganu -->
                            <optgroup label="Terengganu">
                                <option value="Kuala Terengganu" <?php if ($selected_location == "Kuala Terengganu")
                                    echo "selected"; ?>>Kuala Terengganu</option>
                                <option value="Kemaman" <?php if ($selected_location == "Kemaman")
                                    echo "selected"; ?>>
                                    Kemaman</option>
                                <option value="Dungun" <?php if ($selected_location == "Dungun")
                                    echo "selected"; ?>>
                                    Dungun</option>
                            </optgroup>
                            <!-- Federal Territories -->
                            <optgroup label="Federal Territories">
                                <option value="Kuala Lumpur" <?php if ($selected_location == "Kuala Lumpur")
                                    echo "selected"; ?>>Kuala Lumpur</option>
                                <option value="Putrajaya" <?php if ($selected_location == "Putrajaya")
                                    echo "selected"; ?>>Putrajaya</option>
                                <option value="Labuan" <?php if ($selected_location == "Labuan")
                                    echo "selected"; ?>>
                                    Labuan</option>
                            </optgroup>
                        </select>

                    </div>
                    <div class="group">
                        <label>Skill Level Required:</label>
                        <?php $selected = $match['skill_level_required'] ?>
                        <select name="skill_level" required>
                            <option value="" hidden>Select Level</option>
                            <option value="Beginner" <?php if ($selected == "Beginner") {
                                echo ("selected");
                            } ?>>Beginner</option>
                            <option value="Intermediate" <?php if ($selected == "Intermediate") {
                                echo ("selected");
                            } ?>>
                                Intermediate</option>
                            <option value="Advanced" <?php if ($selected == "Advanced") {
                                echo ("selected");
                            } ?>>Advanced</option>
                            <option value="Professional" <?php if ($selected == "Professional") {
                                echo ("selected");
                            } ?>>
                                Professional</option>
                        </select>
                    </div>
                    <div class="group">
                        <label>Start Date:</label>
                        <input type="date" name="startDate" id="startDate"
                            value="<?php echo date($match['start_date']); ?>" required oninput="validDate()">
                    </div>
                    <div class="group">
                        <label>Start Time:</label>
                        <input type="time" name="startTime" id="startTime" value="<?php echo $match['start_time'] ?>"
                            required oninput="validTime()">
                    </div>
                    <div class="group">
                        <label class="details">Duration of Game Match</label>
                        <input type="number" name="duration" value="<?php echo $match['duration'] ?>" required>
                    </div>

                    <div class="group">
                        <label>Max Players:</label>
                        <input type="number" name="max_players" id="max_players"
                            value="<?php echo htmlspecialchars($match['max_players']); ?>" required
                            oninput="validatePlayerInput(this)" onchange="updatePlayers()">
                    </div>
        

                    <div class="group">
                        <label>Current Players:</label>
                        <input type="text" id="current_players"
                            value="<?php echo htmlspecialchars($match['current_players']); ?>" readonly>
                    </div>
                    <div class="group">
                        <label>Description:</label>
                        <input name="description" value="<?php echo nl2br(htmlspecialchars($match['description'])); ?>"
                            required></input>
                    </div>

                    <div class="group">
                        <label>Email:</label>
                        <input type="text" value="<?php echo htmlspecialchars($host['email']); ?>" readonly>
                    </div>

                    <div>
                        <button type="submit" class="update_button" name="update" value="Update">
                            <img src="IMAGE/update_button.png" alt="Submit Button">
                        </button>
                    </div>
                </div>

            </form>
        </section>
        <h1 class="players_title">Member 👥 </h1>
    </div>
    <div class="players_list" style="margin-top:-10px">

        <!-- Host Info -->
        <div>
            <label>Host:</label>
            <a href="player_profile.php?id=<?php echo $host['id']; ?>&match_id=<?php echo $match_id; ?>"
                class="host-name">
                <?php echo htmlspecialchars($host['first_name'] . ' ' . $host['last_name']); ?>
            </a>
        </div>

        <ul id="playersList">
            <?php
            // Query to get players who joined the match
            $playersQuery = "
    SELECT user.id, user.first_name, user.last_name 
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
            $maxPlayers = $match['max_players']; // Max players allowed in the game
            $currentPlayersCount = count($players); // Get the count of current players in the match
            $remainingSlots = $match['current_players'] - $currentPlayersCount; // Remaining slots for "X"
            
            // Loop to display all player slots
            for ($i = 1; $i <= $maxPlayers; $i++) {
                if ($remainingSlots > 0) {
                    // Show "X" placeholders dynamically
                    echo "<li id='player{$i}'>
                Player {$i}: Reserved
                <form action='delete_participant.php' method='POST' style='display: inline;'>
                    <input type='hidden' name='id' value=''>
                    <input type='hidden' name='match_id' value='{$match_id}'>
                    <input type='hidden' name='real' value='0'>
                    <button type='submit' class='delete_button' value='' style='background: none; border: none;' onclick='return confirmDelete()'>
                        <img src='IMAGE/remove_user_button.png' alt='Delete'>
                    </button>
                </form>
                <style>
    /* Apply hover effect to the image */
    .delete_button img{cursor: pointer; width: 40px; height: 40px;transform: translate(20px,1px);}

    .delete_button img:hover {
        transform: translate(20px, 1px) scale(1.1) ; /* Slightly enlarges the image */
        transition: all 0.3s ease; /* Smooth transition */
    }
</style>
            </li>";
                    $remainingSlots--; // Decrease the count of remaining "X"s
                } elseif ($currentPlayerIndex < $currentPlayersCount) {
                    // Show the names of joined players
                    $player = $players[$currentPlayerIndex];
                    $playerName = htmlspecialchars($player['first_name'] . " " . $player['last_name']);
                    echo "<li id='player{$i}'>
                Player {$i}: 
                <a href='player_profile.php?id={$player['id']}&match_id={$match_id}' 
                    style='color: black; 
                           text-decoration: none; 
                           font-weight: 500; 
                           display: inline-block;
                           transition: transform 0.3s ease, box-shadow 0.3s ease;' 
                    onmouseover=\"this.style.color='#EB1436'; 
                                 this.style.transform='translateY(-3px)';\" 
                    onmouseout=\"this.style.color='black'; 
                                this.style.transform='none';\">
                    {$playerName}
                </a>
                <form action='delete_participant.php' method='POST' style='display: inline;'>
                    <input type='hidden' name='id' value='{$player['id']}'>
                    <input type='hidden' name='match_id' value='{$match_id}'>
                    <input type='hidden' name='real' value='1'>
                    <button type='submit' class='delete_button' value='' style='background: none; border: none;' onclick='return confirmDelete()'>
                        <img src='IMAGE/remove_user_button.png' alt='Delete'>
                    </button>
                </form>
                <style>
    .delete_button img{cursor: pointer; width: 40px; height: 40px;transform: translate(20px,5px);;}

    .delete_button img:hover {
        transform: translate(20px, 5px) scale(1.1) ; /* Slightly enlarges the image */
        transition: all 0.3s ease; /* Smooth transition */
    }
</style>
            </li>";
                    $currentPlayerIndex++; // Move to the next player
                } else {
                    // Show "?" for any remaining empty slots
                    echo "<li id='player{$i}'>Player {$i}: ?</li>";
                }
            }
            ?>
        </ul>
    </div>

    <script>
        // JavaScript function to confirm before deleting
        function confirmDelete() {
            return confirm('Are you sure you want to remove this player?');
        }
    </script>



    </div>

    <script src="footer.js"></script>
</body>

</html>

<script>
    function validDate() {
        const inputDate = new Date(document.getElementById('startDate').value);
        const today = new Date();

        inputDate.setHours(0, 0, 0, 0);
        today.setHours(0, 0, 0, 0);

        if (inputDate.getTime() !== today.getTime()) {
            alert("The date must be today! (Unless you join premium 👻)");
            document.getElementById('startDate').value = "<?php echo $match['start_date'] ?>"; // Clear the invalid date
        }
    }

    function validTime() {
        const inputTime = document.getElementById('startTime').value;
        const now = new Date();

        // Get the current time in "HH:MM" format
        const currentTime = now.toTimeString().substring(0, 5);

        // Compare the input time with the current time
        if (inputTime < currentTime) {
            alert("The time must be later than the current time!");
            document.getElementById('startTime').value = "<?php echo $match['start_time'] ?>"; // Clear the invalid input
        }
    }

    function validatePlayerInput() {
        const maxPlayers = parseInt(document.getElementById('max_players').value) || 0;
        const currentPlayers = parseInt(document.getElementById('current_players').value) || 0;

        if (currentPlayers > maxPlayers) {
            alert("Current players cannot exceed maximum players!");
            document.getElementById('current_players').value = "<?php echo $match['current_players'] ?>";
        }
    }
</script>