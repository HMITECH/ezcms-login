<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * Version 2.010413 Dated 20/March/2013
 * Rev: 14-Apr-2014 (2.140413)
 * HMI Technologies Mumbai (2013-14)
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