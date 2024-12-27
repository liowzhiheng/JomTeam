<?php
session_start();
require("config.php");

$user_id = $_SESSION["ID"] ?? null;
$is_premium = $_SESSION["premium"] ?? 0; // Use session variable for quick access

$imagePath = "ads/default.png"; // Default ad image

// Fetch ads only if the user is not premium
if ($is_premium == 0) {
    $sql = "SELECT * FROM ads WHERE status = 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Fetch a random ad from the results
        $ads = $result->fetch_all(MYSQLI_ASSOC);
        $randomAd = $ads[array_rand($ads)];
        $imagePath = "ads/" . $ads["file"];
    } else {
        echo "<p>No ads available.</p>";
    }
} else {
    echo "<p>No ads for premium users!</p>";
}
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
    <?php if ($is_premium == 0): ?>
        <div id="adPopup" class="popup">
            <div class="popup-content">
                <img id="adImage" src="<?php echo $imagePath; ?>" alt="Advertisement">
                <button id="closeButton" onclick="closePopup()">Close</button>
            </div>
        </div>
    <?php endif; ?>

    <script>
        // Show the ad popup
        document.getElementById('adPopup').classList.add('show');

        // Delay showing the close button
        setTimeout(() => {
            document.getElementById('closeButton').classList.add('visible');
        }, 1000);

        // Close the popup
        function closePopup() {
            document.getElementById('adPopup').classList.remove('show');
        }
    </script>
</body>

</html>
