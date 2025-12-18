<?php
$pageTitle = "Dashboard";
$section = "dashboard";

include 'header.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

require_once("db_connection.php"); 

// Get flash message if any
$flash = get_flash_message();
?>

<div class="wrapper">
    <?php if ($flash): ?>
        <div class="alert alert-<?php echo $flash['type']; ?>">
            <?php echo $flash['message']; ?>
        </div>
    <?php endif; ?>
    
    <div class="dashboard-header">
        <h1>Drug Categories Dashboard</h1>
        <p>Browse drugs organized by their categories</p>
    </div>

    <?php
    // Get drug categories from the db using prepared statement
    $query = "SELECT category_id, category_name FROM drug_categories ORDER BY category_name";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categoryId = $row["category_id"];
            $categoryName = $row["category_name"];

            // Section for each drug category
            echo "<section class='category'>";
            echo "<div class='category-banner'>";
            echo "<div class='drug-header__title'>";
            echo "<h2><span class='category-icon'>💊</span> " . htmlspecialchars($categoryName) . "</h2>";
            echo "</div>";
            echo "</div>";
            echo "<ul class='items'>";

            // Get drugs in the current category using prepared statement
            $categoryQuery = "SELECT drug_id, drug_name, description, image_url 
                              FROM drug_details 
                              WHERE category_id = ?
                              ORDER BY drug_name";
            $stmt = $conn->prepare($categoryQuery);
            $stmt->bind_param("i", $categoryId);
            $stmt->execute();
            $categoryResult = $stmt->get_result();

            if ($categoryResult && $categoryResult->num_rows > 0) {
                while ($drug = $categoryResult->fetch_assoc()) {
                    // Display each drug with view details link
                    echo "<li class='drug-card'>";
                    echo "<div class='drug-image'>";
                    echo "<img src='" . htmlspecialchars($drug["image_url"]) . "' alt='" . htmlspecialchars($drug["drug_name"]) . "' />";
                    echo "</div>";
                    echo "<div class='drug-info'>";
                    echo "<h3>" . htmlspecialchars($drug["drug_name"]) . "</h3>";
                    echo "<div class='drug-actions'>";
                    echo "<a href='view_details.php?drug_id=" . (int)$drug["drug_id"] . "' class='btn btn-view'>View Details</a>";
                    echo "<a href='editDrug.php?drug_id=" . (int)$drug["drug_id"] . "' class='btn btn-edit'>Edit</a>";
                    echo "<a href='deleteDrug.php?drug_id=" . (int)$drug["drug_id"] . "' class='btn btn-delete' onclick=\"return confirm('Are you sure you want to delete this drug?');\">Delete</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</li>";
                }
            } else {
                echo "<li class='no-drugs'>No drugs in this category yet.</li>";
            }
            
            $stmt->close();
            echo "</ul>";
            echo "</section>";
        }
        $result->close();
    } else {
        echo "<p class='no-categories'>No drug categories found.</p>";
    }
    
    $conn->close();
    ?>

</div>
</div>

<?php include 'footer.php'; ?>
