<?php
session_start();
require("config.php");

// Handle sorting parameters
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';
$nextOrder = $order === 'ASC' ? 'desc' : 'asc';
$validColumns = ['name', 'rating', 'created_at', 'status'];
if (!in_array($sort, $validColumns)) {
    $sort = 'id';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_id'])) {
    $feedback_id = $_POST['feedback_id'];

    $feedback_id = intval($feedback_id);
    $update_sql = "UPDATE feedback SET status = 'read' WHERE id = $feedback_id";
    mysqli_query($conn, $update_sql);

}

$sql = "SELECT feedback.id AS id, 
           CONCAT(user.first_name, ' ', user.last_name) AS name, 
           feedback.title, 
           feedback.description,
           feedback.rating,
           DATE_FORMAT(feedback.created_at, '%Y-%m-%d') AS created_at, 
           feedback.status,
           images.file
    FROM feedback
    JOIN user ON feedback.user_id = user.id
    LEFT JOIN images ON user.id = images.user_id
    ORDER BY $sort $order";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedback & Report</title>
    <link rel="stylesheet" href="view_feedback.css">
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
            echo '<p id="message" class="message deleted">Feedback deleted successfully!</p>';
        } elseif ($status === 'fail') {
            echo '<p id="message" class="message fail">Something went wrong. Please try again.</p>';
        }
    }
    ?>

    <h2>Manage Feedback & Report</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>
                        <a href="?sort=name&order=<?= $nextOrder ?>">Username
                            <?= $sort === 'name' ? ($order === 'ASC' ? '▲' : '▼') : '' ?>
                        </a>
                    </th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>
                        <a href="?sort=rating&order=<?= $nextOrder ?>">Rating
                            <?= $sort === 'rating' ? ($order === 'ASC' ? '▲' : '▼') : '' ?>
                        </a>
                    </th>
                    <th>
                        <a href="?sort=created_at&order=<?= $nextOrder ?>">Created At
                            <?= $sort === 'created_at' ? ($order === 'ASC' ? '▲' : '▼') : '' ?>
                        </a>
                    </th>
                    <th>
                        <a href="?sort=status&order=<?= $nextOrder ?>">Status
                            <?= $sort === 'status' ? ($order === 'ASC' ? '▲' : '▼') : '' ?>
                        </a>
                    </th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $image = isset($row['file']) && !empty($row['file']) ? 'uploads/' . $row['file'] : 'image/default.png';
                        echo "<tr class='select' onclick='openModal({$row['id']}, \"{$row['name']}\", \"{$row['created_at']}\", \"{$row['title']}\", \"{$row['description']}\", \"{$row['rating']}\", \"{$image}\", event)'>";
                        echo "<td>" . $counter++ . "</td>";
                        echo "<td>" . strtoupper(htmlspecialchars($row['name'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['rating']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td>";
                        echo "<form action='delete_feedback.php' method='POST' class='remove-form' onsubmit='return confirm(\"Are you sure you want to delete this feedback?\");'>";
                        echo "<input type='hidden' name='id' value='" . htmlspecialchars($row["id"]) . "'>";
                        echo "<button type='submit' class='remove-button' onclick='event.stopPropagation();'>Delete</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No feedback & report available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <form id="updateStatus" method="POST" style="display: none;">
        <input type="hidden" id="feedbackIdInput" name="feedback_id" value="">
    </form>

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
        <button class="close-btn" onclick="closeModal(event)">Close</button>
    </div>

    <script>
        const messageElement = document.getElementById('message');
        if (messageElement) {
            setTimeout(() => {
                messageElement.style.display = 'none';
            }, 2000);
        }
    </script>
    <script src="view_feedback.js"></script>
</body>

</html>
