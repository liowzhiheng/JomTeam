<?php
session_start(); // Start up your PHP Session
require("config.php");

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';
$nextOrder = $order === 'ASC' ? 'desc' : 'asc';
$validColumns = ['match_title', 'game_type', 'location', 'start_date', 'start_time'];
if (!in_array($sort, $validColumns)) {
    $sort = 'id';
}

$sql = "SELECT id, match_title, game_type, current_players, max_players, location, start_date, start_time FROM gamematch ORDER BY $sort $order";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Match</title>
    <link rel="stylesheet" href="view_match.css">
    <link rel="shortcut icon" type="image/jpg" href="IMAGE/favicon.png"/>
</head>

<body>
    <nav class="navbar">
        <a href="dashboard.php" class="logo">
            <img src="IMAGE/jomteam_new_logo.png" alt="Logo">
        </a>

        <ul class="menu leftmenu">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="view_user.php">Manage User</a></li>
            <li><a href="view_ads.php">Manage Ads</a></li>
            <li><a href="view_match.php">Manage Match</a></li>
            <li><a href="view_feedback.php">Feedback & Report</a></li>
            <li><a href="view_frame.php">Frame</a></li>
        </ul>

        <ul class="menu rightmenu">
            <li class="notification"><a href="#notification"><img src="IMAGE/NOTIFICATION.png" alt="Notification"></a>
            </li>
            <li class="logout"><a href="logout.php" onclick="return confirm('Are you sure want to logout?')">Log
                    out<img src="IMAGE/LOGOUT.png" alt="Logout"></a></li>
        </ul>
    </nav>

    <?php
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        if ($status === 'deleted') {
            echo '<p id="message" class="message deleted">Match deleted successfully!</p>';
        } elseif ($status === 'fail') {
            echo '<p id="message" class="message fail">Something went wrong. Please try again.</p>';
        }
    }
    ?>

    <h2>Manage Match</h2>
    <div class="function-box">
        <form action="" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search by match title"
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <input type="submit" value="Search">
        </form>
    </div>

    <?php
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $sql = "SELECT id, match_title, game_type, current_players, max_players, location, start_date, start_time FROM gamematch ORDER BY $sort $order";
    if (!empty($_GET['search'])) {
        $search = $conn->real_escape_string($_GET['search']);
        $sql .= " AND match_title LIKE '%$search%'";
    }
    $result = $conn->query($sql);
    ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>
                        <a href="?sort=match_title&order=<?= $nextOrder ?>">Match Title
                            <?= $sort === 'match_title' ? ($order === 'ASC' ? '▲' : '▼') : '' ?>
                        </a>
                    </th>
                    <th>
                        <a href="?sort=game_type&order=<?= $nextOrder ?>">Game Type
                            <?= $sort === 'game_type' ? ($order === 'ASC' ? '▲' : '▼') : '' ?>
                        </a>
                    </th>
                    <th>Current Players</th>
                    <th>Max Players</th>
                    <th>
                        <a href="?sort=location&order=<?= $nextOrder ?>">Location
                            <?= $sort === 'location' ? ($order === 'ASC' ? '▲' : '▼') : '' ?>
                        </a>
                    </th>
                    <th>
                        <a href="?sort=start_date&order=<?= $nextOrder ?>">Start Date
                            <?= $sort === 'start_date' ? ($order === 'ASC' ? '▲' : '▼') : '' ?>
                        </a>
                    </th>
                    <th>
                        <a href="?sort=start_time&order=<?= $nextOrder ?>">Start Time
                            <?= $sort === 'start_time' ? ($order === 'ASC' ? '▲' : '▼') : '' ?>
                        </a>
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='select' onclick=\"document.getElementById('form_" . $row["id"] . "').submit();\">";
                        echo "<td>" . $counter++ . "</td>";
                        echo "<td>" . $row['match_title'] . "</td>";
                        echo "<td>" . $row['game_type'] . "</td>";
                        echo "<td>" . $row['current_players'] . "</td>";
                        echo "<td>" . $row['max_players'] . "</td>";
                        echo "<td>" . $row['location'] . "</td>";
                        echo "<td>" . $row['start_date'] . "</td>";
                        echo "<td>" . $row['start_time'] . "</td>";
                        echo "<td>";
                        echo "<form action='delete_match.php' method='POST' class='remove-form'>";
                        echo "<input type='hidden' name='id' value='" . htmlspecialchars($row["id"]) . "'>";
                        echo "<input type='submit' value='Delete' class='remove-button' onclick='return confirm(\"Are you sure you want to delete this match?\")'>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                        echo "<form id='form_" . $row["id"] . "' action='update_match.php' method='POST' style='display: none;'>";
                        echo "<input type='hidden' name='match_id' value='" . htmlspecialchars($row["id"]) . "'>";
                        echo "</form>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No matchs found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Check if the message element exists and hide it after 2 seconds
        const messageElement = document.getElementById('message');
        if (messageElement) {
            setTimeout(() => {
                messageElement.style.display = 'none';
            }, 2000);
        }
    </script>

</body>

</html>
