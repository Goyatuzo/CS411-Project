<?php session_start(); require_once("ChromePhp.php"); require_once("db.php");
        $db = new db(); $user_id = $_SESSION['user_id'];
	$combo_id = $_SESSION['combo_id'];
	$text = $_GET['commentBox'];
	$combo_rating = $_GET['rating'];
	$db->createReview($combo_id, $user_id, $text, $combo_rating);

	$firstClass = $_SESSION['firstClass'];
	$secondClass = $_SESSION['secondClass'];
	$thirdClass = $_SESSION['thirdClass']; 

	$url = "http://undecided.web.engr.illinois.edu/result.php?classOne=$firstClass&classTwo=$secondClass&classThree=$thirdClass";
	

	
	header( "Location: $url" );
	

?>