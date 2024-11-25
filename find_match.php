<?php
// matches.php

// Include your database connection
require("config.php");

// Query to fetch matches from the database
$query = "SELECT * FROM gamematch ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

// Check if any matches are found
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
    <title>Matches</title>
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
            <li class="logout"><a href="login.php">Log out<img src="image/LOGOUT.png" alt="Logout"></a></li>
        </ul>
    </nav>

    <!-- Search bar -->
    <section class="search-section">
    <div class="search-container">
        <form action="search_match.php" method="GET" class="search-form">
            <!-- Main search bar -->
            <div class="main-search">
                <input 
                    type="text" 
                    name="match_code" 
                    class="search-input" 
                    placeholder="🔍 Search by match code or location..."
                >
                <button type="button" class="filter-toggle-btn" onclick="toggleFilters()">
                    Filters
                </button>
                <button type="submit" class="search-button">
                    Search
                </button>
            </div>

            <!-- Filter section (hidden by default) -->
            <div class="filter-section" id="filterSection">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="sport">Sport:</label>
                        <select name="sport" id="sport">
                            <option value="">All Sports</option>
                            <option value="basketball">Basketball</option>
                            <option value="football">Football</option>
                            <option value="badminton">Badminton</option>
                            <option value="volleyball">Volleyball</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="area">Area:</label>
                        <select name="area" id="area">
                            <option value="">All Areas</option>
                            <option value="north">North</option>
                            <option value="south">South</option>
                            <option value="east">East</option>
                            <option value="west">West</option>
                            <option value="central">Central</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="date">Date:</label>
                        <input type="date" name="date" id="date">
                    </div>
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

                    <div class="filter-group">
                        <label for="gender">Player Gender:</label>
                        <select name="gender" id="gender">
                            <option value="">Any Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="mixed">Mixed</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="skill_level">Skill Level:</label>
                        <select name="skill_level" id="skill_level">
                            <option value="">Any Level</option>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="button" onclick="clearFilters()" class="clear-btn">Clear Filters</button>
                    <button type="button" onclick="applyFilters()" class="apply-btn">Apply Filters</button>
                </div>
            </div>
        </form>
    </div>
</section>
    <!-- Grid of Images Section -->
    <section class="grid-section">
        <div class="grid-container">
            <?php for ($i = 1; $i <= 9; $i++): ?>
                <div class="grid-item">
                    <img src="image/court.jpg" alt="Indoor Court">
                    <p class="image-label">Indoor Court <?php echo $i; ?></p>
                </div>
            <?php endfor; ?>
        </div>
        <button class="view-all-btn">VIEW ALL</button>
    </section>
    <script src="searchbar.js"></script>




     <!-- Display the created matches -->
     <section class="match-list-section">
        <h2>Created Matches</h2>
        <div class="match-container">
            <?php if (!empty($matches)): ?>
                <?php foreach ($matches as $match): ?>
                    <div class="match-item">
                        <h3><?php echo htmlspecialchars($match['match_title']); ?> - <?php echo htmlspecialchars($match['game_type']); ?></h3>
                        <p>Location: <?php echo htmlspecialchars($match['location']); ?></p>
                        <p>Skill Level: <?php echo htmlspecialchars($match['skill_level_required']); ?></p>
                        <p>Max Players: <?php echo htmlspecialchars($match['max_players']); ?> | Current Players: <?php echo htmlspecialchars($match['current_players']); ?></p>
                        <p>Date: <?php echo date("F j, Y", strtotime($match['start_date'])); ?> - <?php echo date("g:i A", strtotime($match['start_date'])); ?></p>
                        <p>Status: <?php echo htmlspecialchars($match['status']); ?></p>
                        <a href="match_details.php?id=<?php echo $match['id']; ?>" class="view-details-btn">View Details</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No matches created yet.</p>
            <?php endif; ?>
        </div>
    </section>
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

