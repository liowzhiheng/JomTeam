<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body, html {
            height: 100%; /* Ensure the body takes up full height of the screen */
            margin: 0; /* Remove default margins */
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            color: white;
            background: rgba(82, 82, 212);
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .container {
            text-align: center; /* Center the text */
        }

        .login-image {
            width: 80%; /* Makes the image responsive */
            max-width: 400px; /* Limits the maximum width */
            margin-top: 20px; /* Add some spacing between the text and the image */
        }

        .message {
            font-size: 1.5rem;
            margin: 20px 0;
            padding: 10px;
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        session_start();
        require('config.php');

        // Get the verification token from the URL
        if (isset($_GET['token'])) {
            $token = mysqli_real_escape_string($conn, $_GET['token']);
            
            // Check if the token exists in the database
            $sql = "SELECT * FROM user WHERE verification_token = '$token' AND email_verified = 0";
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                // Token is valid, verify the email
                $sql2 = "UPDATE user SET email_verified = 1 WHERE verification_token = '$token'";
                if (mysqli_query($conn, $sql2)) {
                    echo "<h1 class='message'>Email Verified Successfully!</h1>";
                } else {
                    echo "<h1 class='message'>Error verifying email. Please try again later.</h1>";
                }
            } else {
                echo "<h1 class='message'>Invalid or expired verification link.</h1>";
            }
        } else {
            echo "<h1 class='message'>No token provided.</h1>";
        }

        mysqli_close($conn);
        ?>
    </div>
</body>
</html>
