<?php session_start(); require_once("ChromePhp.php"); require_once("db.php");
	$db = new db();
	$url = $_SERVER['REQUEST_URI'];
	$review_id = substr($url, 1+strpos($url, "?"));
	$user_id = $_SESSION['user_id'];
	$db->updateReview($review_id, 1, $user_id);
	
	$firstClass = $_SESSION['firstClass'];
	$secondClass = $_SESSION['secondClass'];
	$thirdClass = $_SESSION['thirdClass']; 

	$url = "http://undecided.web.engr.illinois.edu/result.php?classOne=$firstClass&classTwo=$secondClass&classThree=$thirdClass";
	
	
	header( "Location: $url" );