<?php
$pageTitle = "Drug Details";
include 'header.php';

require_once("db_connection.php"); 

if (isset($_GET['drug_id'])) {
    $drugId = $_GET['drug_id'];

    // Query to retrieve drug details by drug_id
    $query = "SELECT drug_name, description, image_url, category_name
              FROM drug_details
              JOIN drug_categories ON drug_details.category_id = drug_categories.category_id
              WHERE drug_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $drugId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $drug = $result->fetch_assoc();

        if ($drug) {
            // Display drug details 
            echo "<div class='section page'>";
            echo "<div class='wrapper'>";
            echo "<div class='media-picture'>";
            echo "<span>";
            echo "<img src='" . $drug['image_url'] . "' alt='" . $drug['drug_name'] . "' />";
            echo "</span>";
            echo "</div>";
            echo "<div class='media-details'>";
            echo "<h1>" . $drug['drug_name'] . "</h1>"; 
            echo "<p><span>Category: </span>" . $drug['category_name'] . "</p>";
            echo "<p>Description: " . $drug['description'] . "</p>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "Drug not found.";
        }
    } else {
        // Query execution failed
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Drug ID not provided.";
}

// Close the database connection
$conn->close();

include 'footer.php';
?>