<?php
session_start(); // Start the PHP session

// Check if the user is logged in
if ($_SESSION["Login"] != "YES") {
    header("Location: index.php");
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
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
</head>

<body>
    <?php
    include('navbar.php');
    include('ads.php');

    if (isset($_GET['status'])) {
        $status = $_GET['status'];

        if ($status === 'success') {
            echo '<p id="message" class="message success">Profile update successfully!</p>';
        } elseif ($status === 'fail') {
            echo '<p id="message" class="message fail">Sorry, something went wrong. Please try again.</p>';
        }
    }


    $location = isset($rows['location']) && !empty($rows['location']) ? htmlspecialchars($rows['location']) : '';
    // Check if location is empty and set default from database
    if (empty($location)) {

        $location = "Default Location from DB";
    }

    $status = isset($rows['status']) && !empty($rows['status']) ? htmlspecialchars($rows['status']) : '';
    // If the status is empty, set a default value or fetch from the database
    if (empty($status)) {
        $status = "Default Status from DB";
    }
    ?>

    <div class="profile-content">
        <h1 class="profile-title">Profile</h1>
        <p class="profile-description">
            Let people know more about you! Share your passions, interests, and achievements.
            Whether it's your love for sports, your favorite hobbies, or your proudest moments,
            let your profile tell your unique story. Join our community and start making meaningful
            connections today!
        </p>
        <form action="update_profile.php" method="post" enctype="multipart/form-data">
            <div class="profile-container">
                <!-- Image Section -->
                <div class="profile-left">
                    <div class="uploaded-images">
                        <?php
                        $res = mysqli_query($conn, "SELECT file FROM images WHERE user_id = " . $_SESSION["ID"]);
                        while ($row = mysqli_fetch_assoc($res)) {
                            if (empty($row['file'])) {
                                echo '<div class="image-container">
                    <img id="imagePreview" src="IMAGE/default.png" alt="Default Image" class="uploaded-image" onclick="document.getElementById(\'imageInput\').click();"/>
                    <div class="overlay-text" onclick="document.getElementById(\'imageInput\').click();">Upload Image</div>
                </div>';
                            } else {
                                echo '<div class="image-container">
                    <img id="imagePreview" src="uploads/' . $row['file'] . '" alt="Uploaded Image" class="uploaded-image" onclick="document.getElementById(\'imageInput\').click();"/>
                    <div class="overlay-text" onclick="document.getElementById(\'imageInput\').click();">Change Image</div>
                </div>';
                            }
                        }
                        ?>
                        <input type="file" name="image" id="imageInput" style="display: none;"
                            onchange="previewImage()" />
                    </div>
                </div>

                <!-- Info Section -->
                <div class="profile-right">
                    <div class="group">
                        <label for="fname">First Name:</label>
                        <input type="text" id="fname" name="fname"
                            value="<?php echo htmlspecialchars($rows['first_name']); ?>"
                            placeholder="Enter your first name">
                    </div>

                    <div class="group">
                        <label for="lname">Last Name:</label>
                        <input type="text" id="lname" name="lname"
                            value="<?php echo htmlspecialchars($rows['last_name']); ?>"
                            placeholder="Enter your last name">
                    </div>

                    <div class="group">
                        <label for="gender">Gender:</label>
                        <input type="text" name="gender" value="<?php echo htmlspecialchars($rows['gender']); ?>"
                            readonly>
                    </div>

                    <div class="group">
                        <label for="email">Email:</label>
                        <input type="text" name="email" value="<?php echo htmlspecialchars($rows['email']); ?>"
                            readonly>
                    </div>

                    <div class="group">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" name="phone"
                            value="<?php echo htmlspecialchars($rows['phone']); ?>"
                            placeholder="Enter your phone number">
                    </div>

                    <div class="group">
                        <label for="location">Location:</label>
                        <div class="location_selection">
                            <select name="location">
                                <!-- Show current location as the default selected option -->
                                <option value="" <?php echo empty($location) ? 'selected' : ''; ?>>Please select your
                                    location

                                </option>
                                <option value="Perlis" <?php echo $location === 'Perlis' ? 'selected' : ''; ?>>Perlis
                                </option>
                                <option value="Kedah" <?php echo $location === 'Kedah' ? 'selected' : ''; ?>>Kedah
                                </option>
                                <option value="Johor" <?php echo $location === 'Johor' ? 'selected' : ''; ?>>Johor
                                </option>
                                <option value="Sabah" <?php echo $location === 'Sabah' ? 'selected' : ''; ?>>Sabah
                                </option>
                                <option value="Kelantan" <?php echo $location === 'Kelantan' ? 'selected' : ''; ?>>
                                    Kelantan</option>
                                <option value="Penang" <?php echo $location === 'Penang' ? 'selected' : ''; ?>>Penang
                                </option>
                                <option value="Sarawak" <?php echo $location === 'Sarawak' ? 'selected' : ''; ?>>Sarawak
                                </option>
                                <option value="Malacca" <?php echo $location === 'Malacca' ? 'selected' : ''; ?>>Malacca
                                </option>
                                <option value="Perak" <?php echo $location === 'Perak' ? 'selected' : ''; ?>>Perak
                                </option>
                                <option value="Selangor" <?php echo $location === 'Selangor' ? 'selected' : ''; ?>>
                                    Selangor</option>
                                <option value="Negeri Sembilan" <?php echo $location === 'Negeri Sembilan' ? 'selected' : ''; ?>>Negeri Sembilan</option>
                                <option value="Pahang" <?php echo $location === 'Pahang' ? 'selected' : ''; ?>>Pahang
                                </option>
                                <option value="Terengganu" <?php echo $location === 'Terengganu' ? 'selected' : ''; ?>>
                                    Terengganu</option>
                            </select>
                        </div>
                    </div>

                    <div class="group">
                        <label for="status">Status:</label>
                        <div class="status_selection">
                            <select name="status">
                                <!-- Display current status if it's set, otherwise display a placeholder -->
                                <option value="" <?php echo empty($status) ? 'selected' : ''; ?>>Please select your
                                    Status
                                </option>
                                <option value="single" <?php echo $status === 'single' ? 'selected' : ''; ?>>Single
                                </option>
                                <option value="not single" <?php echo $status === 'not single' ? 'selected' : ''; ?>>Not
                                    Single</option>
                            </select>
                        </div>
                    </div>

                    <div class="group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description"
                            placeholder="Tell us about yourself..."><?php echo htmlspecialchars($rows['description']); ?></textarea>
                    </div>



                    <div class="group">
                        <label for="interests">Interests:</label>
                        <textarea id="interests" name="interests"
                            placeholder="List your interests (e.g., traveling, cooking, reading, playing basketball)..."><?php echo htmlspecialchars($rows['interests']); ?></textarea>
                    </div>

                    <div class="button">
                        <button type="submit" id="update">Update</button>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <script src="view_profile.js"></script>
    <?php mysqli_close($conn); ?>
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
        <p>Welcome to JomTeam! By using our platform, you agree to these Terms of Service. Please read them carefully.
        </p>
    </div>
</div>

<!-- Modal for Privacy Policy -->
<div id="privacyModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('privacy')">&times;</span>
        <h2>Privacy Policy</h2>
        <p>At JomTeam, we respect your privacy. This policy outlines how we handle your personal data when you use our
            platform.</p>
    </div>
</div>

<script src="footer.js"></script>

</html>