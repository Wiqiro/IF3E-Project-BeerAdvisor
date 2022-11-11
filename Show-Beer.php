<?php
session_start();
require_once("connection.php");
global $bdd;


if (isset($_GET['id'])) {
	$beer_id = $_GET['id'];
	
	
	
	if (isset($_POST['create'])) {
		if (isset($_SESSION['ID'])) {
			$user_id = $_SESSION['ID'];
			if (isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] != '') {
				$size = filesize($_FILES['image']['tmp_name']);
				if ($size < 1000000) {
					$image = file_get_contents($_FILES['image']['tmp_name']);
				}
			}
			$text = $_POST['text'];
			$grade = htmlspecialchars($_POST['grade']);
			$date = date("Y-m-d H:i:s");
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
			echo '<a href="Sign-in.php">You need log in to comment</a>';
		}
	}
		
	$query = "SELECT B.ID AS ID, Name, Alcohol, IBU, Style, Color, DATE_FORMAT(Last_modified, '%D %b. %Y') AS Last_modified, AVG(C.grade) AS Grade 
		FROM beer B INNER JOIN color ON color.ID = B.Color_ID INNER JOIN style ON style.ID = B.Style_ID LEFT JOIN comment C
		ON B.id = C.Beer_id WHERE B.ID = ?";
	$request = $bdd->prepare($query);
	$request->execute(array($beer_id));
	$beer_data = $request->fetch();
	
	$query = "SELECT User_ID, Username, Text, Grade, DATE_FORMAT(Date, '%D %b. %Y at %H:%i') AS Date, Date AS RawDate, C.Picture AS Picture 
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
		$query = $query . "Date DESC";
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
			<div class="image"><a href="index.php"><img src="BeerAdvisor.png" alt="logo"></a></div>
			<div class="header_title">Beer review</div>
			<div class="header_buttons">
				<?php
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

		<?php
			echo '<h3><strong>Beer: ' . $beer_data['Name'] . '</strong></h3>
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
			Style: ' . $beer_data['Style'] . '<br>
			Color: ' . $beer_data['Color'] . '<br>'	;
      	?>
		<hr>
		<form action="" method="post" enctype="multipart/form-data">
				<h3><strong>Add your review</h3></strong>
				
				<?php
				if (isset($size) && $size >= 1000000) {
					echo "The maximum upload size is 1 mb";
				}
				?>
				<textarea name="text" id="text" required minlength="20" maxlength="300" class="new_comment" placeholder="Add your own review"></textarea><br>
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
					<table><tr>
						<th style="font-size: larger"><a href="Profile.php?id='. $com_data['User_ID'] . '">' . $com_data['Username'] . '<th>
						<td style="font-size: smaller">  on the ' . $com_data['Date'] . '</td>
						<td ><p class="stars">';
						
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