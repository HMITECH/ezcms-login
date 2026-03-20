<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * View: Displays the php include blocks in the site
 * 
 */

// **************** ezCMS LAYOUTS CLASS ****************
require_once ("class/includes.class.php");

// **************** ezCMS LAYOUTS HANDLE ****************
$cms = new ezIncludes();

?><!DOCTYPE html><html lang="en"><head>

	<title>PHP Includes : ezCMS Admin</title>
	<?php include('include/head.php'); ?>

</head><body>

<div id="wrap">
	<?php include('include/nav.php'); ?>
	<div class="container">
		  <div id="editBlock" class="row-fluid">
			<div class="span3 white-boxed">
				<ul id="left-tree">
				  <li><i class="icon-plus"></i>
					<a class="<?php echo $cms->homeclass; ?>" href="includes.php">include.php</a>
					<?php echo $cms->treehtml; ?>
				  </li>
				</ul>
			</div>
			<div class="span9 white-boxed">
				<form id="frmlayout" action="includes.php" method="post" enctype="multipart/form-data">
				<?php echo $cms->csrfField(); ?>
					<div class="navbar">
						<div class="navbar-inner">
							<a href="#" id="toggleEditSize" class="btn"><i class="icon-chevron-left"></i></a>
							<input type="submit" name="Submit" id="Submit" value="Save Changes" class="btn btn-primary">
							<div class="btn-group">
							  <a class="btn dropdown-toggle btn-info" data-toggle="dropdown" href="#">
								Save As <span class="caret"></span></a>
							  <div id="SaveAsDDM" class="dropdown-menu" style="padding:10px;">
								<blockquote>
								  <p>Save Include as</p>
								  <small>Only Alphabets - . _ and Numbers, no spaces</small>
								</blockquote>
								<div class="input-prepend input-append">
								  <span class="add-on">includes/</span>
								  <input id="txtSaveAs" name="txtSaveAs" type="text" class="input-medium appendedPrependedInput">
								  <span class="add-on">.php</span>
								</div><br>
								<p><a id="btnsaveas" href="#" class="btn btn-info">Save Now</a></p>
							  </div>

							</div>
							<?php echo $cms->deletebtn; ?>
							<a id="showPages" href="#" class="btn btn btn-warning">Layouts<sup><?php echo $cms->usage['cnt']; ?></sup></a>
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
						<tr><th>#</th><th>Layout Name</th><th>Action</th></tr>
					  </thead><tbody><?php echo $cms->usage['log']; ?></tbody></table>
					</div>					
					<input type="hidden" name="txtName" id="txtName" value="<?php echo $cms->filename; ?>">
					<div class="control-group">
						<label class="control-label" for="txtGitMsg">Revision Message</label>
						<div class="controls">
							<input type="text" id="txtGitMsg" name="revmsg"
								placeholder="Enter a description for this revision"
								title="Enter a message to describe this revision."
								data-toggle="tooltip" value=""
								data-placement="top" minlength="2"
								class="input-block-level tooltipme2">
						</div>
					</div>
					<input border="0" class="input-block-level tooltipme2" name="txtlnk" onFocus="this.select();"
					style="cursor: pointer;" onClick="this.select();"  type="text" title="Include this in your layouts"
					value="&lt;?php include ( &quot;includes/<?php echo $cms->filename; ?>&quot; ); ?&gt;" readonly/>

					<div id="cm-toolbar">
					<button type="button" class="btn btn-mini cm-btn-find"><i class="icon-search"></i> Find</button>
					<button type="button" class="btn btn-mini cm-btn-replace"><i class="icon-retweet"></i> Replace</button>
					<button type="button" class="btn btn-mini cm-btn-goto"><i class="icon-step-forward"></i> Go to Line</button>
					<div class="btn-group">
						<button type="button" class="btn btn-mini dropdown-toggle" data-toggle="dropdown"><i class="icon-font"></i> <span class="cm-size-label">Font Size</span> <span class="caret"></span></button>
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
						<button type="button" class="btn btn-mini dropdown-toggle" data-toggle="dropdown"><i class="icon-resize-small"></i> Fold <span class="caret"></span></button>
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
					<a href="#cm-shortcuts-modal" data-toggle="modal" class="btn btn-mini"><i class="icon-question-sign"></i> Shortcuts</a>
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

<?php include('include/cm_shortcuts_modal.php'); ?>

<div id="ai-sidebar">
	<div class="ai-sidebar-header">
		<span class="ai-sidebar-close" id="ai-sidebar-close"><i class="icon-remove"></i></span>
		<i class="icon-comment"></i> Synapse
	</div>
	<div class="ai-sidebar-section">
		<h5><i class="icon-info-sign"></i> Coming Soon</h5>
		<p>Synapse will be available here to assist you while editing includes.</p>
	</div>
	<div class="ai-sidebar-section">
		<h5><i class="icon-lightbulb"></i> Planned Features</h5>
		<p>Ask questions about your include code, get suggestions, and auto-complete snippets.</p>
	</div>
	<div class="ai-sidebar-section">
		<h5><i class="icon-cog"></i> Include Tools</h5>
		<p>Context-aware tools for the include file currently open in the editor will appear here.</p>
	</div>
</div>

<?php include('include/footer.php'); ?>
<script>
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(0)").addClass('active');
	$("#top-bar li:eq(0) ul li:eq(6)").addClass('active');
	// AI Sidebar toggle
	$('#btn-ai-sidebar').click(function () {
		$('#ai-sidebar').toggleClass('open');
		return false;
	});
	$('#ai-sidebar-close').click(function () {
		$('#ai-sidebar').removeClass('open');
	});
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
		$('#txtName').val(saveasfile+'.php');
		$('#Submit').click();
		return false;
	});
	// Show the pages used in block
	$('#showPages').click(function () {
		$('#pagesBlock').slideToggle();
		return false;
	});
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
</script>
<script src="js/gitFileCode.js"></script>
<script>initCMToolbar(myCode, '#cm-toolbar');</script>
</body></html>
