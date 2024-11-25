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
        u.password,
        u.phone, 
        p.status, 
        p.description, 
        p.location, 
        p.interests, 
        p.preferred_game_types, 
        p.skill_level, 
        p.availability, 
        u.birth_date
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
    <title>Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="footer.css">
</head>

<body>
    <?php if ($_SESSION["LEVEL"] != 1) { ?>
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

        <div class="banner">
            <h1>Find Your Best Sport Buddies</h1>
            <p>Connecting You with Passionate Teammates, Inspiring Workout Partners, and Lifelong Friends! <br>Join Today
                and Kickstart Your Next Adventure on the Field! </p>

        </div>

        <div class="banner-image">
            <img src="IMAGE/swimming.png" alt="Sports">
        </div>

        <div class="find_match">Find Match
            <div class="find_match_content">Finding the perfect match in sports can be a game-changer. <br>It's all about
                connecting with individuals <br>who share your passion for the game and have the same dedication and drive.
            </div>

            <div class="red_button">
                <a href="find_match.php">
                    <img src="IMAGE/red_button.png">
                </a>
            </div>

        </div>
        <!-- Find Match and Create Match Section
    <section class="match-buttons">
         Create Match Button 
        <a href="find_match.php">
            <button class="find-match-btn">Find Match</button>
        </a>

        Find Match Button 
        <a href="create_match.php">
            <button class="create-match-btn">Create Match</button>
        </a>
    </section>

    Description Text (below buttons)
    <div class="match-description">
        <p>Finding the perfect match in sports can be a game-changer.</p>
    </div>
    -->

        <!-- create_your_own_match-->
        <div class="create_your_own_match">
            <div class="create_your_own_match_title">Create your own match</div>
            <div class="create-match-content">
                Not finding the right match? No problem! <br>Create your own game-changing partnership and turn any
                challenge
                into a victory. <br>Join our network and start building your dream team today!
            </div>
            <div class="picture_box">
                <a href="create_match.php">
                    <img src="IMAGE/button.png" class="picture">
                </a>
            </div>
        </div>

        <!-- view profile-->
        <div class="view_profile">Profile
            <div class="view_profile_content"><br>Let people know more about
                you!<br>Share your passions, interests, and achievements.<br>Whether it's your love for sports, your
                favorite hobbies,or your proudest
                moments,<br> let your profile tell your unique story. <br>Join our community and start making
                meaningful connections today!<br>
            </div>

            <div>
                <!-- Profile detail -->
                <div class="uploaded-images">
                    <?php
                    $res = mysqli_query($conn, "SELECT file FROM images WHERE user_id = " . $_SESSION["ID"]);
                    while ($row = mysqli_fetch_assoc($res)) {
                        if (empty($row['file'])) {
                            echo '<div class="image-container">
                <img src="IMAGE/default.png" alt="Default Image" class="uploaded-image"/>
              </div>';
                        } else {
                            echo '<div class="image-container">
                <img src="uploads/' . $row['file'] . '" alt="Uploaded Image" class="uploaded-image"/>
              </div>';
                        }
                    }
                    ?>
                </div>
                <div class="group">

                    <span><?php echo '<span class="main_page_profile_content_description">' . htmlspecialchars($rows['description']); ?></span>
                </div>

                <label class="main_page_profile_content">Name:</label>
                <?php echo '<span class="main_page_profile_content">' . htmlspecialchars($rows['first_name']) . htmlspecialchars($rows['last_name']); ?>
            </div>
            <div class="group">
                <label for="gender" class="main_page_profile_content ">Gender:</label>
                <?php echo '<span class="main_page_profile_content">' . htmlspecialchars($rows['gender']); ?>
            </div>
            <div class="group">
                <label for="age" class="main_page_profile_content ">Age:</label>
                <?php

                if (isset($rows['birth_date']) && !empty($rows['birth_date'])) {
                    $dob = new DateTime($rows['birth_date']);
                    $today = new DateTime();
                    $age = $dob->diff($today)->y;
                    echo '<span class="main_page_profile_content">' . $age . '</span>';
                } else {
                    echo '<span class="main_page_profile_content">N/A</span>';
                }
                ?>
            </div>
            <div class="group">
                <label for="phone" class="main_page_profile_content ">Phone Number:</label>
                <?php echo '<span class="main_page_profile_content">' . htmlspecialchars($rows['phone']); ?>
            </div>

            <div class="group">
                <label for="status" class="main_page_profile_content ">Status:</label>
                <?php echo '<span class="main_page_profile_content">' . htmlspecialchars($rows['status']); ?>
            </div>


            <div class="edit_your_profile_button">
                <a href="view_profile.php">
                    <img src="IMAGE/edit_your_profile.png">
                </a>
            </div>
        </div>
    <?php } else { ?>
        <nav class="navbar">
            <a href="#" class="logo">
                <img src="IMAGE/jomteam.png" alt="Logo">
            </a>

            <ul class="menu leftmenu">
                <li><a href="view_user.php">Manage User</a></li>
                <li><a href="view_ads.php">Manage Ads</a></li>
                <li><a href="#event">Manage Event</a></li>
                <li><a href="#feedback">Feedback & Report</a></li>
            </ul>

            <ul class="menu rightmenu">
                <li class="notification"><a href="#notification"><img src="IMAGE/NOTIFICATION.png" alt="Notification"></a>
                </li>
                <li class="logout"><a href="login.php">Log out<img src="IMAGE/LOGOUT.png" alt="Logout"></a></li>
            </ul>
        </nav>
    <?php } ?>

    <a href="feedback.php" class="feedback-btn">
        <img src="image/FEEDBACK.png" alt="Feedback" class="feedback-img">
    </a>
</body>

<footer>
    <div class="footer-container">
        <div class="footer-links">
<<<<<<< HEAD
            <a href="#" onclick="openModal('terms')">Terms of Service</a> |
=======
            <a href="#" onclick="openModal('terms')">Terms of Service</a> | 
>>>>>>> c35eebe92183841dd1db7b9a8935d6b888470280
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
<<<<<<< HEAD
            Harassment, hate speech, or inappropriate content is strictly prohibited.</p>
        <h3>4. Match Creation and Participation</h3>
        <p>Users creating matches must ensure the information provided (e.g., location, time) is accurate.
            Users participating in matches must adhere to the agreed-upon rules and schedules.</p>
        <h3>5. Payment and Premium Services</h3>
        <p>Premium features may be offered with a subscription. Fees are non-refundable unless specified otherwise.</p>

=======
        Harassment, hate speech, or inappropriate content is strictly prohibited.</p>
        <h3>4. Match Creation and Participation</h3>
        <p>Users creating matches must ensure the information provided (e.g., location, time) is accurate.
        Users participating in matches must adhere to the agreed-upon rules and schedules.</p>
        <h3>5. Payment and Premium Services</h3>
        <p>Premium features may be offered with a subscription. Fees are non-refundable unless specified otherwise.</p>
   
>>>>>>> c35eebe92183841dd1db7b9a8935d6b888470280
    </div>
</div>

<!-- Modal for Privacy Policy -->
<div id="privacyModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('privacy')">&times;</span>
        <h2>Privacy Policy</h2>
<<<<<<< HEAD
        <p>At JomTeam, we respect your privacy. This policy outlines how we handle your personal data when you use our
            platform.</p>
=======
        <p>At JomTeam, we respect your privacy. This policy outlines how we handle your personal data when you use our platform.</p>
>>>>>>> c35eebe92183841dd1db7b9a8935d6b888470280

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
<<<<<<< HEAD
            <li>Data may be shared with third-party providers (e.g., payment processors) necessary to deliver our
                services.</li>
        </ul>

        <h3>5. Security</h3>
        <p>We use advanced encryption and security measures to protect your data. However, no system is completely
            secure.</p>
=======
            <li>Data may be shared with third-party providers (e.g., payment processors) necessary to deliver our services.</li>
        </ul>

        <h3>5. Security</h3>
        <p>We use advanced encryption and security measures to protect your data. However, no system is completely secure.</p>
>>>>>>> c35eebe92183841dd1db7b9a8935d6b888470280

        <h3>6. Your Rights</h3>
        <ul>
            <li>You can access, modify, or delete your personal information by contacting support.</li>
            <li>You can opt out of promotional communications at any time.</li>
        </ul>

        <h3>7. Cookies</h3>
<<<<<<< HEAD
        <p>Our platform uses cookies to enhance your browsing experience. You can manage cookie preferences in your
            browser settings.</p>

        <h3>8. Changes to Privacy Policy</h3>
        <p>We may update this Privacy Policy periodically. Changes will be posted on this page with the revised date.
        </p>
=======
        <p>Our platform uses cookies to enhance your browsing experience. You can manage cookie preferences in your browser settings.</p>

        <h3>8. Changes to Privacy Policy</h3>
        <p>We may update this Privacy Policy periodically. Changes will be posted on this page with the revised date.</p>
>>>>>>> c35eebe92183841dd1db7b9a8935d6b888470280
    </div>
</div>

<script src="footer.js"></script>

</html>