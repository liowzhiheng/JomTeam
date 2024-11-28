<?php
require("config.php");

// Fetch all ads from the database
$sql = "SELECT * FROM ads ORDER BY id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Ads</title>
    <link rel="stylesheet" href="view_ads.css">
</head>

<body>
    <nav class="navbar">
        <a href="#" class="logo">
            <img src="IMAGE/jomteam.png" alt="Logo">
        </a>

        <ul class="menu leftmenu">
        <li><a href="view_user.php">Manage User</a></li>
            <li><a href=view_ads.php>Manage Ads</a></li>
            <li><a href="view_event.php">Manage Event</a></li>
            <li><a href="view_feedback.php">Feedback & Report</a></li>
        </ul>

        <ul class="menu rightmenu">
            <li class="notification"><a href="#notification"><img src="IMAGE/NOTIFICATION.png" alt="Notification"></a>
            </li>
            <li class="logout"><a href="index.php">Log out<img src="IMAGE/LOGOUT.png" alt="Logout"></a></li>
        </ul>
    </nav>

    <?php
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        switch ($status) {
            case 'success':
                echo '<p id="message" class="message success">Ad added successfully!</p>';
                break;
            case 'updated':
                echo '<p id="message" class="message success">Ad updated successfully!</p>';
                break;
            case 'deleted':
                echo '<p id="message" class="message deleted">Ad deleted successfully!</p>';
                break;
            case 'fail':
                echo '<p id="message" class="message fail">Something went wrong. Please try again.</p>';
                break;
            default:
                break;
        }
    }
    ?>

    <h1>Manage Ads</h1>

    <!-- Form for Adding New Ad -->
    <div class="ads-wrapper">
        <div class="box">
            <form method="POST" action="update_ads.php" enctype="multipart/form-data">
                <h2>Add New Ad</h2>
                <input type="text" name="title" placeholder="Ad Title" required>
                <textarea name="description" placeholder="Ad Description" required></textarea>
                <input type="file" name="image" />
                <label class="checkbox-label">
                    <input type="checkbox" name="status"> Active
                </label>
                <button type="submit" name="add_ad">Add Ad</button>
            </form>
        </div>

        <!-- List of Existing Ads -->
        <div class="box">
            <h2>Existing Ads</h2>
            <?php if ($result->num_rows > 0): ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <form method="POST" action="update_ads.php" enctype="multipart/form-data">
                                    <!-- Hidden ID for update -->
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <td><?php echo $counter++; ?></td>
                                    <td><input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>"
                                            required></td>
                                    <td><textarea name="description"
                                            required><?php echo htmlspecialchars($row['description']); ?></textarea></td>
                                    <td>
                                        <?php if ($row['file']): ?>
                                            <img src="ads/<?php echo $row['file']; ?>" alt="Ad Image"
                                                style="width: 100px; height: auto;">
                                        <?php else: ?>
                                            <p>No image</p>
                                        <?php endif; ?>
                                        <input type="file" name="image" />
                                    </td>
                                    <td><input type="checkbox" name="status" <?php echo $row['status'] == 1 ? 'checked' : ''; ?>>
                                        Active</td>
                                    <td>
                                        <button type="submit" name="update_ad">Update</button>
                                        <a href="update_ads.php?delete_id=<?php echo $row['id']; ?>"
                                            onclick="return confirm('Are you sure you want to delete this ad?')">
                                            <button type="button" class="btn delete">Delete</button>
                                        </a>
                                    </td>
                                </form>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p><br>No ads available to display.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const message = document.getElementById('message');
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 2000);
        }
    </script>
</body>

</html>
