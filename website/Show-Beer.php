<?php
session_start();
require_once("connection.php");
global $bdd;


if (isset($_GET['id'])) {
	$beer_id = $_GET['id'];
	
	if (isset($_POST['create'])) {
		if (isset($_SESSION['ID'])) {
			$user_id = $_SESSION['ID'];
			$text = $_POST['text'];
			$grade = htmlspecialchars($_POST['grade']);
			$date = date("Y-m-d H:i:s");
			if (isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] != '') {
				$size = filesize($_FILES['image']['tmp_name']);
				if ($size < 1000000) {
					$image = file_get_contents($_FILES['image']['tmp_name']);
				}
			}
			if (!isset($size)) {
				$query = "INSERT INTO comment(user_ID, beer_id, text, grade, date) VALUES(?,?,?,?,?)";
				$request = $bdd->prepare($query);
				$request->execute(array($user_id, $beer_id, $text, $grade, $date));
			} else if ($size < 1000000) {
				$query = "INSERT INTO comment(user_ID, beer_id, text, grade, date, picture) VALUES(?,?,?,?,?,?)";
				$request = $bdd->prepare($query);
				$request->execute(array($user_id, $beer_id, $text, $grade, $date, $image));
			}
		} else {
			$login_message = 'You need to <u><a href="Sign-in.php">log in</a></u> to comment';
		}
	}
		
	$query = "SELECT B.ID AS ID, Name, Alcohol, IBU, Style, Color, B.Picture AS Picture, DATE_FORMAT(Last_modified, '%D %b. %Y') AS Last_modified, AVG(C.grade) AS Grade 
		FROM beer B INNER JOIN color ON color.ID = B.Color_ID INNER JOIN style ON style.ID = B.Style_ID LEFT JOIN comment C
		ON B.id = C.Beer_id WHERE B.ID = ?";
	$request = $bdd->prepare($query);
	$request->execute(array($beer_id));
	$beer_data = $request->fetch();
	
	$query = "SELECT C.ID As ID, User_ID, Username, Text, Grade, DATE_FORMAT(Date, '%D %b. %Y at %H:%i') AS Date, Date AS RawDate, C.Picture AS Picture 
		FROM user U INNER JOIN comment C ON U.ID = C.User_ID WHERE C.Beer_ID = ? ORDER BY ";
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
	$request->execute(array($beer_id));
	$com_data = $request->fetch();
}

?>

<html>
   <head>
		<meta charset ="UTF-8">
		<meta name = "author" content="Quentin,Eloi,William">
		<meta name ="description" content="This is a page about beer">
		<link rel="shortcut icon" href="" type="image/x-icon">
		<link rel="stylesheet" href="style.css">
		<title>Beer advisor</title>
		
	</head>
	<body>
		<!-- HEADER -->
			<div class="header">
			<div class="image"><a href="Browse-Beers.php"><img src="BeerAdvisor.png" alt="logo"></a></div>
			<div class="header_title">Review</div>
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
		
		<table style="width:100%"><tr>
		<td>
		<?php
			echo '<h3>
			<strong>Beer: ' . $beer_data['Name'] . '</strong></h3>
			Avg grade: ';
			if ($beer_data['Grade'] != 0) {
				echo '<a class="stars">';
				$i = 0;
				while ($i < round($beer_data['Grade'], 0, PHP_ROUND_HALF_ODD)) {
					echo '★';
					$i++;
				}
				while ($i < 5) {
					echo '☆';
					$i++;
				}
				echo '</a> ' . number_format($beer_data['Grade'], 1);
			} else {
				echo '?';
			}
			
			echo '<br>
				
			Last modified: ' . $beer_data['Last_modified'] . '<br>
			Alc: ' . $beer_data['Alcohol'] . '<br>
			Color: ' . $beer_data['Color'] . '<br>
			Style: ' . $beer_data['Style'] . '<br>';
			
			if (isset($_SESSION['ID'])) {
				echo '<br><a href="Edit-beer.php?id=' . $_GET['id'] . '"><u>Edit this beer</u></a>';
				
				if (isset($_SESSION['Admin'])) {
					echo '<br><a href="delete-beer.php?id=' . $_GET['id'] . '" onclick="return confirm(`Are you sure you want to delete this beer ?`);"><u>Delete this beer</u></a>';
				}
			}
			?>
			</td>
			<td style="text-align: right">
				<?php
				if ($beer_data["Picture"] != '')
					echo '<img class="beer_image" src="data:image;base64,' . base64_encode($beer_data["Picture"]) . '" alt=""/>';
				?>
			</td>
			</tr></table>
			
      	
		<hr>
		<form action="" method="post" enctype="multipart/form-data">
				<h3><strong>Add your review</h3></strong>
				
				<textarea name="text" id="text" required minlength="10" maxlength="300" class="new_comment" placeholder="Add your own review"></textarea><br>
				<label for="grade">Grade</label>
				<select name="grade" id="grade" required>
					<option></option>
					<option value=1>1 / 5</option>
					<option value=2>2 / 5</option>
					<option value=3>3 / 5</option>
					<option value=4>4 / 5</option>
					<option value=5>5 / 5</option>	
				</select>
				<input type="file" name="image" accept=".jpg, .jpeg, .png">
				<input type="submit" value="Add review" name="create">
			<?php
		
			if (isset($size) && $size >= 1000000) {
				echo "The maximum upload size is 1 mb";
			}
			if (isset($login_message)) {
				echo $login_message;
			} ?>
		</form>
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
			while ($com_data != null) {
				echo '
				<div class="comment">
					<table style="text-align: center"><tr>
						<th style="font-size: large"><a href="Profile.php?id='. $com_data['User_ID'] . '">' . $com_data['Username'] . '</a></th>
						<td style="font-size: smaller"> on </td> 
						<th><a href="">' . $beer_data['Name'] . '</a></th>
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
						echo '</p></td>
                        <td style="font-size: smaller"> '. number_format($com_data['Grade'], 0) .'</td> ';
						if (isset($_SESSION['Admin']) || (isset($_SESSION['ID']) && $com_data['User_ID'] == $_SESSION['ID'])) {
							echo '<td style="font-size: smaller"> - 
								<a href="delete-comment.php?id=' . $com_data['ID'] . '&user_id=' . $com_data['User_ID'] . '" onclick="return confirm(`Are you sure you want to delete this comment ?`);"><u style="font-size: smaller">Remove</u></a>
							</td>';
						}
					echo '</tr></table>
					<table style="text-align: center">
						<tr><td>
							' . $com_data['Text'] . '
						</td></tr>';

						if ($com_data['Picture'] != '') {
							echo '<tr><td>
							<img class="comment_image" src="data:image;base64,' . base64_encode($com_data["Picture"]) . '" alt=""/>
							</td></tr>';
						}
					echo '</table><br>
				</div>';

				$com_data = $request->fetch();
			}
			?>
		</div>
	</body>
</html>