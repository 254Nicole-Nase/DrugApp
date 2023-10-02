<?php
$pageTitle = "Dashboard";
$section = null;

include("data.php"); 
include("functions.php");

include 'header.php';

// db conn
require_once("db_connection.php"); 
?>

<div class="wrapper">
    <h1>Drugs of Different Categories</h1>

    <?php
    // get drug categories from the db
    $query = "SELECT DISTINCT category_name FROM drug_categories";
    $result = $conn->query($query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categoryName = $row["category_name"];

            // section for each drug category
            echo "<section class='category'>";
            echo "<div class='category-banner'>";
            echo "<div class='drug-header__title'>";
            echo "<h1><a>$categoryName</a></h1>";
            echo "</div>";
            echo "</div>";
            echo "<ul class='items'>";

            // drugs in the current category
            $categoryQuery = "SELECT drug_id, drug_name, description, image_url
                              FROM drug_details
                              WHERE category_id = (
                                  SELECT category_id
                                  FROM drug_categories
                                  WHERE category_name = '$categoryName'
                              )";
            $categoryResult = $conn->query($categoryQuery);

            if ($categoryResult) {
                while ($drug = $categoryResult->fetch_assoc()) {
                    // display each drug and view details link
                    echo "<li>";
                    echo "<img src='" . $drug["image_url"] . "' alt='" . $drug["drug_name"] . "' />";
                    echo "<h2>" . $drug["drug_name"] . "</h2>";
                    echo "<a href='view_details.php?drug_id=" . $drug["drug_id"] . "'>View Details</a>";
                    echo "</li>";
                }
                $categoryResult->close();
            }

            echo "</ul>";
            echo "</section>";
        }
        $result->close();
    }
    ?>

</div>
</div>

<?php include 'footer.php'; ?>