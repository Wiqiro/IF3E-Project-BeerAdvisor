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
			$date = date("Y-m-d");
		
			$query = "INSERT INTO comment(user_ID, beer_id, text, grade, date) VALUES(?,?,?,?,?)";
			$request = $bdd->prepare($query);
			$request->execute(array($user_id, $beer_id, $text, $grade, $date));
		} else {
			echo '<a href="Sign-in.php">You need log in to comment</a>';
		}
	}
		
	$query = "SELECT B.ID AS ID, Name, Alcohol, IBU, Aroma, Style, Color, Last_modified, AVG(C.grade) AS Grade FROM beer B LEFT JOIN comment C ON B.id = C.beer_id WHERE B.id = ?";

	$request = $bdd->prepare($query);
	$request->execute(array($beer_id));
	$beer_data = $request->fetch();
	
	$query = "SELECT Username, Text, Grade, Date FROM user U INNER JOIN comment C ON U.ID = C.User_ID WHERE C.Beer_ID = ?";
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
		<?php
          	echo '<h1>' . $beer_data['Name'] . '</h1><br>';
      	?>

	<form action="" method="post">
		<label for="BeerName">Add Comment</label><br>
		<textarea size="300"  name="text" id="text" required minlength="20" maxlength="300"></textarea><br>

		<label for="grade">Grade</label>
		<select name="grade" id="grade" required>
			<option></option>
			<option value=1>1</option>
			<option value=2>2</option>
			<option value=3>3</option>
			<option value=4>4</option>
			<option value=5>5</option>
		</select><br><br>
		<input type="submit" value="Add" name="create">

	</form>

	<div class="CommentContainer">
    <?php
		while ($com_data != null) {
			echo '
			<div class="Comment">
				<table><tr>
					<th style="font-size: larger">' . $com_data['Username'] . '<th>
					<td style="font-size: smaller">  on ' . $com_data['Date'] . '</td>
					<td style="font-size: smaller"> rated this beer ' . $com_data['Grade'] . ' stars</td>
				</tr></table>
				' . $com_data['Text'] . '
			</div>'	;

			$com_data = $request->fetch();
		}
		?>
	</div>
		
	</body>
</html>