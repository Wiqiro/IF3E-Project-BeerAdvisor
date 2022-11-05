<?php 
session_start();
require_once("connection.php");
global $bdd;
$id = $_SESSION["ID"];

$req = $bdd->prepare("SELECT * FROM user WHERE id = ?");
$req->execute(array($id));
$res = $req->fetch();
$username = $res['Username'];
if(isset($_POST['confirm'])){
    if(isset($_POST['username'])){
        $new_username = htmlspecialchars($_POST['username']);
    }
}

?>

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
    <form action="" method="post">
        <div class="container">
            <hr>
            <table>
                <tr>
                    <td>
                        <label for="username"></label>
                        <input type="text" name="username" id="username" placeholder="Username" value="<?php echo $username ?>">
                    </td>
                </tr>
                <tr>
                    <td>

                    </td>
                </tr>
            </table>
            <input class="registerbtn" type="submit" name="confirm" value="Edit profile">
        </div>
    </form>



</body>
