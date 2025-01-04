<?php
require("config.php");

// Get user ID from session
$userID = $_SESSION["ID"];

// Check if the notification has been marked as seen (only for the notification modal)
if (isset($_POST['notification_seen'])) {
    // Mark the requests as seen only for notifications
    $stmt = $conn->prepare("UPDATE friend_requests SET status = 'seen' WHERE receiver_id = ? AND status = 'pending' AND is_notified = 0");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->close();

    // Mark the match requests as seen only for notifications
    $stmt = $conn->prepare("UPDATE match_request SET status = 'seen' WHERE match_id IN (SELECT match_id FROM gamematch WHERE user_id = ?) AND status = 'pending' AND is_notified = 0");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->close();

    // Mark these requests as notified (so they aren't marked again)
    $stmt = $conn->prepare("UPDATE friend_requests SET is_notified = 1 WHERE receiver_id = ? AND is_notified = 0");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("UPDATE match_request SET is_notified = 1 WHERE match_id IN (SELECT match_id FROM gamematch WHERE user_id = ?) AND is_notified = 0");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->close();

    // Set session variable to indicate notification is seen
    $_SESSION['notification_seen'] = true;
}

// Query for pending friend requests
$stmt = $conn->prepare("
    SELECT user.id AS sender_id, user.first_name, user.last_name, friend_requests.created_at, friend_requests.status
    FROM friend_requests
    JOIN user ON friend_requests.sender_id = user.id
    WHERE friend_requests.receiver_id = ? AND friend_requests.status = 'pending'
");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

// Initialize array to store pending friend requests
$pendingRequests = [];
$pendingCount = 0;

// Fetch all pending friend requests
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pendingRequests[] = [
            'sender_id' => $row['sender_id'],
            'sender_name' => $row['first_name'] . ' ' . $row['last_name'],
            'created_at' => $row['created_at']
        ];
        $pendingCount++;
    }
}

// Query for pending match requests
$stmt = $conn->prepare("
    SELECT user.id AS sender_id, user.first_name, user.last_name, match_request.match_id, gamematch.match_title, match_request.status
    FROM match_request
    JOIN user ON match_request.request_user_id = user.id
    JOIN gamematch ON match_request.match_id = gamematch.id
    WHERE gamematch.user_id = ? 
    AND (match_request.status = 'pending' OR match_request.status = 'accepted' OR match_request.status = 'rejected')
");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

// Initialize array to store pending match requests
$pendingMatchRequests = [];
$pendingMatchCount = 0;

// Fetch all match requests
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pendingMatchRequests[] = [
            'sender_id' => $row['sender_id'],
            'sender_name' => $row['first_name'] . ' ' . $row['last_name'],
            'match_title' => $row['match_title'],
            'status' => $row['status']
        ];
        $pendingMatchCount++;
    }
}

$user_id = $_SESSION['ID'];

$query = "
    SELECT 
        u.first_name, 
        u.last_name, 
        u.gender, 
        u.email, 
        u.password,
        u.phone, 
        u.premium,
        p.status, 
        p.description, 
        p.location, 
        p.interests, 
        p.preferred_game_types, 
        p.skill_level, 
        p.availability,
        p.frame
    FROM 
        user u 
    LEFT JOIN 
        profile p 
    ON 
        u.id = p.user_id 
    WHERE 
        u.id = '$user_id'
";

$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    echo "No profile data found.";
    exit();
}

$rows = mysqli_fetch_assoc($result);

$frame = $rows['frame'];
$sql = "SELECT * FROM frame WHERE id = '$frame'";
$result2 = mysqli_query($conn, $sql);
$rows2 = mysqli_fetch_assoc($result2);
if (!$result2 || mysqli_num_rows($result2) == 0) {
    echo "No profile data found.";
    exit();
}


// Close the statement
$stmt->close();
?>

<!-- Navbar -->
<nav class="navbar">
    <a href="main.php" class="logo">
        <img src="IMAGE/jomteam_new_logo.png" alt="Logo">
    </a>

    <ul class="menu leftmenu">
        <li><a href="main.php">Home</a></li>
        <li><a href="find_match.php">Find Match</a></li>
        <li><a href="create_match.php">Create Match</a></li>
        <li><a href="friends_list.php">Social</a></li>
        <li><a href="premium.php">Premium</a></li>
    </ul>

    <ul class="menu rightmenu">
        <li><a href="history.php">Match Activity</a></li>
        <li class="notification">
            <a href="javascript:void(0);" onclick="showNotifications()">
                <img src="IMAGE/NOTIFICATION.png" alt="Notification" >
                <?php if (($pendingCount + $pendingMatchCount) > 0 && !isset($_SESSION['notification_seen'])): ?>
                    <span class="red-dot"></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="profile">
            <?php
            // Fetch the profile image from the database
            if (isset($_SESSION["ID"])) {
                $res = mysqli_query($conn, "SELECT file FROM images WHERE user_id = " . $_SESSION["ID"]);
                $row = mysqli_fetch_assoc($res);
                if (empty($row['file'])) {
                    echo '<div class="image-container">
                            <a href="view_profile.php">
                                <img src="IMAGE/LOGOUT.png" alt="Profile Image" class="uploaded-image"/>
                            </a>
                          </div>';
                } else {
                    echo '<div class="image-container">
                            <a href="view_profile.php">
                                <img src="uploads/' . $row['file'] . '" alt="Uploaded Image" class="uploaded-image"/>
                            </a>
                          </div>';
                }
            }

            ?>
            <?php if ($rows['premium']): ?>
                </div>
                <div class="image-container-nav ">
                    <img src="IMAGE/<?php echo $rows2['file'] ?>" class="premium-frame-nav" />

                </div>
            <?php endif; ?>

        </li>
        <li class="logout">
            <a href="javascript:void(0);" onclick="confirmLogout()">Logout</a>
        </li>
    </ul>
</nav>

<div id="friendRequestsModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">close</span>
        <h2>Notification</h2>
        <div id="friendRequestsContent"></div>
    </div>
</div>

<script>
    function confirmLogout() {
        var confirmation = confirm("Are you sure you want to logout?");
        if (confirmation) {
            window.location.href = "logout.php";
        }
    }

     function showNotifications() {
        const pendingRequests = <?php echo json_encode($pendingRequests); ?>;
        const pendingMatchRequests = <?php echo json_encode($pendingMatchRequests); ?>;
        const userId = <?php echo json_encode($userId); ?>;  // Assuming userId is passed from PHP
        const pendingCount = <?php echo $pendingCount; ?>;
        const pendingMatchCount = <?php echo $pendingMatchCount; ?>;

        if (pendingCount === 0 && pendingMatchCount === 0) {
            document.getElementById('friendRequestsContent').innerHTML = '<p>No new notifications</p>';
            document.getElementById('friendRequestsModal').style.display = 'block';
            return;
        }

        let requestsContent = '';

        // Display friend requests
        pendingRequests.forEach(function (request) {
            requestsContent += `
        <div class="request-item">
            <p>${request.sender_name} is sending a friend request to you.</p>
        </div>
        `;
        });

        pendingMatchRequests.forEach(function (matchRequest) {
            let statusMessage = '';

            if (matchRequest.status === 'pending') {
                if (matchRequest.receiver_id === userId) {
                    statusMessage = `Someone is requesting to join your match.`;
                }
            } else {
                if (matchRequest.sender_id === userId) {
                    if (matchRequest.status === 'accepted') {
                        statusMessage = `Your request to join "${matchRequest.match_title}" has been accepted.`;
                    } else if (matchRequest.status === 'rejected') {
                        statusMessage = `Your request to join "${matchRequest.match_title}" has been rejected.`;
                    }
                }
            }

            if (statusMessage) {
                requestsContent += `
            <div class="request-item">
                <p>${statusMessage}</p>
            </div>
            `;
            }
        });

        document.getElementById('friendRequestsContent').innerHTML = requestsContent;
        document.getElementById('friendRequestsModal').style.display = 'block';

        // AJAX request to mark notifications as seen
        fetch(window.location.href, {
            method: 'POST',
            body: new URLSearchParams('notification_seen=true')
        }).then(response => {
            document.querySelector('.red-dot').style.display = 'none';
        }).catch(error => {
            console.error('Error:', error);
        });
    }
    function closeModal() {
        document.getElementById('friendRequestsModal').style.display = 'none';
        document.querySelector('.red-dot').style.display = 'none';  // Hide the red dot

        // Send AJAX request to mark all notifications as seen
        fetch(window.location.href, {  // Send AJAX request to mark as seen
            method: 'POST',
            body: new URLSearchParams('notification_seen=true')
        }).then(response => {
            console.log('Notification marked as seen');
        }).catch(error => {
            console.error('Error:', error);
        });
    }

    document.querySelector('.close-btn').addEventListener('click', closeModal);
    document.querySelector('.notification a').addEventListener('click', showNotifications);
</script>
