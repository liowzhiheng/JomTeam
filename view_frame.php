<?php
session_start();
require("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file_name = $frame['file'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file_name = $_FILES['image']['name'];
        $tempname = $_FILES['image']['tmp_name'];
        $folder = 'frame/' . $file_name;
        if (!move_uploaded_file($tempname, $folder)) {
            header("Location: view_frame.php?id=$id&status=fail");
            exit();
        }
    }

    if (isset($_POST['add_frame'])) {
        $insert_query = "
            INSERT INTO frame (file) 
            VALUES ('$file_name')
        ";
        if (mysqli_query($conn, $insert_query)) {
            header("Location: view_frame.php?status=success");
            exit();
        } else {
            header("Location: view_frame.php?status=fail");
            exit();
        }
    }
}

$sql = "SELECT id, file FROM frame";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Match</title>
    <link rel="stylesheet" href="view_frame.css">
    <link rel="shortcut icon" type="image/jpg" href="IMAGE/favicon.png" />
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
        if ($status === 'success') {
            echo '<p id="message" class="message success">Frame added successfully!</p>';
        } elseif ($status === 'fail') {
            echo '<p id="message" class="message fail">Something went wrong. Please try again.</p>';
        }
    }
    ?>

    <h1>Manage Frame</h1>

    <div class="ads-wrapper">
        <div class="box">
            <form method="POST" action="" enctype="multipart/form-data">
                <h2>Add New Frame</h2>
                <div class="upload-area" id="uploadArea" onclick="document.getElementById('image').click();">
                    <img class="default" src="IMAGE/default.png" alt="Default" />
                    <img id="previewImg" src="" alt="Preview" style="display: none;" />
                    <p id="uploadText">Click to upload frame.</p>
                </div>
                <input type="file" name="image" id="image" accept="image/*" required hidden>
                <div class="image-preview" id="imagePreview" style="display: none;">
                    <img id="previewImg" src="" alt="Preview" />
                </div>
                <br>
                <button type="submit" name="add_frame">Add Frame</button>
            </form>
        </div>
    </div>

    <script>
        const messageElement = document.getElementById('message');
        if (messageElement) {
            setTimeout(() => {
                messageElement.style.display = 'none';
            }, 2000);
        }

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
    </script>
</body>

</html>
