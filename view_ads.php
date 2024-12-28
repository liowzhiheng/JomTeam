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
                <div class="upload-area" id="uploadArea" onclick="document.getElementById('image').click();">
                    <img id="previewImg" src="" alt="Preview" style="display: none;" />
                    <p id="uploadText">Click to upload photo.</p>
                </div>
                <input type="file" name="image" id="image" accept="image/*" required hidden>
                <div class="image-preview" id="imagePreview" style="display: none;">
                    <img id="previewImg" src="" alt="Preview" />
                </div>
                <br>
                <label class="checkbox-label">
                    <input type="checkbox" name="status"> Active
                </label>
                <button type="submit" name="add_ad">Add Ad</button>
            </form>
        </div>

        <!-- List of Existing Ads -->
        <div class="box">
            <h2>Existing Ads</h2>
            <div class="cards-container">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <a href="update_ads.php?id=<?php echo $row['id']; ?>" class="ad-card-link">
                            <div class="ad-card">
                                <div class="ad-image">
                                    <img src="ads/<?php echo $row['file']; ?>" alt="Ad Image">
                                </div>
                                <div class="ad-details">
                                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                    <p>Status:
                                        <button class="toggle-btn <?php echo $row['status'] ? 'active' : 'inactive'; ?>"
                                            onclick="toggleStatus(event, <?php echo $row['id']; ?>, <?php echo $row['status']; ?>)">
                                            <?php echo $row['status'] ? 'Active' : 'Inactive'; ?>
                                        </button>
                                    </p>
                                </div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No ads available to display.</p>
                <?php endif; ?>
            </div>
        </div>

        <script>
            document.getElementById('image').addEventListener('change', function (event) {
                const file = event.target.files[0];
                const previewImg = document.getElementById('previewImg');
                const uploadText = document.getElementById('uploadText');

                if (file) {
                    // No need to display the file name
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                        previewImg.style.display = 'block';
                        uploadText.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewImg.style.display = 'none';
                    uploadText.style.display = 'block';
                }
            });

            function toggleStatus(event, id, currentStatus) {
                event.stopPropagation();
                const newStatus = currentStatus ? 0 : 1;
                fetch(`update_ads.php?toggle_status_id=${id}&new_status=${newStatus}`, {
                    method: 'POST'
                })
                    .then(response => response.text())
                    .then(data => {
                        if (data.trim() === 'success') {
                            location.reload();
                        }
                    })
            }

            const message = document.getElementById('message');
            if (message) {
                setTimeout(() => {
                    message.style.display = 'none';
                }, 2000);
            }
        </script>
</body>

</html>