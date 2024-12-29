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


if (isset($_POST['unfollow']) && isset($_POST['friend_id'])) {
    $friend_id = $_POST['friend_id'];

    // Ensure the user is logged in
    if (isset($_SESSION['ID'])) {
        $current_user_id = $_SESSION['ID'];

        // Remove the friendship relationship from the 'friends' table
        $deleteQuery = "DELETE FROM friends WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("iiii", $current_user_id, $friend_id, $friend_id, $current_user_id);
        $deleteStmt->execute();

        // Redirect back to the friends list or a profile page after unfollowing
        header("Location: friends_list.php");
        exit();
    }
}
$current_user_id = $_SESSION['ID'];

// Handle acceptance
if (isset($_POST['accept_request'])) {
    $request_id = intval($_POST['request_id']);

    // Update the friend request status
    $updateQuery = "UPDATE friend_requests SET status = 'accepted' WHERE id = ? AND receiver_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ii", $request_id, $current_user_id);
    $updateStmt->execute();

    // Fetch sender ID
    $requestData = "SELECT sender_id FROM friend_requests WHERE id = ?";
    $requestDataStmt = $conn->prepare($requestData);
    $requestDataStmt->bind_param("i", $request_id);
    $requestDataStmt->execute();
    $requestDataResult = $requestDataStmt->get_result();
    $sender = $requestDataResult->fetch_assoc();

    if ($sender) {
        $sender_id = intval($sender['sender_id']);

        // Insert only one row into the friends table
        $checkFriendQuery = "SELECT * FROM friends WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)";
        $checkFriendStmt = $conn->prepare($checkFriendQuery);
        $checkFriendStmt->bind_param("iiii", $current_user_id, $sender_id, $sender_id, $current_user_id);
        $checkFriendStmt->execute();
        $checkFriendResult = $checkFriendStmt->get_result();

        if ($checkFriendResult->num_rows === 0) {
            // Use consistent order for user_id and friend_id
            $user_id_1 = min($current_user_id, $sender_id);
            $user_id_2 = max($current_user_id, $sender_id);

            $insertFriendQuery = "INSERT INTO friends (user_id, friend_id) VALUES (?, ?)";
            $insertFriendStmt = $conn->prepare($insertFriendQuery);
            $insertFriendStmt->bind_param("ii", $user_id_1, $user_id_2);
            $insertFriendStmt->execute();
        }
    }

    // Redirect to prevent duplicate submissions
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle rejection
if (isset($_POST['reject_request'])) {
    $request_id = intval($_POST['request_id']);

    // Update the friend request status
    $updateQuery = "UPDATE friend_requests SET status = 'rejected' WHERE id = ? AND receiver_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ii", $request_id, $current_user_id);
    $updateStmt->execute();

    // Redirect to prevent duplicate submissions
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch pending friend requests
$requestQuery = "SELECT fr.id, u.first_name, u.last_name, u.id AS sender_id
                 FROM friend_requests fr
                 JOIN user u ON fr.sender_id = u.id
                 WHERE fr.receiver_id = ? AND fr.status = 'pending'";
$requestStmt = $conn->prepare($requestQuery);
$requestStmt->bind_param("i", $current_user_id);
$requestStmt->execute();
$requestResult = $requestStmt->get_result();
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
    <div>
        <div>

        </div class="background">

    </div>

    <div class="profile-container">

        <div id="friendsListContainer" class="profile-details">
            <h1>Your Friends</h1>
            <button id="toggleViewButton" class="back-button">Friend Request</button>
            <ul> 
                <?php if ($friendsResult->num_rows > 0): ?>
                <?php while ($row = $friendsResult->fetch_assoc()): ?>
                    <p class="detail">
                        <?php
                        $profilePicRes = mysqli_query($conn, "SELECT file FROM images WHERE user_id = " . $row['id']);
                        $profilePicRow = mysqli_fetch_assoc($profilePicRes);
                        if (empty($profilePicRow['file'])) {
                            echo '<img src="IMAGE/default.png" alt="Profile Picture" class="profile-pic">';
                        } else {
                            echo '<img src="uploads/' . $profilePicRow['file'] . '" alt="Profile Picture" class="profile-pic">';
                        }
                        ?>
                        <a href="player_profile2.php?id=<?php echo $row['id']; ?>">
                            <span
                                class="friend-name"><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></span>
                        </a>
                    <form action="friends_list.php" method="POST">
                        <input type="hidden" name="friend_id" value="<?php echo $row['id']; ?>">
                        <button name="unfollow" class="unfollow-button">
                            <img src="IMAGE/remove_user_button.png" alt="Unfollow">
                        </button>
                    </form>
                    </p>
                <?php endwhile; ?>
                <?php else: ?>
                    <p class="detail ">You have no friends :(</p>
                <?php endif; ?>
            </ul>
        </div>

    </div>

    <div id="pendingRequestsContainer"
        class="profile-details pending-requests-container request-container hidden_request">
        <div>
            <h1>Pending Friend Requests</h1>
            <button id="toggleViewButtonBack" class="back-button2">Friend List</button>
            <ul>
                <?php if ($requestResult->num_rows > 0): ?>
                    <?php while ($row = $requestResult->fetch_assoc()): ?>
                        <p class="detail">
                            <img src="IMAGE/default.png" alt="Profile Picture" class="profile-pic">
                            <a href="player_profile2.php?id=<?php echo $row['sender_id']; ?>">
                            <span class="friend-name">
                                <?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?>
                            </span>
                            </a>
                        <form method="POST" class="action-form">
                            <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="accept_request" class="action-button accept-button">Accept</button>
                        </form>
                        <form method="POST" class="action-form">
                            <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="reject_request" class="action-button reject-button">Reject</button>
                        </form>
                        </p>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="detail ">You have no pending friend requests.</p>
                <?php endif; ?>
            </ul>
        </div>
    </div>







    <script>
        const toggleButton = document.getElementById('toggleViewButton');
        const toggleButtonBack = document.getElementById('toggleViewButtonBack');
        const pendingContainer = document.querySelector('.pending-requests-container');
        const friendsListContainer = document.querySelector('.profile-container');

        // Toggle from friends list to pending requests
        toggleButton.addEventListener('click', () => {
            pendingContainer.classList.remove('hidden_request');
            friendsListContainer.classList.add('hidden');
        });

        // Toggle from pending requests back to friends list
        toggleButtonBack.addEventListener('click', () => {
            pendingContainer.classList.add('hidden_request');
            friendsListContainer.classList.remove('hidden');
        });

    </script>




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
