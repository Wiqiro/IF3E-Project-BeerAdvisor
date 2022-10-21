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
     header("Location:index.php");
}}

?>


<html lang="en">
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
    <div class="container">
        <h1>Sign in</h1>
        <hr>
        <table>
            <tr>
                <td>
                    <input type="text" name="email" id="email" placeholder="Email" required>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </td>
            </tr>
			</table>
			
			<input class="registerbtn" type="submit" name="confirm" value="Sign in">
        <hr>
        <p class="container">Not register yet ?  <a href="Sign-up.php">Sign up</p>
    </div>
</form>
</body>
</html>