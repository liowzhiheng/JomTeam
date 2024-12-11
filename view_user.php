<?php
session_start(); // Start up your PHP Session

require("config.php"); // Include the database configuration file
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="view_user.css">
</head>

<body>
    <nav class="navbar">
        <a href="#" class="logo">
            <img src="IMAGE/jomteam.png" alt="Logo">
        </a>

        <ul class="menu leftmenu">
            <li><a href="view_user.php">Manage User</a></li>
            <li><a href="view_ads.php">Manage Ads</a></li>
            <li><a href="view_match.php">Manage Match</a></li>
            <li><a href="view_feedback.php">Feedback & Report</a></li>
        </ul>

        <ul class="menu rightmenu">
            <li class="notification"><a href="#notification"><img src="IMAGE/NOTIFICATION.png" alt="Notification"></a>
            </li>
            <li class="logout"><a href="index.php">Log out<img src="IMAGE/LOGOUT.png" alt="Logout"></a></li>
        </ul>
    </nav>

    <?php
    // Display session message if it exists
    if (isset($_SESSION['message'])) {
        echo "<p id='message' class='success-message'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']); // Clear the message after displaying it
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
                <label for="verified">Email Verified:</label>
                <select name="verified">
                    <option value="">Any</option>
                    <option value="1" <?php echo (isset($_GET['verified']) && $_GET['verified'] == '1') ? 'selected' : ''; ?>>Yes</option>
                    <option value="0" <?php echo (isset($_GET['verified']) && $_GET['verified'] == '0') ? 'selected' : ''; ?>>No</option>
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
    $verified = isset($_GET['verified']) ? $_GET['verified'] : '';
    $premium = isset($_GET['premium']) ? $_GET['premium'] : '';

    // Fetch all users from the user table
    $sql = "SELECT * FROM user WHERE 1=1";

    // Check if a search term is provided
    if (!empty($_GET['search'])) {
        // Escape the search string to prevent SQL injection
        $search = $conn->real_escape_string($_GET['search']);
        // Append the search condition to the query
        $sql .= " AND CONCAT(first_name, ' ', last_name) LIKE '%$search%'";
    }

    if (!empty($_GET['level'])) {
        $level = $conn->real_escape_string($_GET['level']);
        $sql .= " AND level = '$level'";
    }

    if (!empty($_GET['verified'])) {
        $verified = $conn->real_escape_string($_GET['verified']);
        $sql .= " AND verified = '$verified'";
    }

    if (!empty($_GET['premium'])) {
        $premium = $conn->real_escape_string($_GET['premium']);
        $sql .= " AND premium = '$premium'";
    }

    // Execute the query
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div class='table-container'>";
        echo "<table class='user-table'>";
        echo "<tr>
        <th>No</th>
        <th>Username</th>
        <th>Role</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Verification</th>
        <th>Creation Time</th>
        <th>Premium</th>
        <th>Action</th>
      </tr>";

        // Initialize counter for sequential numbering
        $counter = 1;

        // Output data of each row (dynamic part)
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $counter++ . "</td>"; // Sequential numbering
            echo "<td>" . strtoupper(htmlspecialchars($row["first_name"] . ' ' . $row["last_name"])) . "</td>";
            $role = ($row['level'] == 1) ? 'admin' : 'user';
            echo "<td>" . htmlspecialchars($role) . "</td>";
            echo "<td>" . htmlspecialchars($row["phone"] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
            $verify = ($row['verified'] == 1) ? 'Yes' : 'No';
            echo "<td>" . htmlspecialchars($verify) . "</td>";
            echo "<td>" . date('Y/m/d', strtotime($row["created_at"])) . "</td>";
            $premium = ($row['premium'] == 1) ? 'Yes' : 'No';
            echo "<td>" . htmlspecialchars($premium) . "</td>";
            echo "<td>";

            // Show "Remove" button only if the user's level is not 1
            if ($row['level'] != 1) {
                echo "<form action='delete_user.php' method='POST' class='remove-form'>";
                echo "<input type='hidden' name='id' value='" . htmlspecialchars($row["id"]) . "'>";
                echo "<input type='submit' value='Remove' class='remove-button' onclick='return confirm(\"Are you sure you want to delete this user?\")'>";
                echo "</form>";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "<p class='p'>No users found.</p>";
    }
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