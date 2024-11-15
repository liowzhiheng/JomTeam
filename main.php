<?php
session_start(); // Start the PHP session

// Check if the user is logged in
if ($_SESSION["Login"] != "YES") {
    header("Location: login.php");
    exit();
}

// Check if USER_ID is set
if (!isset($_SESSION["ID"])) {
    echo "User ID is not set in the session.";
    exit();
}

require("config.php");

// Fetch user data
$user_id = $_SESSION['ID']; // Assuming user ID is stored in session
$result = mysqli_query($conn, "SELECT * FROM profile WHERE user_id = '$user_id'");
$rows = mysqli_fetch_assoc($result);
?>

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
            <img src="IMAGE/jomteam.png" alt="Logo">
        </a>

        <ul class="menu leftmenu">
            <li><a href="main.php">Home</a></li>
            <li><a href="find_match.php">Find Match</a></li>
            <li><a href="create_match.php">Create Match</a></li>
            <li><a href="view_profile.php">Profile</a></li>
            <li><a href="#premium">Premium</a></li>
        </ul>

        <ul class="menu rightmenu">
            <li class="notification"><a href="#notification"><img src="IMAGE/NOTIFICATION.png" alt="Notification"></a>
            </li>
            <li class="logout"><a href="login.php">Log out<img src="IMAGE/LOGOUT.png" alt="Logout"></a></li>
        </ul>
    </nav>

    <div class="banner">
        <h1>Find Your Best Sport Buddies</h1>
        <p>Connecting You with Passionate Teammates, Inspiring Workout Partners, and Lifelong Friends! <br>Join Today
            and Kickstart Your Next Adventure on the Field! </p>

    </div>

    <div class="banner-image">
        <img src="IMAGE/swimming.png" alt="Sports">
    </div>





    <div class="find_match">Find Match
        <div class="find_match_content">Finding the perfect match in sports can be a game-changer. <br>It's all about
            connecting with individuals <br>who share your passion for the game and have the same dedication and drive.
        </div>

        <div class="red_button">
            <a href="find_match.php">
                <img src="IMAGE/red_button.png">
            </a>
        </div>

    </div>
    <!-- Find Match and Create Match Section
    <section class="match-buttons">
         Create Match Button 
        <a href="find_match.php">
            <button class="find-match-btn">Find Match</button>
        </a>

        Find Match Button 
        <a href="create_match.php">
            <button class="create-match-btn">Create Match</button>
        </a>
    </section>

    Description Text (below buttons)
    <div class="match-description">
        <p>Finding the perfect match in sports can be a game-changer.</p>
    </div>
    -->

    <!-- create_your_own_match-->
    <div class="create_your_own_match">
        <div class="create_your_own_match_title">Create your own match</div>
        <div class="create-match-content">
            Not finding the right match? No problem! <br>Create your own game-changing partnership and turn any
            challenge
            into a victory. <br>Join our network and start building your dream team today!
        </div>
        <div class="picture_box">
            <a href="create_match.php">
                <img src="IMAGE/button.png" class="picture">
            </a>
        </div>
    </div>

    <!-- view profile-->
    <div class="view_profile">Profile
        <div class="view_profile_content"><br>Let people know more about
            you!<br>Share your passions, interests, and achievements.<br>Whether it's your love for sports, your
            favorite hobbies,or your proudest
            moments,<br> let your profile tell your unique story. <br>Join our community and start making
            meaningful connections today!<br>
        </div>

        <div>
            <!-- Profile detail -->
            <div class="uploaded-images">
                <?php
                $res = mysqli_query($conn, "SELECT file FROM images WHERE user_id = " . $_SESSION["ID"]);
                while ($row = mysqli_fetch_assoc($res)) {
                    if (empty($row['file'])) {
                        echo '<div class="image-container">
                <img src="IMAGE/default.png" alt="Default Image" class="uploaded-image"/>
              </div>';
                    } else {
                        echo '<div class="image-container">
                <img src="uploads/' . $row['file'] . '" alt="Uploaded Image" class="uploaded-image"/>
              </div>';
                    }
                }
                ?>
            </div>
            <div class="group">

                <span><?php echo '<span class="main_page_profile_content_description">' . htmlspecialchars($rows['description']); ?></span>
            </div>

            <label class="main_page_profile_content">Name:</label>
            <?php echo '<span class="main_page_profile_content">' . htmlspecialchars($rows['name']); ?>
        </div>
        <div class="group">
            <label for="gender" class="main_page_profile_content ">Gender:</label>
            <?php echo '<span class="main_page_profile_content">' . htmlspecialchars($rows['gender']); ?>
        </div>
        <div class="group">
            <label for="age" class="main_page_profile_content ">Age:</label>
            <?php echo '<span class="main_page_profile_content">' . htmlspecialchars($rows['age']); ?>
        </div>
        <div class="group">
            <label for="status" class="main_page_profile_content ">Status:</label>
            <?php echo '<span class="main_page_profile_content">' . htmlspecialchars($rows['status']); ?>
        </div>
        <div class="group">
            <label for="phone" class="main_page_profile_content ">Phone Number:</label>
            <?php echo '<span class="main_page_profile_content">' . htmlspecialchars($rows['phone']); ?>
        </div>


        <div class="edit_your_profile_button">
            <a href="view_profile.php">
                <img src="IMAGE/edit_your_profile.png">
            </a>
        </div>
    </div>

    </div>


    </div>

</body>

</html>