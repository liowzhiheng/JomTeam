<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <nav class="navbar">
        <a href="#" class="logo">
            <img src="IMAGE/S.png" alt="Logo">
        </a>

        <ul class="menu leftmenu">
            <li><a href="#home">Home</a></li>
            <li><a href="#find-match">Find Match</a></li>
            <li><a href="#create-match">Create Match</a></li>
            <li><a href="view_profile.php">Profile</a></li>
            <li><a href="#premium">Premium</a></li>
        </ul>

        <ul class="menu rightmenu">
            <li class="notification"><a href="#notification"><img src="IMAGE/NOTIFICATION.png" alt="Notification"></a>
            </li>
            <li class="logout"><a href="login.php">Log out<img src="IMAGE/LOGOUT.png" alt="Logout"></a></li>
        </ul>
    </nav>

    <div class="banner-content">
        <h1>Find Your Best Sport Buddies</h1>
        <p>Connecting You with Passionate Teammates, Inspiring Workout Partners, and Lifelong Friends! </p>
        <p>Join Today and Kickstart Your Next Adventure on the Field!</p>
    </div>

    <div class="banner-image">
        <img src="IMAGE/sports.png" alt="Sports">
    </div>

    </section>

    <!-- Find Match Section -->
    <section class="find-match">
        <h2>Find Match</h2>
        <p>Finding the perfect match in sports can be a game-changer. It’s all about connecting with individuals who share your passion for the game and have the same dedication and drive.</p>
    </section>

    <!-- Grid of Images Section -->
    <section class="grid-section">
        <div class="grid-container">
            <?php for($i = 0; $i < 9; $i++): ?>
                <div class="grid-item">
                    <img src="IMAGE/court.jpg" alt="Indoor Court">
                </div>
            <?php endfor; ?>
        </div>
        <button class="view-all-btn">VIEW ALL</button>
    </section>

</body>

</html> 