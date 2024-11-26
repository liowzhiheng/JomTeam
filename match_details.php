<?php
// match_details.php

// Include database connection
require("config.php");

// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $match_id = $_GET['id'];

    // Prepare and execute query to fetch match details
    $query = "SELECT * FROM gamematch WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $match_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the match exists
    if ($result->num_rows > 0) {
        $match = $result->fetch_assoc();
    } else {
        // If match doesn't exist, show error message
        echo "Match not found.";
        exit;
    }
} else {
    // If 'id' is not provided in URL, show error message
    echo "No match selected.";
    exit;
}

// Fetch current number of players and max players
$current_players = $match['current_players'];
$max_players = $match['max_players'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="find_match.css">
    <link rel="stylesheet" href="footer.css">
</head>

<body>
    <nav class="navbar">
        <!-- Navbar content here... -->
    </nav>

    <section class="match-details-section">
        <h2>Match Details: <?php echo htmlspecialchars($match['match_title']); ?></h2>
        <div class="match-details-container">
            <img src="gamematch/<?php echo $match['file']; ?>" alt="Match Image" style="width: 200px; height: auto;">
            <p><strong>Game Type:</strong> <?php echo htmlspecialchars($match['game_type']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($match['location']); ?></p>
            <p><strong>Skill Level Required:</strong> <?php echo htmlspecialchars($match['skill_level_required']); ?>
            </p>
            <p><strong>Start Date:</strong> <?php echo date("F j, Y, g:i A", strtotime($match['start_date'])); ?></p>
            <p><strong>End Date:</strong> <?php echo date("F j, Y, g:i A", strtotime($match['end_date'])); ?></p>
            <p><strong>Max Players:</strong> <?php echo htmlspecialchars($match['max_players']); ?> |
                <strong>Current Players:</strong> <?php echo htmlspecialchars($match['current_players']); ?>
            </p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($match['status']); ?></p>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($match['description'])); ?></p>
        </div>

        <!-- Join Match Section -->
        <?php if ($current_players < $max_players): ?>
            <form action="join_match.php" method="POST">
                <input type="hidden" name="match_id" value="<?php echo $match['id']; ?>">
                <button type="submit" class="join-btn">Join Match</button>
            </form>
        <?php else: ?>
            <p>Sorry, this match is full.</p>
        <?php endif; ?>
    </section>

    <script src="footer.js"></script>
</body>

<footer>
    <!-- Footer content here... -->
</footer>

</html>