<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * Version 2.010413 Dated 20/March/2013
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 * $Header: /cygdrive/c/cvs/repo/xampp/htdocs/hmi/ezsite/login/files.php,v 1.2 2017-12-02 09:33:28 a Exp $
 * View: Displays the files on the server in the site
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
<script type="text/javascript">
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(0)").addClass('active');
	$("#top-bar li:eq(0) ul li:eq(7)").addClass('active');
</script>

</body></html>
