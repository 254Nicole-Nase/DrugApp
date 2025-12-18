<?php
session_start();
require_once("db_connection.php");

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $drug_name = sanitize_input($_POST['drug_name']);
    $category_id = (int)$_POST['category'];
    $description = sanitize_input($_POST['description']);

    // Validate inputs
    if (empty($drug_name) || empty($category_id) || empty($description)) {
        set_flash_message('error', 'All fields are required.');
        header("Location: addDrug.php");
        exit;
    }

    // File upload handling
    $upload_dir = "img/";
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        set_flash_message('error', 'Please select an image to upload.');
        header("Location: addDrug.php");
        exit;
    }

    if (!in_array($_FILES['image']['type'], $allowed_types)) {
        set_flash_message('error', 'Invalid image type. Allowed: JPG, PNG, GIF, WEBP');
        header("Location: addDrug.php");
        exit;
    }

    if ($_FILES['image']['size'] > $max_size) {
        set_flash_message('error', 'Image too large. Maximum size: 5MB');
        header("Location: addDrug.php");
        exit;
    }

    // Generate unique filename
    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $image_name = uniqid('drug_') . '.' . $extension;
    $image_path = $upload_dir . $image_name;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
        // Insert into database using prepared statement
        $query = "INSERT INTO drug_details (drug_name, category_id, description, image_url) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("siss", $drug_name, $category_id, $description, $image_path);

        if ($stmt->execute()) {
            set_flash_message('success', 'Drug added successfully!');
            header("Location: dashboard.php");
            exit;
        } else {
            // Delete uploaded file if db insert failed
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            set_flash_message('error', 'Error saving to database: ' . $stmt->error);
            header("Location: addDrug.php");
            exit;
        }
        $stmt->close();
    } else {
        set_flash_message('error', 'Error uploading image. Please try again.');
        header("Location: addDrug.php");
        exit;
    }
}

$conn->close();
header("Location: addDrug.php");
exit;
?>
