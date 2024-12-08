<?php
// Include database connection (for session handling or dynamic content like profile image)
require("config.php");
?>

<nav class="navbar">
    <a href="#" class="logo">
        <img src="IMAGE/jomteam.png" alt="Logo">
    </a>

    <ul class="menu leftmenu">
        <li><a href="main.php">Home</a></li>
        <li><a href="find_match.php">Find Match</a></li>
        <li><a href="create_match.php">Create Match</a></li>
        <li><a href="#premium">Premium</a></li>
    </ul>

    <ul class="menu rightmenu">
        <li class="notification"><a href="#notification"><img src="IMAGE/NOTIFICATION.png" alt="Notification"></a>
        </li>
        <li class="profile">
            <?php
            // Fetch the profile image from the database
            if (isset($_SESSION["ID"])) {
                $res = mysqli_query($conn, "SELECT file FROM images WHERE user_id = " . $_SESSION["ID"]);
                $row = mysqli_fetch_assoc($res);
                // Check if the user has uploaded a profile picture
                if (empty($row['file'])) {
                    // Display default logout icon if no image found
                    echo '<div class="image-container">
                            <a href="view_profile.php">
                                <img src="IMAGE/LOGOUT.png" alt="Profile Image" class="uploaded-image"/>
                            </a>
                          </div>';
                } else {
                    // Display the uploaded profile image
                    echo '<div class="image-container">
                            <a href="view_profile.php">
                                <img src="uploads/' . $row['file'] . '" alt="Uploaded Image" class="uploaded-image"/>
                            </a>
                          </div>';
                }
            }
            ?>
        </li>
        <li class="logout">
            <a href="javascript:void(0);" onclick="confirmLogout()">Logout</a>
        </li>
        <script>
            function confirmLogout() {
                var confirmation = confirm("Are you sure you want to logout?");
                if (confirmation) {
                    window.location.href = "index.php"; // Redirect to index.php if confirmed
                }
            }
        </script>
    </ul>
</nav>