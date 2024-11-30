<?php
// Include your database connection
require("config.php");

// Initialize query for fetching matches
$query = "SELECT * FROM gamematch WHERE 1=1";

// Collect search inputs from the form submission
$searchGameType = isset($_GET['game_type']) ? mysqli_real_escape_string($conn, $_GET['game_type']) : '';
$searchLocation = isset($_GET['area']) ? mysqli_real_escape_string($conn, $_GET['area']) : '';
$searchDate = isset($_GET['date']) ? mysqli_real_escape_string($conn, $_GET['date']) : '';
$searchMatchTitle = isset($_GET['match_title']) ? mysqli_real_escape_string($conn, $_GET['match_title']) : '';

// Add conditions dynamically to the query
if (!empty($searchGameType)) {
    $query .= " AND game_type = '$searchGameType'";
}
if (!empty($searchLocation)) {
    $query .= " AND location LIKE '%$searchLocation%'";
}
if (!empty($searchDate)) {
    $query .= " AND start_date = '$searchDate'";
}
if (!empty($searchMatchTitle)) {
    $query .= " AND match_title LIKE '%$searchMatchTitle%'";
}

// Order by created date
$query .= " ORDER BY created_at DESC";

// Execute the query
$result = mysqli_query($conn, $query);

// Fetch results
if (mysqli_num_rows($result) > 0) {
    $matches = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $matches = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Match</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="find_match.css">
    <link rel="stylesheet" href="searchbar.css">
    <link rel="stylesheet" href="footer.css">
</head>

<body>
    <nav class="navbar">
        <a href="#" class="logo">
            <img src="image/jomteam.png" alt="Logo">
        </a>

        <ul class="menu leftmenu">
            <li><a href="main.php">Home</a></li>
            <li><a href="find_match.php">Find Match</a></li>
            <li><a href="create_match.php">Create Match</a></li>
            <li><a href="view_profile.php">Profile</a></li>
            <li><a href="#premium">Premium</a></li>
        </ul>

        <ul class="menu rightmenu">
            <li class="notification"><a href="#notification"><img src="image/NOTIFICATION.png" alt="Notification"></a>
            </li>
            <li class="logout"><a href="index.php">Log out<img src="image/LOGOUT.png" alt="Logout"></a></li>
        </ul>
    </nav>

    <div class="profile-content">
        <h1 class="profile-title">Find Match</h1>
        <p class="profile-description">
            Finding the perfect match in sports can be a game-changer.
            <br>It's all about connecting with individuals who share you passion for the game.
        </p>
    </div>

    <!-- Search bar -->
 <!-- Search Section -->
 <section class="search-section">
        <div class="search-container">
            <form action="find_match.php" method="GET" class="search-form">
            <div class="main-search">
                <input type="text" name="match_title" class="search-input"
                    placeholder="🔍 Search by match title...">
                <button type="submit" class="search-button">Search</button>
                <button type="button" class="filter-toggle-btn" onclick="toggleFilters()">Filters</button>
            </div>
                <div class="filter-section" id="filterSection">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="game_type">Game Type:</label>
                            <select name="game_type" id="game_type">
                                <option value="">All Game Types</option>
                                <option value="basketball">Basketball</option>
                                <option value="football">Football</option>
                                <option value="badminton">Badminton</option>
                                <option value="volleyball">Volleyball</option>
                                <option value="tennis">Tennis</option>
                                <option value="futsal">Futsal</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="date">Date:</label>
                            <input type="date" name="date" id="date">
                        </div>

                        <div class="filter-row">
                        <div class="filter-group">
                            <label for="time">Time:</label>
                            <select name="time" id="time">
                                <option value="">Any Time</option>
                                <option value="morning">Morning (6AM-12PM)</option>
                                <option value="afternoon">Afternoon (12PM-6PM)</option>
                                <option value="evening">Evening (6PM-10PM)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Display the created matches -->
    <section class="grid-section">
        <div class="grid-container">
            <?php if (!empty($matches)): ?>
                <?php foreach ($matches as $match): ?>
                    <div class="grid-item">
                        <img src="gamematch/<?php echo htmlspecialchars($match['file']); ?>" alt="Match Image" style="width: 200px; height: 200px;">
                        <p class="info_title"><?php echo htmlspecialchars($match['match_title']); ?></p>
                        <p class="info"><?php echo htmlspecialchars($match['game_type']); ?></p>
                        <p class="info">Location: <?php echo htmlspecialchars($match['location']); ?></p>
                        <p class="info">Date: <?php echo htmlspecialchars($match['start_date']); ?></p>
                        <p class="info">Time: <?php echo htmlspecialchars($match['start_time']); ?></p>
                        <a href="match_details.php?id=<?php echo $match['id']; ?>" class="view-all-btn">View Details</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No matches found for your search criteria.</p>
            <?php endif; ?>
        </div>
      
    </section>
    <script src="searchbar.js"></script>
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
