<?php 
session_start();
require_once("connection.php");
global $bdd;
$id = $_SESSION['ID'];
echo $id;
$req = $bdd->prepare("SELECT * FROM user WHERE id = ?");
$req->execute(array($id));
$res = $req->fetch();
$username = $res['Username'];
$bio = $res['Bio'];
if(isset($_POST['confirm'])){
        $new_username = htmlspecialchars($_POST['username']);
        $new_bio = htmlspecialchars($_POST['bio']);
        $new_req = $bdd->prepare("UPDATE user SET Username = ?, Bio = ? WHERE id = ?");
        $new_req->execute(array($new_username,$new_bio,$id));
        header("Location:profile.php?id=".$_SESSION['ID']);
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
                        <input type="text" name="username" id="username" placeholder="Username" value="<?php echo $username ?>" required>
                        <label for="bio"></label>
                        <input type="text" name="bio" id="bio" placeholder="bio" value="<?php echo $bio ?>"
                    </td>
                </tr>
            </table>
            <input class="registerbtn" type="submit" name="confirm" value="Edit profile">
        </div>
    </form>



</body>