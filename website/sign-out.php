<?php
session_start();
require_once("connection.php");
global $bdd;
unset($_SESSION['ID']);
unset($_SESSION['Admin']);
header('location:Browse-Beers.php');
?>