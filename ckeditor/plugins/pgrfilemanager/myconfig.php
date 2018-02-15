<?php

// Start SESSION if not started
if (session_status() !== PHP_SESSION_ACTIVE) session_start(); 

// Set SESSION ADMIN Login Flag to false if not set
if (!isset($_SESSION['EZUSERID'])) $_SESSION['EZUSERID'] = false;

// Check logged in user ID
if (!$_SESSION['EZUSERID'])
	die('<div><h1>Permission denied.<br>You must be logged in to access this</h1>
		 <p><img src="../../../img/noaccess.png"></p></div>');
		 
// Check if user has permissions for this.
if (!isset($_SESSION['MANAGEFILES'])) $_SESSION['MANAGEFILES'] = false;
if (!$_SESSION['MANAGEFILES']) 
	die('<div><h1>Permission denied.<br>You must have page edit privileges to access this</h1>
		 <p><img src="../../../img/noaccess.png"></p></div>');


//real absolute path to root directory (directory you want to use with PGRFileManager) on your server  
PGRFileManagerConfig::$rootPath = '../../../../../site-assets';

//i.e /gallery
$siteFolder =  substr(($_SERVER["PHP_SELF"]), 0, -57);
PGRFileManagerConfig::$urlPath = $siteFolder.'/site-assets/';

//Max file upload size in bytes
PGRFileManagerConfig::$fileMaxSize = 2048 * 2048 * 20;
//Allowed file extensions
//PGRFileManagerConfig::$allowedExtensions = '' means all files
PGRFileManagerConfig::$allowedExtensions = '';
//Allowed image extensions
PGRFileManagerConfig::$imagesExtensions = 'jpg|gif|jpeg|png|bmp';
//Max image file height in px
PGRFileManagerConfig::$imageMaxHeight = 1024;
//Max image file width in px
PGRFileManagerConfig::$imageMaxWidth = 2048;
//Thanks to Cycle.cz
//Allow or disallow edit, delete, move, upload, rename files and folders
PGRFileManagerConfig::$allowEdit = true;		// true - false
//Autorization
PGRFileManagerConfig::$authorize = false;        // true - false
PGRFileManagerConfig::$authorizeUser = 'user';
PGRFileManagerConfig::$authorizePass = 'password';
