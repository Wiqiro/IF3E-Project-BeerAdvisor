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
    <meta charset="UTF-8">
    <meta name="author" content="Quentin,Eloi,William">
    <meta name="description" content="This is a page about beer">
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
			Color: ' . $beer_data['Color'] . '<br>
			Aroma: ' . $beer_data['Aroma'] . '<br><br>';
?>

<form action="" method="post">
    <label for="grade">Grade</label>
    <select name="grade" id="grade" required>
        <option></option>
        <option value=1>1 / 5</option>
        <option value=2>2 / 5</option>
        <option value=3>3 / 5</option>
        <option value=4>4 / 5</option>
        <option value=5>5 / 5</option>
    </select><br>
    <textarea name="text" id="text" required minlength="20" maxlength="300" class="NewComment"
              placeholder="Add your own review"></textarea><br>
    <input type="submit" value="Add review" name="create">

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
				</div>';

        $com_data = $request->fetch();
    }
    ?>
</div>
</body>
</html>