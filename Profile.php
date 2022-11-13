<?php
session_start();
require_once("connection.php");
global $bdd;
if(isset($_SESSION['ID'])){
    $id = $_SESSION['ID'];
}else{$id = 0;}

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
    if(isset($_POST['Add_friend']) && $result_friend == 0 && $id!=$profileid && $id!=0) {
        $req_friend = $bdd->prepare("SELECT COUNT(*) AS count FROM follows WHERE Follower_ID = ? AND Followed_ID = ?");
        $req_friend->execute(array($id, $profileid));
        $result_friend = $req_friend->fetch()['count'];
    }

    if(isset($_POST['Add_friend']) && $result_friend == 0 && $id!=0){
        $req_follow = $bdd->prepare("INSERT INTO follows (Followed_ID,Follower_ID) VALUES (?,?)");
        $req_follow->execute(array($profileid, $id));
        $req_follow->fetch();
        header("Refresh:0");
    }

    if ($result_friend == 1) {
        $message = 'Beer commented by ' . $profile_data['Username'];
    }
    $com_data = null;
    if ($result_friend == 1 || $profileid == $id) {
        $query = "SELECT C.ID As ID, User_ID, Username, Beer_ID, B.Name AS Name, Text, Grade, DATE_FORMAT(Date, '%D %b. %Y at %H:%i') AS Date, Date AS RawDate, C.Picture AS Picture 
		FROM user U INNER JOIN comment C ON U.ID = C.User_ID  INNER JOIN beer B ON B.ID = C.Beer_ID WHERE U.ID = ? ORDER BY ";

        if (isset($_GET['Sort'])) {
            $sorting = $_GET['SortBy'];
            switch ($sorting) {
            case "RatingDesc":
                $query = $query . "Grade DESC";
                break;
            case "RatingAsc":
                $query = $query . "Grade ASC";
                break;
            case "DateDesc":
                $query = $query . "RawDate DESC";
                break;
            case "DateAsc":
                $query = $query . "RawDate ASC";
                break;
            default:
                break;
            }
        } else {
            $query = $query . "RawDate DESC";
        }
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
        <div class="profile">
            <table><tr>
                <td>
					<div class="image" ><?php
                  echo '<img id="profile_picture" src="data:image;base64,' . base64_encode($profile_data["Picture"]) . '" alt=""/>'; ?></div>
                </td>
                <td>

                <?php
                if ($result_friend != 0 && $id!=$profileid && $id!=0) {
                    echo '<button class="" onclick="window.location.href=`Unfollow.php?id=' . $_GET['id'] . '`">Following</button> <br> <br>';

                }
                if($result_friend == 0 && $id!=$profileid && $id!=0){
                    echo '<form method="post">
                        <input type="submit" id="Add_friend" name="Add_friend" Value="Follow">
                    </form>';
                }
                echo 'Username: ' . $username . '<br><br>
                Member since: ' . $date . '<br><br>Bio: <br>' .
                $bio;

                ?><br>
                </td>
            </tr></table>
            <?php
            if ($id == $profileid) {
                echo '<u><a href="Edit-profile.php">Edit profile</a></u>';
            }
                    }
            ?>
            <br>
        </div>
        <hr>
        <div class="CommentContainer">
        <form action="" method="get">
			<input type="hidden" name="id" value="<?php echo $_GET['id']?>">
			<h3>Reviews</h3>
			
			<label for="SortBy">Sort by</label>
			<select name="SortBy">
				<option value="DateDesc">Date: New to old</option>
				<option value="DateAsc">Date: Old to new</option>
				<option value="RatingDesc">Rating: High to low</option>
				<option value="RatingAsc">Rating: Low to High</option>
			</select>

			<input type="submit" value="Sort" name="Sort">
		</form>
		<?php
            if(isset($com_data)){
                while ($com_data != null) {
                    echo '
				<div class="comment">
					<table><tr>
						<th style="font-size: large"><a href="">' . $com_data['Username'] . '</a></th>
						<td style="font-size: smaller"> on </td> 
						<th><a href="Show-Beer.php?id='. $com_data['Beer_ID'] . '">' . $com_data['Name'] . '</a></th>
						<td style="font-size: smaller"> - the ' . $com_data['Date'] . ' - </td>
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
						echo '</p></td>';
						if (isset($_SESSION['Admin']) || (isset($_SESSION['ID']) && $com_data['User_ID'] == $_SESSION['ID'])) {
							echo '<td style="font-size: smaller"> - 
								<a href="delete-comment.php?id=' . $com_data['ID'] . '&user_id=' . $com_data['User_ID'] . '" onclick="return confirm(`Are you sure you want to delete this comment ?`);"><u style="font-size: smaller">Remove</u></a>
							</td>';
						}
					echo '</tr></table>
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
            } else if ($_GET['id'] != 0 && $result_friend == 1){
                echo "This profile don't have any comment";
            } else {
                echo "You need to follow " . $username . " to see their reviews";
            }
        }
			?>
		</div>
    </body>
</html>