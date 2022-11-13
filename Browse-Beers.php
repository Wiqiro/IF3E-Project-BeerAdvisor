<?php
session_start();
require_once("connection.php");
global $bdd;


$query = "SELECT B.ID AS ID, Name, Alcohol, IBU, Style, Color, DATE_FORMAT(Last_modified, '%D %b. %Y') AS Last_modified, B.Picture AS Picture, Last_modified AS RawDate, AVG(C.grade) AS Grade, COUNT(C.grade) AS Count 
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
	case "RevDesc":
		$query = $query . "Count DESC";
		break;
	case "RevAsc":
		$query = $query . "Count ASC";
		break;
	default:
		break;
	}
} else {
	$query = $query . "RawDate DESC";
}
$query = $query . ", Name ASC, RawDate DESC";

$request = $bdd->prepare($query);
$request->execute();
$data = $request->fetch();


$color_req = $bdd->prepare("SELECT * FROM color WHERE ID != 0 ORDER BY ID");
$color_req->execute();
$color_data = $color_req->fetch();

$style_req = $bdd->prepare("SELECT * FROM style WHERE ID != 0 ORDER BY ID");
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
			if (isset($_SESSION['Admin'])) {
				echo '<button onclick="window.location.href=`Admin.php`">Manage</button> ';
			}
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
		<div class=<?php if (isset($_GET['search'])) {echo '"beer_search"';} else {echo '"beer_search_center"';}?>  >
			<br>
			<form action="" method="get">
                <label>
                    <input type="search" name="search" placeholder="Search a beer" size="100">
                </label><input type="submit" value="Search">
				<br>
				<label for="SortBy">Sort by</label>
                <label>
                    <select name="SortBy">
                        <option value="DateDesc">Newest first</option>
                        <option value="DateAsc">Oldest first</option>
                        <option value="RatingDesc">Best reviews first</option>
                        <option value="RatingAsc">Worst reviews first</option>
                        <option value="NameAsc">Alphabetical</option>
                        <option value="NameDesc">Alphabetical (reverse)</option>
                        <option value="AlcDesc">Highest alc. first</option>
                        <option value="AlcAsc">Lowest alc. first</option>
                        <option value="RevDesc">Most reviewed first</option>
                        <option value="RevAsc">Least reviewed first</option>
                    </select>
                </label>

                <label>
                    <select name="BeerColor">
                        <option value="">Any color</option>
                        <?php
                        while ($color_data != null) {
                            echo '<option value="' . $color_data['ID'] . '">' . $color_data['Color'] . '</option>';
                            $color_data = $color_req->fetch();
                        }
                        ?>
                    </select>
                </label>

                <label>
                    <select name="BeerStyle">
                        <option value="">Any style</option>
                        <?php
                        while ($style_data != null) {
                            echo '<option value="' . $style_data['ID'] . '">' . $style_data['Style'] . '</option>';
                            $style_data = $style_req->fetch();
                        }
                        ?>
                    </select>
                </label>

                <label for="MinAlc">Alc:</label>
                <label>
                    <input type="number" name="MinAlc" size="4" min="0" max="67.5" step="0.1" placeholder="Min">
                </label>
                <label>
                    <input type="number" name="MaxAlc" size="4" min="0" max="67.5" step="0.1" placeholder="Max">
                </label>
				<?php if (isset($_SESSION['ID'])) {echo 'You can also <u><a href="Add-beer.php">add a beer</a></u>';} ?>
                
			</form>

		</div><br>
		<?php
		if (isset($_GET['search'])) {
			
			echo '<div><hr>';
			while ($data != null) {
				echo '<div class="BeerSearchResults">
                <table style="width: 100%">
                <tr style="vertical-align: top">
                <td>';
					if ($data['Picture'] != '') {
						echo '<img class="beer_image" id="profile_picture" src="data:image;base64,' . base64_encode($data["Picture"]) . '" alt=""/>';
					} else {
						echo '<img class="beer_image" id="profile_picture" src="empty_beer.jpg" alt=""/>';
					}
				echo '</td>
				<td>
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
					echo '</a> ' . number_format($data['Grade'], 1) . ' (' . $data['Count'] . ' review';
					if ($data['Count'] > 1) {echo 's';}
					echo ')';
				} else {
					echo '?';
				}
				echo  '<br>

				Last modified: ' . $data['Last_modified'] . '<br>
				Alc: ' . $data['Alcohol'] . '<br>
				Style: ' . $data['Style'] . '<br>
				Color: ' . $data['Color'] . '<br>	
                    </td>			
					</tr>
				</a>
                </table>
				</div>';
				$data = $request->fetch();
			}
			echo '</div>';
		} ?>
	</body>
</html>