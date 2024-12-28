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

function check_password($password)
{
    if (strlen($password) < 8)
        return false;
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
                        <input type="password" value="<?php echo htmlspecialchars($user['password']); ?>" readonly
                            id="current-password">
                            <div class="password-container">
                            <button type="button" class="password-toggle"id="togglePassword"><img
                                src="IMAGE/close_eye.png" class="picture_password" alt="Toggle Visibility"
                                id="passwordImage"></button>
                            </div>
                   
                    </div>
                    <form method="post" action="account_security.php" class="reset-form">
                        <button type="submit" name="initiate_password_reset" class="reset-button">Reset
                            Password</button>
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


            // Toggle password visibility
        document.addEventListener('DOMContentLoaded', function () {
            const passwordField = document.getElementById('current-password');
            const togglePasswordButton = document.getElementById('togglePassword');
            const passwordImage = document.getElementById('passwordImage');

            if (togglePasswordButton && passwordField && passwordImage) {
                togglePasswordButton.addEventListener('click', function () {
                    // Toggle password visibility
                    const isPasswordHidden = passwordField.type === 'password';
                    passwordField.type = isPasswordHidden ? 'text' : 'password';

                    // Update the image and alt attribute
                    passwordImage.src = isPasswordHidden ? 'IMAGE/open_eye.png' : 'IMAGE/close_eye.png';
                    passwordImage.alt = isPasswordHidden ? 'Hide Password' : 'Show Password';
                });
            }
        });
        </script>
    </div>
</body>

<footer style="margin-top:120px">
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
        <p>
            Welcome to JomTeam! By using our platform, you agree to these Terms of Service. Please read them carefully.
            If you do not agree with any part of these terms, you may not use our services.
        </p>
        <h3>1. User Accounts</h3>
        <p>Users must provide accurate and up-to-date information during registration.</p>
        <h3>2. Privacy</h3>
        <p>Your privacy is important to us. We are committed to protecting your personal information.</p>
        <h3>3. Acceptable Use</h3>
        <p>You agree not to use the platform for illegal, harmful, or disruptive purposes.
            Harassment, hate speech, or inappropriate content is strictly prohibited.</p>
        <h3>4. Match Creation and Participation</h3>
        <p>Users creating matches must ensure the information provided (e.g., location, time) is accurate.
            Users participating in matches must adhere to the agreed-upon rules and schedules.</p>
        <h3>5. Payment and Premium Services</h3>
        <p>Premium features may be offered with a subscription. Fees are non-refundable unless specified otherwise.</p>

    </div>
</div>

<!-- Modal for Privacy Policy -->
<div id="privacyModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('privacy')">&times;</span>
        <h2>Privacy Policy</h2>
        <p>At JomTeam, we respect your privacy. This policy outlines how we handle your personal data when you use our
            platform.</p>

        <h3>1. Information Collection</h3>
        <p>We collect information you provide when you register, interact with our platform, and use our services.</p>

        <h3>2. Data Usage</h3>
        <p>Your data is used to improve our services and provide a personalized experience.</p>

        <h3>3. How We Use Your Information<br></h3>
        <ul>
            <li>To provide and improve our services.</li>
            <li>To personalize your experience and match recommendations.</li>
            <li>To communicate updates, promotions, or changes to the platform.</li>
        </ul>

        <h3>4. Data Sharing</h3>
        <ul>
            <li>We do not sell your personal information.</li>
            <li>Data may be shared with third-party providers (e.g., payment processors) necessary to deliver our
                services.</li>
        </ul>

        <h3>5. Security</h3>
        <p>We use advanced encryption and security measures to protect your data. However, no system is completely
            secure.</p>

        <h3>6. Your Rights</h3>
        <ul>
            <li>You can access, modify, or delete your personal information by contacting support.</li>
            <li>You can opt out of promotional communications at any time.</li>
        </ul>

        <h3>7. Cookies</h3>
        <p>Our platform uses cookies to enhance your browsing experience. You can manage cookie preferences in your
            browser settings.</p>

        <h3>8. Changes to Privacy Policy</h3>
        <p>We may update this Privacy Policy periodically. Changes will be posted on this page with the revised date.
        </p>
    </div>
</div>

<script src="footer.js"></script>

</html>
