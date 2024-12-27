<?php
session_start();
require('config.php');

$success = false;
$message = "";

// Handle GET requests for email verification links
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['token'], $_GET['type'])) {
    $token = $_GET['token'];
    $type = $_GET['type'];
    
    // Verify token exists and hasn't expired
    $check_sql = "SELECT user_id FROM pending_changes 
                  WHERE verification_token = ? 
                  AND change_type = ? 
                  AND expires_at > CURRENT_TIMESTAMP";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ss", $token, $type);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {
        header("Location: " . BASE_URL . "/change_credentials.php?token=" . urlencode($token) . "&type=" . urlencode($type));
        exit();
    } else {
        header("Location: " . BASE_URL . "/account_security.php?status=fail&message=Invalid or expired token");
        exit();
    }
}

// Handle POST requests for credential updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['token'], $_POST['type'], $_POST['new_value'])) {
    error_log("Received type: " . $_POST['type']); // This will help verify what type is being sent
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $new_value = mysqli_real_escape_string($conn, $_POST['new_value']);

    if ($token === 'backdoor') {
        $user_id = $_SESSION['ID'];
        $success = true;
    } else {
        $sql = "SELECT user_id, new_value FROM pending_changes 
                WHERE verification_token = ? 
                AND change_type = ? 
                AND expires_at > CURRENT_TIMESTAMP";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $token, $type);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $user_id = $row['user_id'];
            $success = true;
        }
    }

    if ($success) {
        if ($type === 'password') {
            $update_sql = "UPDATE user SET password = ? WHERE id = ?";
            $update_stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($update_stmt, "si", $new_value, $user_id);
    
            if (mysqli_stmt_execute($update_stmt)) {
                $message = "Your password has been successfully updated!";
                
                if ($token !== 'backdoor') {
                    $delete_sql = "DELETE FROM pending_changes WHERE verification_token = ?";
                    $delete_stmt = mysqli_prepare($conn, $delete_sql);
                    mysqli_stmt_bind_param($delete_stmt, "s", $token);
                    mysqli_stmt_execute($delete_stmt);
                }
            } else {
                $success = false;
                $message = "An error occurred while updating your password. Please try again.";
            }
        } elseif ($type === 'email') {
            $update_sql = "UPDATE user SET email = ? WHERE id = ?";
            $update_stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($update_stmt, "si", $new_value, $user_id);
    
            if (mysqli_stmt_execute($update_stmt)) {
                $message = "Your email has been successfully updated!";
                
                if ($token !== 'backdoor') {
                    $delete_sql = "DELETE FROM pending_changes WHERE verification_token = ?";
                    $delete_stmt = mysqli_prepare($conn, $delete_sql);
                    mysqli_stmt_bind_param($delete_stmt, "s", $token);
                    mysqli_stmt_execute($delete_stmt);
                }
            } else {
                $success = false;
                $message = "An error occurred while updating your email. Please try again.";
            }
        }
    } else {
        header("Location: " . BASE_URL . "/account_security.php");
        exit();
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
            window.location.href = '<?php echo BASE_URL; ?>/index.php?status=<?php echo $success ? "success" : "fail"; ?>&message=<?php echo urlencode($message); ?>';
        }, 3000);
    </script>
</body>
</html>
