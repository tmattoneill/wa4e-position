<?php
	session_start();
	
	require_once("conn.php");
	require_once("func.php");

	// App Constants
	define ("ERR_EMAIL", "Email address must contain @");
	define ("ERR_BAD_PASS", "Invalid Password.");
	define ("ERR_EMPTY_FIELDS", "All values are required");
	define ("ERR_NO_ACCESS", "ACCESS DENIED");
	define ("ERR_DUPE_EMAIL", "A profile for that email address already exists.");
	define ("ERR_NO_PROFILE", "Could not load profile");
	define ("ERR_NO_PROFILE_ID", "Missing profile_id");

	// vars needed throughout the app
	$salt = 'XyZzy12*_';
	$logged_in = isset($_SESSION["user_id"]);
	
?>