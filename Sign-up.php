<?php
require_once("connection.php");

global $bdd;

if (isset($_POST['confirm'])) {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $date = htmlspecialchars($_POST['birthday']);
    $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO user(username,prenom,date_naissance) VALUES(?,?,?)";
    $request = $bdd->prepare($query);
    $request->execute(array($username, $passwordHash, $date));
    header("Location:index.php");
} ?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Beer advisor</title>
    <meta name="author" content="Quentin,Eloi,William">
    <meta name="description" content="Page to connect or subscribe">
    <link rel="shortcut icon" href="logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="page.css">
</head>
<body class="body_sign_up">
<a href="index.php"><img src="BeerAdvisor.png"></a>
<form name="formulaire" action="" method="post">

    <div class="container">
        <h1>Sign up</h1>
        <hr>
        <table>
            <td>
                <label for="username" class="label_register"></label>
                <input type="text" name="username" id="username" placeholder="Username" required>
            </td>

            <td>
                <label for="email"></label>
                <input type="email" name="email" id="email" placeholder="E-mail" required>
            </td>
            <tr>
                <td>
                    <label for="password"></label>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </td>
                <td>
                    <label for="confirm_password"></label>
                    <input type="password" name="confirm_password" id="confirm_password"
                            placeholder="Confirm password">
                </td>
            </tr>
        </table>

        <label for="date"></label>
        <input type="date" name="birthday" id="date" value="" required>

        <label for="sign-up"></label>
        <input type="submit" class="registerbtn" value="Sign-up" name="confirm">

        </table>

        <hr>
        <p class="container sign_in">Already an account ? <a class="sign_up" href="Sign-in.php">Sign in</p>
    </div>
</form>
</body>
</html>