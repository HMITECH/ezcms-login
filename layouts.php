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
		  <div id="editBlock" class="row">
			<div class="col-md-3 white-boxed">
				<ul id="left-tree">
				  <li><i class="bi bi-file-text"></i>
					<a class="<?php echo $cms->homeclass; ?>" href="layouts.php">layout.php</a>
					<?php echo $cms->treehtml; ?>
				  </li>
				</ul>
			</div>
			<div class="col-md-9 white-boxed">
				<form id="frmlayout" action="layouts.php" method="post" enctype="multipart/form-data">
				<?php echo $cms->csrfField(); ?>
					<div class="toolbar-bar">
							<img src="img/ajax-loader.gif" id="tbloading">
							<a href="#" id="toggleEditSize" class="btn"><i class="bi bi-chevron-left"></i></a>
							<input type="submit" name="Submit" id="Submit" value="Save Changes" class="btn btn-primary">
							<div class="btn-group">
							  <a class="btn dropdown-toggle btn-info" data-bs-toggle="dropdown" href="#">
								Save As <span class="caret"></span></a>
							  <div id="SaveAsDDM" class="dropdown-menu" style="padding:10px;">
								<blockquote>
								  <p>Save layout as</p>
								  <small>Only Alphabets and Numbers, no spaces</small>
								</blockquote>
								<div class="input-group">
								  <span class="input-group-text">layout.</span>
								  <input id="txtSaveAs" name="txtSaveAs" type="text" class="form-control">
								  <span class="input-group-text">.php</span>
								</div><br>
								<p><a id="btnsaveas" href="#" class="btn btn-lg btn-info">Save Now</a></p>
							  </div>

							</div>
							<?php echo $cms->deletebtn; ?>
							<a id="showPages" href="#" class="btn btn btn-warning">Pages<sup><?php echo $cms->usage['cnt']; ?></sup></a>
							<a id="showrevs" href="#" class="btn btn-secondary">Revisions <sup><?php echo $cms->revs['cnt']; ?></sup></a>
							<a id="showdiff" href="#" class="btn btn-inverted btn-danger">Review DIFF</a>
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
					<div class="mb-3">
						<label class="form-label" for="txtGitMsg">Revision Message</label>
						<div class="controls">
							<input type="text" id="txtGitMsg" name="revmsg"
								placeholder="Enter a description for this revision"
								title="Enter a message to describe this revision."
								data-bs-toggle="tooltip"
								value=""
								data-bs-placement="top" minlength="2"
								class="form-control tooltipme2">
					</div>
					<div id="cm-toolbar">
					<button type="button" class="btn btn-mini cm-btn-find"><i class="bi bi-search"></i> Find</button>
					<button type="button" class="btn btn-mini cm-btn-replace"><i class="bi bi-arrow-repeat"></i> Replace</button>
					<button type="button" class="btn btn-mini cm-btn-goto"><i class="bi bi-skip-forward"></i> Go to Line</button>
					<div class="btn-group">
						<button type="button" class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-fonts"></i> <span class="cm-size-label">Font Size</span> <span class="caret"></span></button>
						<ul class="dropdown-menu cm-fontsize-menu">
							<li><a href="#" data-size="11">11px</a></li>
							<li><a href="#" data-size="12">12px</a></li>
							<li><a href="#" data-size="13">13px</a></li>
							<li><a href="#" data-size="14">14px</a></li>
							<li><a href="#" data-size="16">16px</a></li>
							<li><a href="#" data-size="18">18px</a></li>
							<li><a href="#" data-size="20">20px</a></li>
						</ul>
					</div>
					<div class="btn-group">
						<button type="button" class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-arrows-angle-contract"></i> Fold <span class="caret"></span></button>
						<ul class="dropdown-menu cm-fold-menu">
							<li><a href="#" data-fold="0">Fold All</a></li>
							<li class="divider"></li>
							<li><a href="#" data-fold="1">Level 1 – 1 level visible</a></li>
							<li><a href="#" data-fold="2">Level 2 – 2 levels visible</a></li>
							<li><a href="#" data-fold="3">Level 3 – 3 levels visible</a></li>
							<li><a href="#" data-fold="4">Level 4 – 4 levels visible</a></li>
							<li class="divider"></li>
							<li><a href="#" data-fold="none">Unfold All</a></li>
						</ul>
					</div>
					<a href="#cm-shortcuts-modal" data-bs-toggle="modal" class="btn btn-sm"><i class="bi bi-question-circle"></i> Shortcuts</a>
				</div>
				<textarea name="txtContents" id="txtContents" class="form-control"><?php echo $cms->content; ?></textarea>
				</form>
			</div>
		  </div>

		  <div id="diffBlock" class="white-boxed">
			<div class="toolbar-bar">
				<a id="backEditBTN" href="#" class="btn btn-inverted btn-info">Back to Main Editor</a>
				<a id="waysDiffBTN" href="#" class="btn btn-inverted btn-warning">Three Way (3)</a>
				<a id="collaspeBTN" href="#" class="btn btn-inverted btn-warning">Collaspe Unchanged</a>
			</div>
			<table id="diffviewerControld" width="100%" border="0">
			  <tr><td><select><option value="0">Current (Last Saved)</option><?php echo $cms->revs['opt']; ?></select>
				</td><td><select disabled><option selected>Your Current Edit</option></select>
				</td><td><select><option value="0">Current (Last Saved)</option><?php echo $cms->revs['opt']; ?></select>
			  </td></tr>
			</table>
			<div id="diffviewer"></div>
		  </div>
		  <textarea name="txtTemps" id="txtTemps" class="form-control"></textarea>

	</div>
	<br><br>
</div><!-- /wrap  -->

<!-- CodeMirror Keyboard Shortcuts Modal -->
<div id="cm-shortcuts-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="cm-shortcuts-label" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="cm-shortcuts-label">Keyboard Shortcuts</h3>
	</div>
	<div class="modal-body">
		<table class="table table-sm table-striped">
			<thead><tr><th style="width:210px">Key</th><th>Action</th></tr></thead>
			<tbody>
				<tr><td colspan="2"><strong>Search &amp; Navigation</strong></td></tr>
				<tr><td><kbd>Ctrl+F</kbd></td><td>Find</td></tr>
				<tr><td><kbd>Ctrl+G</kbd></td><td>Find Next</td></tr>
				<tr><td><kbd>Shift+Ctrl+G</kbd></td><td>Find Previous</td></tr>
				<tr><td><kbd>Ctrl+H</kbd></td><td>Replace</td></tr>
				<tr><td><kbd>Shift+Ctrl+H</kbd></td><td>Replace All</td></tr>
				<tr><td><kbd>Alt+G</kbd></td><td>Jump to Line</td></tr>
				<tr><td><kbd>Ctrl+Home</kbd></td><td>Go to Start</td></tr>
				<tr><td><kbd>Ctrl+End</kbd></td><td>Go to End</td></tr>
				<tr><td colspan="2"><strong>Editing</strong></td></tr>
				<tr><td><kbd>Ctrl+Z</kbd></td><td>Undo</td></tr>
				<tr><td><kbd>Ctrl+Y</kbd></td><td>Redo</td></tr>
				<tr><td><kbd>Ctrl+A</kbd></td><td>Select All</td></tr>
				<tr><td><kbd>Ctrl+D</kbd></td><td>Delete Line</td></tr>
				<tr><td><kbd>Alt+Up</kbd></td><td>Move Line Up</td></tr>
				<tr><td><kbd>Alt+Down</kbd></td><td>Move Line Down</td></tr>
				<tr><td><kbd>Ctrl+/</kbd></td><td>Toggle Comment</td></tr>
				<tr><td><kbd>Tab</kbd></td><td>Indent</td></tr>
				<tr><td><kbd>Shift+Tab</kbd></td><td>Dedent</td></tr>
				<tr><td><kbd>Ctrl+]</kbd></td><td>Indent More</td></tr>
				<tr><td><kbd>Ctrl+[</kbd></td><td>Indent Less</td></tr>
				<tr><td colspan="2"><strong>Code Folding</strong></td></tr>
				<tr><td><kbd>Ctrl+Q</kbd></td><td>Fold / Unfold Block</td></tr>
			</tbody>
		</table>
	</div>
	<div class="modal-footer">
		<button class="btn" data-bs-dismiss="modal">Close</button>
	</div>
</div>

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
<script src="codemirror/addon/dialog/dialog.js"></script>
<script src="codemirror/addon/search/searchcursor.js"></script>
<script src="codemirror/addon/search/search.js"></script>
<script src="codemirror/addon/scroll/annotatescrollbar.js"></script>
<script src="codemirror/addon/search/matchesonscrollbar.js"></script>
<script src="codemirror/addon/search/jump-to-line.js"></script>
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
<script>initCMToolbar(myCode, '#cm-toolbar');</script>
</body></html>
