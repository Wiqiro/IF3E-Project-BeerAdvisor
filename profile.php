<?php
session_start();
require_once("connection.php");
global $bdd;

$id = $_SESSION['ID'];
$username = $_SESSION['Username'];


?>

<html lang ="fr">
<head>
<meta charset ="UTF-8">
<title>Beer advisor</title>
<meta name = "author" content="Quentin,Eloi,William">
<meta name ="description" content="This is a page about beer">
<link rel="shortcut icon" href="logo.jpg" type="image/x-icon">
<link rel="stylesheet" href="page.css">
</head>
<body>

<div>c'est le profil de <?php
    echo $username;
    ?> </div>



</body>
</html>