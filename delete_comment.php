<?php session_start(); require_once("ChromePhp.php"); require_once("db.php");
	$db = new db();
	$review_id = $_POST[ 'delete' ];
	$user_id = $_SESSION['user_id'];
	$db->deleteReview($review_id, $user_id);

	$firstClass = $_SESSION['firstClass'];
	$secondClass = $_SESSION['secondClass'];
	$thirdClass = $_SESSION['thirdClass']; 

	$url = "http://undecided.web.engr.illinois.edu/result.php?classOne=$firstClass&classTwo=$secondClass&classThree=$thirdClass";
	
	
	header( "Location: $url" );

?>