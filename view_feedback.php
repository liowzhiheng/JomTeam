<?php
session_start(); // Start up your PHP Session
require("config.php");

$sql = "SELECT feedback.id AS id, 
           CONCAT(user.first_name, ' ', user.last_name) AS name, 
           feedback.title, 
           feedback.description,
           feedback.rating,
           DATE_FORMAT(feedback.created_at, '%y-%m-%d') AS created_at, 
           feedback.status,
           images.file
    FROM feedback
    JOIN user ON feedback.user_id = user.id
    LEFT JOIN images ON user.id = images.user_id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedback & Report</title>
    <link rel="stylesheet" href="view_feedback.css">
</head>

<body>
    <nav class="navbar">
        <a href="#" class="logo">
            <img src="IMAGE/jomteam.png" alt="Logo">
        </a>

        <ul class="menu leftmenu">
            <li><a href="view_user.php">Manage User</a></li>
            <li><a href="view_ads.php">Manage Ads</a></li>
            <li><a href="view_event.php">Manage Event</a></li>
            <li><a href="view_feedback.php">Feedback & Report</a></li>
        </ul>

        <ul class="menu rightmenu">
            <li class="notification"><a href="#notification"><img src="IMAGE/NOTIFICATION.png" alt="Notification"></a>
            </li>
            <li class="logout"><a href="login.php">Log out<img src="IMAGE/LOGOUT.png" alt="Logout"></a></li>
        </ul>
    </nav>

    <h2>Manage Feedback & Report</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Rating</th>
                    <th>Created At</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $image = isset($row['file']) && !empty($row['file']) ? 'uploads/' . $row['file'] : 'image/default.png';
                        echo "<tr onclick='openModal({$row['id']}, \"{$row['name']}\", \"{$row['created_at']}\", \"{$row['title']}\", \"{$row['description']}\", \"{$row['rating']}\", \"{$image}\")'>";
                        echo "<td>" . $counter++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['rating']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No feedback & report available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal-overlay" id="modalOverlay"></div>
    <div class="modal" id="infoModal">
        <div class="modal-header">Details</div>
        <div class="modal-content">
            <div class="container">
                <img id="modalImage" src="" alt="User Image" class="modal-image">
                <div>
                    <p><span id="modalName" class="modal-name"></span></p>
                    <p><span id="modalDate" class="modal-date"></span></p>
                </div>
            </div>
            <p><span id="modalTitle" class="modal-title"></span></p>
            <p><span id="modalDescription" class="modal-description"></span></p>
            <div class="modal-rating">
                <p><strong>Rating:</strong> <span id="modalRating"></span></p>
            </div>
        </div>
        <button class="close-btn" onclick="closeModal()">Close</button>
    </div>

    <script src="view_feedback.js"></script>
</body>

</html>