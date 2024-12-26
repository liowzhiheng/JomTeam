<?php
require("config.php");

// Get user ID from session
$userID = $_SESSION["ID"];

// Query to fetch sender's name, profile image, and request date
$stmt = $conn->prepare("
    SELECT user.id AS sender_id, user.first_name, user.last_name, friend_requests.created_at
    FROM friend_requests
    JOIN user ON friend_requests.sender_id = user.id
    WHERE friend_requests.receiver_id = ? AND friend_requests.status = 'pending'
");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

// Initialize array to store pending requests
$pendingRequests = [];
$pendingCount = 0;  // Variable to store the count of pending requests

// Fetch all pending requests
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pendingRequests[] = [
            'sender_id' => $row['sender_id'],
            'sender_name' => $row['first_name'] . ' ' . $row['last_name'],
            'created_at' => $row['created_at']
        ];
        $pendingCount++;  // Increment the count for each pending request
    }
}

// Close the statement
$stmt->close();
?>

<nav class="navbar">
    <a href="main.php" class="logo">
        <img src="IMAGE/jomteam.png" alt="Logo">
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
            <a href="javascript:void(0);" onclick="showFriendRequests()">
                <img src="IMAGE/NOTIFICATION.png" alt="Notification">
                <?php if ($pendingCount > 0): ?>
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
        </li>
        <li class="logout">
            <a href="javascript:void(0);" onclick="confirmLogout()">Logout</a>
        </li>
    </ul>
</nav>

<div id="friendRequestsModal" class="modal">
    <div class="modal-content">
        <!-- Close button is here -->
        <span class="close-btn" onclick="closeModal()">×</span>
        <h2>Friend Requests</h2>
        <div id="friendRequestsContent">
            <!-- Friend requests will be dynamically populated here -->
        </div>
    </div>
</div>


<script>
    function confirmLogout() {
        var confirmation = confirm("Are you sure you want to logout?");
        if (confirmation) {
            window.location.href = "logout.php";
        }
    }

    function closeModal() {
        console.log('Closing modal...');
        document.getElementById('friendRequestsModal').style.display = 'none';
    }

    function showFriendRequests() {
        const pendingRequests = <?php echo json_encode($pendingRequests); ?>;
        const pendingCount = <?php echo $pendingCount; ?>;

        if (pendingCount > 0) {
            let requestsContent = '';
            // Populate the modal with friend requests
            pendingRequests.forEach(function (request) {
                requestsContent += `
                    <div class="request-item">
                        <p>${request.sender_name} is sending a friend request to you.</p>
                    </div>
                `;
            });

            document.getElementById('friendRequestsContent').innerHTML = requestsContent;
            // Display the modal
            document.getElementById('friendRequestsModal').style.display = 'block';
        } else {
            // Hide the modal if there are no pending requests
            document.getElementById('friendRequestsModal').style.display = 'none';
        }
    }
</script>