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
            <li><a href="#user">Manage User</a></li>
            <li><a href="#content">Manage Content</a></li>
            <li><a href="#event">Manage Event</a></li>
            <li><a href="#feedback">Feedback & Report</a></li>
        </ul>

        <ul class="menu rightmenu">
            <li class="notification"><a href="#notification"><img src="IMAGE/NOTIFICATION.png" alt="Notification"></a>
            </li>
            <li class="logout"><a href="login.php">Log out<img src="IMAGE/LOGOUT.png" alt="Logout"></a></li>
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
    <form action="" method="GET" class="search-form">
        <input type="text" name="search" placeholder="Search by username"
            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <input type="submit" value="Search">
    </form>

    <?php
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    // Fetch all users from the database
    $sql = "SELECT * FROM user";
    if (!empty($search)) {
        // Escape the search string to prevent SQL injection
        $search = $conn->real_escape_string($search);
        $sql .= " WHERE email LIKE '%$search%'";
    }
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div class='table-container'>";
        echo "<table class='user-table'>";
        echo "<tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Creation Time</th>
            <th>Premium</th>
            <th>Action</th>
          </tr>";

        // Output data of each row (dynamic part)
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["password"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["password"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["password"]) . "</td>";
            echo "<td>";
            echo "<form action='delete_user.php' method='POST' class='remove-form'>";
            echo "<input type='hidden' name='id' value='" . htmlspecialchars($row["id"]) . "'>";
            echo "<input type='submit' value='Remove' class='remove-button' onclick='return confirm(\"Are you sure you want to delete this user?\")'>";
            echo "</form>";
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