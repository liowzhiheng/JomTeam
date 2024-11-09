<?php
session_start(); // Start up your PHP Session

require('config.php'); // Include the database configuration file

// email and password sent from form
$myemail = $_POST["email"];
$mypassword = $_POST["password"];
$level = "3";

// Sanitize user inputs to prevent SQL injection
$myemail = mysqli_real_escape_string($conn, $myemail);
$mypassword = mysqli_real_escape_string($conn, $mypassword);

$sql = "SELECT * FROM user WHERE email='$myemail'";

$result = mysqli_query($conn, $sql);

$rows = mysqli_fetch_assoc($result);


// mysqli_num_rows is counting table rows
$count = mysqli_num_rows($result);

function check_password($password)
{
  // Check if password length is at least 8 characters
  if (strlen($password) < 8) {
    return false;
  }

  // Define patterns for different character types
  $uppercase = preg_match('/[A-Z]/', $password);
  $lowercase = preg_match('/[a-z]/', $password);
  $number = preg_match('/[0-9]/', $password);
  $symbol = preg_match('/[\W_]/', $password); // \W matches any non-word character, _ is included for underscores

  // Check if at least two of the conditions are met
  $conditionsMet = $uppercase + $lowercase + $number + $symbol;
  return $conditionsMet >= 2;
}

if (check_password($mypassword) && $count != 1) {
  $sql2 = "INSERT INTO user (email, password, level) VALUES ('$myemail', '$mypassword', '$level')";
}
if (mysqli_query($conn, $sql2)) {
  echo 'Succesful !!!!!!!!!!!!';
  echo
    '<html>
      <head>
        <link rel="stylesheet" href="message.css">
      </head>
      <body>
        <h1>New user added successfully</h1>
        <br>
        <p class="button"><a href="login.php">Go back to login page</a></p>
      </body>
      </html>';


} else {
  echo
    header("Location: register.php");
}


mysqli_close($conn);