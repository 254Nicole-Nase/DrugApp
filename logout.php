<?php
session_start(); 

// Check if the user is logged in 
if (isset($_SESSION['admin_id'])) {
    $_SESSION = array();

    session_destroy();

    // Redirect to the login page 
    header("Location: index.php"); 
    exit();
} else {
    // If the user is not logged in
    echo "You are not logged in.";
}
?>