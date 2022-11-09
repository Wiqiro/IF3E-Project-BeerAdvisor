<?php
session_start();
?>


<html lang ="en">
<head>
    <meta charset ="UTF-8">
    <meta name = "author" content="Quentin,Eloi,William">
    <meta name ="description" content="This is a page about beer">
    <link rel="shortcut icon" href="" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title>Beer advisor</title>
</head>
<body>
    <p class="logo"><a href="index.php"><img src="BeerAdvisor.png"></a></p>
    <a href="Sign-up.php">Sign up</a><br>
    <a href="Sign-in.php">Sign in</a><br>
    <a href="Add-beer.php">Add a new beer</a><br>
    <a href="Browse-Beers.php">Browse beers</a><br>
    <?php
    echo '<a href="profile.php?id=">Profile</a>';
    ?>
</body>
</html>