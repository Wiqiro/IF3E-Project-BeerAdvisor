<?php
require_once("connection.php");

global $bdd;

if (isset($_POST['create'])) {
	$name = htmlspecialchars($_POST['BeerName']);
	$color = htmlspecialchars($_POST['BeerColor']);
	$ibu = htmlspecialchars($_POST['BeerIBU']);
	$alcohol = htmlspecialchars($_POST['BeerAlcohol']);
	$style = htmlspecialchars($_POST['BeerStyle']);
	$date = date("Y-m-d");

	$query = "INSERT INTO beer(name, alcohol, color_id, style_id, ibu, last_modified) VALUES(?,?,?,?,?,?)";
	$request = $bdd->prepare($query);
	$request->execute(array($name, $alcohol, $color, $style, $ibu, $date));
	header("Location:index.php");
} ?>


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
		<div class="header_title">Add a beer</div>
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


	 <form action="" method="post">
		<label for="BeerName">Name of the beer</label><br>
		<input type="text" name="BeerName" required><br><br>

		<label for="BeerAlcohol">Alcohol level</label><br>
		<input type="number" step="0.1" min="0" max="67.5" name="BeerAlcohol" required><br><br>

		<label for="BeerColor">Color</label><br>
		<select name="BeerColor" required>
				<option value="1">Straw</option>
				<option value="2">Gold</option>
				<option value="3">Amber</option>
				<option value="4">Brown</option>
				<option value="5">Black</option>
		</select><br><br>

		<label for="BeerStyle">Style</label><br>
		<select name="BeerStyle" id="Style" required>
			<option></option>
			<option value="1">Lager / Pils</option>
			<option value="2">Porter</option>
			<option value="3">Stout</option>
			<option value="4">Pale Ale / English Bitter</option>
			<option value="5">IPA</option>
			<option value="6">Double IPA / Imperial IPA</option>
			<option value="7">Barleywine</option>
		</select><br><br>

		<label for="BeerIBU">IBU</label><br>
		<input type="number" name="BeerIBU" ><br><br>
		<input type="submit" value="Create" name="create">
	 </form>

</body>
</html>