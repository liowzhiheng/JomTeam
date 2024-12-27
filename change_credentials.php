<?php
session_start();
require("config.php");

$errors = [];
$proceed_to_form = false;

// Handle GET request to verify token and type
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (!isset($_GET['token']) || !isset($_GET['type'])) {
        $errors[] = "Invalid request. Missing parameters.";
    } else {
        $token = mysqli_real_escape_string($conn, $_GET['token']);
        $type = mysqli_real_escape_string($conn, $_GET['type']);

        // Validate token and type
        $sql = "SELECT * FROM pending_changes WHERE verification_token = ? AND change_type = ? AND verified = 0";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $token, $type);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $proceed_to_form = true;
        } else {
            $errors[] = "Invalid or expired token.";
        }
    }
}

// Handle POST request to process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $new_value = mysqli_real_escape_string($conn, $_POST['new_value']);

    if ($type === 'email') {
        if (!filter_var($new_value, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        } else {
            $check_sql = "SELECT id FROM user WHERE email = ?";
            $check_stmt = mysqli_prepare($conn, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "s", $new_value);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);

            if (mysqli_num_rows($check_result) > 0) {
                $errors[] = "This email is already in use.";
            }
        }
    } elseif ($type === 'password') {
        if (strlen($new_value) < 8) {
            $errors[] = "Password must be at least 8 characters.";
        }

        if ($_POST['new_value'] !== $_POST['confirm_value']) {
            $errors[] = "Passwords do not match.";
        }
    }

    // If no errors, redirect to verify_change.php
    if (empty($errors)) {
        echo "<form id='redirectForm' action='verify_change.php' method='post'>
                <input type='hidden' name='token' value='" . htmlspecialchars($token) . "'>
                <input type='hidden' name='type' value='" . htmlspecialchars($type) . "'>
                <input type='hidden' name='new_value' value='" . htmlspecialchars($new_value) . "'>
              </form>
              <script>document.getElementById('redirectForm').submit();</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change <?php echo ucfirst($type ?? ''); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="container">
        <h1>Change <?php echo ucfirst($type ?? ''); ?></h1>

        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($proceed_to_form): ?>
            <form method="post">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">

                <?php if ($type === 'email'): ?>
                    <div>
                        <label for="new_value">New Email:</label>
                        <input type="email" id="new_value" name="new_value" required>
                    </div>
                <?php else: ?>
                    <div>
                        <label for="new_value">New Password:</label>
                        <input type="password" id="new_value" name="new_value" required>
                    </div>
                    <div>
                        <label for="confirm_value">Confirm Password:</label>
                        <input type="password" id="confirm_value" name="confirm_value" required>
                    </div>
                <?php endif; ?>

                <button type="submit">Update <?php echo ucfirst($type); ?></button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
