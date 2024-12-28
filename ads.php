<?php
require("config.php");

$user_id = $_SESSION["ID"] ?? null;

if ($user_id === null) {
    echo "User ID is not set.";
    exit();
}

// Query to fetch the premium status of the user
$query = "SELECT premium FROM user WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the result contains any rows
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $premium = $row["premium"];

    if ($premium == 1) {
        // If the user is premium, skip showing ads
        return;
    }
    
    // Fetch the ad if the user is not premium
    $adQuery = "SELECT file FROM ads WHERE status = 1 ORDER BY RAND() LIMIT 1";
    $adResult = $conn->query($adQuery);

    if ($adResult && $adResult->num_rows > 0) {
        $ad = $adResult->fetch_assoc();
        $imagePath = "ads/" . $ad["file"];
    } else {
        $imagePath = "ads/default.png";
    }
} else {
    echo "User not found or no premium status.";
    exit();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="ads.css">
</head>

<body>

    <div id="adPopup" class="popup">
        <div class="popup-content">
            <img id="adImage" src="<?php echo $imagePath; ?>" alt="Advertisement">
            <button id="closeButton" onclick="closePopup()">Close</button>
        </div>
    </div>

    <script>
        document.getElementById('adPopup').classList.add('show');

        setTimeout(() => {
            document.getElementById('closeButton').classList.add('visible');
        }, 1000);

        function closePopup() {
            document.getElementById('adPopup').classList.remove('show');
        }
    </script>
</body>

</html>
