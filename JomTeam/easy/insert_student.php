<?php
session_start(); // Start up your PHP Session


// If the user is not logged in, send them to the login form
if ($_SESSION["Login"] != "YES") {
    header("Location: login.php");
    exit(); // Ensure script stops executing after redirection
}

$user_id = $_SESSION["ID"];

$name = $_POST["name"];
$ic = $_POST["ic"];
$matric = $_POST["matric"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$gender = $_POST["gender"];
$address = $_POST["address"];
$course = $_POST["course"];
$yearOfStudy = $_POST["yearOfStudy"];
$cgpa = $_POST["cgpa"];
$industry = $_POST["industry"];
$location = $_POST["location"];
$startDate = $_POST["startDate"];
$duration = $_POST["duration"];
$relevantCourse = $_POST["relevantCourse"];
$skill = $_POST["skill"];
$experience = $_POST["experience"];
$project = $_POST["project"];
$add1 = $_POST["add1"];
$add2 = $_POST["add2"];

$matric = strtoupper($matric);  // Convert matric to uppercase

require("config.php"); // Include your database connection file

$sql = "INSERT INTO student(user_id, name, ic, matric, phone, email, gender, address, course, yearOfStudy, cgpa, industry, location, startDate, duration, relevantCourse, skill, experience, project, add1, add2, status) 
        VALUES ('$user_id','$name','$ic','$matric','$phone','$email','$gender','$address','$course','$yearOfStudy','$cgpa','$industry','$location','$startDate','$duration','$relevantCourse','$skill','$experience','$project','$add1','$add2', 'pending')";

$result = mysqli_query($conn, $sql);

// Check if query was successful
if ($result) {
    $message = "New record created successfully";
} else {
    $error_message = "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form Submission</title>
    <link rel="stylesheet" href="styles_message.css">
</head>
<body>
    <div class="message-container">
        <?php if (isset($message)) : ?>
            <h1>Success!</h1>
            <h1><?php echo $message; ?></p>
        <?php elseif (isset($error_message)) : ?>
            <h1>Error!</h1>
            <h1><?php echo $error_message; ?></p>
        <?php endif; ?>
        
		<br>
		<p><a class=button href="application_form.php">Click here to insert again</a></p>
        <br>
		<p><a class= button href="main.php">Go back to main page</a></p>
    </div>
</body>
</html>