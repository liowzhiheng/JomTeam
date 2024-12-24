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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ad</title>
</head>

<body>
    <h1>Edit Ad</h1>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'fail'): ?>
        <p style="color: red;">Error updating the ad. Please try again.</p>
    <?php elseif (isset($_GET['status']) && $_GET['status'] == 'upload_fail'): ?>
        <p style="color: red;">Error uploading the image. Please try again.</p>
    <?php elseif (isset($_GET['status']) && $_GET['status'] == 'updated'): ?>
        <p style="color: green;">Ad updated successfully!</p>
    <?php endif; ?>

    <form action="update_ads.php?id=<?php echo $ads['id']; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $ads['id']; ?>">
        <div>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($ads['title']); ?>" required>
        </div>
        <div>
            <label for="description">Description:</label>
            <textarea id="description" name="description"
                required><?php echo htmlspecialchars($ads['description']); ?></textarea>
        </div>
        <div>
            <label for="status">Status:</label>
            <input type="checkbox" id="status" name="status" <?php echo $ads['status'] ? 'checked' : ''; ?>>
            <label for="status">Active</label>
        </div>
        <div>
            <label for="image">Image:</label>
            <input type="file" id="image" name="image">
            <p>Current image: <?php echo $ads['file']; ?></p>
        </div>
        <button type="submit" name="update_ad">Update Ad</button>
    </form>

    <a href="view_ads.php">Back to Ads</a>
</body>

</html>