<?php
session_start();
require_once("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $drug_name = $_POST['drug_name'];
    $category_id = $_POST['category'];
    $description = $_POST['description'];

    $upload_dir = "img/";
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_path = $upload_dir . $image_name;

    if (move_uploaded_file($image_tmp_name, $image_path)) {
        $query = "INSERT INTO drug_details (drug_name, category_id, description, image_url) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("siss", $drug_name, $category_id, $description, $image_path);

        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error uploading image.";
    }
}

$conn->close();
?>
