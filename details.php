<?php 
include('data.php');
include('functions.php');

if(isset($_GET["id"])){
	$id = $_GET['id'];
	if(isset($catalog[$id])){
		$item = $catalog[$id];
	}
}

if (!isset($item)) {
	header("location:catalog.php");
	exit();
}

$pageTitle = $item["title"];
include('header.php');


?>

<div class="section page">

	<div class="wrapper">
		
		<div class="media-picture">
			<span>
				<img src="<?php echo $item["img"];?>" alt="<?php echo $item["title"];?>" />
			
			</span>
		</div>

		<div class="media-details">
			<h1><?php echo $item["title"];?><h1>
				<table>
					<tr>
						<th>Drug category</th>
						<td><?php echo $item["category"]?></td>
					</tr>
					<tr>
						<th>Definition</th>
						<td><?php echo $item["definition"]?></td>
					</tr>
					<tr>
						<th>Origin</th>
						<td><?php echo $item["origin"]?></td>
					</tr>

					<tr>
						<th>Examples</th>
						<td><?php echo implode(", ", $item["examples"]);?></td>
					</tr>
					<tr>
						<th>Effects</th>
						<td><?php echo implode(", ", $item["effects"]);?></td>
					</tr>
					<tr>
						<th>HELP</th>
						<td><?php echo $item["help"]?></td>
					</tr>
					


				</table>
		</div>
	   
		
	</div>
	
</div>
                    </div>

                    <?php include 'footer.php';?>
