<?php
session_start();
require("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $match_id = $_POST['match_id'];
        $match_title = $_POST['match_title'];
        $game_type = $_POST['game_type'];
        $skill_level = $_POST['skill_level'];
        $max_players = $_POST['max_players'];
        $location = $_POST['location'];
        $start_date = $_POST['start_date'];
        $start_time = $_POST['start_time'];
        $duration = $_POST['duration'];
        $status = $_POST['status'];
        $description = $_POST['description'];

        $sqlUpdate = "UPDATE gamematch SET 
                    match_title = '$match_title', 
                    game_type = '$game_type', 
                    skill_level_required = '$skill_level', 
                    max_players = '$max_players', 
                    location = '$location', 
                    start_date = '$start_date', 
                    start_time = '$start_time', 
                    duration = '$duration', 
                    status = '$status', 
                    description = '$description' 
                  WHERE id = $match_id";

        if ($conn->query($sqlUpdate)) {
            $file_name = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $file_name = $_FILES['image']['name'];
                $tempname = $_FILES['image']['tmp_name'];
                $folder = 'gamematch/' . $file_name;
                $result = mysqli_query($conn, "SELECT file FROM gamematch WHERE id = '$match_id'");

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $old_file = 'gamematch/' . $row['file'];

                    $query = mysqli_query($conn, "UPDATE gamematch SET file = '$file_name' WHERE id = '$match_id'");

                } else {
                    $query = mysqli_query($conn, "INSERT INTO gamematch (id, file) VALUES ('$match_id', '$file_name')");
                }

                if (!$query || !move_uploaded_file($tempname, $folder)) {
                    header("Location: update_match.php?status=fail");
                    exit();
                }
            }
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}

$match_id = $_POST['match_id'];
$sqlMatch = "SELECT * FROM gamematch WHERE id = $match_id";
$resultMatch = $conn->query($sqlMatch);
$match = $resultMatch->fetch_assoc();

$sqlParticipants = "
    SELECT mp.*, u.first_name, u.last_name, i.file
    FROM match_participants mp
    JOIN user u ON mp.user_id = u.id
    LEFT JOIN images i ON u.id = i.user_id
    WHERE mp.match_id = $match_id";
$resultParticipants = $conn->query($sqlParticipants);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Match Details</title>
    <link rel="stylesheet" href="update_match.css">
</head>

<body>
    <div class="container">
        <div class="title">
            <a href="view_match.php" class="btn btn-secondary">Back</a>
            <h1>Edit Match Details</h1>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="match_id" value="<?php echo htmlspecialchars($match_id); ?>">

            <div class="image-container">
                <img id="imagePreview" src="gamematch/<?php echo htmlspecialchars($match['file']); ?>"
                    alt="Uploaded Image" class="uploaded-image"
                    onclick="document.getElementById('imageInput').click();" />
                <div class="overlay-text" onclick="document.getElementById('imageInput').click();">Change Image</div>
                <input type="file" name="image" id="imageInput" style="display: none;" onchange="previewImage()" />
            </div>

            <div class="info-section">
                <div class="info-left">
                    <label><strong>Match Title:</strong></label>
                    <input type="text" name="match_title"
                        value="<?php echo htmlspecialchars($match['match_title']); ?>"><br>

                    <label><strong>Start Date:</strong></label>
                    <input type="date" name="start_date"
                        value="<?php echo htmlspecialchars($match['start_date']); ?>"><br>

                    <label><strong>Duration:</strong></label>
                    <input type="number" name="duration"
                        value="<?php echo htmlspecialchars($match['duration']); ?>"><br>

                    <label><strong>Max Players:</strong></label>
                    <input type="number" name="max_players"
                        value="<?php echo htmlspecialchars($match['max_players']); ?>" min="0" id="max_players"><br>

                    <label><strong>Skill Level:</strong></label>
                    <select name="skill_level">
                        <option value="Beginner" <?php echo $match['skill_level_required'] === 'Beginner' ? 'selected' : ''; ?>>
                            Beginner</option>
                        <option value="Intermediate" <?php echo $match['skill_level_required'] === 'Intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                        <option value="Advanced" <?php echo $match['skill_level_required'] === 'Advanced' ? 'selected' : ''; ?>>
                            Advanced</option>
                        <option value="Professional" <?php echo $match['skill_level_required'] === 'Professional' ? 'selected' : ''; ?>>Professional</option>
                    </select><br>

                    <label><strong>Status:</strong></label>
                    <select name="status">
                        <option value="open" <?php echo $match['status'] == 'open' ? 'selected' : ''; ?>>Open</option>
                        <option value="closed" <?php echo $match['status'] == 'closed' ? 'selected' : ''; ?>>Closed
                        </option>
                    </select><br>
                </div>

                <div class="info-right">
                    <label><strong>Game Type:</strong></label>
                    <select name="game_type">
                        <option value="basketball" <?php echo $match['game_type'] === 'basketball' ? 'selected' : ''; ?>>
                            Basketball</option>
                        <option value="football" <?php echo $match['game_type'] === 'football' ? 'selected' : ''; ?>>
                            Football
                        </option>
                        <option value="badminton" <?php echo $match['game_type'] === 'badminton' ? 'selected' : ''; ?>>
                            Badminton
                        </option>
                        <option value="volleyball" <?php echo $match['game_type'] === 'volleyball' ? 'selected' : ''; ?>>
                            Volleyball</option>
                        <option value="tennis" <?php echo $match['game_type'] === 'tennis' ? 'selected' : ''; ?>>Tennis
                        </option>
                        <option value="futsal" <?php echo $match['game_type'] === 'futsal' ? 'selected' : ''; ?>>Futsal
                        </option>
                        <option value="others" <?php echo $match['game_type'] === 'others' ? 'selected' : ''; ?>>Others
                        </option>
                    </select><br>

                    <label><strong>Start Time:</strong></label>
                    <input type="time" name="start_time"
                        value="<?php echo htmlspecialchars($match['start_time']); ?>"><br>

                    <label><strong>Location:</strong></label>
                    <select name="location">
                        <!-- Johor -->
                        <optgroup label="Johor">
                            <option value="Johor Bahru" <?php echo $match['location'] === 'Johor Bahru' ? 'selected' : ''; ?>>
                                Johor Bahru</option>
                            <option value="Skudai" <?php echo $match['location'] === 'Skudai' ? 'selected' : ''; ?>>Skudai
                            </option>
                            <option value="Kulai" <?php echo $match['location'] === 'Kulai' ? 'selected' : ''; ?>>Kulai
                            </option>
                            <option value="Muar" <?php echo $match['location'] === 'Muar' ? 'selected' : ''; ?>>Muar
                            </option>
                            <option value="Batu Pahat" <?php echo $match['location'] === 'Batu Pahat' ? 'selected' : ''; ?>>
                                Batu
                                Pahat</option>
                            <option value="Kota Tinggi" <?php echo $match['location'] === 'Kota Tinggi' ? 'selected' : ''; ?>>
                                Kota
                                Tinggi</option>
                            <option value="Pontian" <?php echo $match['location'] === 'Pontian' ? 'selected' : ''; ?>>
                                Pontian
                            </option>
                        </optgroup>
                        <!-- Kedah -->
                        <optgroup label="Kedah">
                            <option value="Alor Setar" <?php echo $match['location'] === 'Alor Setar' ? 'selected' : ''; ?>>
                                Alor
                                Setar</option>
                            <option value="Sungai Petani" <?php echo $match['location'] === 'Sungai Petani' ? 'selected' : ''; ?>>
                                Sungai Petani</option>
                            <option value="Kulim" <?php echo $match['location'] === 'Kulim' ? 'selected' : ''; ?>>Kulim
                            </option>
                            <option value="Langkawi" <?php echo $match['location'] === 'Langkawi' ? 'selected' : ''; ?>>
                                Langkawi
                            </option>
                        </optgroup>
                        <!-- Kelantan -->
                        <optgroup label="Kelantan">
                            <option value="Kota Bharu" <?php echo $match['location'] === 'Kota Bharu' ? 'selected' : ''; ?>>
                                Kota
                                Bharu</option>
                            <option value="Tanah Merah" <?php echo $match['location'] === 'Tanah Merah' ? 'selected' : ''; ?>>
                                Tanah Merah</option>
                            <option value="Gua Musang" <?php echo $match['location'] === 'Gua Musang' ? 'selected' : ''; ?>>
                                Gua
                                Musang</option>
                        </optgroup>
                        <!-- Malacca -->
                        <optgroup label="Malacca">
                            <option value="Malacca City" <?php echo $match['location'] === 'Malacca City' ? 'selected' : ''; ?>>
                                Malacca City</option>
                            <option value="Ayer Keroh" <?php echo $match['location'] === 'Ayer Keroh' ? 'selected' : ''; ?>>
                                Ayer
                                Keroh</option>
                            <option value="Jasin" <?php echo $match['location'] === 'Jasin' ? 'selected' : ''; ?>>Jasin
                            </option>
                        </optgroup>
                        <!-- Negeri Sembilan -->
                        <optgroup label="Negeri Sembilan">
                            <option value="Seremban" <?php echo $match['location'] === 'Seremban' ? 'selected' : ''; ?>>
                                Seremban
                            </option>
                            <option value="Port Dickson" <?php echo $match['location'] === 'Port Dickson' ? 'selected' : ''; ?>>
                                Port Dickson</option>
                            <option value="Nilai" <?php echo $match['location'] === 'Nilai' ? 'selected' : ''; ?>>Nilai
                            </option>
                        </optgroup>
                        <!-- Pahang -->
                        <optgroup label="Pahang">
                            <option value="Kuantan" <?php echo $match['location'] === 'Kuantan' ? 'selected' : ''; ?>>
                                Kuantan
                            </option>
                            <option value="Temerloh" <?php echo $match['location'] === 'Temerloh' ? 'selected' : ''; ?>>
                                Temerloh
                            </option>
                            <option value="Bentong" <?php echo $match['location'] === 'Bentong' ? 'selected' : ''; ?>>
                                Bentong
                            </option>
                            <option value="Cameron Highlands" <?php echo $match['location'] === 'Cameron Highlands' ? 'selected' : ''; ?>>Cameron Highlands</option>
                        </optgroup>
                        <!-- Penang -->
                        <optgroup label="Penang">
                            <option value="George Town" <?php echo $match['location'] === 'George Town' ? 'selected' : ''; ?>>
                                George Town</option>
                            <option value="Bayan Lepas" <?php echo $match['location'] === 'Bayan Lepas' ? 'selected' : ''; ?>>
                                Bayan Lepas</option>
                            <option value="Butterworth" <?php echo $match['location'] === 'Butterworth' ? 'selected' : ''; ?>>
                                Butterworth</option>
                        </optgroup>
                        <!-- Perak -->
                        <optgroup label="Perak">
                            <option value="Ipoh" <?php echo $match['location'] === 'Ipoh' ? 'selected' : ''; ?>>Ipoh
                            </option>
                            <option value="Taiping" <?php echo $match['location'] === 'Taiping' ? 'selected' : ''; ?>>
                                Taiping
                            </option>
                            <option value="Lumut" <?php echo $match['location'] === 'Lumut' ? 'selected' : ''; ?>>Lumut
                            </option>
                        </optgroup>
                        <!-- Perlis -->
                        <optgroup label="Perlis">
                            <option value="Kangar" <?php echo $match['location'] === 'Kangar' ? 'selected' : ''; ?>>Kangar
                            </option>
                            <option value="Arau" <?php echo $match['location'] === 'Arau' ? 'selected' : ''; ?>>Arau
                            </option>
                        </optgroup>
                        <!-- Sabah -->
                        <optgroup label="Sabah">
                            <option value="Kota Kinabalu" <?php echo $match['location'] === 'Kota Kinabalu' ? 'selected' : ''; ?>>
                                Kota Kinabalu</option>
                            <option value="Sandakan" <?php echo $match['location'] === 'Sandakan' ? 'selected' : ''; ?>>
                                Sandakan
                            </option>
                            <option value="Tawau" <?php echo $match['location'] === 'Tawau' ? 'selected' : ''; ?>>Tawau
                            </option>
                        </optgroup>
                        <!-- Sarawak -->
                        <optgroup label="Sarawak">
                            <option value="Kuching" <?php echo $match['location'] === 'Kuching' ? 'selected' : ''; ?>>
                                Kuching
                            </option>
                            <option value="Miri" <?php echo $match['location'] === 'Miri' ? 'selected' : ''; ?>>Miri
                            </option>
                            <option value="Sibu" <?php echo $match['location'] === 'Sibu' ? 'selected' : ''; ?>>Sibu
                            </option>
                        </optgroup>
                        <!-- Selangor -->
                        <optgroup label="Selangor">
                            <option value="Shah Alam" <?php echo $match['location'] === 'Shah Alam' ? 'selected' : ''; ?>>
                                Shah
                                Alam</option>
                            <option value="Petaling Jaya" <?php echo $match['location'] === 'Petaling Jaya' ? 'selected' : ''; ?>>
                                Petaling Jaya</option>
                            <option value="Subang Jaya" <?php echo $match['location'] === 'Subang Jaya' ? 'selected' : ''; ?>>
                                Subang Jaya</option>
                        </optgroup>
                        <!-- Terengganu -->
                        <optgroup label="Terengganu">
                            <option value="Kuala Terengganu" <?php echo $match['location'] === 'Kuala Terengganu' ? 'selected' : ''; ?>>Kuala Terengganu</option>
                            <option value="Kemaman" <?php echo $match['location'] === 'Kemaman' ? 'selected' : ''; ?>>
                                Kemaman
                            </option>
                            <option value="Dungun" <?php echo $match['location'] === 'Dungun' ? 'selected' : ''; ?>>Dungun
                            </option>
                        </optgroup>
                        <!-- Federal Territories -->
                        <optgroup label="Federal Territories">
                            <option value="Kuala Lumpur" <?php echo $match['location'] === 'Kuala Lumpur' ? 'selected' : ''; ?>>
                                Kuala Lumpur</option>
                            <option value="Putrajaya" <?php echo $match['location'] === 'Putrajaya' ? 'selected' : ''; ?>>
                                Putrajaya</option>
                            <option value="Labuan" <?php echo $match['location'] === 'Labuan' ? 'selected' : ''; ?>>Labuan
                            </option>
                        </optgroup>
                    </select><br>

                    <label><strong>Current Players:</strong></label>
                    <p class="read">
                        <?php echo !empty($match['current_players']) ? $match['current_players'] : 'N/A'; ?></p><br>

                    <label><strong>Description:</strong></label>
                    <textarea name="description"
                        rows="4"><?php echo htmlspecialchars($match['description']); ?></textarea><br>
                </div>
            </div>
            <div class="form-actions">
                <a href="delete_match.php?id=<?php echo $_POST['match_id']; ?>" class="btn btn-danger"
                    onclick="return confirm('Are you sure you want to delete this match?')">Delete</a>
                <button type="submit" name="update" class="btn btn-primary">Update</button>
            </div>
        </form>

        <h2>Participants</h2>
        <?php if ($resultParticipants->num_rows > 0): ?>
            <div class="participants">
                <?php while ($participant = $resultParticipants->fetch_assoc()): ?>
                    <?php
                    $image = !empty($participant['file']) ? 'uploads/' . htmlspecialchars($participant['file']) : 'IMAGE/default.png';
                    $participantId = htmlspecialchars($participant['id']); // Assuming `id` is the unique identifier for the participant
                    ?>
                    <div class="participant">
                        <img src="<?php echo $image; ?>" alt="User Image" class="participant-image">
                        <div class="participant-info">
                            <p><strong>Name:</strong>
                                <?php echo htmlspecialchars($participant['first_name'] . ' ' . $participant['last_name']); ?>
                            </p>
                            <p><strong>Join Date:</strong>
                                <?php
                                $joinDate = new DateTime($participant['join_date']);
                                echo $joinDate->format('d M Y');
                                ?>
                            </p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No participants have joined this match yet.</p>
        <?php endif; ?>

    </div>

    <script>
        document.getElementById('max_players').addEventListener('input', function () {
            var maxPlayers = parseInt(this.value);
            var currentPlayers = document.getElementById('current_players');

            // Set the max value of current_players to max_players value
            currentPlayers.setAttribute('max', maxPlayers);

            // If current_players value is greater than max_players, reset it to max
            if (parseInt(currentPlayers.value) > maxPlayers) {
                currentPlayers.value = maxPlayers;
            }
        });

        document.getElementById('current_players').addEventListener('input', function () {
            if (parseInt(this.value) < 0) {
                this.value = 0;
            }
        });
    </script>
    <script src="view_profile.js"></script>
</body>

</html>