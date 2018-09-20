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

// **************** DATABASE ****************
require_once ("cms.class.php"); // REDIS + PDO Class for database access
$dbh = new db; // database handle available in layouts

// **************** SITE DETAILS ****************
$site = $dbh->getSiteData();

// **************** REQUESTED URI ****************
$uri = strtok($_SERVER["REQUEST_URI"],'?'); // get the requested URI
$siteFolder =  substr(htmlspecialchars($_SERVER["PHP_SELF"]), 0, -10);
if ($siteFolder) $uri = substr( $uri , strlen($siteFolder) );

// **************** PAGE DETAILS ****************
$page = $dbh->getPageData($uri);

// Setup CMS Template variable to be used in the layouts
$maincontent = $page["maincontent"];
$header      = ($page["useheader"] == 1) ? $page["headercontent"] : $site["headercontent"];
$sidebar     = ($page["useside"]   == 1) ? $page["sidecontent"]   : $site["sidecontent"];
$siderbar    = ($page["usesider"]  == 1) ? $page["sidercontent"]  : $site["sidercontent"];
$footer      = ($page["usefooter"] == 1) ? $page["footercontent"] : $site["footercontent"];

// Verify the layout files to be used,
if (!file_exists($page['layout'])) $page['layout'] = 'layout.php';

// Serve the selected layout file
include($page['layout']);
exit;

?>