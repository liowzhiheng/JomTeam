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
                        <input type="text" name ="match_title" value="<?php echo htmlspecialchars($match['match_title']); ?>" required>
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
                            <option value="Skudai" <?php if ($selected_location == "Skudai") {
                                echo ("selected");
                            } ?>>Skudai
                            </option>
                            <option value="Kulai" <?php if ($selected_location == "Kulai") {
                                echo ("selected");
                            } ?>>Kulai</option>
                            <option value="Impian Emas" <?php if ($selected_location == "Impian Emas") {
                                echo ("selected");
                            } ?>>
                                Impian Emas</option>
                            <option value="Sutera" <?php if ($selected_location == "Sutera") {
                                echo ("selected");
                            } ?>>Sutera
                            </option>
                            <option value="Tun Aminah" <?php if ($selected_location == "Tun Aminah") {
                                echo ("selected");
                            } ?>>Tun
                                Aminah</option>
                            <option value="Nusa Bestari" <?php if ($selected_location == "Nusa Bestari") {
                                echo ("selected");
                            } ?>>
                                Nusa Bestari</option>
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
                        <input type="number" name="current_players" id="current_players"
                            value="<?php echo htmlspecialchars($match['current_players']); ?>" required
                            oninput="validatePlayerInput(this)" onchange="updatePlayers()">
                    </div>
                    <div class="group">
                        <label>Description:</label>
                        <input name="description" value="<?php echo nl2br(htmlspecialchars($match['description'])); ?>" required></input>
                    </div>

                    <div class="group">
                        <label>Email:</label>
                        <input type="text" value="<?php echo htmlspecialchars($host['email']); ?>" readonly>
                    </div>
                    <div>
                        <input type="submit" name="update" value="Update"></input>
                    </div>
                </div>

            </form>
        </section>

        <div style="width:100%;">
            <br>
            <h1 class="profile-title">Player List: </h1>
            <?php
            $id = $match['id'];
            $query2 = "
            SELECT user.id, user.first_name, user.last_name 
            FROM match_participants
            INNER JOIN user ON match_participants.user_id = user.id
            WHERE match_participants.match_id = $id 
            ORDER BY match_participants.join_date ASC";
            $result2 = mysqli_query($conn, $query2);

            if (mysqli_num_rows($result2) > 0) {
                $players = mysqli_fetch_all($result2, MYSQLI_ASSOC);
            } else {
                $players = [];
            }
            ?>
            <table style="width:80%;">
                <tr>
                    <th>Num</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
                <?php if ($match['current_players'] > 0) {
                    $num_X = $match['current_players'] - mysqli_num_rows($result2);
                    $num = 1;
                    if (!empty($players)) {
                        foreach ($players as $player) { ?>
                            <tr>
                                <td><?php echo $num; ?></td>
                                <td><?php echo $player['first_name'] . $player['last_name']; ?></td>
                                <td>
                                    <form action='delete_participant.php' method='POST'>
                                        <input type="hidden" name="id" value="<?php echo $player['id']; ?>">
                                        <input type="hidden" name="match_id" value="<?php echo $match_id; ?>">
                                        <input type="hidden" name="real" value="1">
                                        <input type="submit" value="Remove"
                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                    </form>
                                </td>
                            </tr>
                            <?php
                            $num += 1;
                        }
                    }
                    if ($num_X != 0) {
                        for ($i = 0; $i < $num_X; $i++) {
                            ?>
                            <tr>
                                <td><?php echo $num; ?></td>
                                <td>X</td>
                                <td>
                                    <form action='delete_participant.php' method='POST'>
                                        <input type="hidden" name="id" value="<?php echo $player['id']; ?>">
                                        <input type="hidden" name="match_id" value="<?php echo $match_id; ?>">
                                        <input type="hidden" name="real" value="0">
                                        <input type="submit" value="Remove"
                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                    </form>
                                </td>
                            </tr>
                            <?php
                            $num += 1;
                        }
                    }
                }
                ?>
            </table>
        </div>
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
            alert("The date must be today!");
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