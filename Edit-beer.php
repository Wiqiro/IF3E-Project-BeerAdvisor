<?php
session_start();
require_once("connection.php");
global $bdd;

if (isset($_GET['id'])) {
	$req = $bdd->prepare("SELECT * FROM beer WHERE id = ?");
	$req->execute(array($_GET['id']));
	$res = $req->fetch();
}

if (isset($_POST['confirm']) && isset($_SESSION['ID'])) {

	$name = htmlspecialchars($_POST['BeerName']);
	$color = htmlspecialchars($_POST['BeerColor']);
	$ibu = htmlspecialchars($_POST['BeerIBU']);
	$alcohol = htmlspecialchars($_POST['BeerAlcohol']);
	$style = htmlspecialchars($_POST['BeerStyle']);
	$date = date("Y-m-d H:i:s");
	if (isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] != '') {
		$size = filesize($_FILES['image']['tmp_name']);
		if ($size < 1000000) {
			$image = file_get_contents($_FILES['image']['tmp_name']);
		}
	}
	if (!isset($size)) {
		$query = "UPDATE beer SET Name = ?, Alcohol = ?, Color_id = ?, Style_id = ?, Ibu = ?, Last_modified = ? WHERE ID = ?";
		$request = $bdd->prepare($query);
		$request->execute(array($name, $alcohol, $color, $style, $ibu, $date, $res['ID']));
		header("Location:Show-Beer.php?id=" . $_GET['id']);
	} else if ($size < 1000000) {
		$query = "UPDATE beer SET Name = ?, Alcohol = ?, Color_id = ?, Style_id = ?, Ibu = ?, Last_modified = ?, Picture = ? WHERE ID = ?";
		$request = $bdd->prepare($query);
		$request->execute(array($name, $alcohol, $color, $style, $ibu, $date, $image, $res['ID']));
		header("Location:Show-Beer.php?id=" . $_GET['id']);
	}

	
}
$color_req = $bdd->prepare("SELECT * FROM color ORDER BY ID");
$color_req->execute();
$color_data = $color_req->fetch();

$style_req = $bdd->prepare("SELECT * FROM style ORDER BY ID");
$style_req->execute();
$style_data = $style_req->fetch();
?>


<html lang ="en">
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
        <div class="header_title">Edit beer</div>
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


	 <form style="margin: 20px" action="" method="post" enctype="multipart/form-data">
		<label for="BeerName">Name of the beer</label><br>
		 
            <input type="text" name="BeerName" maxlength="100" style="max-width:238px" value="<?php echo $res['Name']?>" required><br><br>

		<label for="BeerAlcohol">Alcohol level</label><br>
        <input type="number" step="0.1" min="0" max="67.5" name="BeerAlcohol" value="<?php echo $res['Alcohol']?>" required><br><br>

		<label for="BeerColor">Color</label><br>
			<select name="BeerColor" value="<?php echo $res['Color_ID']?>">
				<?php
				while ($color_data != null) {
					echo '<option value="' . $color_data['ID'] . '"';
					if ($color_data['ID'] == $res['Color_ID']) {
						echo ' selected ';
					}
					echo '>' . $color_data['Color'] . '</option>';
					$color_data = $color_req->fetch();
				}
				?>
			</select><br><br>

		<label for="BeerStyle">Style</label><br>
		<select name="BeerStyle" id="Style" required>
			<?php
			while ($style_data != null) {
				echo '<option value="' . $style_data['ID'] . '"';
					if ($style_data['ID'] == $res['Style_ID']) {
						echo ' selected ';
					}
					echo '>' . $style_data['Style'] . '</option>';
				$style_data = $style_req->fetch();
			}
			?>
		</select><br><br>

		<label for="BeerIBU">IBU</label><br>
		<input type="number" name="BeerIBU" min="0" max="1000" value="<?php echo $res['IBU']?>"><br><br>
		
		<label for="image">Picture</label><br>
		<input type="file" name="image" accept=".jpg, .jpeg, .png">
		<?php
		if (isset($size) && $size >= 1000000) {
			echo "The maximum upload size is 1 mb";
		}
		?>
		<br><br>

		<input type="submit" value="Confirm" name="confirm">

	 </form>

</body>
</html>