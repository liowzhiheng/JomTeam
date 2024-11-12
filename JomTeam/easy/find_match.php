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
</head>

<body>
    <nav class="navbar">
        <a href="#" class="logo">
            <img src="image/jomteam.png" alt="Logo">
        </a>

        <ul class="menu leftmenu">
            <li><a href="main.php">Home</a></li>
            <li><a href="find_match.php">Find Match</a></li>
            <li><a href="#create-match">Create Match</a></li>
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
            <form action="search_match.php" method="GET" style="width: 100%; display: flex; gap: 15px;">
                <input 
                    type="text" 
                    name="match_code" 
                    class="search-input" 
                    placeholder="🔍 Enter match code to find your game..."
                    required
                >
                <button type="submit" class="search-button">
                    Search
                </button>
            </form>
        </div>
    </section>  <!-- Added closing tag for search-section -->

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

</body>
</html>