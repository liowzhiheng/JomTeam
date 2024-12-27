<?php
session_start();
require('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    
    if ($new_password === $confirm_password) {
        $sql = "UPDATE user SET password = ? WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $new_password, $email);
        
        if (mysqli_stmt_execute($stmt)) {
            $message = "<div style='padding: 15px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: 500; color: #ffffff; background: rgba(76, 175, 80, 0.2);'>Password successfully updated!</div>";
        }
    } else {
        $message = "<div style='padding: 15px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: 500; color: #ffffff; background: rgba(244, 67, 54, 0.2);'>Passwords do not match!</div>";
        echo "<script>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 3000);
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="reset_password.css">
    <link rel="stylesheet" href="navbar.css">
</head>
<body>

    <div class="profile-content">
        <h1 class="profile-title">Reset Password</h1>
        
        <?php if (isset($message)) echo $message; ?>

        <div class="profile-container">
            <form method="post" action="password_update.php">
                <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                
                <div class="key_in">
                    <input type="password" name="password" id="password" placeholder="New Password" required />
                    <button type="button" id="togglePassword">
                        <img src="IMAGE/close_eye.png" class="picture_password" alt="Toggle Visibility" id="passwordImage" />
                    </button>
                    <span id="passwordFeedback" class="validation-feedback">Password must be at least 8 characters and contain at least 2 of the following: uppercase, lowercase, number, symbol</span>
                    <div id="revealPassword" onclick="togglePasswordVisibility()"></div>
                </div>

                <div class="key_in">
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required />
                    <button type="button" id="toggleConfirmPassword">
                        <img src="IMAGE/close_eye.png" class="picture_password" alt="Toggle Visibility" id="confirmPasswordImage" />
                    </button>
                    <span id="confirmPasswordFeedback" class="validation-feedback">Passwords do not match</span>
                    <div id="revealConfirmPassword" onclick="toggleConfirmPasswordVisibility()"></div>
                </div>

                <div class="button">
                    <button type="submit">Reset Password</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');

        password.addEventListener('input', function() {
            const feedback = document.getElementById('passwordFeedback');
            if (!validatePassword(this.value) && this.value) {
                feedback.textContent = 'Password must be at least 8 characters and contain at least 2 of the following: uppercase, lowercase, number, symbol';
                feedback.style.display = 'block';
            } else {
                feedback.style.display = 'none';
            }
        });

        confirmPassword.addEventListener('input', function() {
            const feedback = document.getElementById('confirmPasswordFeedback');
            if (this.value !== password.value) {
                feedback.textContent = 'Passwords do not match';
                feedback.style.display = 'block';
            } else {
                feedback.style.display = 'none';
            }
        });

        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const passwordImage = document.getElementById('passwordImage');
            
            const isPasswordHidden = passwordField.type === 'password';
            passwordField.type = isPasswordHidden ? 'text' : 'password';
            passwordImage.src = isPasswordHidden ? 'IMAGE/open_eye.png' : 'IMAGE/close_eye.png';
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPasswordField = document.getElementById('confirm_password');
            const confirmPasswordImage = document.getElementById('confirmPasswordImage');
            
            const isPasswordHidden = confirmPasswordField.type === 'password';
            confirmPasswordField.type = isPasswordHidden ? 'text' : 'password';
            confirmPasswordImage.src = isPasswordHidden ? 'IMAGE/open_eye.png' : 'IMAGE/close_eye.png';
        });

        function validatePassword(password) {
            const minLength = password.length >= 8;
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSymbol = /[\W_]/.test(password);

            const conditions = [hasUpper, hasLower, hasNumber, hasSymbol];
            return minLength && conditions.filter(Boolean).length >= 2;
        }
    </script>
</body>
</html>
