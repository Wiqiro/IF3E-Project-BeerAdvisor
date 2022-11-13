<?php
require_once("connection.php");
session_start();
global $bdd;
if (isset($_POST['confirm'])) {
    $username = htmlspecialchars($_POST['username']);
    $date = date("Y-m-d");
    $birth_date = $_POST['birthday'];
    $diff = date_diff(date_create($birth_date), date_create($date));
    $age = $diff->format('%y');
    $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    if ($age >= 18) {
        if (strlen($username) <= 20) {
            $req_username = $bdd->prepare("SELECT * FROM user WHERE username = ?");
            $req_username->execute(array($username));
            $username_exist = $req_username->rowCount();
            if ($username_exist == 0) {
                if ($_POST['password'] == $_POST['confirm_password']) {
                    $request = $bdd->prepare("INSERT INTO user (Username,Password,Creation_date) VALUES (?,?,?)");
                    $request->execute(array($username, $passwordHash, $date));
                    header("Location:Sign-in.php");
                } else {
                    $error = "Password doesn't match";
                }
            } else {
                $error = "username already tooken";
            }
        } else {
            $error = "Username might be less than 20 char";
        }
    } else {
        $error = "You need to be older than 18 years old";
    }
} ?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Beer advisor</title>
    <meta name="author" content="Quentin,Eloi,William">
    <meta name="description" content="Page to connect or subscribe">
    <link rel="shortcut icon" href="logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <div class="image"><a href="Browse-Beers.php"><img src="BeerAdvisor.png" alt="logo"></a></div>
        <div class="header_title">Sign-up</div>
        <div class="header_buttons">
        <?php
        if (isset($_SESSION['Admin'])) {
            echo '<button onclick="window.location.href=`Admin.php`">Manage</button> ';
        }
        if (isset($_SESSION['ID'])) {
            echo '<button onclick="window.location.href=`Profile.php?id=' . $_SESSION['ID'] . '`">Profile</button>
            <button onclick="window.location.href=`sign-out.php`">Sign-out</button>';
        } else {
            echo '<button onclick="window.location.href=`Sign-in.php`">Sign-in</button>
            <button onclick="window.location.href=`Sign-up.php`">Sign-up</button>';
        }
        ?>
        </div>
    </div>
    <hr>
    <!-- HEADER -->

<form method="post">
    <div class="sign">

        <table>
            <td>
                <label for="username" class="label_register"></label>
                <input type="text" name="username" id="username" placeholder="Username" required>
            </td>
            <td>
                <label for="password"></label>
                <input type="password" name="password" id="password" placeholder="Password" required>
            </td>
            <tr>
                <td>
                    <label for="date"></label>
                    <input type="date" name="birthday" id="date" value="" required>

                </td>
                <td>
                    <label for="confirm_password"></label>
                    <input type="password" name="confirm_password" id="confirm_password"
                           placeholder="Confirm password">
                </td>
            </tr>
        </table>
        <label for="sign-up"></label>
        <input type="submit" class="bouton" value="Sign-up" id="sign-up" alt="sign up" name="confirm">
        <div>
            <?php
            if (isset($error)) {
                echo $error;
            }
            ?>
        </div>
        </table>

        <hr>
        <p class="">Already an account ? <a class="sign_up" href="Sign-in.php"><u>Sign-in</u></p>
    </div>
</form>
</body>
</html>