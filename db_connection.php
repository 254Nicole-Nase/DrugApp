<?php
/**
 * Database Configuration
 * Update these values to match your MySQL/MariaDB setup
 */

// Database settings - UPDATE THESE IF NEEDED
$hostname = "127.0.0.1";  // Use IP instead of 'localhost' for better compatibility
$username = "root";       // Your MySQL/MariaDB username
$password = "root";           // Your MySQL/MariaDB password (empty if none set)
$database = "drug_app";
$port = 3306;             // Default MySQL port (change if different)

// Create connection with port specification
$conn = new mysqli($hostname, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    // More helpful error message
    $error_msg = "Database connection failed: " . $conn->connect_error;
    $error_msg .= "<br><br><strong>Troubleshooting:</strong>";
    $error_msg .= "<ul>";
    $error_msg .= "<li>Make sure MySQL/MariaDB service is running</li>";
    $error_msg .= "<li>Check your username and password in db_connection.php</li>";
    $error_msg .= "<li>Run setup_database.bat to create the database</li>";
    $error_msg .= "<li>Try port 3307 if using MariaDB alongside MySQL</li>";
    $error_msg .= "</ul>";
    die($error_msg);
}

// Set charset
if (!$conn->set_charset("utf8mb4")) {
    printf("Error loading character set utf8mb4: %s\n", $conn->error);
    exit();
}

/**
 * Helper function to sanitize input
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Helper function for displaying flash messages
 */
function set_flash_message($type, $message) {
    $_SESSION['flash_type'] = $type;
    $_SESSION['flash_message'] = $message;
}

function get_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $type = $_SESSION['flash_type'];
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        return ['type' => $type, 'message' => $message];
    }
    return null;
}
?>
