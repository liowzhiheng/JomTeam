<?php
session_start();
require("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];

        if (isset($_POST['update'])) {
            $first_name = $conn->real_escape_string($_POST['first_name']);
            $last_name = $conn->real_escape_string($_POST['last_name']);
            $gender = $conn->real_escape_string($_POST['gender']);
            $email = $conn->real_escape_string($_POST['email']);
            $phone = $conn->real_escape_string($_POST['phone']);
            $birth_date = $conn->real_escape_string($_POST['birth_date']);
            $level = $conn->real_escape_string($_POST['level']);
            $premium = isset($_POST['premium']) ? 1 : 0;
            $email_verified = isset($_POST['email_verified']) ? 1 : 0;

            $sqlUpdateUser = "
                UPDATE user 
                SET 
                    first_name = '$first_name',
                    last_name = '$last_name',
                    gender = '$gender',
                    email = '$email',
                    phone = '$phone',
                    birth_date = '$birth_date',
                    level = '$level',
                    premium = '$premium',
                    email_verified = '$email_verified'
                WHERE id = $user_id
            ";

            if ($conn->query($sqlUpdateUser)) {
                $file_name = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $file_name = $_FILES['image']['name'];
                    $tempname = $_FILES['image']['tmp_name'];
                    $folder = 'uploads/' . $file_name;
                    $result = mysqli_query($conn, "SELECT file FROM images WHERE user_id = '$user_id'");

                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $old_file = 'uploads/' . $row['file'];

                        $query = mysqli_query($conn, "UPDATE images SET file = '$file_name' WHERE user_id = '$user_id'");

                    } else {
                        $query = mysqli_query($conn, "INSERT INTO images (user_id, file) VALUES ('$user_id', '$file_name')");
                    }

                    if (!$query || !move_uploaded_file($tempname, $folder)) {
                        $_SESSION['status'] = 'fail';
                    }
                }
                $_SESSION['status'] = 'success';
            } else {
                $_SESSION['status'] = 'fail';
            }
        }
    }
}

$match_id = $_POST['match_id'];
$user_id = $_POST['user_id'];
$sqlUser = "SELECT * FROM user WHERE id = $user_id";
$resultUser = $conn->query($sqlUser);
$user = $resultUser->fetch_assoc();

$sqlProfile = "SELECT * FROM profile WHERE user_id = $user_id";
$resultProfile = $conn->query($sqlProfile);
$profile = $resultProfile->fetch_assoc();

$sqlImages = "SELECT * FROM images WHERE user_id = $user_id";
$resultImages = $conn->query($sqlImages);
$images = $resultImages->fetch_assoc();

$sqlRatings = "SELECT * FROM player_ratings WHERE rated_user_id = $user_id";
$resultRatings = $conn->query($sqlRatings);
$ratings = $resultRatings->fetch_assoc();

$avgRatingQuery = "SELECT AVG(rating) AS avg_rating FROM player_ratings WHERE rated_user_id = $user_id";
$avgRatingResult = mysqli_query($conn, $avgRatingQuery);
$avgRating = mysqli_fetch_assoc($avgRatingResult)['avg_rating'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ads</title>
    <link rel="stylesheet" href="update_user.css">
    <link rel="shortcut icon" type="image/jpg" href="IMAGE/favicon.png"/>
</head>

<body>
    <?php
    if (isset($_SESSION['status'])) {
        if ($_SESSION['status'] == 'success') {
            echo "<div id='message' class='success-message'>User information updated successfully!</div>";
        } else {
            echo "<div id='message' class='fail-message'>Something went wrong. Please try again.</div>";
        }
        session_unset();
    }
    ?>

    <div class="wrapper">
        <div class="container">
            <div class="title">
                <form id="backForm" action="update_match.php" method="post" style="display:none;">
                    <input type="hidden" name="match_id" value="<?php echo $match_id; ?>" />
                </form>
                <button onclick="document.getElementById('backForm').submit();" class="btn btn-secondary">Back</button>
                <h1>User Information</h1>
            </div>
            <form class="form-grid" method="POST" enctype="multipart/form-data" action="">
                <input type="hidden" name="user_id" value="<?php echo $user['id'] ?? ''; ?>">
                <input type="hidden" name="match_id" value="<?php echo $match_id; ?>" />

                <!-- Profile Picture -->
                <div class="image-container">
                    <?php if (empty($images['file'])) { ?>
                        <img id="imagePreview" src="IMAGE/default.png" alt="Default Image" class="uploaded-image"
                            onclick="document.getElementById('imageInput').click();" />
                        <div class="overlay-text" onclick="document.getElementById('imageInput').click();">Upload Image
                        </div>
                    <?php } else { ?>
                        <img id="imagePreview" src="uploads/<?php echo htmlspecialchars($images['file']); ?>"
                            alt="Uploaded Image" class="uploaded-image"
                            onclick="document.getElementById('imageInput').click();" />
                        <div class="overlay-text" onclick="document.getElementById('imageInput').click();">Change Image
                        </div>
                    <?php } ?>
                    <input type="file" name="image" id="imageInput" style="display: none;" onchange="previewImage()" />
                    <div class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star <?php echo $i <= round($ratings) ? 'selected' : ''; ?>">&#9733;</span>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="info-section">
                    <div class="info-left">
                        <label>First Name:</label>
                        <input type="text" name="first_name" value="<?php echo $user['first_name'] ?? ''; ?>"><br>

                        <label>Last Name:</label>
                        <input type="text" name="last_name" value="<?php echo $user['last_name'] ?? ''; ?>"><br>

                        <label>Gender:</label>
                        <select name="gender" required>
                            <option value="male" <?php if (($user['gender'] ?? '') === 'male')
                                echo 'selected'; ?>>Male</option>
                            <option value="female" <?php if (($user['gender'] ?? '') === 'female')
                                echo 'selected'; ?>>Female
                            </option>
                            <option value="other" <?php if (($user['gender'] ?? '') === 'other')
                                echo 'selected'; ?>>Other
                            </option>
                        </select><br>

                        <label>Email:</label>
                        <input type="email" name="email" value="<?php echo $user['email'] ?? ''; ?>"><br>

                        <label>Phone:</label>
                        <input type="text" name="phone" value="<?php echo $user['phone'] ?? ''; ?>"><br>

                        <label>Birth Date:</label>
                        <input type="date" name="birth_date" value="<?php echo $user['birth_date'] ?? ''; ?>"><br>

                        <label>Level:</label>
                        <select name="level">
                            <option value="1" <?php if ((string) ($user['level'] ?? '') === '1')
                                echo 'selected'; ?>>Admin
                            </option>
                            <option value="3" <?php if ((string) ($user['level'] ?? '') === '3')
                                echo 'selected'; ?>>User
                            </option>
                        </select><br>

                        <div class="checkbox-section">
                            <div class="checkbox-item">
                                <label>Premium:</label>
                                <input type="checkbox" name="premium" value="1" <?php if (($user['premium'] ?? false))
                                    echo 'checked'; ?>>
                            </div>
                            <div class="checkbox-item">
                                <label>Email Verified:</label>
                                <input type="checkbox" name="email_verified" value="1" <?php if (($user['email_verified'] ?? false))
                                    echo 'checked'; ?>>
                            </div>
                        </div>
                    </div>

                    <div class="info-right">
                        <label>Created At:</label>
                        <p> <?php echo !empty($user['created_at']) ? $user['created_at'] : 'N/A'; ?></p><br>
                        <label>Updated At:</label>
                        <p> <?php echo !empty($user['updated_at']) ? $user['updated_at'] : 'N/A'; ?></p><br>
                        <label>Last Activity:</label>
                        <p> <?php echo !empty($user['last_activity']) ? $user['last_activity'] : 'N/A'; ?></p><br>
                        <label>Status:</label>
                        <p> <?php echo !empty($profile['status']) ? $profile['status'] : 'N/A'; ?></p><br>
                        <label>Description:</label>
                        <p> <?php echo !empty($profile['description']) ? $profile['description'] : 'N/A'; ?></p><br>
                        <label>Location:</label>
                        <p> <?php echo !empty($profile['location']) ? $profile['location'] : 'N/A'; ?></p><br>
                        <label>Interests:</label>
                        <p> <?php echo !empty($profile['interests']) ? $profile['interests'] : 'N/A'; ?></p><br>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="delete_user.php?id=<?php echo $_POST['user_id']; ?>" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
    <script src="view_profile.js"></script>
    <script>
        const messageElement = document.getElementById('message');
        if (messageElement) {
            setTimeout(() => {
                messageElement.style.display = 'none';
            }, 2000);
        }
    </script>
</body>

</html>