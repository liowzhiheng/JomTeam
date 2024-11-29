<?php
session_start();
require('config.php');

// Get all form data
$firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
$lastName = mysqli_real_escape_string($conn, $_POST['last_name']);
$gender = mysqli_real_escape_string($conn, $_POST['gender']);
$dob = mysqli_real_escape_string($conn, $_POST['dob']);
$myemail = mysqli_real_escape_string($conn, $_POST['email']);
$mypassword = mysqli_real_escape_string($conn, $_POST['password']);
$confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm_password']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$countryCode = mysqli_real_escape_string($conn, $_POST['country_code']); // Get the country code
$level = "3";  // Default user level

$errors = [];

// Validate email
if (!filter_var($myemail, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
} else {
    // Check if email already exists
    $sql = "SELECT * FROM user WHERE email='$myemail'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $errors[] = "Email already exists.";
    }
}

// Validate phone number (basic check, add more if needed)
if (!preg_match("/^\+?\d{1,3}[-\s]?\(?\d{1,3}\)?[-\s]?\d{1,4}[-\s]?\d{1,4}[-\s]?\d{1,9}$/", $phone)) {
    $errors[] = "Invalid phone number format.";
}

// Validate passwords
if ($mypassword !== $confirmPassword) {
    $errors[] = "Passwords do not match.";
}

// Password strength check
function check_password($password) {
    if (strlen($password) < 8) {
        return false;
    }
    $uppercase = preg_match('/[A-Z]/', $password);
    $lowercase = preg_match('/[a-z]/', $password);
    $number = preg_match('/[0-9]/', $password);
    $symbol = preg_match('/[\W_]/', $password);
    return ($uppercase + $lowercase + $number + $symbol) >= 2;
}

if (!check_password($mypassword)) {
    $errors[] = "Password must be at least 8 characters and contain at least 2 of the following: uppercase, lowercase, number, symbol.";
}

// If there are errors, show them and return to the registration page
if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
    header("Location: register.php");
    exit();
}

// Concatenate country code and phone number
$fullPhone = $countryCode . $phone; // Combine country code and phone number

// Insert user into the database
$sql2 = "INSERT INTO user (first_name, last_name, gender, birth_date, email, password, phone, level) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
         
$stmt = mysqli_prepare($conn, $sql2);

mysqli_stmt_bind_param($stmt, "ssssssss", 
    $firstName, 
    $lastName, 
    $gender, 
    $dob, 
    $myemail, 
    $mypassword, 
    $fullPhone,  // Save the full phone number with the country code
    $level
);

if (mysqli_stmt_execute($stmt)) {
    // Registration success
    echo '
    <html>
    <head>
        <link rel="stylesheet" href="check_register.css">
        <link rel="stylesheet" href="animation.css">
        <meta http-equiv="refresh" content="3;url=index.php">
    </head>
    <body>
    <div class="background"></div>
    <div class="container">
        <h1>Registration Successful!</h1>
        <img id="randomImage" src="image/login_done_1.jpg" alt="Login Successful" class="login-image" />
    <div></body>
    <script src="background_effect.js" defer></script>
    </html>';
} else {
    $_SESSION['errors'] = ["An error occurred during registration. Please try again."];
    header("Location: register.php");
}

mysqli_close($conn);
?>


<script>
    document.addEventListener('DOMContentLoaded', function () {
    const imageFolder = 'image/'; // Set the path to your folder containing images
    const images = ['login_done_1.jpg', 'login_done_2.jpg', 'login_done_3.jpg', 'login_done_4.jpg']; // List of images in the folder
    const randomImageElement = document.getElementById('randomImage');
    
    // Function to pick a random image
    function setRandomImage() {
        const randomIndex = Math.floor(Math.random() * images.length); // Get a random index
        const randomImage = images[randomIndex]; // Get the image name at the random index
        randomImageElement.src = imageFolder + randomImage; // Set the src of the image element
    }

    setRandomImage(); // Call the function to set a random image on page load

    // Optionally, set the image to change every few seconds:
    // setInterval(setRandomImage, 5000); // Uncomment this to change the image every 5 seconds
});

</script>
