<?php
if(isset($_POST['note1']) || isset($_POST['note2']) || isset($_POST['note3']) || isset($_POST['note4']) || isset($_POST['note5']))

?>

<html lang ="en">
<head>
	<meta charset ="UTF-8">
	<title>Beer advisor</title>
	<meta name = "author" content="Quentin,Eloi,William">
	<meta name ="description" content="This is a page about beer">
	<link rel="shortcut icon" href="logo.jpg" type="image/x-icon">
	<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="title">Beer advisor</div>
<br>

<div><Name> Alcohol<br>

	<form method="post" action="note.php"> Note :
		<input id="grade" type="submit" value="1" name="note1">
		<input id="grade" type="submit" value="2" name="note2">
		<input id="grade" type="submit" value="3" name="note3">
		<input id="grade" type="submit" value="4" name="note4">
		<input id="grade" type="submit" value="5" name="note5">
</div>
</body>
</html>