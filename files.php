<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * View: Displays the files on the server in the site-assets folder
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("class/ezcms.class.php"); // CMS Class for database access
$cms = new ezCMS(); // create new instance of CMS Class with loginRequired = true

?><!DOCTYPE html><html lang="en"><head>

	<title>File Manager : ezCMS Admin</title>
	<?php include('include/head.php'); ?>

</head><body>

<div id="wrap">
	<?php include('include/nav.php'); ?>
	<div class="container"><div class="white-boxed">
		<iframe id="shrFrm" src="ckeditor/plugins/pgrfilemanager/PGRFileManager.php"
			frameborder='0' marginheight='0' marginwidth='0' scrolling="no"></iframe>
	</div></div>
	<br><br>
</div><!-- /wrap  -->

<?php include('include/footer.php'); ?>
<script>
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(0)").addClass('active');
	$("#top-bar li:eq(0) ul li:eq(9)").addClass('active');
</script>

</body></html>
