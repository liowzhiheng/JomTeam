<?php
session_start(); // Start the PHP session

// Check if USER_ID is set
if (!isset($_SESSION["ID"])) {
    echo "User ID is not set in the session.";
    exit();
}

require("config.php");

// Fetch user and profile data
$user_id = $_SESSION['ID'];

//created match list
$query2 = "SELECT * FROM gamematch WHERE user_id = '$user_id'";
$result2 = mysqli_query($conn, $query2);
if (mysqli_num_rows($result2) > 0) {
    $matches = mysqli_fetch_all($result2, MYSQLI_ASSOC);
} else {
    $matches = [];
}

//joined match list
$query3 = "SELECT match_id FROM match_participants WHERE user_id = '$user_id'";
$result3 = mysqli_query($conn, $query3);
if (mysqli_num_rows($result3) > 0) {
    $match_ids = [];
    while ($row = mysqli_fetch_assoc($result3)) {
        $match_ids[] = $row['match_id'];
    }
    $match_ids_string = implode(',', $match_ids);
    $query4 = "SELECT * FROM gamematch WHERE id IN ($match_ids_string) ";
    $result4 = mysqli_query($conn, $query4);
    $joined_match = mysqli_fetch_all($result4, MYSQLI_ASSOC);
} else {
    $matches_id = [];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
</head>

<body>
<?php if ($_SESSION["LEVEL"] != 1) { ?>
    <?php
    include('navbar.php');
    include('ads.php');
    ?>
    <div>
        <div class="grid-section">
            <h1 class="created_match_title">Created Match</h1>
            <div class="grid-container">
                <?php if (!empty($matches)): ?>
                    <?php foreach ($matches as $match): ?>
                        <div class="grid-item">
                            <img src="gamematch/<?php echo htmlspecialchars($match['file']); ?>" alt="Match Image"
                                style="width: 200px; height: 200px;">
                            <p class="info_title"><?php echo htmlspecialchars($match['match_title']); ?></p>
                            <p class="info"><?php echo htmlspecialchars($match['game_type']); ?></p>
                            <p class="info">Location: <?php echo htmlspecialchars($match['location']); ?></p>
                            <p class="info">Date: <?php echo htmlspecialchars($match['start_date']); ?></p>
                            <p class="info">Time: <?php echo htmlspecialchars($match['start_time']); ?></p>
                            <a href="match_details.php?id=<?php echo $match['id']; ?>" class="view-all-btn">View Details</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="grid-section">
                        <p class="info_title">No match created.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div>
            <h1 class="created_match_title">Joined Match</h1>
            <div class=".grid-section">
                <div class="grid-container">
                    <?php if (!empty($joined_match)): ?>
                        <?php foreach ($joined_match as $match): ?>
                            <div class="grid-item">
                                <img src="gamematch/<?php echo htmlspecialchars($match['file']); ?>" alt="Match Image"
                                    style="width: 200px; height: 200px;">
                                <p class="info_title"><?php echo htmlspecialchars($match['match_title']); ?></p>
                                <p class="info"><?php echo htmlspecialchars($match['game_type']); ?></p>
                                <p class="info">Location: <?php echo htmlspecialchars($match['location']); ?></p>
                                <p class="info">Date: <?php echo htmlspecialchars($match['start_date']); ?></p>
                                <p class="info">Time: <?php echo htmlspecialchars($match['start_time']); ?></p>
                                <a href="match_details.php?id=<?php echo $match['id']; ?>" class="view-all-btn">View Details</a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="grid-section">
                            <p class="info_title">No match joined.</p>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
    <?php } ?>
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

</html>