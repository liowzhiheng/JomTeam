<?php
session_start();
require('config.php');

// Get email and password from form
$myemail = mysqli_real_escape_string($conn, $_POST["email"]);

// First check if email exists
$sql = "SELECT * FROM user WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $myemail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {
    $rows = mysqli_fetch_assoc($result);

    // Verify the password
    if (!strcmp($_POST["password"], $rows["password"])) {
        // Password is correct
        $_SESSION["Login"] = "YES";
        $_SESSION["USER"] = $rows["email"];
        $_SESSION["ID"] = $rows["id"];
        $_SESSION["LEVEL"] = $rows["level"];

        // Check if profile exists
        $check_profile_sql = "SELECT * FROM profile WHERE user_id = ?";
        $stmt_profile = mysqli_prepare($conn, $check_profile_sql);
        mysqli_stmt_bind_param($stmt_profile, "i", $rows["id"]);
        mysqli_stmt_execute($stmt_profile);
        $profile_result = mysqli_stmt_get_result($stmt_profile);

        if (mysqli_num_rows($profile_result) == 0) {
            // Create profile and image records using prepared statements
            $insert_profile_sql = "INSERT INTO profile (user_id) VALUES (?)";
            $stmt_insert_profile = mysqli_prepare($conn, $insert_profile_sql);
            mysqli_stmt_bind_param($stmt_insert_profile, "i", $rows["id"]);
            mysqli_stmt_execute($stmt_insert_profile);

            $insert_image_sql = "INSERT INTO images (user_id) VALUES (?)";
            $stmt_insert_image = mysqli_prepare($conn, $insert_image_sql);
            mysqli_stmt_bind_param($stmt_insert_image, "i", $rows["id"]);
            mysqli_stmt_execute($stmt_insert_image);
        }

        // Show success message and redirect options
        ?>
        <!DOCTYPE html>
        <html>

        <head>
            <link rel="stylesheet" href="check_login.css">
            <meta http-equiv="refresh" content="3;url=<?php echo ($_SESSION["LEVEL"] == 1) ? 'view_user.php' : 'main.php'; ?>">
        </head>

        <body>
            <div class="container">
                <h2>Hi! <strong><?php echo htmlspecialchars($_SESSION["USER"]); ?></strong></h2>
                <img id="randomImage" src="image/login_done_1.jpg" alt="Login Successful" class="login-image" />
            </div>
        </body>

        </html>
        <?php
    } else {
        // Password is incorrect
        $_SESSION["Login"] = "NO";
        header("Location: login.php?error=password");
        exit();
    }
} else {
    // Email not found
    $_SESSION["Login"] = "NO";
    header("Location: login.php?error=email");
    exit();
}

mysqli_close($conn);
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const imageFolder = 'image/'; // Set the path to your folder containing images
    const images = ['login_done_1.jpg', 'login_done_2.jpg', 'login_done_3.jpg', 'login_done_4.jpg']; // List of images in the folder
    const randomImageElement = document.getElementById('randomImage');
    
    // Function to pick a random image
    function setRandomImage() {
        const randomIndex = Math.floor(Math.random() * images.length); // Get a random index
        const randomImage = images[randomIndex]; // Get the image name at the random index
        randomImageElement.src = imageFolder + randomImage; // Set the src of the image element
    }

    setRandomImage(); // Call the function to set a random image on page load

    // Optionally, set the image to change every few seconds:
    // setInterval(setRandomImage, 5000); // Uncomment this to change the image every 5 seconds
});

</script>