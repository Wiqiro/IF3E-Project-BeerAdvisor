<?php
$bdd = null;
try {
   $bdd = new PDO('mysql:host=localhost;dbname=beeradvisor;charset=utf8', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
   die('Error : ' . $e->getMessage()); // print the error message
}
?>