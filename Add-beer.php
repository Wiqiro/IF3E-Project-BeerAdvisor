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

   $query = "INSERT INTO beer(name, alcohol, color, style, ibu, last_modified) VALUES(?,?,?,?,?,?)";
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
    <link rel="stylesheet" href="page.css">
    <title>Beer advisor</title>
</head>
<body>
    <h1 style="text-align:center"><strong>BeerAdvisor</strong></h1>
    <h2>Add a new beer</h2>

    <form action="" method="post">
      <label for="BeerName">Name of the beer</label><br>
      <input type="text" name="BeerName" id="BeerName"><br><br>

      <label for="BeerAlcohol">Alcohol level</label><br>
      <input type="number" name="BeerAlcohol" id="BeerAlcohol"><br><br>

      <label for="BeerColor">Color</label><br>
      <select name="BeerColor" id="BeerColor">
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
      </select><br><br>

      <label for="BeerStyle">Style</label><br>
      <select name="BeerStyle" id="BeerStyle">
         <option value="Lager">Lager / Pils (IBU 8 to 12)</option>
         <option value="Porter">Porter (IBU 20 to 40)</option>
         <option value="Stout">Stout (IBU 30 to 50)</option>
         <option value="PA">Pale Ale / English Bitter (IBU 30 to 40)</option>
         <option value="IPA">IPA (IBU 40 to 60)</option>
         <option value="DoubleIPA">Double IPA / Imperial IPA (IBU 60 to 100)</option>
         <option value="Barleywine">Barleywine (IBU 80 to 100)</option>
      </select><br><br>

      <label for="BeerIBU">IBU</label><br>
      <input type="number" name="BeerIBU" id="BeerIBU"><br><br>
      <input type="submit" value="Create" name="create">
    </form>

</body>
</html>