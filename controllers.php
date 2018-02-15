<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 * $Header: /cygdrive/c/cvs/repo/xampp/htdocs/hmi/ezsite/login/controllers.php,v 1.1 2017-12-02 09:31:43 a Exp $
 * View: Displays the php controller of the site
 * /index.php
 */

// **************** ezCMS CONTROLLER CLASS ****************
require_once ("class/controller.class.php");

// **************** ezCMS CONTROLLER HANDLE ****************
$cms = new ezController();

?><!DOCTYPE html><html lang="en"><head>

	<title>URL Router : ezCMS Admin</title>
	<?php include('include/head.php'); ?>

</head><body>

<div id="wrap">
	<?php include('include/nav.php'); ?>
	<div class="container">

		<div id="editBlock" class="white-boxed">
		  <form id="frmHome" action="controllers.php" method="post" enctype="multipart/form-data">
			<div class="navbar">
				<div class="navbar-inner">
					<input type="submit" name="Submit" value="Save Changes" class="btn btn-primary ">
					<?php if ($_SESSION['EDITORTYPE'] == 3) {?>
					<a id="showrevs" href="#" class="btn btn-secondary">Revision Log <sup><?php echo $cms->revs['cnt']; ?></sup></a>
					<?php } ?>
					<?php if ($_SESSION['EDITORTYPE'] == 3) {?>
					<a id="showdiff" href="#" class="btn btn-inverted btn-danger">Review DIFF</a>
					<?php } ?>
				</div>
			</div>
			<?php echo $cms->msg; ?>
			<div id="revBlock">
			  <table class="table table-striped"><thead>
				<tr><th>#</th><th>User Name</th><th>Message</th><th>Date &amp; Time</th><th>Action</th></tr>
			  </thead><tbody><?php echo $cms->revs['log']; ?></tbody></table>
			</div>
			<div class="control-group">
				<label class="control-label" for="txtGitMsg">Revision Message</label>
				<div class="controls">
					<input type="text" id="txtGitMsg" name="revmsg"
						placeholder="Enter a description for this revision"
						title="Enter a message to describe this revision."
						data-toggle="tooltip"
						value=""
						data-placement="top" minlength="2"
						class="input-block-level tooltipme2">
				</div>
			</div>
			<textarea name="txtContents" id="txtContents" class="input-block-level"><?php echo $cms->content; ?></textarea>
		  </form>
		</div>

		<div id="diffBlock" class="white-boxed">
			<div class="navbar"><div class="navbar-inner">
				<a id="backEditBTN" href="#" class="btn btn-inverted btn-info">Back to Main Editor</a>
				<a id="waysDiffBTN" href="#" class="btn btn-inverted btn-warning">Three Way (3)</a>
				<a id="collaspeBTN" href="#" class="btn btn-inverted btn-warning">Collaspe Unchanged</a>
			</div></div>
			<table id="diffviewerControld" width="100%" border="0">
			  <tr><td><select><option value="0">Current (Last Saved)</option><?php echo $cms->revs['opt']; ?></select>
				</td><td><select disabled><option selected>Your Current Edit</option></select>
				</td><td><select><option value="0">Current (Last Saved)</option><?php echo $cms->revs['opt']; ?></select>
			  </td></tr>
			</table>
			<div id="diffviewer"></div>
		</div>
		<textarea name="txtTemps" id="txtTemps" class="input-block-level"></textarea>

	</div>
	<br><br>
</div><!-- /wrap  -->

<?php include('include/footer.php'); ?>
<script type="text/javascript">
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(0)").addClass('active');
	$("#top-bar li:eq(0) ul li:eq(1)").addClass('active');
</script>

<?php if ($_SESSION['EDITORTYPE'] == 3) { ?>

	<script src="codemirror/lib/codemirror.js"></script>
	<script src="codemirror/mode/javascript/javascript.js"></script>
	<script src="codemirror/mode/htmlmixed/htmlmixed.js"></script>
	<script src="codemirror/addon/edit/matchbrackets.js"></script>
	<script src="codemirror/mode/xml/xml.js"></script>
	<script src="codemirror/addon/fold/foldcode.js"></script>
	<script src="codemirror/addon/fold/foldgutter.js"></script>
	<script src="codemirror/addon/fold/brace-fold.js"></script>
	<script src="codemirror/addon/fold/xml-fold.js"></script>
	<script src="codemirror/addon/fold/markdown-fold.js"></script>
	<script src="codemirror/addon/fold/comment-fold.js"></script>
	<script src="codemirror/addon/merge/diff_match_patch.js"></script>
	<script src="codemirror/addon/merge/merge.js"></script>
	<script src="codemirror/mode/css/css.js"></script>
	<script src="codemirror/mode/clike/clike.js"></script>
	<script src="codemirror/mode/php/php.js"></script>
	<script language="javascript" type="text/javascript">
		var revJson = <?php echo json_encode($cms->revs['jsn']); ?>;
		var	cmTheme = '<?php echo $_SESSION["CMTHEME"]; ?>',
			cmMode = 'application/x-httpd-php';
	</script>
	<script src="js/gitFileCode.js"></script>

<?php } else { ?>

	<script language="javascript" type="text/javascript" src="js/edit_area/edit_area_full.js"></script>
	<script type="text/javascript">
		editAreaLoader.init({
			id:"txtContents",
			syntax: "php",
			allow_toggle: true,
			start_highlight: true,
			toolbar: "search, go_to_line, |, undo, redo, |, select_font, |, change_smooth_selection, highlight, reset_highlight"
		});
	</script>

<?php } ?>

</body></html>