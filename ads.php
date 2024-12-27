<?php
session_start();
require("config.php");

$user_id = $_SESSION["ID"] ?? null;
$is_premium = $_SESSION["premium"] ?? 0;

if ($user_id === null) {
    header("Location: index.php");
    exit();
}

$imagePath = "ads/default.png"; // Default ad image
if ($is_premium == 0) {
    $query = "SELECT file FROM ads WHERE status = 1 ORDER BY RAND() LIMIT 1";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $ad = $result->fetch_assoc();
        $imagePath = "ads/" . $ad["file"];
    }
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
