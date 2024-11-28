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

// Fetch user and profile data
$user_id = $_SESSION['ID'];

$query = "
    SELECT 
        u.first_name, 
        u.last_name, 
        u.gender, 
        u.email, 
        u.password,
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
    <title>Profile</title>
    <link rel="stylesheet" href="view_profile.css">
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
            <li class="logout"><a href="index.php">Log out<img src="IMAGE/LOGOUT.png" alt="Logout"></a></li>
        </ul>
    </nav>

    <div class="profile-content">
        <h1 class="profile-title">Profile</h1>
        <p class="profile-description">
            Let people know more about you! Share your passions, interests, and achievements.
            Whether it's your love for sports, your favorite hobbies, or your proudest moments,
            let your profile tell your unique story. Join our community and start making meaningful
            connections today!
        </p>
        <div class="profile-container">
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

            <div class="profile-right">
                <!-- Profile Form -->
                <form id="profileForm" action="update_profile.php" method="post">
                    <div class="group">
                        <label for="name">First Name:</label>
                        <input type="text" id="fname" name="fname"
                            value="<?php echo htmlspecialchars($rows['first_name']); ?>" placeholder="Enter your name">
                    </div>

                    <div class="group">
                        <label for="name">Last Name:</label>
                        <input type="text" id="lname" name="lname"
                            value="<?php echo htmlspecialchars($rows['last_name']); ?>" placeholder="Enter your name">
                    </div>

                    <div class="group">
                        <label for="gender">Gender:</label>
                        <input type="text" id="gender" name="gender"
                            value="<?php echo htmlspecialchars($rows['gender']); ?>" placeholder="Enter your gender">
                    </div>

                    <div class="group">
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email"
                            value="<?php echo htmlspecialchars($rows['email']); ?>" placeholder="Enter your email">
                    </div>

                    <div class="group">
                        <label for="password">Password:</label>
                        <input type="text" id="password" name="password"
                            value="<?php echo htmlspecialchars($rows['password']); ?>" placeholder="Enter your email">
                    </div>

                    <div class="group">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" name="phone"
                            value="<?php echo htmlspecialchars($rows['phone']); ?>" placeholder="Enter your email">
                    </div>

                    <div class="group">
                        <label for="status">Status:</label>
                        <input type="text" id="status" name="status"
                            value="<?php echo htmlspecialchars($rows['status']); ?>" placeholder="Enter your status">
                    </div>
                    <div class="group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description"
                            placeholder="Describe yourself..."><?php echo htmlspecialchars($rows['description']); ?></textarea>
                    </div>
                    <div class="group">
                        <label for="location">Location:</label>
                        <input type="text" id="location" name="location"
                            value="<?php echo htmlspecialchars($rows['location']); ?>"
                            placeholder="Enter your location">
                    </div>
                    <div class="group">
                        <label for="interests">Interests:</label>
                        <textarea id="interests" name="interests"
                            placeholder="List your interests..."><?php echo htmlspecialchars($rows['interests']); ?></textarea>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="button">
                            <img src="IMAGE/button_2.png" alt="Submit Button">
                        </button>
                    </div>


                </form>
            </div>
        </div>
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
        <p>At JomTeam, we respect your privacy. This policy outlines how we handle your personal data when you use our platform.</p>

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
            <li>Data may be shared with third-party providers (e.g., payment processors) necessary to deliver our services.</li>
        </ul>

        <h3>5. Security</h3>
        <p>We use advanced encryption and security measures to protect your data. However, no system is completely secure.</p>

        <h3>6. Your Rights</h3>
        <ul>
            <li>You can access, modify, or delete your personal information by contacting support.</li>
            <li>You can opt out of promotional communications at any time.</li>
        </ul>

        <h3>7. Cookies</h3>
        <p>Our platform uses cookies to enhance your browsing experience. You can manage cookie preferences in your browser settings.</p>

        <h3>8. Changes to Privacy Policy</h3>
        <p>We may update this Privacy Policy periodically. Changes will be posted on this page with the revised date.</p>
    </div>
</div>
<script src="footer.js"></script>

</html>
