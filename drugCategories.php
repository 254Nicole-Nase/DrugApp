<?php 
$pageTitle = "Drug Categories";
$section = null;

include("data.php");
include("functions.php");

include 'header.php';?>


	<div class="wrapper">
    <h1>Drug Categories</h1>
    <ul class="items">
        <?php
        // Loop through categories and generate HTML for each
        foreach ($catalog as $id => $item) {
            if (isset($item["category"])) {
                echo get_category_html($id, $item);
            }
        }
        ?>
    </ul>
	</div>
</div>


<?php include 'footer.php';?>