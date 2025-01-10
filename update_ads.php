<?php
session_start();
require("config.php");

if (isset($_GET['toggle_status_id']) && isset($_GET['new_status'])) {
    $id = intval($_GET['toggle_status_id']);
    $new_status = intval($_GET['new_status']);
    $sql = "UPDATE ads SET status = $new_status WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'error';
    }
    exit;
}

// Fetch Ad Data for Editing
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM ads WHERE id = $id");

    if ($result->num_rows > 0) {
        $ads = $result->fetch_assoc();
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = isset($_POST['status']) ? 1 : 0;
    $file_name = $ads['file']; // Default to existing file

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file_name = $_FILES['image']['name'];
        $tempname = $_FILES['image']['tmp_name'];
        $folder = 'ads/' . $file_name;
        if (!move_uploaded_file($tempname, $folder)) {
            header("Location: update_ads.php?id=$id&status=upload_fail");
            exit();
        }
    }

    // Update Ad
    if (isset($_POST['update_ad'])) {
        $id = intval($_POST['id']);

        $update_query = "
            UPDATE ads 
            SET 
                title = '$title',
                description = '$description',
                status = '$status',
                file = '$file_name'
            WHERE id = $id
        ";

        if (mysqli_query($conn, $update_query)) {
            header("Location: view_ads.php?status=updated");
            exit();
        } else {
            header("Location: update_ads.php?id=$id&status=fail");
            exit();
        }
    }

    // Add Ad
    if (isset($_POST['add_ad'])) {
        $insert_query = "
            INSERT INTO ads (title, description, status, file) 
            VALUES ('$title', '$description', '$status', '$file_name')
        ";
        if (mysqli_query($conn, $insert_query)) {
            header("Location: view_ads.php?status=success");
            exit();
        } else {
            header("Location: view_ads.php?status=fail");
            exit();
        }
    }
}

// Delete Ad
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $delete_query = "DELETE FROM ads WHERE id = $id";

    if ($conn->query($delete_query) === TRUE) {
        header("Location: view_ads.php?status=deleted");
        exit();
    } else {
        header("Location: update_ads.php?id=$id&status=delete_fail");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ads</title>
    <link rel="stylesheet" href="update_ads.css">
    <link rel="shortcut icon" type="image/jpg" href="IMAGE/favicon.png"/>
</head>

<body>
    <div class="container">
        <div class="title">
            <a href="view_ads.php" class="btn btn-secondary">Back</a>
            <h1>Edit Ad</h1>
        </div>

        <div class="status-messages">
            <?php if (isset($_GET['status']) && $_GET['status'] == 'fail'): ?>
                <p class="error-message">Error updating the ad. Please try again.</p>
            <?php elseif (isset($_GET['status']) && $_GET['status'] == 'upload_fail'): ?>
                <p class="error-message">Error uploading the image. Please try again.</p>
            <?php elseif (isset($_GET['status']) && $_GET['status'] == 'updated'): ?>
                <p class="success-message">Ad updated successfully!</p>
            <?php endif; ?>
        </div>

        <form action="update_ads.php?id=<?php echo $ads['id']; ?>" method="POST" enctype="multipart/form-data"
            class="form">
            <input type="hidden" name="id" value="<?php echo $ads['id']; ?>">

            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($ads['title']); ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description"
                    required><?php echo htmlspecialchars($ads['description']); ?></textarea>
            </div>

            <div class="form-group status">
                <label>Status:</label>
                <label style="color: black; font-weight:lighter">
                    <input type="checkbox" id="status" name="status" <?php echo $ads['status'] ? 'checked' : ''; ?>>Active
                </label>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 20px;">
                <div>
                    <label>Current Image:</label>
                    <div class="upload-area" id="uploadArea" style="cursor: default; border: 2px solid #ccc;">
                        <img src="ads/<?php echo $ads['file']; ?>" alt="Current Image"
                            style="max-height: 200px; weight: auto;">
                    </div>
                </div>
                <div>
                    <label>Upload New Image:</label>
                    <div class="upload-area" id="uploadArea" onclick="document.getElementById('image').click();">
                        <img id="previewImg" src="" alt="Preview" style="display: none;" />
                        <p id="uploadText">Click to upload photo.</p>
                    </div>
                </div>
                <input type="file" name="image" id="image" accept="image/*" hidden>
                <div class="image-preview" id="imagePreview" style="display: none;">
                    <img id="previewImg" src="" alt="Preview" />
                </div>
            </div>

            <div class="form-actions">
                <a href="update_ads.php?delete_id=<?php echo $ads['id']; ?>" class="btn btn-danger"
                    onclick="return confirm('Are you sure you want to delete this ad?');">Delete</a>
                <button type="submit" name="update_ad" class="btn btn-primary">Update</button>
            </div>
        </form>

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
        </script>
</body>

</html>