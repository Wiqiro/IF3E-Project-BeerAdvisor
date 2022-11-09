<?php
session_start();
require_once("connection.php");
global $bdd;

$id = $_SESSION['ID'];

if (isset($_GET['id']) and $_GET['id'] > 0){
$getid = intval($_GET['id']);
$req_profile = $bdd->prepare("SELECT * FROM user WHERE id = ?");
$req_profile->execute(array($_GET['id']));
$res = $req_profile->fetch();
$username = $res['Username'];
$date = $res['Creation_date'];
$bio = $res['Bio'];
?>


<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Beer advisor</title>
    <meta name="author" content="Quentin,Eloi,William">
    <meta name="description" content="This is a page about beer">
    <link rel="shortcut icon" href="logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<p class="logo"><a href="index.php"><img src="BeerAdvisor.png"></a></p>
<div>c'est le profil de <?php
    echo $username;
    echo '<br>';
    echo $date;
    echo '<br>bio<br>';
    echo $bio;
    echo '<br><href="Comment '
    ?>
    <br><?php
    if ($id == $getid) {
        echo '<a href="Edit-profile.php">Edit profile</a>';
        echo '<form action="" method="post">

        <label for="friend">Add friends :</label><br>
        <input type="text" name="add_friend" id="friend" required>
        <br>
        <label for="confirm"></label>
        <input type="submit" name="confirm" id="confirm">
    </form>';
    }
    }
    ?>



</div>
</body>
</html>