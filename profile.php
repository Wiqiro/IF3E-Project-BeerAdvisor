<?php
session_start();
require_once("connection.php");
global $bdd;

$id = $_SESSION['ID'];

if(isset($_GET['id']) AND $_GET['id'] > 0){
    $getid = intval($_GET['id']);
    $req_profile = $bdd->prepare("SELECT * FROM user WHERE id = ?");
    $req_profile->execute(array($_GET['id']));
    $res = $req_profile->fetch();
    $username = $res['Username'];
    $date = $res['Creation_date'];
    if($id == $getid){
?> <a href="Edit-profile.txt">Edit profile</a>
    <?php
    }
}
    ?>



<html lang ="fr">
    <head>
        <meta charset ="UTF-8">
        <title>Beer advisor</title>
        <meta name = "author" content="Quentin,Eloi,William">
        <meta name ="description" content="This is a page about beer">
        <link rel="shortcut icon" href="logo.jpg" type="image/x-icon">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div>c'est le profil de <?php
            echo $username;
            echo $date;
            ?>
        </div>

    </body>
</html>