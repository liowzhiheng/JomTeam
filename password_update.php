<?php
session_start();
require('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = mysqli_real_escape_string($conn, $_POST['password']);
    
    $sql = "UPDATE user SET password = ? WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $new_password, $email);
    
    if (mysqli_stmt_execute($stmt)) {
        $success = true;
        $message = "Your password has been successfully updated!";
    } else {
        $success = false;
        $message = "An error occurred while updating your password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            background: rgba(82, 82, 212);
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .container {
            text-align: center;
            padding: 20px;
        }

        .login-image {
            width: 80%;
            max-width: 400px;
            margin-top: 20px;
        }

        .message {
            font-size: 1.5rem;
            margin: 20px 0;
            padding: 10px;
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.3);
        }
    </style>
    <link rel="stylesheet" href="animation.css">
</head>
<body>
    <div class="container">
        <h1><?php echo $message; ?></h1>
        <img id="randomImage" alt="<?php echo $success ? 'Success' : 'Error'; ?>" class="login-image" />
    </div>

    <script src="random_pic.js"></script>
    <script src="background_effect.js" defer></script>
    <script>
        setTimeout(() => {
            window.location.href = 'index.php';
        }, 3000);
    </script>
</body>
</html>
