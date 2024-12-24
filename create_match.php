<?php
session_start(); // Start the PHP session

// Check if the user is logged in
if ($_SESSION["Login"] != "YES") {
    header("Location: index.php");
    exit();
}

// Check if USER_ID is set
if (!isset($_SESSION["ID"])) {
    echo "User ID is not set in the session.";
    exit();
}
$user_id = $_SESSION["ID"];

require("config.php");

$query = "
    SELECT 
        u.first_name, 
        u.last_name, 
        u.gender, 
        u.phone
    FROM 
        user u 
    WHERE 
        u.id = '$user_id'
";

$result = mysqli_query($conn, $query);
$rows = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Own Match</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="create_match_2.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">

</head>

<body>
    <audio id="popSound" src="sound/pop-sound.mp3" preload="auto"></audio>

    <?php
    include('navbar.php');
    include('ads.php');
    ?>

    <div class="profile-content">
        <h1 class="profile-title">Create your own match</h1>
        <p class="profile-description">
            Not finding the right match? <br>No problem! Create your own game-changing partnership and turn any
            challenge into a victory.
        </p>
    </div>

    <div class="profile-content">
        <!-- start detail -->
        <section class="profile-container">
            <form method="post" action="match_information.php" enctype="multipart/form-data">
                <!-- left -->

                <div class="profile-left">

                    <div class="upload-area" id="uploadArea">
                        <img src="IMAGE/login_done_1.jpg" alt="Upload Icon" class="upload-icon">
                        <p>Click me to upload photo.</p>
                        <p class="file-name" id="fileName">No file chosen</p>
                    </div>
                    <input type="file" name="image" id="image" accept="image/*" required hidden>
                    <div class="image-preview" id="imagePreview" style="display: none;">
                        <img id="previewImg" src="" alt="Preview" />
                    </div>
                </div>

                <!-- right -->
                <div class="profile-right">
                    <div class="group">
                        <label>Full Name</label>
                        <input type="text" name="name"
                            value="<?php echo htmlspecialchars($rows['first_name'] . ' ' . $rows['last_name']); ?>"
                            readonly>
                    </div>

                    <div class="group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($rows['phone']); ?>"
                            readonly>
                    </div>
                    <div class="group">
                        <label>Gender</label>
                        <input type="text" name="gender" value="<?php echo htmlspecialchars($rows['gender']); ?>"
                            readonly>
                    </div>

                    <div class="group">
                        <label class="details">Match Title</label>
                        <input type="text" name="match_title" required>
                    </div>

                    <div class="group">
                        <label class="details">Game Type</label>
                        <select name="game_type" required>
                            <option value="" hidden>Select Game Type</option>
                            <option value="basketball">Basketball</option>
                            <option value="football">Football</option>
                            <option value="badminton">Badminton</option>
                            <option value="volleyball">Volleyball</option>
                            <option value="tennis">Tennis</option>
                            <option value="futsal">Futsal</option>
                            <option value="others">Others</option>
                        </select>
                    </div>

                    <div class="group">
                        <label class="details">Location</label>
                        <select name="location" required>
                            <option value="" hidden>Select Location</option>
                            <!-- Johor -->
                            <optgroup label="Johor">
                                <option value="Johor Bahru">Johor Bahru</option>
                                <option value="Skudai">Skudai</option>
                                <option value="Kulai">Kulai</option>
                                <option value="Muar">Muar</option>
                                <option value="Batu Pahat">Batu Pahat</option>
                                <option value="Kota Tinggi">Kota Tinggi</option>
                                <option value="Pontian">Pontian</option>
                            </optgroup>
                            <!-- Kedah -->
                            <optgroup label="Kedah">
                                <option value="Alor Setar">Alor Setar</option>
                                <option value="Sungai Petani">Sungai Petani</option>
                                <option value="Kulim">Kulim</option>
                                <option value="Langkawi">Langkawi</option>
                            </optgroup>
                            <!-- Kelantan -->
                            <optgroup label="Kelantan">
                                <option value="Kota Bharu">Kota Bharu</option>
                                <option value="Tanah Merah">Tanah Merah</option>
                                <option value="Gua Musang">Gua Musang</option>
                            </optgroup>
                            <!-- Malacca -->
                            <optgroup label="Malacca">
                                <option value="Malacca City">Malacca City</option>
                                <option value="Ayer Keroh">Ayer Keroh</option>
                                <option value="Jasin">Jasin</option>
                            </optgroup>
                            <!-- Negeri Sembilan -->
                            <optgroup label="Negeri Sembilan">
                                <option value="Seremban">Seremban</option>
                                <option value="Port Dickson">Port Dickson</option>
                                <option value="Nilai">Nilai</option>
                            </optgroup>
                            <!-- Pahang -->
                            <optgroup label="Pahang">
                                <option value="Kuantan">Kuantan</option>
                                <option value="Temerloh">Temerloh</option>
                                <option value="Bentong">Bentong</option>
                                <option value="Cameron Highlands">Cameron Highlands</option>
                            </optgroup>
                            <!-- Penang -->
                            <optgroup label="Penang">
                                <option value="George Town">George Town</option>
                                <option value="Bayan Lepas">Bayan Lepas</option>
                                <option value="Butterworth">Butterworth</option>
                            </optgroup>
                            <!-- Perak -->
                            <optgroup label="Perak">
                                <option value="Ipoh">Ipoh</option>
                                <option value="Taiping">Taiping</option>
                                <option value="Lumut">Lumut</option>
                            </optgroup>
                            <!-- Perlis -->
                            <optgroup label="Perlis">
                                <option value="Kangar">Kangar</option>
                                <option value="Arau">Arau</option>
                            </optgroup>
                            <!-- Sabah -->
                            <optgroup label="Sabah">
                                <option value="Kota Kinabalu">Kota Kinabalu</option>
                                <option value="Sandakan">Sandakan</option>
                                <option value="Tawau">Tawau</option>
                            </optgroup>
                            <!-- Sarawak -->
                            <optgroup label="Sarawak">
                                <option value="Kuching">Kuching</option>
                                <option value="Miri">Miri</option>
                                <option value="Sibu">Sibu</option>
                            </optgroup>
                            <!-- Selangor -->
                            <optgroup label="Selangor">
                                <option value="Shah Alam">Shah Alam</option>
                                <option value="Petaling Jaya">Petaling Jaya</option>
                                <option value="Subang Jaya">Subang Jaya</option>
                            </optgroup>
                            <!-- Terengganu -->
                            <optgroup label="Terengganu">
                                <option value="Kuala Terengganu">Kuala Terengganu</option>
                                <option value="Kemaman">Kemaman</option>
                                <option value="Dungun">Dungun</option>
                            </optgroup>
                            <!-- Federal Territories -->
                            <optgroup label="Federal Territories">
                                <option value="Kuala Lumpur">Kuala Lumpur</option>
                                <option value="Putrajaya">Putrajaya</option>
                                <option value="Labuan">Labuan</option>
                            </optgroup>
                        </select>

                    </div>
                    <div class="group">
                        <label class="details">Start Date</label>
                        <input type="date" name="startDate" id="startDate" required oninput="validDate()">
                    </div>
                    <div class="group">
                        <label class="details">Start Time</label>
                        <input type="time" name="startTime" id="startTime" required oninput="validTime()">
                    </div>
                    <div class="group">
                        <label class="details">Duration of Game Match</label>
                        <input type="text" name="duration" required>
                    </div>

                    <div class="group">
                        <label class="details">Skill Level</label>
                        <select name="skill_level" required>
                            <option value="" hidden>Select Level</option>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                            <option value="Professional">Professional</option>
                        </select>
                    </div>
                    <div class="group">
                        <label class="details">Maximum Players</label>
                        <input type="number" name="max_players" id="max_players" value="0" min="1" required
                            oninput="validatePlayerInput(this)" onchange="updatePlayers()">
                    </div>


                    <div class="group">
                        <label class="details">Current Players</label>
                        <input type="number" name="current_players" id="current_players" min="0" value="0" required
                            oninput="validatePlayerInput(this)" onchange="updatePlayers()">
                    </div>


                    <div class="group">
                        <label class="details">Description</label>
                        <textarea name="description" rows="4" required></textarea>
                    </div>


                    <div>
                        <button type="submit" class="button" name="create" value="Submit">
                            <img src="IMAGE/button_3.png" alt="Submit Button">
                        </button>
                    </div>

                </div>
            </form>
        </section>


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
            // Display the creator as the Host
            echo "<li id='host'>Host: " . htmlspecialchars($rows['first_name'] . ' ' . $rows['last_name']) . "</li>";

            // Ensure max_players and current_players are properly set
            $max_players = isset($rows['max_players']) ? $rows['max_players'] : 0;
            $current_players = isset($rows['current_players']) ? $rows['current_players'] : 0;

            // Generate players list
            for ($i = 0; $i < $max_players; $i++) {
                if ($i < $current_players) {
                    echo "<li id='player" . ($i + 1) . "'>Player " . ($i + 1) . ": X</li>";
                } else {
                    echo "<li id='player" . ($i + 1) . "'>Player " . ($i + 1) . ": ?</li>";
                }
            }
            ?>
        </ul>
    </div>


    <script src="view_profile.js"></script>
    <?php mysqli_close($conn); ?>
</body>

<!-- footer-->
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

    function validatePlayerInput() {
        const maxPlayers = parseInt(document.getElementById('max_players').value) || 0;
        const currentPlayers = parseInt(document.getElementById('current_players').value) || 0;

        if (currentPlayers > maxPlayers) {
            alert("Current players cannot exceed maximum players!");
            document.getElementById('current_players').value = 1;
        }
    }

    function validDate() {
        const inputDate = new Date(document.getElementById('startDate').value);
        const today = new Date();

        inputDate.setHours(0, 0, 0, 0);
        today.setHours(0, 0, 0, 0);

        if (inputDate.getTime() !== today.getTime()) {
            alert("The date must be today! (Unless you join premium 👻)");
            document.getElementById('startDate').value = ""; // Clear the invalid date
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
            document.getElementById('startTime').value = ""; // Clear the invalid input
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
        // Function to update the player list
        function updatePlayers() {
            const hostName = document.querySelector('input[name="name"]').value; // Get the creator's name
            const maxPlayers = parseInt(document.getElementById('max_players').value) || 0;
            const currentPlayers = parseInt(document.getElementById('current_players').value) || 0;

            // Select the players list container
            const playersList = document.getElementById('playersList');
            playersList.innerHTML = ''; // Clear existing list

            // Add host
            const hostElement = document.createElement('li');
            hostElement.id = 'host';
            hostElement.textContent = `Host: ${hostName}`;
            playersList.appendChild(hostElement);

            // Add players
            for (let i = 0; i < maxPlayers; i++) {
                const playerElement = document.createElement('li');
                playerElement.id = `player${i + 1}`;

                if (i < currentPlayers) {
                    playerElement.textContent = `Player ${i + 1}: X`;
                } else {
                    playerElement.textContent = `Player ${i + 1}: ?`;
                }

                playersList.appendChild(playerElement);
            }
        }

        // Function to validate player input (optional, as in your original script)
        function validatePlayerInput(input) {
            const value = parseInt(input.value) || 0;
            if (value < 0) input.value = 0;
        }

        // Add event listeners for input changes
        document.getElementById('max_players').addEventListener('change', updatePlayers);
        document.getElementById('current_players').addEventListener('change', updatePlayers);

    }

    // Attach event listeners for changes in max_players and current_players inputs
    document.getElementById('max_players').addEventListener('input', updatePlayersList);
    document.getElementById('current_players').addEventListener('input', updatePlayersList);

    // Initialize the player list on page load
    document.addEventListener('DOMContentLoaded', updatePlayersList);

</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const imageInput = document.getElementById("image");
        const uploadArea = document.getElementById("uploadArea");
        const imagePreview = document.getElementById("imagePreview");
        const previewImg = document.getElementById("previewImg");

        imageInput.addEventListener("change", () => {
            const file = imageInput.files[0]; // Get the uploaded file
            if (file) {
                const reader = new FileReader();

                reader.onload = (e) => {
                    previewImg.src = e.target.result; // Set the preview image source
                    imagePreview.style.display = "flex"; // Show the preview
                    uploadArea.style.display = "none"; // Hide the upload prompt
                };

                reader.readAsDataURL(file); // Convert the file to a data URL
            }
        });

        // Allow re-uploading the same file by resetting the input
        uploadArea.addEventListener("click", () => {
            imageInput.click(); // Trigger the file input
        });

        imageInput.addEventListener("click", () => {
            imageInput.value = null; // Reset file input to allow re-upload
        });
    });




</script>

</html>
