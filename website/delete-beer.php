<?php
session_start();
require_once("connection.php");
global $bdd;

if (isset($_GET['id']) && isset($_SESSION['Admin'])) {
    $request = $bdd->prepare("DELETE FROM comment WHERE Beer_ID = ?;
                            DELETE FROM beer WHERE ID = ?");
    $request->execute(array($_GET['id'], $_GET['id']));

    header("Location:Browse-Beers.php");
}