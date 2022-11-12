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

    $req_friend = $bdd->prepare("SELECT COUNT(*) AS count FROM follows WHERE Follower_ID = ? AND Followed_ID = ?");
    $req_friend->execute(array($id, $profileid));
    $result_friend = $req_friend->fetch()['count'];


    if(isset($_POST['Add_friend']) && $result_friend == 0){

        $req_follow = $bdd->prepare("INSERT INTO follows (Followed_ID,Follower_ID) VALUES (?,?)");
        $req_follow->execute(array($profileid, $id));
        $req_follow->fetch();
    }

    if ($result_friend == 1) {
        $message = 'Beer commented by ' . $profile_data['Username'];
    }
    $com_data = null;
    if ($result_friend == 1 || $profileid == $id) {

        $query = "SELECT User_ID, Beer_ID, B.Name AS Name, Text, Grade, DATE_FORMAT(Date, '%D %b. %Y at %H:%i') AS Date, C.Picture AS Picture 
		FROM user U INNER JOIN comment C ON U.ID = C.User_ID  INNER JOIN beer B ON B.ID = C.Beer_ID WHERE U.ID = ?";
        $request = $bdd->prepare($query);
        $request->execute(array($profileid));
        $com_data = $request->fetch();
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
                $bio;
                if ($id == $profileid) {
                    echo '<br><br><u><a href="Edit-profile.php">Edit profile</a></u>';
                } else if (!isset($message)) {
                    echo '<form method="post"><br>
                            <input type="submit" id="Add_friend" name="Add_friend" Value="Follow">
                        </form>';
                }
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
        
        <hr>

        <?php
        if (isset($message)) {
            echo $message;
        } else if ($id != $profileid) {
            echo 'You have to follow this user to be able to see their comments';
        }
        ?>

        <div class="CommentContainer">
		<?php
			while ($com_data != null) {
				echo '
				<div class="comment">
					<table><tr>
						<th style="font-size: larger"><a href="Show-Beer.php?id='. $com_data['Beer_ID'] . '">' . $com_data['Name'] . '<th>
						<td style="font-size: smaller">  on the ' . $com_data['Date'] . '</td>
						<td><p class="stars">';
						
						$i = 0;
						while ($i < $com_data['Grade']) {
							echo '★';
							$i++;
						}
						while ($i < 5) {
							echo '☆';
							$i++;
						}
						echo '</p></td>
					</tr></table>
					<table><tr>
						<td>
							' . $com_data['Text'] . '
						</td>
						<td>
							<img src="data:image;base64,' . base64_encode($com_data["Picture"]) . '" alt=""/>
						</td>
					</tr></table><br>
				</div>'	;

				$com_data = $request->fetch();
			}
			?>
		</div>
    </body>
</html>