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

    // Check if email is verified (skip for admin users)
    if ($rows["email_verified"] == 0 && $rows["level"] != 1) {
        // Email is not verified and user is not admin
        $_SESSION["Login"] = "NO";
        $_SESSION["LoginError"] = "Your email address is not verified. Please check your email.";
        header("Location: index.php?error=email_not_verified");
        exit();
    }

    // Verify the password
    if (!strcmp($_POST["password"], $rows["password"])) {
        // Password is correct
        $_SESSION["Login"] = "YES";
        $_SESSION["USER"] = $rows["first_name"] . " " . $rows["last_name"];
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
            <link rel="stylesheet" href="animation.css">
            <meta http-equiv="refresh" content="3;url=<?php echo ($_SESSION["LEVEL"] == 1) ? 'dashboard.php' : 'main.php'; ?>">
            <style>
        .background {
            position: fixed;
            /* Stays fixed in place behind content */
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            /* Pushes it behind everything else */
            pointer-events: none;
            /* Prevent interaction with shapes */
        }
        .container {
            position: relative;
            z-index: 1;
            /* Ensures content stays above the background */
            padding: 20px;
        }
    </style>

        </head>

        <body>
            <div class="background"></div>
            <div class="container">
                <h2>Hi! <strong><?php echo htmlspecialchars($_SESSION["USER"]); ?></strong></h2>
                <img id="randomImage" alt="Login Successful" class="login-image" />
            </div>
        </body>
        <script src="random_pic.js"></script>
        <script src="background_effect.js" defer></script>

        </html>
        <?php
    } else {
        // Password is incorrect
        $_SESSION["Login"] = "NO";
        header("Location: index.php?error=password");
        exit();
    }
} else {
    // Email not found
    $_SESSION["Login"] = "NO";
    header("Location: index.php?error=email");
    exit();
}

mysqli_close($conn);
?>
