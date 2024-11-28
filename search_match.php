<?php
// search_match.php

// Include your database connection
require("config.php");

// Initialize query
$query = "SELECT * FROM gamematch WHERE 1=1";

// Collect search inputs
$searchGameType = isset($_GET['game_type']) ? mysqli_real_escape_string($conn, $_GET['game_type']) : '';
$searchLocation = isset($_GET['area']) ? mysqli_real_escape_string($conn, $_GET['area']) : '';
$searchDate = isset($_GET['date']) ? mysqli_real_escape_string($conn, $_GET['date']) : '';

// Add conditions dynamically
if (!empty($searchGameType)) {
    $query .= " AND game_type = '$searchGameType'";
}

if (!empty($searchLocation)) {
    $query .= " AND location LIKE '%$searchLocation%'";
}

if (!empty($searchDate)) {
    $query .= " AND start_date = '$searchDate'";
}

// Add ordering for better results
$query .= " ORDER BY created_at DESC";

// Execute the query
$result = mysqli_query($conn, $query);

// Fetch results
if (mysqli_num_rows($result) > 0) {
    $matches = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $matches = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="find_match.css">
</head>
<body>
    <h1>Search Results</h1>
    <div class="grid-container">
        <?php if (!empty($matches)): ?>
            <?php foreach ($matches as $match): ?>
                <div class="grid-item">
                    <img src="gamematch/<?php echo htmlspecialchars($match['file']); ?>" alt="Match Image" style="width: 200px; height: 200px;">
                    <p class="info_title"><?php echo htmlspecialchars($match['match_title']); ?></p>
                    <p class="info"><?php echo htmlspecialchars($match['game_type']); ?></p>
                    <p class="info">Location: <?php echo htmlspecialchars($match['location']); ?></p>
                    <p class="info">Date: <?php echo htmlspecialchars($match['start_date']); ?></p>
                    <a href="match_details.php?id=<?php echo $match['id']; ?>" class="view-all-btn">View Details</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No matches found for your search criteria.</p>
        <?php endif; ?>
    </div>
</body>
</html>
