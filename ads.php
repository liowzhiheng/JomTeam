<?php
require("config.php");

// Fetch a random active ad
$query = "SELECT file FROM ads WHERE status = 1 ORDER BY RAND() LIMIT 1";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $ad = $result->fetch_assoc();
    $imagePath = "ads/" . $ad["file"];
} else {
    $imagePath = "ads/default.png";
}

$conn->close();
?>

<link rel="stylesheet" href="ads.css">

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