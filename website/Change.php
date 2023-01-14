<?php
session_start();
require_once("connection.php");
global $bdd;
if(isset($_POST['confirm'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $request = $bdd->prepare("SELECT * FROM user WHERE Username = ?");
    $request->execute(array($username));
    $res = $request->fetch(PDO::FETCH_ASSOC);
    if ($res) {
        $id = $res['ID'];
        if ($_POST['password'] == $_POST['confirm_password']) {
            $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $request = $bdd->prepare("UPDATE user SET Username = ?, Password = ? WHERE id = ?");
            $request->execute(array($username,$passwordHash,$id));
            header("Location:Sign-in.php");
        } else {
            $error = "New password doesn't match";
        }
    } else {
        $error = "unknown username";
    }
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
    <!-- HEADER -->
        <div class="header">
        <div class="image"><a href="Browse-Beers.php"><img src="BeerAdvisor.png" alt="logo"></a></div>
        <div class="header_title">Update password</div>
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
                <tr>
                    <td>
                        <label for="confirm_password"></label>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password" required>
                    </td>
                </tr>
            </table>
            <?php
            if (isset($error)) {
                echo $error;
            }
            ?>
            <input class="button" type="submit" name="confirm" alt="sign in" value="Change password">
        </div>
    </form>
</body>
</html>