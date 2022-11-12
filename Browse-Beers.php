<?php
session_start();
require_once("connection.php");
global $bdd;


$query = "SELECT B.ID AS ID, Name, Alcohol, IBU, Style, Color, DATE_FORMAT(Last_modified, '%D %b. %Y') AS Last_modified, Last_modified AS RawDate, AVG(C.grade) AS Grade 
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
	if ($_GET['MaxAlc'] != '') {
		$query = $query . " AND B.Alcohol <= " . $_GET['MaxAlc'];
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
		$query = $query . "RawDate DESC";
		break;
	case "DateAsc":
		$query = $query . "RawDate ASC";
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
	$query = $query . "RawDate DESC";
}

$request = $bdd->prepare($query);
$request->execute();
$data = $request->fetch();


$color_req = $bdd->prepare("SELECT * FROM color ORDER BY ID");
$color_req->execute();
$color_data = $color_req->fetch();

$style_req = $bdd->prepare("SELECT * FROM style ORDER BY ID");
$style_req->execute();
$style_data = $style_req->fetch();
	
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
			<div class="image"><a href="Browse-Beers.php"><img src="BeerAdvisor.png" alt="logo"></a></div>
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
				<?php
				while ($color_data != null) {
					echo '<option value="' . $color_data['ID'] . '">' . $color_data['Color'] . '</option>';
					$color_data = $color_req->fetch();
				}
				?>
			</select>

			<select name="BeerStyle">
				<option value="">Any style</option>
				<?php
				while ($style_data != null) {
					echo '<option value="' . $style_data['ID'] . '">' . $style_data['Style'] . '</option>';
					$style_data = $style_req->fetch();
				}
				?>
			</select>
			
			<label for="MinAlc">Alc:</label>
			<input type="number" name="MinAlc" size="4" min="0" max="67.5" step="0.1" placeholder="Min">
			<input type="number" name="MaxAlc" size="4" min="0" max="67.5" step="0.1" placeholder="Max">
		</form>
		<div >
			<?php
			while ($data != null) {

				echo '<div class="BeerSearchResults">
                <a href="Show-Beer.php?id=' . $data['ID'] . '" class="BeerContainer">
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
				</a><br><br>
				</div>';
				$data = $request->fetch();
			}?>
			
		</div>
		
	</body>
</html>