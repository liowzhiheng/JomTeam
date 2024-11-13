<?php
session_start();

// If the user is not logged in send him/her to the login form
if ($_SESSION["Login"] != "YES")
  header("Location: login.php");

if ($_SESSION["LEVEL"] == 1) {
  require("config.php");

  $email = $_POST["email"];
  $password = $_POST["password"];
  $level = $_POST["level"];

  // Insert the new user into the database
  $sql = "INSERT INTO user (email, password, level) VALUES ('$email', '$password', '$level')";

  if (mysqli_query($conn, $sql)) {
    echo
      '<html>
    <head>
      <link rel="stylesheet" href="message.css">
    </head>
    <body>
      <h1>New user added successfully</h1>
      <br>
      <p class="button"><a href="main.php">Go back to main page</a></p>
    </body>
    </html>';
  } else {
    echo
      '<html>
    <head>
      <link rel="stylesheet" href="styles_message.css">
    </head>
    <body>
      <h1>Error: ' . $sql . '<br>' . mysqli_error($conn) . '</h1>
      <br>
      <p class="button"><a href="main.php">Go back to main page</a></p>
      </body>
    </html>';
  }
  mysqli_close($conn);
}
