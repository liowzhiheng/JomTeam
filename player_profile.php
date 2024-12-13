<?php
// player_profile.php
require("config.php");
session_start();

// Check if user is logged in
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit();
}

// Check if a user ID is provided
if (!isset($_GET['id'])) {
    echo "No user specified.";
    exit();
}

$profile_user_id = intval($_GET['id']);
$current_user_id = $_SESSION['ID'];


// Diagnostic query to log all images for the user
$diagnosticQuery = "SELECT * FROM images WHERE user_id = ?";
$diagnosticStmt = $conn->prepare($diagnosticQuery);
$diagnosticStmt->bind_param("i", $profile_user_id);
$diagnosticStmt->execute();
$diagnosticResult = $diagnosticStmt->get_result();

// Log all images for debugging
while ($row = $diagnosticResult->fetch_assoc()) {
    error_log("Image for user " . $profile_user_id . ": " . print_r($row, true));
}

// Fetch user basic information
$userQuery = "SELECT id, first_name, last_name, email, gender, birth_date, phone FROM user WHERE id = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param("i", $profile_user_id);
$userStmt->execute();
$userResult = $userStmt->get_result();

if ($userResult->num_rows == 0) {
    echo "User not found.";
    exit();
}

$user = $userResult->fetch_assoc();

// Fetch user profile information
$profileQuery = "SELECT * FROM profile WHERE user_id = ?";
$profileStmt = $conn->prepare($profileQuery);
$profileStmt->bind_param("i", $profile_user_id);
$profileStmt->execute();
$profileResult = $profileStmt->get_result();
$profile = $profileResult->fetch_assoc() ?: [];

// Fetch user's profile picture with improved error handling
$imageQuery = "
    SELECT file FROM images 
    WHERE user_id = ? AND is_profile_picture = 1 
    LIMIT 1
";
$imageStmt = $conn->prepare($imageQuery);
$imageStmt->bind_param("i", $profile_user_id);
$imageStmt->execute();
$imageResult = $imageStmt->get_result();

// Handle profile picture selection
if ($imageResult->num_rows > 0) {
    // Profile picture exists
    $profilePictureRow = $imageResult->fetch_assoc();
    $profilePicture = $profilePictureRow['file'];
    error_log("Profile picture found: $profilePicture");
} else {
    // If no profile picture, try to select the first uploaded image
    $fallbackQuery = "
        SELECT file FROM images 
        WHERE user_id = ? 
        LIMIT 1
    ";
    $fallbackStmt = $conn->prepare($fallbackQuery);
    $fallbackStmt->bind_param("i", $profile_user_id);
    $fallbackStmt->execute();
    $fallbackResult = $fallbackStmt->get_result();

    if ($fallbackResult->num_rows > 0) {
        $fallbackRow = $fallbackResult->fetch_assoc();
        $profilePicture = $fallbackRow['file'];
        error_log("Fallback image selected: $profilePicture");
    } else {
        // Default image if no images found
        $profilePicture = 'default.png';
        error_log("No images found. Using default profile picture.");
    }
}

// Check if the file exists on the server
$imagePath = 'uploads/' . $profilePicture;
if (!file_exists($imagePath)) {
    $profilePicture = 'default.png';
    error_log("Image file not found on server: $imagePath. Using default profile picture.");
}

// Fetch user's ratings
$ratingsQuery = "
    SELECT pr.rating, pr.created_at, 
           u.first_name AS rater_first_name, u.last_name AS rater_last_name,
           gm.match_title
    FROM player_ratings pr
    JOIN user u ON pr.rater_id = u.id
    LEFT JOIN gamematch gm ON pr.match_id = gm.id
    WHERE pr.rated_user_id = ?
    ORDER BY pr.created_at DESC
";
$ratingsStmt = $conn->prepare($ratingsQuery);
$ratingsStmt->bind_param("i", $profile_user_id);
$ratingsStmt->execute();
$ratingsResult = $ratingsStmt->get_result();

// Calculate average rating
$avgRatingQuery = "SELECT AVG(rating) AS avg_rating FROM player_ratings WHERE rated_user_id = ?";
$avgRatingStmt = $conn->prepare($avgRatingQuery);
$avgRatingStmt->bind_param("i", $profile_user_id);
$avgRatingStmt->execute();
$avgRatingResult = $avgRatingStmt->get_result();
$avgRating = $avgRatingResult->fetch_assoc()['avg_rating'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>'s Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="player_profile.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">

</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="profile-container">
    <!-- <button onclick="window.history.back()" class="back-button">← Back</button> -->
    <button onclick="window.location.href='match_details.php?id=<?php echo $_GET['match_id']; ?>'" class="back-button">← Back</button>
    
    <div class="profile-header">

            <div class="uploaded-images">
                <img id="imagePreview" src="<?php echo !empty($profilePicture) ? 'uploads/' . htmlspecialchars($profilePicture) : 'IMAGE/default.png'; ?>" alt="Profile Picture" class="profile-image">
            </div>

            <h1><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h1>
            
            <div class="profile-rating">
                <span>Average Rating: <?php printf("%.1f", $avgRating ?: 0); ?> / 5.0</span>
                <div class="stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star <?php echo $i <= round($avgRating) ? 'selected' : ''; ?>">&#9733;</span>
                    <?php endfor; ?>
                </div>

                <div class="detail">
                    <strong>Gender:</strong> 
                    <?php echo htmlspecialchars($user['gender'] ?? 'Not specified'); ?>
                </div>

                <div class="detail">
                    <strong>Age:</strong> 
                    <?php 
                    if (!empty($user['birth_date'])) {
                        $birthDate = new DateTime($user['birth_date']);
                        $today = new DateTime();
                        $age = $birthDate->diff($today)->y;
                        echo htmlspecialchars($age . ' years old');
                    } else {
                        echo 'Not specified';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="profile-details">
            <h2>Profile Information</h2>

                        <div class="detail">
                <strong>Email:</strong> 
                <?php echo htmlspecialchars($user['email']); ?>
            </div>

            <div class="detail">
                <strong>Phone Number:</strong> 
                <?php echo htmlspecialchars($user['phone'] ?? 'Not provided'); ?>
            </div>

            <div class="detail">
                <strong>Location:</strong> 
                <?php echo htmlspecialchars($profile['location'] ?? 'Not specified'); ?>
            </div>

            <div class="detail">
                <strong>Interests:</strong> 
                <?php echo htmlspecialchars($profile['interests'] ?? 'Not specified'); ?>
            </div>

            <div class="detail">
                <strong>Preferred Game Types:</strong> 
                <?php echo htmlspecialchars($profile['preferred_game_types'] ?? 'Not specified'); ?>
            </div>

            <div class="detail">
                <strong>Skill Level:</strong> 
                <?php echo htmlspecialchars($profile['skill_level'] ?? 'Not specified'); ?>
            </div>

            <div class="detail">
                <strong>Status:</strong> 
                <?php echo htmlspecialchars($profile['status'] ?? 'Not specified'); ?>
            </div>

            <div class="detail">
                <strong>Description:</strong> 
                <?php echo htmlspecialchars($profile['description'] ?? 'No description provided'); ?>
            </div>

            <div class="detail">
                <strong>Availability:</strong> 
                <?php echo htmlspecialchars($profile['availability'] ?? 'Not specified'); ?>
            </div>

            <div class="detail">
                <strong>Last Active:</strong> 
                <?php 
                if (!empty($profile['last_active'])) {
                    $last_active = strtotime($profile['last_active']);
                    echo htmlspecialchars(date('F j, Y, g:i a', $last_active));
                } else {
                    echo 'Never';
                }
                ?>
            </div>

        </div>

        <div class="ratings-section">
            <!-- <h2>Player Ratings</h2>
            <?php if ($ratingsResult->num_rows > 0): ?>
                <?php while ($rating = $ratingsResult->fetch_assoc()): ?>
                    <div class="rating-item">
                        <div class="rating-header">
                            <span class="rater-name">
                                <?php echo htmlspecialchars($rating['rater_first_name'] . ' ' . $rating['rater_last_name']); ?>
                            </span>
                            <span class="rating-value"><?php echo number_format($rating['rating'], 1); ?> / 5.0</span>
                        </div>
                        <?php if (!empty($rating['match_title'])): ?>
                            <div class="rating-match">Match: <?php echo htmlspecialchars($rating['match_title']); ?></div>
                        <?php endif; ?>
                        <div class="rating-date"><?php echo date('F j, Y', strtotime($rating['created_at'])); ?></div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No ratings yet.</p>
            <?php endif; ?> -->

            <?php if ($current_user_id != $profile_user_id): ?>

            <div class="ratings-section">
            <?php if ($current_user_id != $profile_user_id): ?>
            <div class="rate-player-section">
                <h2>Rate This Player</h2>
                <form action="submit_rating.php" method="POST">
                <input type="hidden" name="rated_user_id" value="<?php echo $profile_user_id; ?>">
                <input type="hidden" name="match_id" value="<?php echo $_GET['match_id']; ?>">

                    <div class="rating-input">
                        <label for="rating">Rating:</label>
                        <div class="stars">
                            <input type="radio" name="rating" id="star5" value="5"><label for="star5" class="star">&#9733;</label>
                            <input type="radio" name="rating" id="star4" value="4"><label for="star4" class="star">&#9733;</label>
                            <input type="radio" name="rating" id="star3" value="3"><label for="star3" class="star">&#9733;</label>
                            <input type="radio" name="rating" id="star2" value="2"><label for="star2" class="star">&#9733;</label>
                            <input type="radio" name="rating" id="star1" value="1"><label for="star1" class="star">&#9733;</label>
                        </div>
                    </div>

                    <button type="submit">Submit Rating</button>
                </form>
            </div>
            <?php endif; ?>
        </div>

            <?php endif; ?>
        </div>
    </div>

    <script src="footer.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stars = document.querySelectorAll('.profile-rating .star');
            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    stars.forEach((s, i) => {
                        s.classList.toggle('selected', i <= index);
                    });
                });

                star.addEventListener('mouseover', () => {
                    stars.forEach((s, i) => {
                        s.style.color = i <= index ? '#f1c40f' : '#ddd';
                    });
                });

                star.addEventListener('mouseout', () => {
                    stars.forEach((s) => {
                        s.style.color = s.classList.contains('selected') ? '#f1c40f' : '#ddd';
                    });
                });
            });
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
