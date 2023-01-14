<?php
session_start();
require_once("connection.php");
global $bdd;
$id = $_SESSION['ID'];
$profileid = $_GET['id'];

$req_unfollow = $bdd->prepare("DELETE FROM follows WHERE Follower_ID = ? AND Followed_ID = ?");
$req_unfollow->execute(array($id,$profileid));
header('location:Profile.php?id='. $_GET['id']);
?>