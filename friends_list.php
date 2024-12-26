<?php
require("config.php");
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['ID'];

// Fetch friends
$friendsQuery = "
    SELECT 
        u.id, 
        u.first_name, 
        u.last_name, 
        IFNULL(i.file, 'default.png') AS profile_picture
    FROM friends f
    JOIN user u ON (f.user_id = u.id OR f.friend_id = u.id)
    LEFT JOIN images i ON i.user_id = u.id AND i.is_profile_picture = 1
    WHERE (f.user_id = ? OR f.friend_id = ?)
      AND u.id != ?";

$friendsStmt = $conn->prepare($friendsQuery);
$friendsStmt->bind_param("iii", $current_user_id, $current_user_id, $current_user_id);
$friendsStmt->execute();
$friendsResult = $friendsStmt->get_result();

if ($friendsResult->num_rows == 0) {
    echo "You have no friends.";
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="friends_list.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="friends_list.css">
    <title>Social</title>
</head>

<body>
    <?php
    include('navbar.php');
    include('ads.php');
    ?>


    <div class="banner">
        <h1>Social</h1>
        <p>Discover a vibrant community where connections meet action!<br> Find teammates who share your passion,
            workout partners to push your limits, and friends to create lasting memories. <br>Whether you're scoring
            goals, hitting the gym, or simply enjoying the journey.<br>Join now and make every moment count! </p>

    </div>


    <div class="profile-container">
        <div class="profile-details">
            <h1>Your Friends</h1>
            <ul>
                <?php while ($row = $friendsResult->fetch_assoc()): ?>
                    <p class="detail">
                        <?php
                        // Check if the user has a profile picture
                        $profilePicRes = mysqli_query($conn, "SELECT file FROM images WHERE user_id = " . $row['id'] . " AND is_profile_picture = 1");
                        $profilePicRow = mysqli_fetch_assoc($profilePicRes);

                        // Display the profile picture or the default image if none exists
                        if (empty($profilePicRow['file'])) {
                            // If no profile picture, show default image
                            echo '<img src="IMAGE/default.png" alt="Profile Picture" class="profile-pic">';
                        } else {
                            // If profile picture exists, show it
                            echo '<img src="uploads/' . $profilePicRow['file']. '" alt="Profile Picture" class="profile-pic">';
                        }
                        ?>
                        <a href="player_profile.php?id=<?php echo $row['id']; ?>">
                            <span
                                class="friend-name"><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></span>
                        </a>
                    </p>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>







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