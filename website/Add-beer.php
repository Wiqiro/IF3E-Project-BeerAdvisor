<?php
session_start();
require_once("connection.php");
global $bdd;

if (isset($_POST['create'])) {
	$name = htmlspecialchars($_POST['BeerName']);
	$color = htmlspecialchars($_POST['BeerColor']);
	$ibu = htmlspecialchars($_POST['BeerIBU']);
	$alcohol = htmlspecialchars($_POST['BeerAlcohol']);
	$style = htmlspecialchars($_POST['BeerStyle']);
	$date = date("Y-m-d H:i:s");
	if (isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] != '') {
		$size = filesize($_FILES['image']['tmp_name']);
		if ($size < 1000000) {
			$image = file_get_contents($_FILES['image']['tmp_name']);
		}
	}
	if (!isset($size)) {
		$query = "INSERT INTO beer(name, alcohol, color_id, style_id, ibu, last_modified) VALUES(?,?,?,?,?,?)";
		$request = $bdd->prepare($query);
		$request->execute(array($name, $alcohol, $color, $style, $ibu, $date));
		header("Location:Browse-Beers.php");
	} else if ($size < 1000000) {
		$query = "INSERT INTO beer(name, alcohol, color_id, style_id, ibu, last_modified, picture) VALUES(?,?,?,?,?,?,?)";
		$request = $bdd->prepare($query);
		$request->execute(array($name, $alcohol, $color, $style, $ibu, $date, $image));
		header("Location:Browse-Beers.php");
	}

	
} 
$color_req = $bdd->prepare("SELECT * FROM color ORDER BY ID");
$color_req->execute();
$color_data = $color_req->fetch();

$style_req = $bdd->prepare("SELECT * FROM style ORDER BY ID");
$style_req->execute();
$style_data = $style_req->fetch();
?>


<html lang ="en">
<head>
	 <meta charset ="UTF-8">
	 <meta name = "author" content="Quentin,Eloi,William">
	 <meta name ="description" content="This is a page about beer">
	 <link rel="shortcut icon" href="" type="image/x-icon">
	 <link rel="stylesheet" href="style.css">
	 <title>Beer advisor</title>
</head>
<body>
	<!-- HEADER -->
	<div class="header">
		<div class="image"><a href="Browse-Beers.php"><img src="BeerAdvisor.png" alt="logo"></a></div>
		<div class="header_title">Add beer</div>
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


	<form style="margin: 20px" action="" method="post" enctype="multipart/form-data">
		<label for="BeerName">Name of the beer</label><br>
		<input type="text" name="BeerName" maxlength="100" style="max-width:238px" required><br><br>

		<label for="BeerAlcohol">Alcohol level</label><br>
		<input type="number" step="0.1" min="0" max="67.5" name="BeerAlcohol" required><br><br>

		<label for="BeerColor">Color</label><br>
		<select name="BeerColor">
			<?php
			while ($color_data != null) {
				echo '<option value="' . $color_data['ID'] . '">' . $color_data['Color'] . '</option>';
				$color_data = $color_req->fetch();
			}
			?>
		</select><br><br>

		<label for="BeerStyle">Style</label><br>
		<select name="BeerStyle" id="Style" required>
			<?php
			while ($style_data != null) {
				echo '<option value="' . $style_data['ID'] . '">' . $style_data['Style'] . '</option>';
				$style_data = $style_req->fetch();
			}
			?>
		</select><br><br>

		<label for="BeerIBU">IBU</label><br>
		<input type="number" min="0" max="2600" name="BeerIBU"><br><br>

		<label for="image">Picture</label><br>
		<input type="file" name="image" accept=".jpg, .jpeg, .png">
		<?php
		if (isset($size) && $size >= 1000000) {
			echo "The maximum upload size is 1 mb";
		}
		?>
		<br><br>

		<input type="submit" value="Create" name="create">

	 </form>

</body>
</html>