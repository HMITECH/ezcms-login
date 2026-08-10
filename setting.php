<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * View: Displays the default setting of the site
 * 
 */

// **************** ezCMS SETTINGS CLASS ****************
require_once ("class/settings.class.php");

// **************** ezCMS SETTINGS HANDLE ****************
$cms = new ezSettings();

?><!DOCTYPE html><html lang="en"><head>

	<title>Default Settings : ezCMS Admin</title>
	<?php include('include/head.php'); ?>
	<style>
	/* Block tabs (#myTab) blend into the CodeMirror editor below. The editor's
	   colours vary by theme, so --cm-bg / --cm-fg are read from the live CM in JS. */
	.tabbable { --cm-bg:#ffffff; --cm-fg:#333333; }
	.tabbable > .tab-content { padding-top:0; }             /* no gap under the tabs */
	#myTab.nav-tabs { border-bottom:0; margin-bottom:0; gap:3px; }
	#myTab .nav-link {
		border:1px solid var(--cm-bg);
		border-radius:5px 5px 0 0;
		color:#555; background:transparent;
		padding:6px 16px;
	}
	#myTab .nav-link:not(.active):hover { background:rgba(128,128,128,.14); color:#111; border-color:var(--cm-bg); }
	#myTab .nav-link.active {
		background:var(--cm-bg); color:var(--cm-fg);
		border-color:var(--cm-bg);
	}
	</style>

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
			  <tr><td><select id="revSelL"><option value="0">Current (Last Saved)</option></select>
				</td><td><select disabled><option selected>Your Current Edit</option></select>
				</td><td><select id="revSelR"><option value="0">Current (Last Saved)</option></select>
			  </td></tr>
			</table>
			<div id="difBlockHeader"><div id="diffviewerHeader"></div></div>
			<div id="difBlockSide1"><div id="diffviewerSide1"></div></div>
			<div id="difBlockSide2"><div id="diffviewerSide2"></div></div>
			<div id="difBlockFooter"><div id="diffviewerFooter"></div></div>
		</div>

		<div id="editBlock" class="white-boxed" >
		  <form id="frmSettings" action="setting.php" method="post" enctype="multipart/form-data" class="form-horizontal">
		<?php echo $cms->csrfField(); ?>
			<div class="navbar">
				<div class="navbar-inner">
					<input type="submit" name="Submit" value="Save Changes" class="btn btn-primary">
					<a id="showrevs" href="#" class="btn btn-secondary">Revisions <sup id="revcount">…</sup></a>
					<a id="showdiff" href="#" class="btn btn-inverted btn-danger">Review DIFF</a>
				</div>
			</div>
			<?php echo $cms->msg; ?>
			<div id="revBlock">
			  <table class="table table-striped"><thead>
				<tr><th>#</th><th>User Name</th><th>Message</th><th>Date &amp; Time</th><th>Action</th></tr>
			  </thead><tbody id="revBody"><tr><td colspan="5" class="text-muted">Loading revisions …</td></tr></tbody></table>
			</div>
			<div class="control-group">
				<label class="control-label" for="txtGitMsg">Revision Message</label>
				<div class="controls">
					<input type="text" id="txtGitMsg" name="revmsg"
						placeholder="Enter a description for this revision"
						title="Enter a message to describe this revision."
						data-bs-toggle="tooltip"
						value=""
						data-bs-placement="top" minlength="2"
						class="input-block-level tooltipme2">
				</div>
			</div>
			<div class="tabbable tabs-top">
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#d-header">Header</a></li>
			  <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#d-sidebar">Aside 1</a></li>
			  <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#d-siderbar">Aside 2</a></li>
			  <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#d-footers">Footer</a></li>
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
<script>
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(0)").addClass('active');
	$("#top-bar li:eq(0) ul li:eq(0)").addClass('active');
	// Bootstrap 5 shows the tab natively via data-bs-toggle="tab"; just track the hash.
	$('#myTab a').on('shown.bs.tab', function (e) {
		window.location.hash = $(this).attr('href').replace('#d-','');
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
<script src="codemirror/mode/css/css.js"></script>
<script src="codemirror/mode/clike/clike.js"></script>
<script>

var revJson = {};   // per-revision content cache, filled on demand via AJAX

// ---- Lazy revision loader: after page load, fetch the total count, the diff
//      options and the most recent revisions; content is fetched on demand. ----
var ezRevs = {
	load: function () {
		$('#revBody').html('<tr><td colspan="5" class="text-muted">Loading …</td></tr>');
		$.getJSON('setting.php?ajaxRevs&page=1', function (d) {
			if (!d || !d.status) return;
			$('#revcount').text(d.count);
			if (d.opts) {   // populate the diff dropdowns (all revisions)
				var o = '<option value="0">Current (Last Saved)</option>';
				d.opts.forEach(function (x) { o += '<option value="' + x.id + '">' + x.label + '</option>'; });
				$('#revSelL, #revSelR').html(o);
			}
			var html = '';
			if (!d.rows.length) html = '<tr><td colspan="5">There are no revisions.</td></tr>';
			d.rows.forEach(function (r) {
				html += '<tr><td>' + r.id + '</td><td>' + r.user + '</td><td>' + $('<i>').text(r.msg || '').html() +
					'</td><td>' + r.date + '</td><td data-rev-id="' + r.id + '">' +
					'<a href="#">Fetch</a> &nbsp;|&nbsp; <a href="#">Diff</a> &nbsp;|&nbsp; ' +
					'<a href="?purgeRev=' + r.id + '" class="conf-del">Purge</a></td></tr>';
			});
			$('#revBody').html(html);
		}).fail(function () { $('#revBody').html('<tr><td colspan="5" class="text-danger">Failed to load revisions.</td></tr>'); });
	}
};
// fetch a revision's content (cached) then run the callback
function ensureRev(id, cb) {
	if (revJson[id]) { cb(revJson[id]); return; }
	$.getJSON('setting.php?ajaxRevContent&id=' + id, function (d) {
		if (!d || !d.status) { alert('Could not load revision ' + id); return; }
		revJson[id] = { header: d.header, side1: d.side1, side2: d.side2, footer: d.footer };
		cb(revJson[id]);
	}).fail(function () { alert('Could not load revision ' + id); });
}
$(function () { ezRevs.load(); });

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

// Click on Fetch or DIFF in the revision log (rows are added dynamically, so
// delegate; the revision's content is fetched on demand via ensureRev)
$('#revBody').on('click', 'a', function () {

	var action = $(this).text(), loadID = $(this).parent().data('rev-id');

	if (action == 'Fetch') {
		ensureRev(loadID, function (r) {
			myCodeHeader.setValue(r.header);
			myCodeSide1 .setValue(r.side1);
			myCodeSide2 .setValue(r.side2);
			myCodeFooter.setValue(r.footer);
		});
		return false;

	} else if (action == 'Diff') {
		ensureRev(loadID, function (r) {
			$("#txtTemps").val(r.header); codeRightHeader = $("#txtTemps").val();
			$("#txtTemps").val(r.side1);  codeRightSide1  = $("#txtTemps").val();
			$("#txtTemps").val(r.side2);  codeRightSide2  = $("#txtTemps").val();
			$("#txtTemps").val(r.footer); codeRightFooter = $("#txtTemps").val();
			$('#diffviewerControld td:last-child select').val(loadID);
			$('#showdiff').click();
		});
		return false;
	}
	// "Purge" is a normal link (?purgeRev=…) guarded by .conf-del — let it proceed
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
	var revID2Load = $(this).val(), side = $(this).parent().index();

	function apply(revHeaderLoad, revSide1Load, revSide2Load, revFooterLoad) {
		if (side == 0) {
			dvHeader.left.orig.setValue(revHeaderLoad);
			dvSide1.left.orig.setValue(revSide1Load);
			dvSide2.left.orig.setValue(revSide2Load);
			dvFooter.left.orig.setValue(revFooterLoad);
			codeLeftHeader = revHeaderLoad; codeLeftSide1 = revSide1Load;
			codeLeftSide2 = revSide2Load;   codeLeftFooter = revFooterLoad;
		} else {
			dvHeader.right.orig.setValue(revHeaderLoad);
			dvSide1.right.orig.setValue(revSide1Load);
			dvSide2.right.orig.setValue(revSide2Load);
			dvFooter.right.orig.setValue(revFooterLoad);
			codeRightHeader = revHeaderLoad; codeRightSide1 = revSide1Load;
			codeRightSide2 = revSide2Load;   codeRightFooter = revFooterLoad;
		}
	}

	if (revID2Load == '0') {
		apply($("#txtHeader").val(), $("#txtSide1").val(), $("#txtSide2").val(), $("#txtFooter").val());
	} else {
		ensureRev(revID2Load, function (r) {
			$("#txtTemps").val(r.header); var h = $("#txtTemps").val();
			$("#txtTemps").val(r.side1);  var s1 = $("#txtTemps").val();
			$("#txtTemps").val(r.side2);  var s2 = $("#txtTemps").val();
			$("#txtTemps").val(r.footer); var f = $("#txtTemps").val();
			apply(h, s1, s2, f);
		});
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
$(window).on('load', function () {
	myCodeHeader = CodeMirror.fromTextArea(document.getElementById("txtHeader"), codeMirrorJSON);
	myCodeFooter = CodeMirror.fromTextArea(document.getElementById("txtFooter"), codeMirrorJSON);
	myCodeSide1 = CodeMirror.fromTextArea(document.getElementById("txtSide"), codeMirrorJSON);
	myCodeSide2 = CodeMirror.fromTextArea(document.getElementById("txtrSide"), codeMirrorJSON);
	// blend the block tabs into the editor by borrowing the active CM theme colours
	var cm = document.querySelector('.tab-pane.active .CodeMirror') || document.querySelector('.tabbable .CodeMirror');
	if (cm) {
		var cs = getComputedStyle(cm), tb = document.querySelector('.tabbable');
		tb.style.setProperty('--cm-bg', cs.backgroundColor);
		tb.style.setProperty('--cm-fg', cs.color);
	}
});

</script>
<script>
	if(window.location.hash) $('a[href="'+window.location.hash.replace('#','#d-')+'"]').click();
</script>
</body></html>
