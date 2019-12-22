<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * View: Displays the layouts in the site
 * 
 */

// **************** ezCMS LAYOUTS CLASS ****************
require_once ("class/layouts.class.php");

// **************** ezCMS LAYOUTS HANDLE ****************
$cms = new ezLayouts();

?><!DOCTYPE html><html lang="en"><head>

	<title>PHP Layouts : ezCMS Admin</title>
	<?php include('include/head.php'); ?>

</head><body>

<div id="wrap">
	<?php include('include/nav.php'); ?>
	<div class="container">
		  <div id="editBlock" class="row-fluid">
			<div class="span3 white-boxed">
				<ul id="left-tree">
				  <li><i class="icon-list-alt"></i>
					<a class="<?php echo $cms->homeclass; ?>" href="layouts.php">layout.php</a>
					<?php echo $cms->treehtml; ?>
				  </li>
				</ul>
			</div>
			<div class="span9 white-boxed">
				<form id="frmlayout" action="layouts.php" method="post" enctype="multipart/form-data">
					<div class="navbar">
						<div class="navbar-inner">
							<img src="img/ajax-loader.gif" id="tbloading">
							<a href="#" id="toggleEditSize" class="btn"><i class="icon-chevron-left"></i></a>
							<input type="submit" name="Submit" id="Submit" value="Save Changes" class="btn btn-primary">
							<div class="btn-group">
							  <a class="btn dropdown-toggle btn-info" data-toggle="dropdown" href="#">
								Save As <span class="caret"></span></a>
							  <div id="SaveAsDDM" class="dropdown-menu" style="padding:10px;">
								<blockquote>
								  <p>Save layout as</p>
								  <small>Only Alphabets and Numbers, no spaces</small>
								</blockquote>
								<div class="input-prepend input-append">
								  <span class="add-on">layout.</span>
								  <input id="txtSaveAs" name="txtSaveAs" type="text" class="input-medium appendedPrependedInput">
								  <span class="add-on">.php</span>
								</div><br>
								<p><a id="btnsaveas" href="#" class="btn btn-large btn-info">Save Now</a></p>
							  </div>

							</div>
							<?php echo $cms->deletebtn; ?>
							<a id="showPages" href="#" class="btn btn btn-warning">Pages<sup><?php echo $cms->usage['cnt']; ?></sup></a>
							<a id="showrevs" href="#" class="btn btn-secondary">Revisions <sup><?php echo $cms->revs['cnt']; ?></sup></a>
							<a id="showdiff" href="#" class="btn btn-inverted btn-danger">Review DIFF</a>
						</div>
					</div>
					<?php echo $cms->msg; ?>
					<div id="revBlock">
					  <table class="table table-striped"><thead>
						<tr><th>#</th><th>User Name</th><th>Message</th><th>Date &amp; Time</th><th>Action</th></tr>
					  </thead><tbody><?php echo $cms->revs['log']; ?></tbody></table>
					</div>
					<div id="pagesBlock">
					  <table class="table table-striped"><thead>
						<tr><th>ID</th><th>Page Name</th><th>URL</th></tr>
					  </thead><tbody><?php echo $cms->usage['log']; ?></tbody></table>
					</div>					
					<input type="hidden" name="txtName" id="txtName" value="<?php echo $cms->filename; ?>">
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
<script>
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(0)").addClass('active');
	$("#top-bar li:eq(0) ul li:eq(5)").addClass('active');
	$('#btnsaveas').click( function () {
		var saveasfile = $('#txtSaveAs').val().trim();
		if (saveasfile.length < 1) {
			alert('Enter a valid filename to save as.');
			$('#txtSaveAs').focus();
			return false;
		}
		if (!saveasfile.match(/^[a-z0-9_\-\.]+$/ig)) {
			alert('Enter a valid filename with lower case alphabets and numbers only.');
			$('#txtSaveAs').focus();
			return false;
		}
		$('#txtName').val('layout.'+saveasfile+'.php');
		$('#Submit').click();
		return false;
	});
	// Show the pages used in block
	$('#showPages').click(function () {
		$('#pagesBlock').slideToggle();
		return false;
	});
	//$('title').text('<?php echo $cms->filename; ?>');
	$('title').text( location.search.split('=')[1] );
</script>
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
<script>
	var revJson = <?php echo json_encode($cms->revs['jsn']); ?>;
	var	cmTheme = '<?php echo $_SESSION["CMTHEME"]; ?>',
		cmMode = 'application/x-httpd-php';
	$('#frmlayout').submit( function() {
		myCode.save();
		$('.alert').remove();
		$('#tbloading').parent().children().hide();
		$('#tbloading').show();
		$.post( $(this).prop('action')+'?ajax', $(this).serialize(), function(data) {
			$('#tbloading').parent().children().show();
			$('#tbloading').hide();
			$(data).insertBefore('#revBlock');
		}).fail( function() {
            alert( "Request Failed" );
		});
		return false;
	});
</script>
<script src="js/gitFileCode.js"></script>
</body></html>
