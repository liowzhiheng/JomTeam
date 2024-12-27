<?php
require("config.php");

$user_id = $_SESSION["ID"] ?? null;
$is_premium = $_SESSION["premium"] ?? 0; // Use session variable for quick access

// Fetch a random active ad
$query = "SELECT file FROM ads WHERE status = 1 ORDER BY RAND() LIMIT 1";
$result = $conn->query($query);

$imagePath = "ads/default.png"; // Default ad image

if ($is_premium == 0) {
    if ($result && $result->num_rows > 0) {
        $ad = $result->fetch_assoc();
        $imagePath = "ads/" . $ad["file"];
    }
} else {
    // If the user is premium, you might want to exit or do something else
    exit();
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
    const adPopup = document.getElementById('adPopup');
    if (adPopup) {
        adPopup.classList.add('show');
        setTimeout(() => {
            document.getElementById('closeButton').classList.add('visible');
        }, 1000);
    }

    function closePopup() {
        if (adPopup) {
            adPopup.classList.remove('show');
        }
    }
</script>

</body>
</html>
