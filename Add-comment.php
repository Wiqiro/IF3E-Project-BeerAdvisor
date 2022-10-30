La page est inutile mais je la laisse au cas ou

<!-- <?php
/* require_once("connection.php");

global $bdd;

if (isset($_POST['create'])) {
	$text = $_POST['text'];
	$grade = htmlspecialchars($_POST['grade']);
	$date = date("Y-m-d");

	$query = "INSERT INTO comment(User_ID,beer_id,text, grade, date) VALUES(2,2,?,?,?)";
	$request = $bdd->prepare($query);
	$request->execute(array($text, $grade, $date)); */
} ?>


<html lang ="en">
<head>
	<meta charset ="UTF-8">
	<meta name = "author" content="Quentin,Eloi,William">
	<meta name ="description" content="This is a page about beer">
	<link rel="shortcut icon" href="" type="image/x-icon">
	<link rel="stylesheet" href="page.css">
	<title>Beer advisor</title>
</head>
<body>
	<h1 style="text-align:center"><strong>BeerAdvisor</strong></h1>
	<h2>Add a new comment to this beer</h2>

	<form action="" method="post">
		<label for="BeerName">Comment</label><br>
		<input type="text" name="text" id="text"><br><br>

		<label for="grade">Grade</label><br>
		<select name="grade" id="grade">
				<option value=1>1</option>
				<option value=2>2</option>
				<option value=3>3</option>
				<option value=4>4</option>
				<option value=5>5</option>
		</select><br><br>

		<input type="submit" value="Create" name="create">

	 </form>

</body>
</html> -->