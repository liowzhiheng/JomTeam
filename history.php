<?php
session_start(); // Start the PHP session

// Check if USER_ID is set
if (!isset($_SESSION["ID"])) {
    echo "User ID is not set in the session.";
    exit();
}

require("config.php");

// Fetch user and profile data
$user_id = $_SESSION['ID'];

//created match list
$query2 = "SELECT * FROM gamematch WHERE user_id = '$user_id'";
$result2 = mysqli_query($conn, $query2);
if (mysqli_num_rows($result2) > 0) {
    $matches = mysqli_fetch_all($result2, MYSQLI_ASSOC);
} else {
    $matches = [];
}

//joined match list
$query3 = "SELECT match_id FROM match_participants WHERE user_id = '$user_id'";
$result3 = mysqli_query($conn, $query3);
if (mysqli_num_rows($result3) > 0) {
    $match_ids = [];
    while ($row = mysqli_fetch_assoc($result3)) {
        $match_ids[] = $row['match_id'];
    }
    $match_ids_string = implode(',', $match_ids);
    $query4 = "SELECT * FROM gamematch WHERE id IN ($match_ids_string) ";
    $result4 = mysqli_query($conn, $query4);
    $joined_match = mysqli_fetch_all($result4, MYSQLI_ASSOC);
} else {
    $matches_id = [];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
</head>

<body>
<?php if ($_SESSION["LEVEL"] != 1) { ?>
    <?php
    include('navbar.php');
    include('ads.php');
    ?>
    <div>
        <div class="grid-section">
            <h1 class="created_match_title">Created Match</h1>
            <div class="grid-container">
                <?php if (!empty($matches)): ?>
                    <?php foreach ($matches as $match): ?>
                        <div class="grid-item">
                            <img src="gamematch/<?php echo htmlspecialchars($match['file']); ?>" alt="Match Image"
                                style="width: 200px; height: 200px;">
                            <p class="info_title"><?php echo htmlspecialchars($match['match_title']); ?></p>
                            <p class="info"><?php echo htmlspecialchars($match['game_type']); ?></p>
                            <p class="info">Location: <?php echo htmlspecialchars($match['location']); ?></p>
                            <p class="info">Date: <?php echo htmlspecialchars($match['start_date']); ?></p>
                            <p class="info">Time: <?php echo htmlspecialchars($match['start_time']); ?></p>
                            <a href="match_details.php?id=<?php echo $match['id']; ?>" class="view-all-btn">View Details</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="grid-section">
                        <p class="info_title">No match created.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div>
            <h1 class="created_match_title">Joined Match</h1>
            <div class=".grid-section">
                <div class="grid-container">
                    <?php if (!empty($joined_match)): ?>
                        <?php foreach ($joined_match as $match): ?>
                            <div class="grid-item">
                                <img src="gamematch/<?php echo htmlspecialchars($match['file']); ?>" alt="Match Image"
                                    style="width: 200px; height: 200px;">
                                <p class="info_title"><?php echo htmlspecialchars($match['match_title']); ?></p>
                                <p class="info"><?php echo htmlspecialchars($match['game_type']); ?></p>
                                <p class="info">Location: <?php echo htmlspecialchars($match['location']); ?></p>
                                <p class="info">Date: <?php echo htmlspecialchars($match['start_date']); ?></p>
                                <p class="info">Time: <?php echo htmlspecialchars($match['start_time']); ?></p>
                                <a href="match_details.php?id=<?php echo $match['id']; ?>" class="view-all-btn">View Details</a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="grid-section">
                            <p class="info_title">No match joined.</p>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
    <?php } ?>
</body>
