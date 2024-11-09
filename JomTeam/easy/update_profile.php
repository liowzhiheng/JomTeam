<?php
session_start(); // Start the PHP session

// Check if the user is logged in
if ($_SESSION["Login"] != "YES") {
    header("Location: login.php");
    exit();
}

require("config.php");

$user_id = $_SESSION["ID"];
$userData = [];

// Check if the form data has been submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Prepare an array to store the fields that need to be updated
    $updateFields = [];

    // Handle the uploaded file
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $fileTmpPath = $_FILES['profile_image']['tmp_name'];
        $fileName = $_FILES['profile_image']['name'];
        $fileSize = $_FILES['profile_image']['size'];
        $fileType = $_FILES['profile_image']['type'];

        // Validate the file type (ensure it's an image)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($fileType, $allowedTypes)) {
            // Check the file size (limit to 2MB)
            if ($fileSize > 2 * 1024 * 1024) {
                echo "File size exceeds the maximum limit of 2MB.";
                exit();
            }

            // Move the file to the desired folder
            $uploadDir = 'uploads/';
            $newFileName = uniqid() . '_' . $fileName;
            $uploadPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                $updateFields[] = "profile_image = '$uploadPath'";  // Add the image path to the update fields
            } else {
                echo "File upload failed.";
                exit();
            }
        } else {
            echo "Invalid file type. Only JPEG, PNG, and GIF are allowed.";
            exit();
        }
    }

    // Check for other form data and add it to the update fields
    $name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : null;
    $gender = isset($_POST['gender']) ? mysqli_real_escape_string($conn, $_POST['gender']) : null;
    $age = isset($_POST['age']) ? (int) $_POST['age'] : null;
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : null;
    $phone = isset($_POST['phone']) ? mysqli_real_escape_string($conn, $_POST['phone']) : null;
    $description = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : null;

    // Add other fields to update array if they're not null
    if ($name !== null) {
        $updateFields[] = "name = '$name'";
    }
    if ($gender !== null) {
        $updateFields[] = "gender = '$gender'";
    }
    if ($age !== null) {
        $updateFields[] = "age = $age";
    }
    if ($status !== null) {
        $updateFields[] = "status = '$status'";
    }
    if ($phone !== null) {
        $updateFields[] = "phone = '$phone'";
    }
    if ($description !== null) {
        $updateFields[] = "description = '$description'";
    }

    if (empty($updateFields)) {
        echo "No fields to update.";
        exit();
    }

    // Join the update fields into a single string
    $updateFieldsString = implode(", ", $updateFields);

    // Using prepared statements to prevent SQL injection
    $updateProfileQuery = "
        UPDATE profile 
        SET $updateFieldsString 
        WHERE user_id = ?
    ";

    if ($stmt = mysqli_prepare($conn, $updateProfileQuery)) {
        mysqli_stmt_bind_param($stmt, 's', $user_id); // 's' for string type
        if (mysqli_stmt_execute($stmt)) {
            echo "success";
        } else {
            echo "Error updating profile. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing query. Please try again later.";
    }
}

mysqli_close($conn);
?>