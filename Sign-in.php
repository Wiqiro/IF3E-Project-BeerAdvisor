<?php
require_once("connection.php");
global $bdd;
if(isset($_POST['confirm'])){


$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);

$query = "SELECT * FROM user WHERE Username = ?";
$request = $bdd->prepare($query);
$request->execute(array($email));
$res = $request->fetch(PDO::FETCH_ASSOC);
var_dump($password);
var_dump($email);
if($res){
    $passwordHash = $res['prenom'];
     if(password_verify($password, $passwordHash)){
         echo "Connection réussie";
     }else{echo "Erreur de connection";};
}}


?>


<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Beer advisor</title>
    <meta name="author" content="Quetin,Eloi,Willianm">
    <meta name="description" content="Page to connect or subscribe">
    <link rel="shortcut icon" href="logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="page.css">
</head>
<body class="body" >
<a href="index.php"><img src="BeerAdvisor.png"></a>
<form action="" method="post">
    <div >
        <h1>Sign up</h1>
        <hr>
        <table>
            <tr>
                <td>
                    <label for="email">E-mail</label>
                    <input type="text" name="email" id="email" placeholder="Email" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="log-in"></label>
                    <input class="registerbtn" type="submit" name="confirm">
                </td>
            </tr>
        </table>
        <hr>
        <p class="container">Not register yet ?  <a href="Sign-up.php">sign up</p>
    </div>
</form>
</body>
</html>