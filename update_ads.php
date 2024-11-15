<?php
session_start();
require("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve form data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = isset($_POST['status']) ? 1 : 0; // Active status if checkbox is checked

    // Initialize the file_name to NULL (no image)
    $file_name = null;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // If new image is uploaded
        $file_name = $_FILES['image']['name'];
        $tempname = $_FILES['image']['tmp_name'];
        $folder = 'ads/' . $file_name;
        move_uploaded_file($tempname, $folder);
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

    // Update Ad
    if (isset($_POST['update_ad'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);

        // Fetch the current ad info if no new image is uploaded
        if (!$file_name) {
            $result = mysqli_query($conn, "SELECT file FROM ads WHERE id = '$id'");
            $row = mysqli_fetch_assoc($result);
            $file_name = $row['file'];  // Use the existing file if no new one is uploaded
        }

        $update_query = "
            UPDATE ads 
            SET 
                title = '$title',
                description = '$description',
                status = '$status',
                file = '$file_name'
            WHERE id = '$id'
        ";

        if (mysqli_query($conn, $update_query)) {
            header("Location: view_ads.php?status=updated");
            exit();
        } else {
            header("Location: view_ads.php?status=fail");
            exit();
        }
    }
}

// Delete Ad (if delete request is sent)
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_query = "DELETE FROM ads WHERE id = '$delete_id'";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: view_ads.php?status=deleted");
        exit();
    } else {
        header("Location: view_ads.php?status=fail");
        exit();
    }
}
?>