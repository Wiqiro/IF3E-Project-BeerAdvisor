<?php
session_start();
require_once("connection.php");
global $bdd;


if (isset($_GET['id']) && isset($_SESSION['ID']) && $_GET['id'] == $_SESSION['ID']) {
    $request = $bdd->prepare("UPDATE user SET Picture = '' WHERE ID = ?");
    $request->execute(array($_GET['id']));

    header('Location:Profile.php?id=' . $_GET['id']);
}
?>