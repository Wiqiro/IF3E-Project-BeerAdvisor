<?php
session_start();
require_once("connection.php");
global $bdd;
if(isset($_POST['confirm'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $query = "SELECT * FROM user WHERE Username = ?";
    $request = $bdd->prepare($query);
    $request->execute(array($username));
    $res = $request->fetch(PDO::FETCH_ASSOC);
            if($res){
                $passwordHash = $res['Password'];
                if(password_verify($password, $passwordHash)){
                    echo "Connection réussie";
                    $_SESSION['ID'] = $res['ID'];
                    $_SESSION['Username'] = $res['Username'];

                    header("Location:profile.php");
                }else{$error = "Wrong password";}
            }else{$error = "Wrong username";}
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beer advisor</title>
    <meta name="author" content="Quetin,Eloi,Willianm">
    <meta name="description" content="Page to connect or subscribe">
    <link rel="shortcut icon" href="" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>
<body class="body">
<a href="index.php"><img src="BeerAdvisor.png"></a>
<form action="" method="post">
    <div class="container">
        <h1>Sign in</h1>
        <hr>
        <table>
            <tr>
                <td>
                    <label for="username"></label>
                    <input type="text" name="username" id="username" placeholder="Username" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="password"></label>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </td>
            </tr>
			</table>
        <?php
        if(isset($error)){
            echo $error;
        }
        ?>

			<input class="registerbtn" type="submit" name="confirm" value="Sign in">
        <hr>
        <p class="container">Not register yet ?  <a href="Sign-up.php">Sign up</p>
    </div>
</form>
</body>
</html>