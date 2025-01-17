<?php
session_start();
require("config.php");

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';
$nextOrder = $order === 'ASC' ? 'desc' : 'asc';
$validColumns = ['name', 'created_at'];
if (!in_array($sort, $validColumns)) {
    $sort = 'id';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="view_user.css">
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
    // Display session message if it exists
    if (isset($_SESSION['message'])) {
        echo "<p id='message' class='success-message'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>

    <h2>Manage Users</h2>
    <div class="function-box">
        <form action="" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search by username"
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <input type="submit" value="Search">
        </form>

        <button class="filter-btn" onclick="toggleFilterBox()">Filter</button>
        <div id="filter-box" class="filter-box">
            <form action="" method="GET">
                <label for="level">Role:</label>
                <select name="level">
                    <option value="">All Roles</option>
                    <option value="1" <?php echo (isset($_GET['level']) && $_GET['level'] == '1') ? 'selected' : ''; ?>>
                        Admin</option>
                    <option value="3" <?php echo (isset($_GET['level']) && $_GET['level'] == '3') ? 'selected' : ''; ?>>
                        User</option>
                </select>
                <br>
                <label for="email_verified">Email Verified:</label>
                <select name="email_verified">
                    <option value="">Any</option>
                    <option value="1" <?php echo (isset($_GET['email_verified']) && $_GET['email_verified'] == '1') ? 'selected' : ''; ?>>Yes</option>
                    <option value="0" <?php echo (isset($_GET['email_verified']) && $_GET['email_verified'] == '0') ? 'selected' : ''; ?>>No</option>
                </select>
                <br>
                <label for="verified">Premium:</label>
                <select name="premium">
                    <option value="">Any</option>
                    <option value="1" <?php echo (isset($_GET['premium']) && $_GET['premium'] == '1') ? 'selected' : ''; ?>>Yes</option>
                    <option value="0" <?php echo (isset($_GET['premium']) && $_GET['premium'] == '0') ? 'selected' : ''; ?>>No</option>
                </select>
                <br>
                <div class="button-container">
                    <input type="submit" value="Apply">
                </div>
            </form>
        </div>
    </div>

    <?php
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $level = isset($_GET['level']) ? $_GET['level'] : '';
    $verified = isset($_GET['email_verified']) ? $_GET['email_verified'] : '';
    $premium = isset($_GET['premium']) ? $_GET['premium'] : '';

    $sql = "SELECT id, CONCAT(first_name, ' ', last_name) AS name, level, email, phone, email_verified, premium, created_at FROM user WHERE 1=1";

    // Check if a search term is provided
    if (!empty($_GET['search'])) {
        $search = $conn->real_escape_string($_GET['search']);
        $sql .= " AND CONCAT(first_name, ' ', last_name) LIKE '%$search%'";
    }

    if (!empty($_GET['level'])) {
        $level = $conn->real_escape_string($_GET['level']);
        $sql .= " AND level = '$level'";
    }

    if ($verified !== '') {
        if (strtolower($verified) === 'yes') {
            $verified = 1;
        } elseif (strtolower($verified) === 'no') {
            $verified = 0;
        }
        $sql .= " AND email_verified = '$verified'";
    }

    if ($premium !== '') {
        if (strtolower($premium) === 'yes') {
            $premium = 1;
        } elseif (strtolower($premium) === 'no') {
            $premium = 0;
        }
        $sql .= " AND premium = '$premium'";
    }

    $sql .= " ORDER BY $sort $order";
    $result = $conn->query($sql);
    ?>

    <div class="table-container">
        <table class="user-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>
                        <a href="?sort=name&order=<?= $nextOrder ?>">Username
                            <?= $sort === 'name' ? ($order === 'ASC' ? '▲' : '▼') : '' ?>
                        </a>
                    </th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Verification</th>
                    <th>
                        <a href="?sort=created_at&order=<?= $nextOrder ?>">Creation Time
                            <?= $sort === 'created_at' ? ($order === 'ASC' ? '▲' : '▼') : '' ?>
                        </a>
                    </th>
                    <th>Premium</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $counter = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='select' style='cursor: pointer;' onclick=\"document.getElementById('form_" . $row["id"] . "').submit();\">";
                        echo "<td>" . $counter++ . "</td>";
                        echo "<td>" . strtoupper(htmlspecialchars($row["name"])) . "</td>";
                        $role = ($row['level'] == 1) ? 'admin' : 'user';
                        echo "<td>" . htmlspecialchars($role) . "</td>";
                        echo "<td>" . htmlspecialchars($row["phone"] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                        $verify = ($row['email_verified'] == 1) ? 'Yes' : 'No';
                        echo "<td>" . htmlspecialchars($verify) . "</td>";
                        echo "<td>" . date('Y/m/d', strtotime($row["created_at"])) . "</td>";
                        $premium = ($row['premium'] == 1) ? 'Yes' : 'No';
                        echo "<td>" . htmlspecialchars($premium) . "</td>";
                        echo "<td>";

                        // Show "Remove" button only if the user's level is not 1
                        if ($row['level'] != 1) {
                            echo "<form action='delete_user.php' method='POST' class='remove-form'>";
                            echo "<input type='hidden' name='id' value='" . htmlspecialchars($row["id"]) . "'>";
                            echo "<input type='submit' value='Delete' class='remove-button' onclick='return confirm(\"Are you sure you want to delete this user?\")'>";
                            echo "</form>";
                        }
                        echo "</td>";
                        echo "</tr>";
                        echo "<form id='form_" . $row["id"] . "' action='update_user.php' method='POST' style='display: none;'>";
                        echo "<input type='hidden' name='user_id' value='" . htmlspecialchars($row["id"]) . "'>";
                        echo "</form>";
                    }
                } else {
                    echo "<tr><td colspan='9' class='p'>No users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
    $conn->close();
    ?>

    <script>
        function toggleFilterBox() {
            const filterBox = document.getElementById('filter-box');
            filterBox.classList.toggle('show');
        }

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
