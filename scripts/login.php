<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Script: logs in the user to the ezCMS
 * 
 */

// Start SESSION if not started 
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start(); 
}

// Set SESSION ADMIN Login Flag to false if not set
if (!isset($_SESSION['LOGGEDIN'])) {
	$_SESSION['LOGGEDIN'] = false;
}

// Make sure post parameters were sent...
if ( (!isset($_POST["userid"])) || (!isset($_POST["passwd"])) ) {
	header('HTTP/1.1 400 BAD REQUEST');
	die('Invalid Request');
}

// Get the POST data
$userid = $_POST["userid"];
$passwd = $_POST["passwd"];

// Form variables must have something in them...
if ( (empty($userid)) || (empty($passwd)) ) { 
	header("Location: ../?flg=failed&userid=$userid"); 
	exit; 
}

// **************** DATABASE ****************
require_once ("../../cms.class.php"); // PDO Class for database access
$dbh = new db; // database handle

// Prepare SQL to fetch user's record from database
$stmt = $dbh->prepare('SELECT * FROM `users` WHERE `email` = ? AND (`passwd` = SHA2( ? , 512 ) OR `passwd` = ?) LIMIT 1');
$stmt->execute( array($userid, $passwd, $passwd) );

// Check if User Record is present and returned from the database
if ($stmt->rowCount()) { 

	// Fetch the user record from database
	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	// User must be ACTIVE to login
	if (!$user['active']) { 
		header("Location: ../?flg=inactive&userid=$userid"); 
		exit; 
	}
	
	//login the user
	$_SESSION['LOGGEDIN']   = true;  // login flag to TRUE
	$_SESSION['EZUSERID']   = $user['id']; // User's ID
	$_SESSION["CMTHEME"]    = $user['cmtheme']; // Default Code Mirror theme
	
	// Encrypt the password on first login if is not.
	if ($user['passwd'] == $passwd)
		// $passwd is not hashed 
		$dbh->query('UPDATE `users` SET `passwd` = SHA2( `passwd` , 512 ) WHERE `id`='.$user['id']);
	
	// Redirect to logged in page
	if ( isset( $_SESSION['AFTERLOGINPAGE'] )) {
		$goto = $_SESSION['AFTERLOGINPAGE'];
		unset($_SESSION['AFTERLOGINPAGE']);
		header("Location: $goto");
	} else {
		header("Location: ../pages.php");
	}
	exit;
	
}

// Login failed
header("Location: ../?flg=failed&userid=".$userid);
exit;

?>
