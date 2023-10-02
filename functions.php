<?php 
function get_item_html($id,$item){
	$output = "<li><a href='details.php?id=$id'><img src='".$item["img"]."' alt ='".$item["title"]."'><p>View Details </p></a></li>";
	return $output;
}

function get_category_html($id, $category) {
    $output = "<li>";
    $output .= "<a href='details.php?id=$id'>";
    $output .= "<img src='" . $category["img"] . "' alt='" . $category["title"] . "'>";
    $output .= "<h2>" . $category["title"] . "</h2>";
    $output .= "</a>";
    $output .= "</li>";

    return $output;
}


function array_category($catalog, $category){
    $output = array();
    
    foreach($catalog as $id => $item){
        if (isset($item["category"]) && strtolower($category) == strtolower($item["category"])) {
             $output[] = $id;
        }
    }
    return $output;
}

?>