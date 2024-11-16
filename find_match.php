<?php
// matches.php
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
</body>
</html>