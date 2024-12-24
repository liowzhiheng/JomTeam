<?php
session_start();
require("config.php");

//Users
$sqlUsers = "SELECT COUNT(*) AS active_users FROM user WHERE DATE(last_activity) = CURDATE()";
$resultUsers = $conn->query($sqlUsers);
$rowUsers = $resultUsers->fetch_assoc();
$activeUsers = $rowUsers['active_users'] ?? 0;

//Ads
$sqlAds = "SELECT COUNT(*) AS active_ads FROM ads WHERE status = 1";
$resultAds = $conn->query($sqlAds);
$rowAds = $resultAds->fetch_assoc();
$activeAds = $rowAds['active_ads'] ?? 0;

//Matches
$sqlMatches = "SELECT COUNT(*) AS upcoming_matches FROM gamematch WHERE start_date > CURDATE()";
$resultMatches = $conn->query($sqlMatches);
$rowMatches = $resultMatches->fetch_assoc();
$upcomingMatches = $rowMatches['upcoming_matches'] ?? 0;

//Feedbacks
$sqlFeedback = "SELECT COUNT(*) AS new_feedback FROM feedback WHERE DATE(created_at) = CURDATE()";
$resultFeedback = $conn->query($sqlFeedback);
$rowFeedback = $resultFeedback->fetch_assoc();
$newFeedback = $rowFeedback['new_feedback'] ?? 0;
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
        const activeUsers = <?php echo $activeUsers; ?>;
        const activeAds = <?php echo $activeAds; ?>;
        const upcomingMatches = <?php echo $upcomingMatches; ?>;
        const newFeedback = <?php echo $newFeedback; ?>;
    </script>
    <script src="dashboard.js" defer></script>
</head>

<body>
    <nav class="navbar">
        <a href="dashboard.php" class="logo">
            <img src="IMAGE/jomteam.png" alt="Logo">
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
                        <span class="count"><?php echo $upcomingMatches; ?></span>
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
            <div class="activity-list">
                <div class="activity-item">
                    <p><strong>New User:</strong> John Doe has joined.</p>
                </div>
                <div class="activity-item">
                    <p><strong>New Match:</strong> Match between Team A and Team B scheduled.</p>
                </div>
                <div class="activity-item">
                    <p><strong>New Feedback:</strong> Feedback received on Match scheduling.</p>
                </div>
            </div>
        </section>

        <section class="details">
            <h2>Detailed Insights</h2>
            <div class="details-grid">
                <div class="details-item">
                    <h3>User Logins</h3>
                    <p>Display a table or graph of recent user login activity here.</p>
                </div>
                <div class="details-item">
                    <h3>Ad Performance</h3>
                    <p>Include details or graphs about ad performance metrics.</p>
                </div>
            </div>
        </section>
    </main>
</body>

</html>