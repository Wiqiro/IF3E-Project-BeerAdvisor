<?php 
session_start();
require_once("connection.php");
global $bdd;

if (!isset($_SESSION['Admin'])) {
    header("Location:Browse-Beers.php");
}

$id = $_SESSION['ID'];

if (isset($_GET['delete_color'])) {
    $request = $bdd->prepare("UPDATE beer SET Color_ID = 0 WHERE Color_ID = ?;
    DELETE FROM color WHERE ID = ?");
    $request->execute(array($_GET['color_id'], $_GET['color_id']));
} else if (isset($_GET['edit_color'])) {
    $request = $bdd->prepare("UPDATE color SET Color = ? WHERE ID = ?");
    $request->execute(array($_GET['color_new_name'], $_GET['color_id']));
} else if (isset($_GET['add_color'])) {
    $request = $bdd->prepare("INSERT INTO color (Color) VALUES (?)");
    $request->execute(array($_GET['color_new_name']));

} else if (isset($_GET['delete_style'])) {
    $request = $bdd->prepare("UPDATE beer SET Style_ID = 0 WHERE Style_ID = ?;
    DELETE FROM style WHERE ID = ?");
    $request->execute(array($_GET['style_id'], $_GET['style_id']));
} else if (isset($_GET['edit_style'])) {
    $request = $bdd->prepare("UPDATE style SET Style = ? WHERE ID = ?");
    $request->execute(array($_GET['style_new_name'], $_GET['style_id']));
} else if (isset($_GET['add_style'])) {
    $request = $bdd->prepare("INSERT INTO style (Style) VALUES (?)");
    $request->execute(array($_GET['style_new_name']));
}

if (isset($_GET['color_id']) || isset($_GET['style_id'])) {
    header('Location:Admin.php');
}

$color_req = $bdd->prepare("SELECT * FROM color WHERE ID != 0 ORDER BY ID");
$color_req->execute();
$color_data = $color_req->fetch();

$style_req = $bdd->prepare("SELECT * FROM style WHERE ID != 0 ORDER BY ID");
$style_req->execute();
$style_data = $style_req->fetch();


?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beer advisor</title>
    <meta name="author" content="Quentin,Eloi,William">
    <meta name="description" content="Page to connect or subscribe">
    <link rel="shortcut icon" href="logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <div class="image"><a href="Browse-Beers.php"><img src="BeerAdvisor.png" alt="logo"></a></div>
        <div class="header_title">Admin board</div>
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
    
    <table class="admin_table">
        <tr>
            <th style="font-size: xx-large">Colors</th>
            <th style="font-size: xx-large">Styles</th>
        </tr>
        <tr class="admin_form_container">
            <td ><?php
                while ($color_data != null) {
                    echo '
                    <form action="" class="admin_form" method="get">
                    <input type="hidden" name="color_id" value="' . $color_data['ID'] . '">
                    <table><tr>
                        <td><input type="text" name="color_new_name" value="'. $color_data['Color'] . '" required></td>
                        <td><input type="submit" name="edit_color" value="Edit"></td>
                        <td><input type="submit" onclick="return confirm(`Are you sure you want to delete this color ?\n(Warning: All linked beers will lose their color)`);" name="delete_color" value="&nbsp-&nbsp"></td>
                    </tr></table>
                    </form><br>';
                    $color_data = $color_req->fetch();
                }
                ?>
                <form action="" class="admin_form" method="get">
                    <table><tr>
                        <td><input type="text" name="color_new_name" placeholder="Add color" required></td>
                        <td><input type="submit" name="add_color" value="&nbsp+&nbsp"></td>
                    </tr></table>
                </form>
            </td>

            <td><?php
                while ($style_data != null) {
                    echo '
                    <form action="" class="admin_form" method="get">
                    <input type="hidden" name="style_id" value="' . $style_data['ID'] . '">
                    <table><tr>
                        <td><input type="text" name="style_new_name" value="'. $style_data['Style'] . '" required></td>
                        <td><input type="submit" name="edit_style" value="Edit"></td>
                        <td><input type="submit" onclick="return confirm(`Are you sure you want to delete this style ?\n(Warning: All linked beers will lose their style)`);" name="delete_style" value="&nbsp-&nbsp"></td>
                    </tr></table>
                    </form><br>';
                    $style_data = $style_req->fetch();
                }
                ?>
                <form action="" class="admin_form" method="get">
                    <table><tr>
                        <td><input type="text" name="style_new_name" placeholder="Add style" required></td>
                        <td><input type="submit" name="add_style" value="&nbsp+&nbsp"></td>
                    </tr></table>
                </form>
            </td>


        </tr>
    </table>
			

</body>
