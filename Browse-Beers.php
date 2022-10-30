<?php

require_once("connection.php");
global $bdd;

$query = "SELECT * FROM beer ORDER BY name";
$request = $bdd->prepare($query);
$request->execute();
$data = $request->fetch();
	
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
		while ($data != null) {
			echo '<div> Name:' .$data['Name'] .
			'<a href="Show-Beer.php?id=' . $data['ID'] . '">  Show Beer</a>
			</div><br>'	;

			$data = $request->fetch();
		}
		?>
		
	</body>
</html>