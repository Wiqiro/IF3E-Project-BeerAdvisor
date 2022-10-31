<?php

require_once("connection.php");
global $bdd;


$query = "SELECT * FROM beer /* WHERE name LIKE ? ORDER BY name */";
if (isset($_GET['search'])) {
	$query = $query . "WHERE name LIKE '%" . $_GET['search'] . "%'";
	
} else {

}
$query = $query . "ORDER BY name";

$request = $bdd->prepare($query);
$request->execute();
$data = $request->fetch();
	
?>

<html>
   <head>
		<meta charset ="UTF-8">
		<meta name = "author" content="Quentin,Eloi,William">
		<meta name ="description" content="This is a page about beer">
		<link rel="shortcut icon" href="" type="image/x-icon">
		<link rel="stylesheet" href="page.css">
		<title>Beer advisor</title>
	</head>
	<body>

		<form action="" method="get">

			<input type="text" name="search" id="search" placeholder="Search a beer"><input type="submit" value="Search">

			<label for="SortBy">Sort by</label>
			<select name="SortBy" id="SortBy">
					<option value="RatingDesc">Rating: High to low</option>
					<option value="RatingAsc">Rating: Low to High</option>
					<option value="DateDesc">Date: New to old</option>
					<option value="DateAsc">Date: Old to new</option>
					<option value="NameAsc">Name: A-Z</option>
					<option value="NameDesc">Name: Z-A</option>
					<option value="AlcAsc">Alcohol: Low to High</option>
					<option value="AlcDesc">Alcohol: High to Low</option>

			</select>
			<label for="BeerColor">Color</label>
			<select name="BeerColor" id="BeerColor">
					<option></option>
					<option value="PaleStraw">Pale straw</option>
					<option value="Straw">Straw</option>
					<option value="PaleGold">Pale gold</option>
					<option value="DeepGold">Deep gold</option>
					<option value="PaleAmber">Pale amber</option>
					<option value="MediumAmber">Medium amber</option>
					<option value="DeepAmber">Deep Amber</option>
					<option value="AmberBrown">Amber brown</option>
					<option value="Brown">Brown</option>
					<option value="RubyBrown">Ruby brown</option>
					<option value="DeepBrown">Deep brown</option>
					<option value="Black">Black</option>
			</select>
			
		</form>


		<?php
		while ($data != null) {
			echo '<div> Name:' .$data['Name'] .
			'<a href="Show-Beer.php?id=' . $data['ID'] . '">  Show Beer</a>
			</div><br>'	;

			$data = $request->fetch();
		}
		?>
		
	</body>
</html>