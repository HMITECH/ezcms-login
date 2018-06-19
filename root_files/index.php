<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net and mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Controller: Front-end Router - index.php
 *
 * Renders all the pages in the CMS.
 */

// **************** Page Protocol ****************
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? "https://" : "http://";

// **************** DATABASE ****************
require_once ("cms.class.php"); // PDO Class for database access
$dbh = new db; // database handle available in layouts

// **************** SITE DETAILS ****************
$site = $dbh->query('SELECT * FROM `site` ORDER BY `id` DESC LIMIT 1')
		->fetch(PDO::FETCH_ASSOC); // get the site details

// **************** REQUESTED URI ****************
$uri = strtok($_SERVER["REQUEST_URI"],'?'); // get the requested URI
$siteFolder =  substr(htmlspecialchars($_SERVER["PHP_SELF"]), 0, -10);
if ($siteFolder) $uri = substr( $uri , strlen($siteFolder) );

// **************** PAGE DETAILS ****************
$stmt = $dbh->prepare('SELECT * FROM `pages` WHERE `url` = ? ORDER BY `id` DESC LIMIT 1');
$stmt->execute( array($uri) );

// Check if page is found in database.
if ($stmt->rowCount()) {

	// Page is found in Database
	$page = $stmt->fetch(PDO::FETCH_ASSOC);

	// Check if page is published or not.
	if (!$page["published"]) {

		// Start session if not started to check ADMIN login status
		if (session_status() !== PHP_SESSION_ACTIVE) {
			session_start();
		}

		// Set SESSION ADMIN Login Flag to false if not set
		if (!isset($_SESSION['LOGGEDIN'])) {
			$_SESSION['LOGGEDIN'] = false;
		}

		 // Check if Admin is logged in -
		 // unpublished pages are visible to ADMIN.
		if (!$_SESSION['LOGGEDIN']) {
			// If ADMIN is NOT logged in then serve 404 page as it is unpublished
			$stmt->execute( array('/.') );
			$page = $stmt->fetch(PDO::FETCH_ASSOC);
		}

	}
} else {
	// Page is NOT found, server 404 page
	$stmt = $dbh->prepare('SELECT * FROM `pages` WHERE `id` = 2 LIMIT 1');
	$stmt->execute();
	$page = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Verify the layout files to be used,
// fall back to default if not found
if (!file_exists($page['layout'])) {
	$page['layout'] = 'layout.php';
}

// build canonical URL
$page['canonical'] = $protocol.$_SERVER['HTTP_HOST'].$page["url"];

// Setup CMS Template variable to be used in the layouts
$maincontent = $page["maincontent"];
$header      = ($page["useheader"] == 1) ? $page["headercontent"] : $site["headercontent"];
$sidebar     = ($page["useside"]   == 1) ? $page["sidecontent"]   : $site["sidecontent"];
$siderbar    = ($page["usesider"]  == 1) ? $page["sidercontent"]  : $site["sidercontent"];
$footer      = ($page["usefooter"] == 1) ? $page["footercontent"] : $site["footercontent"];
// you can add your own variable here, eg: $mymodscrp = '';
// This variable are available in layout files for use.


// Set 404 header when severing page not found
if ($page['id']==2) {
	Header("HTTP/1.0 404 Not Found");
}

// Serve the selected layout file
include($page['layout']);
die();

?>
