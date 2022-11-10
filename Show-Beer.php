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
		
	$query = "SELECT B.ID AS ID, Name, Alcohol, IBU, Style, Color, Last_modified, AVG(C.grade) AS Grade 
		FROM beer B INNER JOIN color ON color.ID = B.Color_ID INNER JOIN style ON style.ID = B.Style_ID LEFT JOIN comment C
		ON B.id = C.Beer_id WHERE B.ID = ?";
	$request = $bdd->prepare($query);
	$request->execute(array($beer_id));
	$beer_data = $request->fetch();
	
	$query = "SELECT User_ID, Username, Text, Grade, DATE_FORMAT(Date, '%D %b. %Y at %H:%i') AS Date, C.Picture AS Picture 
		FROM user U INNER JOIN comment C ON U.ID = C.User_ID WHERE C.Beer_ID = ?";
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
    	<p class="logo"><a href="index.php"><img src="BeerAdvisor.png"></a></p>

		<?php
			echo '<h3><strong>Beer: ' . $beer_data['Name'] . '</strong></h3>
			Last modified: ' . $beer_data['Last_modified'] . '<br>
			Avg grade: ' . number_format($beer_data['Grade'], 1) . ' / 5<br>
			Alc: ' . $beer_data['Alcohol'] . '<br>
			Style: ' . $beer_data['Style'] . '<br>
			Color: ' . $beer_data['Color'] . '<br>'	;
      	?>

		<form action="" method="post" enctype="multipart/form-data">
				<label for="grade">Grade</label>
				<select name="grade" id="grade" required>
					<option></option>
					<option value=1>1 / 5</option>
					<option value=2>2 / 5</option>
					<option value=3>3 / 5</option>
					<option value=4>4 / 5</option>
					<option value=5>5 / 5</option>	
				</select>

				<?php
				if (isset($size) && $size >= 1000000) {
					echo "The maximum upload size is 1 mb";
				}
				?>
				<br>
				<textarea name="text" id="text" required minlength="20" maxlength="300" class="NewComment" placeholder="Add your own review"></textarea><br>
				<input type="file" name="image" accept=".jpg, .jpeg, .png">

				<input type="submit" value="Add review" name="create">
		</form>

		<div class="CommentContainer">
		<?php
			while ($com_data != null) {
				echo '
				<div class="Comment">
					<table><tr>
						<th style="font-size: larger"><a href="profile.php?id='. $com_data['User_ID'] . '">' . $com_data['Username'] . '<th>
						<td style="font-size: smaller">  on the ' . $com_data['Date'] . '</td>
						<td class="stars""><p>';
						
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
					</tr></table>
				</div>'	;

				$com_data = $request->fetch();
			}
			?>
		</div>
	</body>
</html>