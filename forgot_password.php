<?php
session_start();
require('config.php');

$message = '';
$status = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists
    $sql = "SELECT id FROM user WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['id'];
        $token = bin2hex(random_bytes(32));
        
        // Store token in pending_changes
        $sql = "INSERT INTO pending_changes (user_id, change_type, verification_token) VALUES (?, 'password', ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $user_id, $token);
        
        if (mysqli_stmt_execute($stmt)) {
            $reset_link = "http://localhost/JomTeam/change_credentials.php?token=$token&type=password";
            $to = $email;
            $subject = "Password Reset Request";
            $message = "Click the following link to reset your password: $reset_link";
            $headers = "From: jomteam@example.com";
            
            mail($to, $subject, $message, $headers);
            
            $status = 'success';
            $message = "Password reset link has been sent to your email.";
        }
    } else {
        $status = 'error';
        $message = "Email not found in our records.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="forgot_password.css">
    <link rel="stylesheet" href="navbar.css">
</head>

<body>
    <div class="profile-content">
        <h1 class="profile-title">Forgot Password</h1>
        
        <?php if ($message): ?>
            <div class="alert <?php echo $status; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="profile-container">
            <form method="post" id="emailForm">
                <div class="group">
                    <label>Enter your email address:</label>
                    <input type="email" name="email" id="emailInput" required>
                </div>
                <div class="button">
                    <button type="submit">Send Reset Link</button>
                </div>
            </form>
        </div>

        <div class="backdoor-container">
            <button onclick="redirectToReset()" class="backdoor-link">Developer Reset</button>
        </div>

        <script>
        function redirectToReset() {
            const email = document.getElementById('emailInput').value;
            if(email) {
                window.location.href = 'reset_password.php?email=' + encodeURIComponent(email);
            }
        }
        </script>
</body>
</html>
