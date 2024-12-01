<?php
session_start(); // Start up your PHP Session
require("config.php");

$sql = "SELECT id, match_title, game_type, current_players, max_players, location, start_date, start_time, status FROM gamematch";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Match</title>
    <link rel="stylesheet" href="view_event.css">
</head>

<body>
    <nav class="navbar">
        <a href="#" class="logo">
            <img src="IMAGE/jomteam.png" alt="Logo">
        </a>

        <ul class="menu leftmenu">
            <li><a href="view_user.php">Manage User</a></li>
            <li><a href="view_ads.php">Manage Ads</a></li>
            <li><a href="view_event.php">Manage Match</a></li>
            <li><a href="view_feedback.php">Feedback & Report</a></li>
        </ul>

        <ul class="menu rightmenu">
            <li class="notification"><a href="#notification"><img src="IMAGE/NOTIFICATION.png" alt="Notification"></a>
            </li>
            <li class="logout"><a href="index.php">Log out<img src="IMAGE/LOGOUT.png" alt="Logout"></a></li>
        </ul>
    </nav>

    <h2>Manage Event</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Match Title</th>
                    <th>Game Type</th>
                    <th>Current Players</th>
                    <th>Max Players</th>
                    <th>Location</th>
                    <th>Start Date</th>
                    <th>Start Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $counter++ . "</td>";
                        echo "<td>" . $row['match_title'] . "</td>";
                        echo "<td>" . $row['game_type'] . "</td>";
                        echo "<td>" . $row['current_players'] . "</td>";
                        echo "<td>" . $row['max_players'] . "</td>";
                        echo "<td>" . $row['location'] . "</td>";
                        echo "<td>" . $row['start_date'] . "</td>";
                        echo "<td>" . $row['start_time'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No events found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>