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

// Fetch user and profile data
$user_id = $_SESSION['ID'];

$query = "
    SELECT 
        u.first_name, 
        u.last_name, 
        u.gender, 
        u.email, 
        u.password,
        u.phone, 
        p.status, 
        p.description, 
        p.location, 
        p.interests, 
        p.preferred_game_types, 
        p.skill_level, 
        p.availability 
    FROM 
        user u 
    LEFT JOIN 
        profile p 
    ON 
        u.id = p.user_id 
    WHERE 
        u.id = '$user_id'
";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "No profile data found.";
    exit();
}

$rows = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="view_profile.css">
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

    <div class="profile-content">
        <h1 class="profile-title">Profile</h1>
        <p class="profile-description">
            Let people know more about you! Share your passions, interests, and achievements.
            Whether it's your love for sports, your favorite hobbies, or your proudest moments,
            let your profile tell your unique story. Join our community and start making meaningful
            connections today!
        </p>
        <div class="profile-container">
            <div class="profile-left">
                <div class="uploaded-images">
                    <?php
                    $res = mysqli_query($conn, "SELECT file FROM images WHERE user_id = " . $_SESSION["ID"]);
                    while ($row = mysqli_fetch_assoc($res)) {
                        if (empty($row['file'])) {
                            // Display default image with overlay text
                            echo '<div class="image-container">
                <img src="IMAGE/default.png" alt="Default Image" class="uploaded-image" onclick="document.getElementById(\'imageInput\').click();" />
                <div class="overlay-text">Upload Image</div>
              </div>';
                        } else {
                            // Display uploaded image with overlay text
                            echo '<div class="image-container">
                <img src="uploads/' . $row['file'] . '" alt="Uploaded Image" class="uploaded-image" onclick="document.getElementById(\'imageInput\').click();" />
                <div class="overlay-text">Click to Change</div>
              </div>';
                        }
                    }
                    ?>

                    <form action="update_image.php" method="POST" enctype="multipart/form-data"
                        class="image-upload-form">
                        <input type="file" name="image" id="imageInput" style="display: none;"
                            onchange="enableSubmitButton()" />
                        <button type="submit" name="submit" class="submit-button" id="uploadButton"
                            disabled>Upload</button>
                    </form>
                </div>
            </div>

            <div class="profile-right">
                <!-- Profile Form -->
                <form id="profileForm" action="update_profile.php" method="post">
                    <div class="group">
                        <label for="name">First Name:</label>
                        <input type="text" id="fname" name="fname"
                            value="<?php echo htmlspecialchars($rows['first_name']); ?>" placeholder="Enter your name">
                    </div>

                    <div class="group">
                        <label for="name">Last Name:</label>
                        <input type="text" id="lname" name="lname"
                            value="<?php echo htmlspecialchars($rows['last_name']); ?>" placeholder="Enter your name">
                    </div>

                    <div class="group">
                        <label for="gender">Gender:</label>
                        <input type="text" id="gender" name="gender"
                            value="<?php echo htmlspecialchars($rows['gender']); ?>" placeholder="Enter your gender">
                    </div>

                    <div class="group">
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email"
                            value="<?php echo htmlspecialchars($rows['email']); ?>" placeholder="Enter your email">
                    </div>

                    <div class="group">
                        <label for="password">Password:</label>
                        <input type="text" id="password" name="password"
                            value="<?php echo htmlspecialchars($rows['password']); ?>" placeholder="Enter your email">
                    </div>

                    <div class="group">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" name="phone"
                            value="<?php echo htmlspecialchars($rows['phone']); ?>" placeholder="Enter your email">
                    </div>

                    <div class="group">
                        <label for="status">Status:</label>
                        <input type="text" id="status" name="status"
                            value="<?php echo htmlspecialchars($rows['status']); ?>" placeholder="Enter your status">
                    </div>
                    <div class="group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description"
                            placeholder="Describe yourself..."><?php echo htmlspecialchars($rows['description']); ?></textarea>
                    </div>
                    <div class="group">
                        <label for="location">Location:</label>
                        <input type="text" id="location" name="location"
                            value="<?php echo htmlspecialchars($rows['location']); ?>"
                            placeholder="Enter your location">
                    </div>
                    <div class="group">
                        <label for="interests">Interests:</label>
                        <textarea id="interests" name="interests"
                            placeholder="List your interests..."><?php echo htmlspecialchars($rows['interests']); ?></textarea>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="button">
                            <img src="IMAGE/button_2.png" alt="Submit Button">
                        </button>
                    </div>


                </form>
            </div>
        </div>
    </div>

    <script src="view_profile.js"></script>
    <?php mysqli_close($conn); ?>
</body>

</html>