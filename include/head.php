<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Include: Displays the common head
 * 
 */
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">	
<meta name="author" content="mo.ahmed@hmi-tech.net">
<meta name="robots" content="noindex, nofollow">
<link type="image/x-icon" href="favicon.ico" rel="icon"/>
<link type="image/x-icon" href="favicon.ico" rel="shortcut icon"/>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="js/jquery.treeview/jquery.treeview.css" rel="stylesheet">
<?php if ((isset($_SESSION['EDITORTYPE'])) &&  ($_SESSION['EDITORTYPE'] == 3)) { ?>
	<link href="codemirror/lib/codemirror.css" rel="stylesheet">
	<link rel="stylesheet" href="codemirror/addon/fold/foldgutter.css" />
	<link rel="stylesheet" href="codemirror/addon/merge/merge.css" />
	<?php if ($_SESSION["CMTHEME"]!='default') { ?>
		<link rel="stylesheet" href="codemirror/theme/<?php echo $_SESSION["CMTHEME"]; ?>.css">
	<?php } ?>
	<link rel="stylesheet" href="codemirror/addon/hint/show-hint.css">
<?php } ?>
<link href="css/custom.css" rel="stylesheet">
<script src="js/jquery-1.9.1.min.js"></script>