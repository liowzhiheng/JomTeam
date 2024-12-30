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

require("config.php");

$user_id = $_SESSION['ID'];

$query = "
    SELECT 
        u.first_name, 
        u.last_name, 
        u.gender, 
        u.email, 
        u.password,
        u.phone, 
        u.premium,
        p.status, 
        p.description, 
        p.location, 
        p.interests, 
        p.preferred_game_types, 
        p.skill_level, 
        p.availability,
        p.frame
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

$frame = $rows['frame'];
$sql = "SELECT * FROM frame WHERE id = '$frame'";
$result2 = mysqli_query($conn, $sql);
$rows2 = mysqli_fetch_assoc($result2);
if (!$result2 || mysqli_num_rows($result2) == 0) {
    echo "No profile data found.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <link rel="stylesheet" href="view_profile.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
</head>

<body>
    <?php
    include('navbar.php');
    include('ads.php');

    if (isset($_GET['status'])) {
        $status = $_GET['status'];

        if ($status === 'success') {
            echo '<p id="message" class="message success">Profile update successfully!</p>';
        } elseif ($status === 'fail') {
            echo '<p id="message" class="message fail">Sorry, something went wrong. Please try again.</p>';
        }
    }


    $location = isset($rows['location']) && !empty($rows['location']) ? htmlspecialchars($rows['location']) : '';
    // Check if location is empty and set default from database
    if (empty($location)) {

        $location = "Default Location from DB";
    }

    $status = isset($rows['status']) && !empty($rows['status']) ? htmlspecialchars($rows['status']) : '';
    // If the status is empty, set a default value or fetch from the database
    if (empty($status)) {
        $status = "Default Status from DB";
    }
    ?>

    <!-- Add button to see account security -->
    <div class="button">
        <a href="account_security.php" class="account-security-button">
            <button type="button" class="security-btn">
                Account Security
            </button>
        </a>
    </div>

    <div class="profile-content">
        <h1 class="profile-title">Profile</h1>
        <p class="profile-description">
            Let people know more about you! Share your passions, interests, and achievements.
            Whether it's your love for sports, your favorite hobbies, or your proudest moments,
            let your profile tell your unique story. Join our community and start making meaningful
            connections today!
        </p>
        <form action="update_profile.php" method="post" enctype="multipart/form-data">
            <div class="profile-container">

                <!-- Image Section -->
                <div class="profile-left">
                    <div class="uploaded-images">
                        <?php
                        $res = mysqli_query($conn, "SELECT file FROM images WHERE user_id = " . $_SESSION["ID"]);
                        while ($row = mysqli_fetch_assoc($res)) {
                            if (empty($row['file'])) {
                                if ($rows['premium']): ?>
                                    <div class="premium-profile-frame">
                                    <?php endif; ?>
                                    <div class="image-container">
                                        <img id="imagePreview" src="IMAGE/default.png" alt="Default Image"
                                            class="uploaded-image" onclick="document.getElementById('imageInput').click();" />
                                        <div class="overlay-text" onclick="document.getElementById('imageInput').click();">
                                            Upload Image</div>
                                    </div>
                                    <?php if ($rows['premium']): ?>
                                    </div>
                                <?php endif; ?>
                            <?php } else {
                                if ($rows['premium']): ?>
                                    <div class="premium-profile-frame">
                                    <?php endif; ?>
                                    <div class="image-container">
                                        <img id="imagePreview" src="uploads/<?php echo $row['file']; ?>" alt="Uploaded Image"
                                            class="uploaded-image" onclick="document.getElementById('imageInput').click();" />
                                        <div class="overlay-text" onclick="document.getElementById('imageInput').click();">
                                            Change Image</div>
                                    </div>
                                    <?php if ($rows['premium']): ?>
                                    </div>
                                <?php endif; ?>
                                <?php $isPremium = $rows['premium']; ?>
                                <div class="image-container">
                                    <?php if ($isPremium): ?>
                                        <img src="IMAGE/<?php echo $rows2['file'] ?>" class="premium-frame"/>
                                        <div class="frame-selector">
                                            <button name="frame" value="1">Frame 1</button>
                                            <button name="frame" value="2">Frame 2</button>
                                            <button name="frame" value="3">Frame 3</button>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            <?php }


                        }
                        ?>

                        <input type="file" name="image" id="imageInput" style="display: none;"
                            onchange="previewImage()" />
                    </div>
                </div>

                <!-- Info Section -->
                <div>
                    <input type="hidden" id="frame" name="frame" style="position:absolute;" value="<?php echo htmlspecialchars($rows['frame']); ?>"/>
                </div>
                <div class="profile-right">
                    <div class="group">
                        <label for="fname">First Name:</label>
                        <input type="text" id="fname" name="fname"
                            value="<?php echo htmlspecialchars($rows['first_name']); ?>"
                            placeholder="Enter your first name">
                    </div>

                    <div class="group">
                        <label for="lname">Last Name:</label>
                        <input type="text" id="lname" name="lname"
                            value="<?php echo htmlspecialchars($rows['last_name']); ?>"
                            placeholder="Enter your last name">
                    </div>

                    <div class="group">
                        <label for="gender">Gender:</label>
                        <input type="text" name="gender" value="<?php echo htmlspecialchars($rows['gender']); ?>"
                            readonly>
                    </div>

                    <div class="group">
                        <label for="email">Email:</label>
                        <input type="text" name="email" value="<?php echo htmlspecialchars($rows['email']); ?>"
                            readonly>
                    </div>

                    <div class="group">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" name="phone"
                            value="<?php echo htmlspecialchars($rows['phone']); ?>"
                            placeholder="Enter your phone number">
                    </div>

                    <div class="group">
                        <label for="location">Location:</label>
                        <div class="location_selection">
                            <select name="location">
                                <!-- Show current location as the default selected option -->
                                <option value="" <?php echo empty($location) ? 'selected' : ''; ?>>Please select your
                                    location</option>

                                <!-- Johor -->
                                <optgroup label="Johor">
                                    <option value="Johor Bahru" <?php echo $location === 'Johor Bahru' ? 'selected' : ''; ?>>Johor Bahru</option>
                                    <option value="Skudai" <?php echo $location === 'Skudai' ? 'selected' : ''; ?>>Skudai
                                    </option>
                                    <option value="Kulai" <?php echo $location === 'Kulai' ? 'selected' : ''; ?>>Kulai
                                    </option>
                                    <option value="Muar" <?php echo $location === 'Muar' ? 'selected' : ''; ?>>Muar
                                    </option>
                                    <option value="Batu Pahat" <?php echo $location === 'Batu Pahat' ? 'selected' : ''; ?>>Batu Pahat</option>
                                    <option value="Kota Tinggi" <?php echo $location === 'Kota Tinggi' ? 'selected' : ''; ?>>Kota Tinggi</option>
                                    <option value="Pontian" <?php echo $location === 'Pontian' ? 'selected' : ''; ?>>
                                        Pontian</option>
                                </optgroup>

                                <!-- Kedah -->
                                <optgroup label="Kedah">
                                    <option value="Alor Setar" <?php echo $location === 'Alor Setar' ? 'selected' : ''; ?>>Alor Setar</option>
                                    <option value="Sungai Petani" <?php echo $location === 'Sungai Petani' ? 'selected' : ''; ?>>Sungai Petani</option>
                                    <option value="Kulim" <?php echo $location === 'Kulim' ? 'selected' : ''; ?>>Kulim
                                    </option>
                                    <option value="Langkawi" <?php echo $location === 'Langkawi' ? 'selected' : ''; ?>>
                                        Langkawi</option>
                                </optgroup>

                                <!-- Kelantan -->
                                <optgroup label="Kelantan">
                                    <option value="Kota Bharu" <?php echo $location === 'Kota Bharu' ? 'selected' : ''; ?>>Kota Bharu</option>
                                    <option value="Tanah Merah" <?php echo $location === 'Tanah Merah' ? 'selected' : ''; ?>>Tanah Merah</option>
                                    <option value="Gua Musang" <?php echo $location === 'Gua Musang' ? 'selected' : ''; ?>>Gua Musang</option>
                                </optgroup>

                                <!-- Malacca -->
                                <optgroup label="Malacca">
                                    <option value="Malacca City" <?php echo $location === 'Malacca City' ? 'selected' : ''; ?>>Malacca City</option>
                                    <option value="Ayer Keroh" <?php echo $location === 'Ayer Keroh' ? 'selected' : ''; ?>>Ayer Keroh</option>
                                    <option value="Jasin" <?php echo $location === 'Jasin' ? 'selected' : ''; ?>>Jasin
                                    </option>
                                </optgroup>

                                <!-- Negeri Sembilan -->
                                <optgroup label="Negeri Sembilan">
                                    <option value="Seremban" <?php echo $location === 'Seremban' ? 'selected' : ''; ?>>
                                        Seremban</option>
                                    <option value="Port Dickson" <?php echo $location === 'Port Dickson' ? 'selected' : ''; ?>>Port Dickson</option>
                                    <option value="Nilai" <?php echo $location === 'Nilai' ? 'selected' : ''; ?>>Nilai
                                    </option>
                                </optgroup>

                                <!-- Pahang -->
                                <optgroup label="Pahang">
                                    <option value="Kuantan" <?php echo $location === 'Kuantan' ? 'selected' : ''; ?>>
                                        Kuantan</option>
                                    <option value="Temerloh" <?php echo $location === 'Temerloh' ? 'selected' : ''; ?>>
                                        Temerloh</option>
                                    <option value="Bentong" <?php echo $location === 'Bentong' ? 'selected' : ''; ?>>
                                        Bentong</option>
                                    <option value="Cameron Highlands" <?php echo $location === 'Cameron Highlands' ? 'selected' : ''; ?>>Cameron Highlands</option>
                                </optgroup>

                                <!-- Penang -->
                                <optgroup label="Penang">
                                    <option value="George Town" <?php echo $location === 'George Town' ? 'selected' : ''; ?>>George Town</option>
                                    <option value="Bayan Lepas" <?php echo $location === 'Bayan Lepas' ? 'selected' : ''; ?>>Bayan Lepas</option>
                                    <option value="Butterworth" <?php echo $location === 'Butterworth' ? 'selected' : ''; ?>>Butterworth</option>
                                </optgroup>

                                <!-- Perak -->
                                <optgroup label="Perak">
                                    <option value="Ipoh" <?php echo $location === 'Ipoh' ? 'selected' : ''; ?>>Ipoh
                                    </option>
                                    <option value="Taiping" <?php echo $location === 'Taiping' ? 'selected' : ''; ?>>
                                        Taiping</option>
                                    <option value="Lumut" <?php echo $location === 'Lumut' ? 'selected' : ''; ?>>Lumut
                                    </option>
                                </optgroup>

                                <!-- Perlis -->
                                <optgroup label="Perlis">
                                    <option value="Kangar" <?php echo $location === 'Kangar' ? 'selected' : ''; ?>>Kangar
                                    </option>
                                    <option value="Arau" <?php echo $location === 'Arau' ? 'selected' : ''; ?>>Arau
                                    </option>
                                </optgroup>

                                <!-- Sabah -->
                                <optgroup label="Sabah">
                                    <option value="Kota Kinabalu" <?php echo $location === 'Kota Kinabalu' ? 'selected' : ''; ?>>Kota Kinabalu</option>
                                    <option value="Sandakan" <?php echo $location === 'Sandakan' ? 'selected' : ''; ?>>
                                        Sandakan</option>
                                    <option value="Tawau" <?php echo $location === 'Tawau' ? 'selected' : ''; ?>>Tawau
                                    </option>
                                </optgroup>

                                <!-- Sarawak -->
                                <optgroup label="Sarawak">
                                    <option value="Kuching" <?php echo $location === 'Kuching' ? 'selected' : ''; ?>>
                                        Kuching</option>
                                    <option value="Miri" <?php echo $location === 'Miri' ? 'selected' : ''; ?>>Miri
                                    </option>
                                    <option value="Sibu" <?php echo $location === 'Sibu' ? 'selected' : ''; ?>>Sibu
                                    </option>
                                </optgroup>

                                <!-- Selangor -->
                                <optgroup label="Selangor">
                                    <option value="Shah Alam" <?php echo $location === 'Shah Alam' ? 'selected' : ''; ?>>
                                        Shah Alam</option>
                                    <option value="Petaling Jaya" <?php echo $location === 'Petaling Jaya' ? 'selected' : ''; ?>>Petaling Jaya</option>
                                    <option value="Subang Jaya" <?php echo $location === 'Subang Jaya' ? 'selected' : ''; ?>>Subang Jaya</option>
                                </optgroup>

                                <!-- Terengganu -->
                                <optgroup label="Terengganu">
                                    <option value="Kuala Terengganu" <?php echo $location === 'Kuala Terengganu' ? 'selected' : ''; ?>>Kuala Terengganu</option>
                                    <option value="Kemaman" <?php echo $location === 'Kemaman' ? 'selected' : ''; ?>>
                                        Kemaman</option>
                                    <option value="Dungun" <?php echo $location === 'Dungun' ? 'selected' : ''; ?>>Dungun
                                    </option>
                                </optgroup>

                                <!-- Federal Territories -->
                                <optgroup label="Federal Territories">
                                    <option value="Kuala Lumpur" <?php echo $location === 'Kuala Lumpur' ? 'selected' : ''; ?>>Kuala Lumpur</option>
                                    <option value="Putrajaya" <?php echo $location === 'Putrajaya' ? 'selected' : ''; ?>>
                                        Putrajaya</option>
                                    <option value="Labuan" <?php echo $location === 'Labuan' ? 'selected' : ''; ?>>Labuan
                                    </option>
                                </optgroup>
                            </select>

                        </div>
                    </div>

                    <div class="group">
                        <label for="status">Status:</label>
                        <div class="status_selection">
                            <select name="status">
                                <!-- Display current status if it's set, otherwise display a placeholder -->
                                <option value="" <?php echo empty($status) ? 'selected' : ''; ?>>Please select your
                                    Status
                                </option>
                                <option value="single" <?php echo $status === 'single' ? 'selected' : ''; ?>>Single
                                </option>
                                <option value="not single" <?php echo $status === 'not single' ? 'selected' : ''; ?>>Not
                                    Single</option>
                            </select>
                        </div>
                    </div>

                    <div class="group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description"
                            placeholder="Tell us about yourself..."><?php echo htmlspecialchars($rows['description']); ?></textarea>
                    </div>



                    <div class="group">
                        <label for="interests">Interests:</label>
                        <textarea id="interests" name="interests"
                            placeholder="List your interests (e.g., traveling, cooking, reading, playing basketball)..."><?php echo htmlspecialchars($rows['interests']); ?></textarea>
                    </div>

                    <div class="button">
                        <p><button type="submit" id="update">
                                <img src="IMAGE/update_button_white.png" alt="Update"
                                    style="width: 130px; height: auto;">
                            </button></p>

                    </div>
                </div>
            </div>
        </form>
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
        <p>Welcome to JomTeam! By using our platform, you agree to these Terms of Service. Please read them carefully.
        </p>
    </div>
</div>

<!-- Modal for Privacy Policy -->
<div id="privacyModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('privacy')">&times;</span>
        <h2>Privacy Policy</h2>
        <p>At JomTeam, we respect your privacy. This policy outlines how we handle your personal data when you use our
            platform.</p>
    </div>
</div>

<script src="footer.js"></script>

</html>
