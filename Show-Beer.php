<?php

require_once("connection.php");
global $bdd;



if (isset($_GET['id'])) {
   $beer_id = $_GET['id'];

   if (isset($_POST['create'])) {
      $text = $_POST['text'];
      $grade = htmlspecialchars($_POST['grade']);
      $date = date("Y-m-d");
   
      $query = "INSERT INTO comment(User_ID, beer_id, text, grade, date) VALUES(?,?,?,?,?)";
      $request = $bdd->prepare($query);
      $request->execute(array(1, $beer_id, $text, $grade, $date));
   }
   
   $query = "SELECT * from beer WHERE ID = ?";
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
		<link rel="stylesheet" href="page.css">
		<title>Beer advisor</title>
	</head>
	<body>
		<?php
      echo 'Bière: ' . $beer_data['Name'] . '<br><br>';
      ?>

   <form action="" method="post">
		<label for="BeerName">Add Comment</label><br>
		<input type="text" name="text" id="text">

		<label for="grade">Grade</label>
		<select name="grade" id="grade">
				<option value=1>1</option>
				<option value=2>2</option>
				<option value=3>3</option>
				<option value=4>4</option>
				<option value=5>5</option>
		</select><br><br>
		<input type="submit" value="Add" name="create">

	 </form>

      
      <?php
		while ($com_data != null) {
			echo '<div> Commentaire:' . $com_data['Text'] . '
			</div><br>'	;

			$com_data = $request->fetch();
		}
		?>
		
	</body>
