<?php
session_start();
require_once("connection.php");
global $bdd;

if (isset($_GET['id']) && isset($_SESSION['ID']) && (isset($_SESSION['Admin']) || (isset($_GET['user_id']) && $_GET['user_id'] == $_SESSION['ID']))) {
    $request = $bdd->prepare("DELETE FROM comment WHERE ID = ?");
    $request->execute(array($_GET['id']));

    header("Location:".$_SERVER['HTTP_REFERER']);
    
}