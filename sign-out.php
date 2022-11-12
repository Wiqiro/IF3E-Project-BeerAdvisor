<?php
session_start();
require_once("connection.php");
global $bdd;
$_SESSION['ID'] = 0;
header('location:Browse-Beers.php');
?>