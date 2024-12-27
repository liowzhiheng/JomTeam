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
        $verification_token = bin2hex(random_bytes(16));
       
        // Store token in pending_changes with timestamp
        $sql = "INSERT INTO pending_changes (user_id, change_type, verification_token, created_at) 
                VALUES (?, 'password', ?, NOW())";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $user_id, $verification_token);
       
        if (mysqli_stmt_execute($stmt)) {
            $verification_link = "http://jomteam.com/verify_change.php?token=$verification_token&type=password";
            $subject = "Password Reset Request";
            $message = "Click the link to reset your password:\n\n$verification_link";
            $headers = "From: no-reply@jomteam.com";
           
            mail($email, $subject, $message, $headers);
           
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
                <?php echo htmlspecialchars($message); ?>
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
    </div>
</body>
</html>