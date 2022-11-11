<?php
session_start();
require_once("connection.php");
global $bdd;


$query = "SELECT B.ID AS ID, Name, Alcohol, IBU, Style, Color, DATE_FORMAT(Last_modified, '%D %b. %Y') AS Last_modified, AVG(C.grade) AS Grade 
		FROM beer B INNER JOIN color ON color.ID = B.Color_ID INNER JOIN style ON style.ID = B.Style_ID LEFT JOIN comment C
		ON B.id = C.Beer_id ";


if (isset($_GET['search'])) {
	$query = $query . "WHERE name LIKE '%" . $_GET['search'] . "%'";
	
	if ($_GET['BeerColor'] != '') {
		$query = $query . " AND B.Color_ID = " . $_GET['BeerColor'];
	}
	if ($_GET['BeerStyle'] != '') {
		$query = $query . " AND B.Style_ID = " . $_GET['BeerStyle'];
	}
	if ($_GET['MinAlc'] != '') {
		$query = $query . " AND B.Alcohol >= " . $_GET['MinAlc'];
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

			<select name="BeerColor">
				<option value="">Any color</option>
				<option value="1">Straw</option>
				<option value="2">Gold</option>
				<option value="3">Amber</option>
				<option value="4">Brown</option>
				<option value="5">Black</option>
			</select>

			<select name="BeerStyle">
				<option value="">Any style</option>
				<option value="1">Lager / Pils</option>
				<option value="2">Porter</option>
				<option value="3">Stout</option>
				<option value="4">Pale Ale / English Bitter</option>
				<option value="5">IPA</option>
				<option value="6">Double IPA / Imperial IPA</option>
				<option value="7">Barleywine</option>
			</select>
			
			<label for="MinAlc">Alc:</label>
			<input type="number" name="MinAlc" size="4" min="0" max="67.5" step="0.1" placeholder="Min">
			<input type="number" name="MaxAlc" size="4" min="0" max="67.5" step="0.1" placeholder="Max">
			

		</form>

		<div class="BeerSearchResults">
			<?php
			while ($data != null) {
				echo '<a href="Show-Beer.php?id=' . $data['ID'] . '" class="BeerContainer">
				<h3><strong>Name: ' . $data['Name'] . '</strong></h3>
				Avg grade: ';
				if ($data['Grade'] != 0) {
					echo '<a class="stars">';
					$i = 0;
					while ($i < round($data['Grade'], 0, PHP_ROUND_HALF_ODD)) {
						echo '★';
						$i++;
					}
					while ($i < 5) {
						echo '☆';
						$i++;
					}
					echo '</a> ' . number_format($data['Grade'], 1);
				} else {
					echo '?';
				}
				echo  '<br>
				Last modified: ' . $data['Last_modified'] . '<br>
				Alc: ' . $data['Alcohol'] . '<br>
				Style: ' . $data['Style'] . '<br>
				Color: ' . $data['Color'] . '<br>				
				</a><br><br>';

				$data = $request->fetch();
			}?>
			
		</div>
		
	</body>
</html>