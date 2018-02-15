<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * Version 2.010413 Dated 20/March/2013
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 * $Header: /cygdrive/c/cvs/repo/xampp/htdocs/hmi/ezsite/login/setting.php,v 1.2 2017-12-02 09:33:28 a Exp $
 * View: Displays the default setting of the site
*/

// **************** ezCMS SETTINGS CLASS ****************
require_once ("class/settings.class.php");

// **************** ezCMS SETTINGS HANDLE ****************
$cms = new ezSettings();

?><!DOCTYPE html><html lang="en"><head>

	<title>Default Settings : ezCMS Admin</title>
	<?php include('include/head.php'); ?>

</head><body>

<div id="wrap">
	<?php include('include/nav.php'); ?>
	<div class="container">

		<div id="diffBlock" class="white-boxed">
			<div class="navbar"><div class="navbar-inner">
				<a id="backEditBTN" href="#" class="btn btn-inverted btn-info">Back to Main Editor</a>
				<a id="waysDiffBTN" href="#" class="btn btn-inverted btn-warning">Three Way DIFF</a>
				<a id="collaspeBTN" href="#" class="btn btn-inverted btn-warning">Collaspe Unchanged</a>
			</div></div>
			<table id="diffviewerControld" width="100%" border="0">
			  <tr><td><select><option value="0">Current (Last Saved)</option><?php echo $cms->revs['opt']; ?></select>
				</td><td><select disabled><option selected>Your Current Edit</option></select>
				</td><td><select><option value="0">Current (Last Saved)</option><?php echo $cms->revs['opt']; ?></select>
			  </td></tr>
			</table>
			<div id="difBlockHeader"><div id="diffviewerHeader"></div></div>
			<div id="difBlockSide1"><div id="diffviewerSide1"></div></div>
			<div id="difBlockSide2"><div id="diffviewerSide2"></div></div>
			<div id="difBlockFooter"><div id="diffviewerFooter"></div></div>
		</div>

		<div id="editBlock" class="white-boxed" >
		  <form id="frmSettings" action="setting.php" method="post" enctype="multipart/form-data" class="form-horizontal">
			<div class="navbar">
				<div class="navbar-inner">
					<input type="submit" name="Submit" value="Save Changes" class="btn btn-primary">
					<?php if ($_SESSION['EDITORTYPE'] == 3) {?>
					<a id="showrevs" href="#" class="btn btn-secondary">Revisions <sup><?php echo $cms->revs['cnt']; ?></sup></a>
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
			<div class="tabbable tabs-top">
			<ul class="nav nav-tabs" id="myTab">
			  <li class="active"><a href="#d-header">Header</a></li>
			  <li><a href="#d-sidebar">Aside 1</a></li>
			  <li><a href="#d-siderbar">Aside 2</a></li>
			  <li><a href="#d-footers">Footer</a></li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane active" id="d-header">
					<textarea name="headercontent" id="txtHeader"><?php echo $cms->site['headercontent']; ?></textarea>
				</div>
				<div class="tab-pane" id="d-sidebar">
					<textarea name="sidecontent" id="txtSide"><?php echo $cms->site['sidecontent']; ?></textarea>
				</div>
				<div class="tab-pane" id="d-siderbar">
					<textarea name="sidercontent" id="txtrSide"><?php echo $cms->site['sidercontent']; ?></textarea>
				</div>
				<div class="tab-pane" id="d-footers">
					<textarea name="footercontent" id="txtFooter"><?php echo $cms->site['footercontent']; ?></textarea>
				</div>
			</div>
			</div>
		  </form>
		</div>
		<textarea name="txtTemps" id="txtTemps" class="input-block-level"></textarea>

	</div>
	<br><br>
</div><!-- /wrap  -->
<?php include('include/footer.php'); ?>
<script type="text/javascript">
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(0)").addClass('active');
	$("#top-bar li:eq(0) ul li:eq(0)").addClass('active');
	$('#myTab a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
		window.location.hash = $(this).attr('href').replace('#d-','');
	});
</script>
<?php if ($_SESSION['EDITORTYPE'] == 0) { ?>

	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script type="text/javascript">
	  CKEDITOR.replace( 'txtHeader', { uiColor : '#59ACFF' });
	  CKEDITOR.replace( 'txtrSide' , { uiColor : '#FFD5AA' });
	  CKEDITOR.replace( 'txtSide'  , { uiColor : '#FFAAAA' });
	  CKEDITOR.replace( 'txtFooter', { uiColor : '#CCCCCC' });
	</script>

<?php } else if ($_SESSION['EDITORTYPE'] == 1) { ?>

	<script language="javascript" type="text/javascript" src="js/edit_area/edit_area_full.js"></script>
	<script language="javascript" type="text/javascript">
	var txtHeader_loaded = false;
	var txtFooter_loaded = false;
	var txtSide_loaded = false;
	var txtSider_loaded = false;
	var getEditAreaJSON = function (strID) {
		return {
			id: strID,
			syntax: "html",
			allow_toggle: false,
			start_highlight: true,
			toolbar: "search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight"
		}
	}
	$('#myTab a').click(function (e) {
		e.preventDefault();
		if ((!txtFooter_loaded)&&($(this).attr('href')=='#d-footer')) {
			editAreaLoader.init(getEditAreaJSON("txtFooter"));
			txtFooter_loaded = true;
		}
		if ((!txtSider_loaded)&&($(this).attr('href')=='#d-siderbar')) {
			editAreaLoader.init(getEditAreaJSON("txtrSide"));
			txtSider_loaded = true;
		}
		if ((!txtSide_loaded)&&($(this).attr('href')=='#d-sidebar')) {
			editAreaLoader.init(getEditAreaJSON("txtSide"));
			txtSide_loaded = true;
		}
	});
	editAreaLoader.init(getEditAreaJSON("txtHeader"));
	txtHeader_loaded = true;
	</script>

<?php } else if ($_SESSION['EDITORTYPE'] == 3) { ?>

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
	<script language="javascript" type="text/javascript">

	var revJson = <?php echo json_encode($cms->revs['jsn']); ?>;

	var myCodeHeader, myCodeSide1, myCodeSide2, myCodeFooter;

	// DIFF Viewer Options
	var panes = 2, collapse = false,
		codeMainHeader, codeRightHeader, codeLeftHeader,
		codeMainSide1, codeRightSide1, codeLeftSide1,
		codeMainSide2, codeRightSide2, codeLeftSide2,
		codeMainFooter, codeRightFooter, codeLeftFooter,
		dvHeader, dvSide1, dvSide2, dvFooter;
	var codeMirrorJSON = {
		lineNumbers: true,
		matchBrackets: true,
		mode: "htmlmixed",
		indentUnit: 4,
		indentWithTabs: true,
		theme: '<?php echo $_SESSION["CMTHEME"]; ?>',
		lineWrapping: true,
		extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
		foldGutter: true,
		gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
	}

	// function to build DIFF UI
	var buildDiffUI = function () {
		var target;

		target = document.getElementById("diffviewerHeader");
		target.innerHTML = "";
		dvHeader = CodeMirror.MergeView(target, {
			value: codeMainHeader,
			origLeft: panes == 3 ? codeLeftHeader : null,
			orig: codeRightHeader,
			lineNumbers: true,
			mode: "htmlmixed",
			theme: '<?php echo $_SESSION["CMTHEME"]; ?>',
			extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
			foldGutter: true,
			gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
			highlightDifferences: true,
			connect: null,
			collapseIdentical: collapse
		});

		target = document.getElementById("diffviewerSide1");
		target.innerHTML = "";
		dvSide1 = CodeMirror.MergeView(target, {
			value: codeMainSide1,
			origLeft: panes == 3 ? codeLeftSide1 : null,
			orig: codeRightSide1,
			lineNumbers: true,
			mode: "htmlmixed",
			theme: '<?php echo $_SESSION["CMTHEME"]; ?>',
			extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
			foldGutter: true,
			gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
			highlightDifferences: true,
			connect: null,
			collapseIdentical: collapse
		});

		target = document.getElementById("diffviewerSide2");
		target.innerHTML = "";
		dvSide2 = CodeMirror.MergeView(target, {
			value: codeMainSide2,
			origLeft: panes == 3 ? codeLeftSide2 : null,
			orig: codeRightSide2,
			lineNumbers: true,
			mode: "htmlmixed",
			theme: '<?php echo $_SESSION["CMTHEME"]; ?>',
			extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
			foldGutter: true,
			gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
			highlightDifferences: true,
			connect: null,
			collapseIdentical: collapse
		});

		target = document.getElementById("diffviewerFooter");
		target.innerHTML = "";
		dvFooter = CodeMirror.MergeView(target, {
			value: codeMainFooter,
			origLeft: panes == 3 ? codeLeftFooter : null,
			orig: codeRightFooter,
			lineNumbers: true,
			mode: "htmlmixed",
			theme: '<?php echo $_SESSION["CMTHEME"]; ?>',
			extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
			foldGutter: true,
			gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
			highlightDifferences: true,
			connect: null,
			collapseIdentical: collapse
		});

	}

	// Change to DIff UI
	$('#showdiff').click( function () {
		$('#editBlock').slideUp('slow');
		$('#diffBlock').slideDown('slow', function () {

			codeMainHeader = myCodeHeader.getValue();
			if (!codeLeftHeader) codeLeftHeader = $('#txtHeader').val();
			if (!codeRightHeader) codeRightHeader = $('#txtHeader').val();

			codeMainSide1 = myCodeSide1.getValue();
			if (!codeLeftSide1) codeLeftSide1 = $('#txtSide').val();
			if (!codeRightSide1) codeRightSide1 = $('#txtSide').val();

			codeMainSide2 = myCodeSide2.getValue();
			if (!codeLeftSide2) codeLeftSide2 = $('#txtrSide').val();
			if (!codeRightSide2) codeRightSide2 = $('#txtrSide').val();

			codeMainFooter = myCodeFooter.getValue();
			if (!codeLeftFooter) codeLeftFooter = $('#txtFooter').val();
			if (!codeRightFooter) codeRightFooter = $('#txtFooter').val();

			buildDiffUI();
		});
		return false;
	});

	// Click on Fetch or DIFF in revision log
	$('#revBlock a').click( function () {

		var loadID = $(this).parent().data('rev-id');

		if ($(this).text() == 'Fetch') {

			myCodeHeader.setValue(revJson[loadID].header);
			myCodeSide1 .setValue(revJson[loadID].side1);
			myCodeSide2 .setValue(revJson[loadID].side2);
			myCodeFooter.setValue(revJson[loadID].footer);
			return false;

		} else if ($(this).text() == 'Diff') {

			$("#txtTemps").val(revJson[loadID]);
			codeRight= $("#txtTemps").val();
			$("#txtTemps").val(revJson[loadID].header);
			codeRightHeader = $("#txtTemps").val();
			$("#txtTemps").val(revJson[loadID].side1);
			codeRightSide1 = $("#txtTemps").val();
			$("#txtTemps").val(revJson[loadID].side2);
			codeRightSide2 = $("#txtTemps").val();
			$("#txtTemps").val(revJson[loadID].footer);
			codeRightFooter = $("#txtTemps").val();
			$('#diffviewerControld td:last-child select').val(loadID);
			$('#showdiff').click();
			return false;

		}

	});

	// Toggle Collapse Unchanged sections
	$("#collaspeBTN").click( function () {
		if (collapse) {
			collapse = false;
			$(this).text('Collapase Unchanged');
		} else {
			collapse = true;
			$(this).text('Expand Unchanged');
		}
		codeMainHeader = dvHeader.editor().getValue();
		codeMainSide1 = dvSide1.editor().getValue();
		codeMainSide2 = dvSide2.editor().getValue();
		codeMainFooter = dvFooter.editor().getValue();
		buildDiffUI();
		return false;
	});

	// Toggle 2 or 3 wya Diff
	$("#waysDiffBTN").click( function () {
		if (panes == 2) {
			panes = 3;
			$(this).text('Two Way (2)');
			$('#diffviewerControld td').width('33.33%');
			$('#diffviewerControld td:first-child').show();
		} else {
			panes = 2;
			$(this).text('Three Way (3)');
			$('#diffviewerControld td').width('50%');
			$('#diffviewerControld td:first-child').hide();
		}
		codeMainHeader = dvHeader.editor().getValue();
		codeMainSide1 = dvSide1.editor().getValue();
		codeMainSide2 = dvSide2.editor().getValue();
		codeMainFooter = dvFooter.editor().getValue();
		buildDiffUI();
		return false;
	});

	// Change Rev in Diff Viewer select dropdown
	$('#diffviewerControld select').change( function () {
		var revID2Load = $(this).val();
		var revHeaderLoad, revSide1Load, revSide2Load, revFooterLoad;

		if (revID2Load == '0') {
			revHeaderLoad = $("#txtHeader").val();
			revSide1Load = $("#txtSide1").val();
			revSide2Load  = $("#txtSide2").val();
			revFooterLoad = $("#txtFooter").val();
		} else {
			$("#txtTemps").val(revJson[revID2Load].header);
			revHeaderLoad = $("#txtTemps").val();
			$("#txtTemps").val(revJson[revID2Load].side1);
			revSide1Load = $("#txtTemps").val();
			$("#txtTemps").val(revJson[revID2Load].side2);
			revSide2Load = $("#txtTemps").val();
			$("#txtTemps").val(revJson[revID2Load].footer);
			revFooterLoad = $("#txtTemps").val();
		}
		if ($(this).parent().index() == 0) {
			dvHeader.left.orig.setValue(revHeaderLoad);
			dvSide1.left.orig.setValue(revSide1Load);
			dvSide2.left.orig.setValue(revSide2Load);
			dvFooter.left.orig.setValue(revFooterLoad);
			codeLeftHeader = revHeaderLoad;
			codeLeftSide1 = revSide1Load;
			codeLeftSide2 = revSide2Load;
			codeLeftFooter = revFooterLoad;
		} else {
			dvHeader.right.orig.setValue(revHeaderLoad);
			dvSide1.right.orig.setValue(revSide1Load);
			dvSide2.right.orig.setValue(revSide2Load);
			dvFooter.right.orig.setValue(revFooterLoad);
			codeRightHeader = revHeaderLoad;
			codeRightSide1 = revSide1Load;
			codeRightSide2 = revSide2Load;
			codeRightFooter = revFooterLoad;
		}
	});

	// Back to Main editor from DIFF UI
	$('#backEditBTN').click( function () {
		$('#editBlock').slideDown();
		$('#diffBlock').slideUp();
		myCodeHeader.setValue(dvHeader.editor().getValue());
		myCodeSide1 .setValue(dvSide1 .editor().getValue());
		myCodeSide2 .setValue(dvSide2 .editor().getValue());
		myCodeFooter.setValue(dvFooter.editor().getValue());
		return false;
	});

	$('#myTab a').click(function (e) {
		e.preventDefault();
		myCodeHeader.refresh();
		myCodeSide1.refresh();
		myCodeSide2.refresh();
		myCodeFooter.refresh();
	});
	$(window).load( function () {
		myCodeHeader = CodeMirror.fromTextArea(document.getElementById("txtHeader"), codeMirrorJSON);
		myCodeFooter = CodeMirror.fromTextArea(document.getElementById("txtFooter"), codeMirrorJSON);
		myCodeSide1 = CodeMirror.fromTextArea(document.getElementById("txtSide"), codeMirrorJSON);
		myCodeSide2 = CodeMirror.fromTextArea(document.getElementById("txtrSide"), codeMirrorJSON);
	});

	</script>

<?php } ?>
<script language="javascript" type="text/javascript">
	if(window.location.hash) $('a[href="'+window.location.hash.replace('#','#d-')+'"]').click();
</script>
</body></html>
