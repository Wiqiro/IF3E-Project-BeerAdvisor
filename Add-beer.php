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

	$query = "INSERT INTO beer(name, alcohol, color_id, style, ibu, last_modified) VALUES(?,?,?,?,?,?)";
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
	<p class="logo"><a href="index.php"><img src="BeerAdvisor.png"></a></p>

	 <h2>Add a new beer</h2>

	 <form action="" method="post">
		<label for="BeerName">Name of the beer</label><br>
		<input type="text" name="BeerName" id="BeerName" required><br><br>

		<label for="BeerAlcohol">Alcohol level</label><br>
		<input type="number" name="BeerAlcohol" id="BeerAlcohol" required><br><br>

		<label for="BeerColor">Color</label><br>
		<select name="BeerColor" id="BeerColor" required>
				<option value="1">Straw</option>
				<option value="2">Gold</option>
				<option value="3">Amber</option>
				<option value="4">Brown</option>
				<option value="5">Black</option>
		</select><br><br>

		<label for="BeerStyle">Style</label><br>
		<select name="BeerStyle" id="BeerStyle" required>
			<option></option>
			<option value="1">Lager / Pils (IBU 8 to 12)</option>
			<option value="2">Porter (IBU 20 to 40)</option>
			<option value="3">Stout (IBU 30 to 50)</option>
			<option value="4">Pale Ale / English Bitter (IBU 30 to 40)</option>
			<option value="5">IPA (IBU 40 to 60)</option>
			<option value="5">Double IPA / Imperial IPA (IBU 60 to 100)</option>
			<option value="5">Barleywine (IBU 80 to 100)</option>
		</select><br><br>

		<label for="BeerIBU">IBU</label><br>
		<input type="number" name="BeerIBU" id="BeerIBU" required><br><br>
		<input type="submit" value="Create" name="create">
	 </form>

</body>
</html>