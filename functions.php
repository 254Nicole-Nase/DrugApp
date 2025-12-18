<?php 
/**
 * Generate HTML for displaying an item in the catalog
 */
function get_item_html($id, $item) {
    $output = "<li><a href='details.php?id=" . (int)$id . "'>";
    $output .= "<img src='" . htmlspecialchars($item["img"]) . "' alt='" . htmlspecialchars($item["title"]) . "'>";
    $output .= "<p>View Details</p></a></li>";
    return $output;
}

/**
 * Generate HTML for displaying a category card
 */
function get_category_html($id, $category) {
    $output = "<li>";
    $output .= "<a href='details.php?id=" . (int)$id . "'>";
    $output .= "<img src='" . htmlspecialchars($category["img"]) . "' alt='" . htmlspecialchars($category["title"]) . "'>";
    $output .= "<h2>" . htmlspecialchars($category["title"]) . "</h2>";
    $output .= "</a>";
    $output .= "</li>";
    return $output;
}

/**
 * Filter catalog items by category
 */
function array_category($catalog, $category) {
    $output = array();
    
    foreach ($catalog as $id => $item) {
        if (isset($item["category"]) && strtolower($category) == strtolower($item["category"])) {
            $output[] = $id;
        }
    }
    return $output;
}

/**
 * Format text for safe HTML output
 */
function safe_output($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
?>
