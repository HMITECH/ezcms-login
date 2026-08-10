<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Include: Displays the header
 * 
 */
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">	
<meta name="author" content="mo.ahmed@hmi-tech.net">
<meta name="robots" content="noindex, nofollow">
<link type="image/x-icon" href="favicon.ico" rel="icon"/>
<link type="image/x-icon" href="favicon.ico" rel="shortcut icon"/>
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="vendor/bootstrap-icons/bootstrap-icons.min.css" rel="stylesheet">
<link href="css/bs2-compat.css" rel="stylesheet">
<link href="js/jquery.treeview/jquery.treeview.css" rel="stylesheet">
<link href="codemirror/lib/codemirror.css" rel="stylesheet">
<link rel="stylesheet" href="codemirror/addon/fold/foldgutter.css" />
<link rel="stylesheet" href="codemirror/addon/merge/merge.css" />
<?php if ((isset($_SESSION["CMTHEME"])) && ($_SESSION["CMTHEME"]!='default')) { ?>
	<link rel="stylesheet" href="codemirror/theme/<?php echo $_SESSION["CMTHEME"]; ?>.css">
<?php } ?>
<link rel="stylesheet" href="codemirror/addon/hint/show-hint.css">
<link rel="stylesheet" href="codemirror/addon/dialog/dialog.css">
<link href="css/custom.css" rel="stylesheet">
<link href="css/ezcms-icons.css" rel="stylesheet">
<script src="js/jquery-4.0.0.min.js"></script>
<script src="js/jquery-migrate-4.0.2.min.js"></script>