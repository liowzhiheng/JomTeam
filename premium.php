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

// Fetch user data
$query = "SELECT first_name, last_name, gender, phone, premium FROM user WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $premium_status = $user['premium'];
} else {
    echo "User not found.";
    exit();
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JOM Premium</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="premium.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="shortcut icon" type="image/jpg" href="IMAGE/favicon.png"/>
</head>

<body>
    <?php include('navbar.php'); ?>

    <?php if (isset($premium_status) && $premium_status == 1): ?>

        <div class="profile-content">
            <h1 class="profile-title">JOM Premium!</h1>
            <p class="profile-description">
                With JOM Premium, you can unlock exclusive tools, and personalized tools.<br>
                Take control of your journey and experience success like never before.
            </p>
        </div>

        <div class="ads">
            <div class="adsfree-container">
                <img class="adsfree" src="IMAGE/ads_free_2.png" />
                <img class="adsfree-hover" src="IMAGE/hover_image.png" alt="Hover Image">
                <h1 class="profile-title_adsfree">ADS Free</h1>
            </div>
            <div class="adsfree-container">
                <img class="time" src="IMAGE/time.png" />
                <img class="time-hover" src="IMAGE/hover_image_2.png" alt="Hover Image">
                <h1 class="profile-title_time">Extend Time</h1>
            </div>
        </div>

        <div class="profile-content">
            <a href="cancel_premium.php">
                <img class="get_premium" src="IMAGE/cancel_premium.png" alt="Cancel Premium" />
            </a>
            <h1 class="ads-profile-title">🎉 You are a Premium Member! Enjoy your exclusive benefits! 🌟</h1>
        </div>
    <?php else: ?>
        <div class="profile-content">
            <h1 class="profile-title">JOM Premium!</h1>
            <p class="profile-description">
                With JOM Premium, you can unlock exclusive tools, and personalized tools.<br>
                Take control of your journey and experience success like never before.
            </p>
        </div>

        <div class="ads">
            <div class="adsfree-container">
                <img class="adsfree" src="IMAGE/ads_free_2.png" />
                <img class="adsfree-hover" src="IMAGE/hover_image.png" alt="Hover Image">
                <h1 class="profile-title_adsfree">ADS Free</h1>
            </div>
            <div class="adsfree-container">
                <img class="time" src="IMAGE/time.png" />
                <img class="time-hover" src="IMAGE/hover_image_2.png" alt="Hover Image">
                <h1 class="profile-title_time">Extend Time</h1>
            </div>
        </div>

        <div class="profile-content">
            <a href="payment.php">
                <img class="get_premium" src="IMAGE/get_premium.png" alt="Get Premium" />
            </a>
            <h1 class="ads-profile-title">Just RM 2.00 / month!<br>No commitments, no worries, cancel anytime!</h1>
        </div>
    <?php endif; ?>
</body>

<script>
    function cancelPremium() {
        if (confirm("Are you sure you want to cancel your premium subscription?")) {
            fetch('cancel_premium.php');
        }
    }
</script>




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
