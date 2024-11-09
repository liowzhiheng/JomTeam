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

require("config.php"); // Include the database configuration file

// Fetch the user's profile information from the database
$user_id = $_SESSION["ID"];
$sql = "SELECT * FROM profile WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);

// Check if the user has a profile
if (mysqli_num_rows($result) > 0) {
    $rows = mysqli_fetch_assoc($result);
} else {
    // Initialize $rows with empty values if no profile is found
    $rows = [
        'name' => '',
        'gender' => '',
        'age' => '',
        'status' => '',
        'phone' => '',
        'description' => ''
    ];
}
mysqli_close($conn);
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
            <img src="IMAGE/jomteam.jpg" alt="Logo">
        </a>
        <ul class="menu leftmenu">
            <li><a href="main.php">Home</a></li>
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

    <div id="message"></div>

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
                <form id="profileForm" method="POST" enctype="multipart/form-data">
                    <img src="IMAGE/ZH.png" alt="Profile Picture" class="profile-pic" id="profilePic"
                        onclick="triggerFileInput()">
                    <input type="file" id="fileInput" name="profile_image" accept="image/*" style="display: none;"
                        onchange="previewImage(event)">
                    <div class="profile-name">
                        <input type="text" id="nameInput" name="name"
                            value="<?php echo htmlspecialchars($rows['name']); ?>" placeholder="Enter your name"
                            style="display: none;">
                        <span id="nameDisplay"><?php echo htmlspecialchars($rows['name']); ?></span>
                        <a href="#" class="edit" onclick="editName()">
                            <img src="IMAGE/EDIT.png" alt="Edit">
                        </a>
                    </div>
            </div>

            <div class="profile-right">
                <form id="profileForm">
                    <div class="group">
                        <label for="gender">Gender:</label>
                        <input type="text" id="gender" name="gender"
                            value="<?php echo htmlspecialchars($rows['gender']); ?>" placeholder="Enter your gender">
                    </div>

                    <div class="group">
                        <label for="age">Age:</label>
                        <input type="text" id="age" name="age" value="<?php echo htmlspecialchars($rows['age']); ?>"
                            placeholder="Enter your age">
                    </div>

                    <div class="group">
                        <label for="status">Status:</label>
                        <input type="text" id="status" name="status"
                            value="<?php echo htmlspecialchars($rows['status']); ?>" placeholder="Enter your status">
                    </div>

                    <div class="group">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" name="phone"
                            value="<?php echo htmlspecialchars($rows['phone']); ?>"
                            placeholder="Enter your phone number">
                    </div>

                    <div class="group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description"
                            placeholder="Describe yourself..."><?php echo htmlspecialchars($rows['description']); ?></textarea>
                    </div>

                    <div class="button-container">
                        <button type="submit" class="button">O</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="view_profile.js"></script>
</body>

</html>