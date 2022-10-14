<html lang ="en">
<head>
    <meta charset ="UTF-8">
    <meta name = "author" content="Quentin,Eloi,William">
    <meta name ="description" content="This is a page about beer">
    <link rel="shortcut icon" href="" type="image/x-icon">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Beer advisor</title>
</head>
<body>
    <h1 style="text-align:center"><strong>BeerAdvisor</strong></h1>
    <h2>Add a new beer</h2>
    <form action="new_beer.php">
        <label for="BeerName">Name of the beer</label><br>
        <input type="text" name="BeerName" id="BeerName"><br><br>

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

        <label for="BeerIBU">IBU</label><br>
        <input type="number" name="BeerIBU" id="BeerIBU"><br><br>

        <input type="submit" value="Create">
    </form>

    <?php

        /* $db = new PDO("mysql:host=localhost;dbname=beeradvisor.sql;charset=utf8", "root", "");
        $req = $db->prepare("INSERT INTO beer (Name, Alcohol, IBU, Aroma, Clarity, Style) VALUES ("Beer1", 784, 20, "Grass", "Straw", "IBU");
 */
        echo "bonjour"; 
    ?>
    
</body>
</html>