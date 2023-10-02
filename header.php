<html>
<head>
	<title><?php echo $pageTitle;?></title>
	
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
    <div class="header">
    <header>
        <h1>Drug Info Center</h1>
        <?php
        session_start();

                if (isset($_SESSION['admin_id'])) {
                    echo '<h3><span>Welcome! , ' . $_SESSION['admin_username'] . '</span></h3>';
                }
                
        ?>
</header>
<nav>
    <ul>
        <li class="addDrug <?php if($section == "addDrug"){echo "on";}?>"><a href="addDrug.php?cat=addDrug">Add Drug</a></li>
        <li class="dashboard <?php if($section == "dashboard"){echo "on";}?>"><a href="dashboard.php">Dashboard</a></li>
        <li class="drugCategories <?php if($section == "drugCategories"){echo "on";}?>"><a href="drugCategories.php">Drug Categories</a></li>
        <!--<li class="dashboard <?php if($section == "null"){echo "on";}?>"><a href="dashboard.php?cat=dashboard">Dashboard</a></li>-->
        <!--<li class="drugCategories <?php if($section == "null"){echo "on";}?>"><a href="http://localhost/DrugApp/drugCategories.php">Drug Categories</a></li>-->
        <li><a href="logout.php">Logout</a></li>

    </ul>
</nav>

</div>
    <div class="container">