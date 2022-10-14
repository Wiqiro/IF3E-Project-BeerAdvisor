<html>
   <title>Beer advisor</title>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
   $beer_name = $_GET["BeerName"];

   $db = new PDO("mysql:host=localhost;dbname=beeradvisor;charset=utf8", "root", "");
   /* $db->exec("INSERT INTO beer (name) VALUES ('bonjour')"); */
   $req = $db->prepare("INSERT INTO beer (name) VALUES (':name')");
   $req->execute(["name" => $_POST["test"]]);
}?>