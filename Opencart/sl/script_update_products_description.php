<?php
$hostname = "localhost";
$username = "root";
$password = "";

// Create connection
$dbh1 = new mysqli($hostname, $username, $password, "opencart_stonemysql"); // From database connection
$dbh2 = new mysqli($hostname, $username, $password, "opencart_stoneledgeoc_new"); //updated database connection

// Check connection
if ($dbh1->connect_error || $dbh2->connect_error) {
  die("Connection failed: " . $dbh1->connect_error);
  die("Connection failed: " . $dbh2->connect_error);
}

// CF Share update in OC products Start here
$share = "SELECT * FROM `opencart_stonemysql`.`dbo.share` WHERE 1";
$shareresult = $dbh1->query($share);
echo "<pre>";
$titlemapping = array(
	"Vegetables" => array("vegetables","vegetable","Vegetable Share5","Vegetable Share1","Vegetable Share","Vegetables Available This Week"),
	"Fruit" => array("fruits", "fruit","Fruit Share7", "Fruit Share", "Fruit Share1"),
	"Mushrooms" => array("Mushrooms", "Mushroom", "Mushroom Share"),
	"Mushrooms in Bulk" => array("Mushrooms", "Mushroom", "Mushroom Share"),
	"Dry Beans" => array("Dry Beans", "Dry Bean","Dry Bean Share", "Dry Beans Share", "Dry Beans- Black Turtle", "Dry Beans- Pinto", "Dry Beans- Kidney Beans, Light Red", "Dry Beans- Cannellini", "Dry Beans- Cranberry"),
	"Seed Oils" => array("Seed Oils", "Seed Oil","Seed Oil- Sunflower Seed, Organic","Seed Oil- Roasted Pumpkin Seed Oil", "Seed Oil- Flax Seed, Organic", "Seed Oil- Butternut Squash"),
	"Caps" => array("Caps", "Cap"),
	"Nuts" => array("Nuts", "Nut"),
	"Seed Snacks" => array("Seed Snacks", "Seed Snack"),
	"Herbs" => array("Herbs", "Herb"),
	"Maple Syrup" => array("Maple Syrup Grade A  Light", "Maple Syrup Grade A Dark"),
	"Coffee and Tea" => array("Coffee and Tea", "Coffee", "Tea","Coffee Espresso","Coffee Farm  Blend","Coffee Farm  Blend","Coffee Share1","Coffee Share7","Coffee Share","Coffee Decaffeinated Medium Roast",  "Coffee Honduran Cloud Forest Dark Roast", "Coffee Guatemalan Highlands Medium Cafe  Roast"),
	"Fruit in Bulk" => array("Fruit in Bulk", "Fruit", "Fruit Available This Week"),
	"Vinegar" => array("Vinegar- Black Raspberry Vinegar", "Vinegar- Balsamic Apple", "Vinegar- Black Raspberry Vinegar","Vinegar- Fire Apple Vinegar","Vinegar- Maple Vinegar","Vinegar- Peach Vinegar"),
);

if ($shareresult->num_rows > 0) {
  	// output data of each row
	while($row = $shareresult->fetch_assoc()) {
		if(isset($row['htmlList'])){
			if (array_key_exists($row['title'],$titlemapping))
		  	{
			  	$title = $titlemapping[$row['title']];
			  	$query_parts = array();
				foreach ($title as $val) {
				    $query_parts[] = "'".mysqli_real_escape_string($dbh2,$val)."'";
				}

				$title = implode(' OR oc_product_description.name = ', $query_parts);
		  	}
			else
		  	{
				$title = "'".mysqli_real_escape_string($dbh2, $row['title'])."'";
		  	}
			$replaced_text = str_replace("../../img/uploads/image/","https://stoneledge.opencartdev.work/image/catalog/", $row['htmlList']);
			$replaced_text = str_replace("https://www.stoneledge.farm","https://stoneledge.opencartdev.work", $replaced_text);
			$replaced_text_2 = str_replace("../../img/uploads/image/","https://stoneledge.opencartdev.work/image/catalog/", $row['description']);
			$replaced_text_2 = str_replace("https://www.stoneledge.farm","https://stoneledge.opencartdev.work", $replaced_text_2);
			$replaced_text_new = mysqli_real_escape_string($dbh2, $replaced_text);
			$replaced_text2_new = mysqli_real_escape_string($dbh2, $replaced_text_2);
					
			$sql_update =	"UPDATE `oc_product_description`
				INNER JOIN
				oc_product ON oc_product_description.product_id = oc_product.product_id 
				SET oc_product_description.description = '$replaced_text_new',
				    oc_product_description.short_description = '$replaced_text2_new'
				WHERE (oc_product_description.name = $title) 
				AND oc_product.product_type IN (2,3,4)";
			if (mysqli_query($dbh2, $sql_update)) {
		      	echo "Record updated successfully share.<br/>";
		   	} else {
		      	echo "Error updating record 1: " . mysqli_error($dbh2);
		   	}
	   	}
	}
} else {
  echo "0 results share";
}
// CF Share update in OC products End here 

// CF Shareoption update in OC products start here
$shareOptions = "SELECT * FROM `opencart_stonemysql`.`dbo.shareOptions` WHERE 1";
$shareOptionsresult = $dbh1->query($shareOptions);

if ($shareOptionsresult->num_rows > 0) {
  	// output data of each row
  	$row = array();
	while($row = $shareOptionsresult->fetch_assoc()) {

		if(isset($row['description'])){
			if (array_key_exists($row['title'],$titlemapping))
		  	{
			  	$title = $titlemapping[$row['title']];
			  	$query_parts = array();
				foreach ($title as $val) {
				    $query_parts[] = "'".mysqli_real_escape_string($dbh2,$val)."'";
				}

				$title = implode(' OR oc_product_description.name = ', $query_parts);
		  	}
			else
		  	{
				$title = "'".mysqli_real_escape_string($dbh2, $row['title'])."'";
		  	}
			$replaced_text_so = str_replace("../../img/uploads/image/","https://stoneledge.opencartdev.work/image/catalog/", $row['description']);
			$replaced_text_so = str_replace("https://www.stoneledge.farm","https://stoneledge.opencartdev.work", $replaced_text_so);
			$replaced_text_so_new = mysqli_real_escape_string($dbh2, $replaced_text_so);
					
			$sql_update =	"UPDATE `oc_product_description`
				INNER JOIN
				oc_product ON oc_product_description.product_id = oc_product.product_id 
				SET oc_product_description.description = '$replaced_text_so_new'
				WHERE (oc_product_description.name = $title)
				AND oc_product.product_type IN (2,3,4)";
			
			// print_r($sql_update_so);
			if (mysqli_query($dbh2, $sql_update)) {
		      	echo "Record updated successfully shareoption.<br/>";
		   	} else {
		      	echo "Error updating record 2: " . mysqli_error($dbh2);
		   	}
		}
	}
} else {
  echo "0 results shareoption";
}
// CF Shareoption update in OC products start here


// CF marketplace update in OC products start here
$marketplace = "SELECT * FROM `opencart_stonemysql`.`dbo.marketplace` WHERE 1";
$marketplaceresult = $dbh1->query($marketplace);

if ($marketplaceresult->num_rows > 0) {
  	// output data of each row
	$row = array();
	while($row = $marketplaceresult->fetch_assoc()) {
		if(isset($row['htmlList'])){
			if (array_key_exists($row['title'],$titlemapping))
		  	{
			  	$title = $titlemapping[$row['title']];
			  	$query_parts = array();
				foreach ($title as $val) {
				    $query_parts[] = "'".mysqli_real_escape_string($dbh2,$val)."'";
				}

				$title = implode(' OR oc_product_description.name = ', $query_parts);
		  	}
			else
		  	{
				$title = "'".mysqli_real_escape_string($dbh2, $row['title'])."'";
		  	}
			$replaced_text_mp = str_replace("../../img/uploads/image/","https://stoneledge.opencartdev.work/image/catalog/", $row['htmlList']);
			$replaced_text_mp = str_replace("https://www.stoneledge.farm","https://stoneledge.opencartdev.work", $replaced_text_mp);
			$replaced_text_mp_new = mysqli_real_escape_string($dbh2, $replaced_text_mp);
					
			$sql_update =	"UPDATE `oc_product_description`
				INNER JOIN
				oc_product ON oc_product_description.product_id = oc_product.product_id 
				SET oc_product_description.description = '$replaced_text_mp_new'
				WHERE (oc_product_description.name = $title) 
				AND oc_product.product_type IN (1)";
			if (mysqli_query($dbh2, $sql_update)) {
		      	echo "Record updated successfully marketplace.<br/>";
		   	} else {
		      	echo "Error updating record 3: " . mysqli_error($dbh2);
		   	}
	   	}
	}
} else {
  echo "0 results marketplace";
}
// CF marketplace update in OC products start here


// CF product update in OC products start here
$products = "SELECT * FROM `opencart_stonemysql`.`dbo.products` JOIN `opencart_stonemysql`.`dbo.productOptions` WHERE 1";
$productsresult = $dbh1->query($products);
if ($productsresult->num_rows > 0) {
  	// output data of each row
  	$row = array();
	while($row = $productsresult->fetch_assoc()) {
		if(isset($row['htmlList'])){
			if (array_key_exists($row['title'],$titlemapping))
		  	{
			  	$title = $titlemapping[$row['title']];
			  	$query_parts = array();
				foreach ($title as $val) {
				    $query_parts[] = "'".mysqli_real_escape_string($dbh2,$val)."'";
				}

				$title = implode(' OR oc_product_description.name = ', $query_parts);
		  	}
			else
		  	{
				$title = "'".mysqli_real_escape_string($dbh2, $row['title'])."'";
		  	}
			$replaced_text = str_replace("../../img/uploads/image/","https://stoneledge.opencartdev.work/image/catalog/", $row['description']);
			$replaced_text_new = mysqli_real_escape_string($dbh2, $replaced_text);
					
			$sql_update =	"UPDATE `oc_product_description`
				INNER JOIN
				oc_product ON oc_product_description.product_id = oc_product.product_id 
				SET oc_product_description.description = '$replaced_text_new'
				WHERE (oc_product_description.name = $title)
				AND oc_product.product_type IN (1)";
			
			if (mysqli_query($dbh2, $sql_update)) {
		      	echo "Record updated successfully products.<br/>";
		   	} else {
		      	echo "Error updating record 4: " . mysqli_error($dbh2);
		   	}
		}
	}
} else {
  echo "0 results product";
}
// CF product update in OC products start here


$dbh1->close();
echo "</pre>";