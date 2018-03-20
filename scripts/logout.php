<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Script: Logs out the user
 * 
 */
header ("Expires: Thu, 17 May 2001 10:17:17 GMT");    // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0

session_start();
session_unset();							// Unset session data
unset($_COOKIE[session_name()]);			// Clear the session cookie
session_destroy();							// Destroy session data

header("Location: ../?flg=logout");
exit;
?>