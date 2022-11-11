<?php
session_start();
require_once("connection.php");
global $bdd;


$query = "SELECT B.ID AS ID, Name, Alcohol, IBU, Style, Color, Last_modified, AVG(C.grade) AS Grade 
		FROM beer B INNER JOIN color ON color.ID = B.Color_ID INNER JOIN style ON style.ID = B.Style_ID LEFT JOIN comment C
		ON B.id = C.Beer_id ";


if (isset($_GET['search'])) {
	$query = $query . "WHERE name LIKE '%" . $_GET['search'] . "%'";
	
	if ($_GET['BeerColor'] != '') {
		$query = $query . " AND B.color_ID = " . $_GET['BeerColor'];
	}
	if ($_GET['BeerStyle'] != '') {
		$query = $query . " AND B.style_ID = " . $_GET['BeerStyle'];
	}
	
}
$query = $query . " GROUP BY B.id ORDER BY ";

if (isset($_GET['SortBy'])) {
	$sorting = $_GET['SortBy'];
	switch ($sorting) {
	case "RatingDesc":
		$query = $query . "Grade DESC";
		break;
	case "RatingAsc":
		$query = $query . "Grade ASC";
		break;
	case "DateDesc":
		$query = $query . "Last_modified DESC";
		break;
	case "DateAsc":
		$query = $query . "Last_modified ASC";
		break;
	case "NameAsc":
		$query = $query . "Name ASC";
		break;
	case "NameDesc":
		$query = $query . "Name DESC";
		break;
	case "AlcAsc":
		$query = $query . "Alcohol ASC";
		break;
	case "AlcDesc":
		$query = $query . "Alcohol DESC";
		break;
	default:
		break;
	}
} else {
	$query = $query . "Last_modified DESC";
}


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
		<link rel="stylesheet" href="style.css" type="text/css">
		<title>Beer advisor</title>
	</head>
	<body>
		<!-- HEADER -->
		<div class="header">
			<div class="image"><a href="index.php"><img src="BeerAdvisor.png" alt="logo"></a></div>
			<div class="header_title">Browse beers</div>
			<div class="header_buttons">
				<?php
				if (isset($_SESSION['ID'])) {
					echo '<button onclick="window.location.href=`Profile.php?id=' . $_SESSION['ID'] . '`">Profile</button>
					<button onclick="window.location.href=`sign-out.php`">Sign-out</button>';
				} else {
					echo '<button onclick="window.location.href=`Sign-in.php`">Sign-in</button>
					<button onclick="window.location.href=`Sign-up.php`">Sign-up</button>';
				}
				?>
			</div>
		</div>
		<hr>
		<!-- HEADER -->
		<form action="" method="get">
			<input type="search" name="search" placeholder="Search a beer" size="100"><input type="submit" value="Search">
			You can also <u><a href="Add-beer.php">add a beer</a></u>
			<br>

			<label for="SortBy">Sort by</label>
			<select name="SortBy">
				<option value="DateDesc">Date: New to old</option>
				<option value="DateAsc">Date: Old to new</option>
				<option value="RatingDesc">Rating: High to low</option>
				<option value="RatingAsc">Rating: Low to High</option>
				<option value="NameAsc">Name: A-Z</option>
				<option value="NameDesc">Name: Z-A</option>
				<option value="AlcAsc">Alcohol: Low to High</option>
				<option value="AlcDesc">Alcohol: High to Low</option>
			</select>

			<label for="BeerColor">Color</label>
			<select name="BeerColor">
				<option></option>
				<option value="1">Straw</option>
				<option value="2">Gold</option>
				<option value="3">Amber</option>
				<option value="4">Brown</option>
				<option value="5">Black</option>
			</select>

			<label for="BeerStyle">Style</label>
			<select name="BeerStyle">
				<option></option>
				<option value="1">Lager / Pils</option>
				<option value="2">Porter</option>
				<option value="3">Stout</option>
				<option value="4">Pale Ale / English Bitter</option>
				<option value="5">IPA</option>
				<option value="6">Double IPA / Imperial IPA</option>
				<option value="7">Barleywine</option>
			</select>

		</form>

		<div class="BeerSearchResults">
			<?php
			while ($data != null) {
				echo '<a href="Show-Beer.php?id=' . $data['ID'] . '" class="BeerContainer">
				<h3><strong>Name: ' . $data['Name'] . '</strong></h3>
				Last modified: ' . $data['Last_modified'] . '<br>
				Avg grade: ' . number_format($data['Grade'], 1) . ' / 5<br>
				Alc: ' . $data['Alcohol'] . '<br>
				Style: ' . $data['Style'] . '<br>
				Color: ' . $data['Color'] . '<br>				
				</a><br><br>';

				$data = $request->fetch();
			}?>
			
		</div>
		
	</body>
</html>