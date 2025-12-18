<?php
session_start();
require_once("db_connection.php");

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// Get drug ID
if (!isset($_GET['drug_id']) || !is_numeric($_GET['drug_id'])) {
    set_flash_message('error', 'Invalid drug ID.');
    header("Location: dashboard.php");
    exit;
}

$drug_id = (int)$_GET['drug_id'];

// First, get the drug details to delete the image file
$query = "SELECT drug_name, image_url FROM drug_details WHERE drug_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $drug_id);
$stmt->execute();
$result = $stmt->get_result();
$drug = $result->fetch_assoc();
$stmt->close();

if (!$drug) {
    set_flash_message('error', 'Drug not found.');
    header("Location: dashboard.php");
    exit;
}

// Delete the drug from database
$deleteQuery = "DELETE FROM drug_details WHERE drug_id = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("i", $drug_id);

if ($stmt->execute()) {
    // Delete the image file if it exists
    if ($drug['image_url'] && file_exists($drug['image_url'])) {
        unlink($drug['image_url']);
    }
    
    set_flash_message('success', 'Drug "' . $drug['drug_name'] . '" deleted successfully!');
} else {
    set_flash_message('error', 'Error deleting drug: ' . $stmt->error);
}

$stmt->close();
$conn->close();

header("Location: dashboard.php");
exit;
?>

