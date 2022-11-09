<?php
session_start();
require_once("connection.php");
global $bdd;

$id = $_SESSION['ID'];

if (isset($_GET['id']) and $_GET['id'] > 0){
$getid = intval($_GET['id']);
$req_profile = $bdd->prepare("SELECT * FROM user WHERE id = ?");
$req_profile->execute(array($_GET['id']));
$res = $req_profile->fetch();
$username = $res['Username'];
$date = $res['Creation_date'];
$bio = $res['Bio'];
$req_friend = $bdd->prepare("SELECT * FROM follows WHERE Followed_ID = ?");
$req_friend->execute(array($id));
$result_friend = $req_friend->fetch();
if ($req_friend->rowCount() < 0) {
    $follower_id = $result_friend['Follower_ID'];
    $friend_request = $result_friend['Request'];
}
?>


<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Beer advisor</title>
    <meta name="author" content="Quentin,Eloi,William">
    <meta name="description" content="This is a page about beer">
    <link rel="shortcut icon" href="logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<p class="logo"><a href="index.php"><img src="BeerAdvisor.png"></a></p>
<div>c'est le profil de <?php
    echo $username;
    echo '<br>';
    echo $date;
    echo '<br>bio<br>';
    echo $bio;
    echo '<br><href="Comment'
    ?>
    <br><?php
    if ($id == $getid) {
        echo '<a href="Edit-profile.php">Edit profile</a>';
    } else if ($follower_id != $getid) {
        echo '<form method="post">
<label for="Add_friend">Add Friend</label>
    <input type="submit" id="Add_friend" name="Add_friend" Value="Add friend">
</form>';
    }else if ($friend_request == 0){
        echo 'en attente';
    }else {echo 'suivit';
    }

    }
    ?>


</div>
</body>
</html>