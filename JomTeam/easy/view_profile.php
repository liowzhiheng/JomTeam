<?php
session_start(); // Start up your PHP Session

// Check if the user is logged in
if ($_SESSION["Login"] != "YES") {
    header("Location: index.php");
    exit();
}

// Check if USER_ID is set
if (!isset($_SESSION["ID"])) {
    echo "User ID is not set in the session.";
    exit();
}

require("config.php"); // Include the database configuration file

// Fetch the user's profile information from the database
$user_id = $_SESSION["ID"];
$sql = "SELECT * FROM profile WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);

// Check if the user has a profile
if (mysqli_num_rows($result) > 0) {
    $rows = mysqli_fetch_assoc($result);
} else {
    // Initialize $rows with empty values if no profile is found
    $rows = [
        'name' => '',
        'ic' => '',
        'gender' => '',
        'matric' => '',
        'phone' => '',
        'email' => ''
    ];
}
mysqli_close($conn);

echo "<h2 class = welcome><b>Welcome " . $_SESSION["USER"] . "</b></h2> ";
?>

<!DOCTYPE html>
<html>

<head>
    <title>Viewing Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>

<body>
    <br>
    <div class="wrapper">
        <div class="container">
            <h1>Profile Settings</h1>
            <form method="post" action="update_profile.php">
                <div class="content">
                    <div class="personal_info">
                        <div class="input-box">
                            <span class="details">Full Name</span>
                            <input name="name" type="text" id="name" size="30" value="<?php echo ($rows['name']); ?>">
                        </div>
                        <div class="input-box">
                            <span class="details">IC No.</span>
                            <input name="ic" type="text" id="ic" size="30" value="<?php echo ($rows['ic']); ?>">
                        </div>
                        <div class="input-box">
                            <span class="details">Gender</span>
                            <input name="gender" type="text" id="gender" size="30" value="<?php echo ($rows['gender']); ?>">
                        </div>
                        <div class="input-box">
                            <span class="details">Matric No.</span>
                            <input name="matric" type="text" id="matric" size="30" value="<?php echo ($rows['matric']); ?>">
                        </div>
                        <div class="input-box">
                            <span class="details">Phone Number</span>
                            <input name="phone" type="text" id="phone" size="30" value="<?php echo ($rows['phone']); ?>">
                        </div>
                        <div class="input-box">
                            <span class="details">Email</span>
                            <input name="email" type="text" id="email" size="30" value="<?php echo htmlspecialchars($rows['email']); ?>">
                        </div>
                    </div>
                    <div>
                        <input type="submit" class=button value="Update">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <a href='main.php' class="button">Return Main Page</a>
</body>

</html>