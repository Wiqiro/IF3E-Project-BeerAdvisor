<?php
require_once("connection.php");
global $bdd;
session_start();
$id = $_SESSION['id'];

if(isset($_SESSION['id'])){
    $query = "SELECT * FROM user WHERE id = ?";
    $req = $bdd->prepare($query);
    $req->execute(array($id));
    $res = $req->fetch(PDO::FETCH_ASSOC);
}


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
<div class="profile"><img src="user_icon.png" class="image_icon" alt="icon">Name<br>Bio</div>




</body>
</html>