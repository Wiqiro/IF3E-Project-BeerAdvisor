<?php 
session_start();
require_once("connection.php");
global $bdd;
$id = $_SESSION['ID'];
$req = $bdd->prepare("SELECT * FROM user WHERE id = ?");
$req->execute(array($id));
$res = $req->fetch();
$username = $res['Username'];
$bio = $res['Bio'];
if(isset($_POST['confirm'])){
        $new_username = htmlspecialchars($_POST['username']);
        $new_bio = htmlspecialchars($_POST['bio']);
        
        if (isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] != '') {
            $size = filesize($_FILES['image']['tmp_name']);
            if ($size < 1000000) {
                $new_image = file_get_contents($_FILES['image']['tmp_name']);
            }
        }
        if (!isset($size)) {
            $new_req = $bdd->prepare("UPDATE user SET Username = ?, Bio = ? WHERE id = ?");
            $new_req->execute(array($new_username,$new_bio,$id));
            header("Location:Profile.php?id=".$_SESSION['ID']);
        } else if ($size < 1000000) {
            $new_req = $bdd->prepare("UPDATE user SET Username = ?, Bio = ?, Picture = ? WHERE id = ?");
            $new_req->execute(array($new_username, $new_bio, $new_image, $id));
            header("Location:Profile.php?id=".$_SESSION['ID']);
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
  <!-- HEADER -->
  <div class="header">
        <div class="image"><a href="Browse-Beers.php"><img src="BeerAdvisor.png" alt="logo"></a></div>
        <div class="header_title">Profile</div>
        <div class="header_buttons">
        <?php
        if (isset($_SESSION['ID'])) {
            echo '<button class="" onclick="window.location.href=`Profile.php?id=' . $_SESSION['ID'] . '`">Profile</button>
            <button onclick="window.location.href=`sign-out.php`">Sign-out</button>';
        }
        if($_SESSION['ID'] == 0) {
            echo '<button onclick="window.location.href=`Sign-in.php`">Sign-in</button>
            <button onclick="window.location.href=`Sign-up.php`">Sign-up</button>';
        }
        ?>
        </div>
    </div>
    <hr>
    <!-- HEADER -->
    <form action="" method="post" enctype="multipart/form-data">
        <div id="center">
            <table>
                <tr>
                    <td>
                        <label for="username">Username :</label>
                        <input type="text" name="username" id="username" placeholder="Username" value="<?php echo $username ?>" required>
                        <label for="bio">Bio :</label>
                        <input type="text" name="bio" id="bio" placeholder="bio" value="<?php echo $bio ?>">
                        <label for="image">Profile picture:</label>
                        <input type="file" name="image" accept=".jpg, .jpeg, .png">
                        <?php 
                        if (isset($size) && $size >= 1000000) {
                            echo "The maximum upload size is 1 mb";
                        }
                        ?>
                    </td>
                </tr>
            </table>

            <br>
            <input class="" type="submit" name="confirm" value="Edit profile">

        </div>
    </form>



</body>
