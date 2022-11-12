<?php
session_start();
require_once("connection.php");
global $bdd;
$id = $_SESSION['ID'];
if (isset($_GET['id']) and $_GET['id'] > 0) {
    $profileid = intval($_GET['id']);
    $req_profile = $bdd->prepare("SELECT * FROM user WHERE id = ?");
    $req_profile->execute(array($_GET['id']));
    $profile_data = $req_profile->fetch();
    $profile_exist = $req_profile->rowCount();
    if($profile_exist == 1){
    $username = $profile_data['Username'];
    $date = $profile_data['Creation_date'];
    $bio = $profile_data['Bio'];
    $req_friend = $bdd->prepare("SELECT * FROM follows WHERE Follower_ID = ? AND Followed_ID = ?");
    $req_friend->execute(array($id, $profileid));
    $result_friend = $req_friend->rowCount();
    if(isset($_POST['Add_friend']) && $result_friend == 0 && $id!=$profileid){
        $req_follow = $bdd->prepare("INSERT INTO follows (Followed_ID,Follower_ID) VALUES (?,?)");
        $req_follow->execute(array($profileid, $id));
        $req_follow->fetch();
    }






?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Beer advisor</title>
        <meta name="author" content="Quentin,Eloi,William">
        <meta name="description" content="This is a page about beer">
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
            if ($_SESSION != 0) {
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
        <div>
            <table><tr>
                <td>
					<div class="image" ><?php
                  echo '<img id="profile_picture" src="data:image;base64,' . base64_encode($profile_data["Picture"]) . '" alt=""/>'; ?></div>
                </td>
                <td>

                <?php

                if ($result_friend != 0 && $id!=$profileid) {
                    echo '<div>Following</div> <br>';
                }
                if($result_friend == 0 && $id!=$profileid){
                    echo '<form method="post">
                        <input type="submit" id="Add_friend" name="Add_friend" Value="Follow">
                    </form>';
                }
                echo $username . '<br><br>
                Member since: ' . $date . '<br><br>Bio: <br>' .
                $bio . '<br>'
                ?><br>
                </td>
            </tr></table>

            <?php
            if ($id == $profileid) {
                echo '<u><a href="Edit-profile.php">Edit profile</a></u>';
            }
            if (isset($message)) {
                echo $message;
            }
                    }else{echo "this profile doesn't exist";}
                    }else{echo "You need to be connected";}
            ?>
            <br>
        </div>
    </body>
</html>