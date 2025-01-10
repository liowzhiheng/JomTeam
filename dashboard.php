<?php
session_start();
require("config.php");

//Users
$sqlTotalUsers = "SELECT COUNT(*) AS total_users FROM user";
$resultTotalUsers = $conn->query($sqlTotalUsers);
$rowTotalUsers = $resultTotalUsers->fetch_assoc();
$totalUsers = $rowTotalUsers['total_users'] ?? 0;

$sqlUsers = "SELECT COUNT(*) AS active_users FROM user WHERE DATE(last_activity) = CURDATE()";
$resultUsers = $conn->query($sqlUsers);
$rowUsers = $resultUsers->fetch_assoc();
$activeUsers = $rowUsers['active_users'] ?? 0;

//Ads
$sqlTotalAds = "SELECT COUNT(*) AS total_ads FROM ads";
$resultTotalAds = $conn->query($sqlTotalAds);
$rowTotalAds = $resultTotalAds->fetch_assoc();
$totalAds = $rowTotalAds['total_ads'] ?? 0;

$sqlAds = "SELECT COUNT(*) AS active_ads FROM ads WHERE status = 1";
$resultAds = $conn->query($sqlAds);
$rowAds = $resultAds->fetch_assoc();
$activeAds = $rowAds['active_ads'] ?? 0;

//Matches
$sqlMatches = "
    SELECT 
        DAYNAME(start_date) AS day_name, 
        COUNT(*) AS matches_on_day 
    FROM 
        gamematch 
    WHERE 
        CONCAT(start_date, ' ', start_time) > NOW() 
    GROUP BY 
        DAYNAME(start_date), start_date
    ORDER BY 
        FIELD(DAYNAME(start_date), 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
";
$resultMatches = $conn->query($sqlMatches);
$upcomingMatchesByDay = [];
while ($row = $resultMatches->fetch_assoc()) {
    $upcomingMatchesByDay[$row['day_name']] = ($upcomingMatchesByDay[$row['day_name']] ?? 0) + $row['matches_on_day'];
}

//Feedbacks
$sqlFeedback = "SELECT COUNT(*) AS new_feedback FROM feedback WHERE DATE(created_at) = CURDATE()";
$resultFeedback = $conn->query($sqlFeedback);
$rowFeedback = $resultFeedback->fetch_assoc();
$newFeedback = $rowFeedback['new_feedback'] ?? 0;

//Latest
$sqlRecentUsers = "SELECT first_name, last_name, created_at FROM user ORDER BY created_at DESC LIMIT 3";
$resultRecentUsers = $conn->query($sqlRecentUsers);

$sqlRecentMatches = "SELECT g.start_date, u.first_name, u.last_name 
                     FROM gamematch g 
                     JOIN user u ON g.user_id = u.id 
                     ORDER BY g.start_date DESC 
                     LIMIT 3";
$resultRecentMatches = $conn->query($sqlRecentMatches);

$sqlRecentFeedback = "SELECT title, created_at FROM feedback ORDER BY created_at DESC LIMIT 3";
$resultRecentFeedback = $conn->query($sqlRecentFeedback);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Pass PHP variable to JavaScript -->
    <script>
        const totalUsers = <?php echo $totalUsers; ?>;
        const activeUsers = <?php echo $activeUsers; ?>;
        const activeUsersPercentage = totalUsers > 0 ? (activeUsers / totalUsers) * 100 : 0;
        const inactiveUsersPercentage = 100 - activeUsersPercentage;
        const totalAds = <?php echo $totalAds; ?>;
        const activeAds = <?php echo $activeAds; ?>;
        const activeAdsPercentage = totalAds > 0 ? (activeAds / totalAds) * 100 : 0;
        const inactiveAdsPercentage = 100 - activeAdsPercentage;
        const upcomingMatchesByDay = <?php echo json_encode($upcomingMatchesByDay); ?>;
        const newFeedback = <?php echo $newFeedback; ?>;
    </script>
    <script src="dashboard.js" defer></script>
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
        </ul>

        <ul class="menu rightmenu">
            <li class="notification"><a href="#notification"><img src="IMAGE/NOTIFICATION.png" alt="Notification"></a>
            </li>
            <li class="logout"><a href="logout.php" onclick="return confirm('Are you sure want to logout?')">Log
                    out<img src="IMAGE/LOGOUT.png" alt="Logout"></a></li>
        </ul>
    </nav>

    <main class="dashboard-container">
        <section class="overview">
            <h2>Dashboard Overview</h2>
            <div class="card-container">
                <div class="card">
                    <h3>Users</h3>
                    <div class="card-data">
                        <span class="count"><?php echo $activeUsers; ?></span>
                        <span class="label">Active Users Today</span>
                    </div>
                    <canvas id="activeUsersChart" width="400" height="400"></canvas>
                </div>
                <div class="card">
                    <h3>Ads</h3>
                    <div class="card-data">
                        <span class="count"><?php echo $activeAds; ?></span>
                        <span class="label">Active Ads</span>
                    </div>
                    <canvas id="activeAdsChart" width="400" height="400"></canvas>
                </div>
                <div class="card">
                    <h3>Matches</h3>
                    <div class="card-data">
                        <span class="count"><?php echo array_sum($upcomingMatchesByDay); ?></span>
                        <span class="label">Upcoming Matches</span>
                    </div>
                    <canvas id="upcomingMatchesChart" width="400" height="400"></canvas>
                </div>
                <div class="card">
                    <h3>Feedback</h3>
                    <div class="card-data">
                        <span class="count"><?php echo $newFeedback; ?></span>
                        <span class="label">New Feedback</span>
                    </div>
                    <canvas id="newFeedbackChart" width="400" height="400"></canvas>
                </div>
            </div>
        </section>

        <section class="latest-activity">
            <h2>Latest Activity</h2>
            <div class="activity-container">
                <div class="activity-list">
                    <h3>New Users</h3>
                    <?php while ($user = $resultRecentUsers->fetch_assoc()) { ?>
                        <div class="activity-item">
                            <p><strong><?php echo strtoupper($user['first_name'] . ' ' . $user['last_name']); ?></strong>
                                joined
                                on
                                <?php echo date("d M Y", strtotime($user['created_at'])); ?>.
                            </p>
                        </div>
                    <?php } ?>

                    <h3>New Matches</h3>
                    <?php while ($match = $resultRecentMatches->fetch_assoc()) { ?>
                        <div class="activity-item">
                            <p><strong>Match Created By:</strong>
                                <?php echo strtoupper($match['first_name'] . ' ' . $match['last_name']); ?> on
                                <?php echo date("d M Y", strtotime($match['start_date'])); ?>.
                            </p>
                        </div>
                    <?php } ?>

                    <h3>New Feedback</h3>
                    <?php while ($feedback = $resultRecentFeedback->fetch_assoc()) { ?>
                        <div class="activity-item">
                            <p><strong>Feedback:</strong> "<?php echo $feedback['title']; ?>" received on
                                <?php echo date("d M Y", strtotime($feedback['created_at'])); ?>.
                            </p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    </main>
</body>

</html>