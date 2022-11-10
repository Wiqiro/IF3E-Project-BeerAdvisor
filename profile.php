<?php
session_start();
require_once("connection.php");
global $bdd;
$id = $_SESSION['ID'];
if (isset($_GET['id']) and $_GET['id'] > 0) {
    $profileid = intval($_GET['id']);
    $req_profile = $bdd->prepare("SELECT * FROM user WHERE id = ?");
    $req_profile->execute(array($_GET['id']));
    $res = $req_profile->fetch();
    $username = $res['Username'];
    $date = $res['Creation_date'];
    $bio = $res['Bio'];
    $req_friend = $bdd->prepare("SELECT COUNT(*) FROM follows WHERE Follower_ID = ? AND Followed_ID = ?");
    $req_friend->execute(array($id, $profileid));
    $result_friend = $req_friend->fetch();
    if(isset($_POST['Add_friend'])){
        $req_add_friend = $bdd->prepare("INSERT INTO follows (Followed_ID,Follower_ID) VALUES (?,?)");
        $req_add_friend->execute(array($id,$profileid));
        $req_add_friend->fetch();
    }
    if ($result_friend == 1) {
        $message = 'Following';
    }

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
    if ($id == $profileid) {
        echo '<a href="Edit-profile.php">Edit profile</a>';
    } else if (isset($message)) {
        echo $message;
    } else {
        echo '<form method="post">
                    <label for="Add_friend">Add Friend</label>
                    <input type="submit" id="Add_friend" name="Add_friend" Value="Add friend">
              </form>';
    }
    ?>


</div>
</body>
</html>