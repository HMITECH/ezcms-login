/*
* Version 2.0.0 Dated 23-Dec-2012
* HMI Technologies Mumbai (2012-13)
** Javascript ** 
*
* this file contains the common javascript for handling version diff
*
*/

var myCode = CodeMirror.fromTextArea(document.getElementById("txtContents"), {
	lineNumbers: true,
	matchBrackets: true,
	mode: cmMode,
	indentUnit: 4,
	indentWithTabs: true,
	theme: cmTheme,
	lineWrapping: true,
	extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
	foldGutter: true,
	gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
	viewportMargin: Infinity
});

// DIFF Viewer Options
var codeMain = myCode.getValue(),
	codeRight = $("#txtContents").val(), 
	codeLeft = codeRight,
	panes = 2, collapse = false, dv;
	
// function to build DIFF UI
var buildDiffUI = function () {
	var target = document.getElementById("diffviewer");
	target.innerHTML = "";
	dv = CodeMirror.MergeView(target, {
		value: codeMain,
		origLeft: panes == 3 ? codeLeft : null,
		orig: codeRight,
		lineNumbers: true,
		mode: cmMode,
		theme: cmTheme,
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
		codeMain = myCode.getValue(),
		buildDiffUI();
	});
	return false;
});

// Click on Fetch or DIFF in revision log
$('#revBlock a').click( function () {
	if ($(this).text() == 'Fetch') {
		var loadID = $(this).parent().data('rev-id');
		myCode.setValue(revJson[loadID]);
		return false;
	} else if ($(this).text() == 'Diff') {
		var loadID = $(this).parent().data('rev-id');
		$("#txtTemps").val(revJson[loadID]);
		codeRight= $("#txtTemps").val();
		$('#diffviewerControld td:last-child select').val(loadID);
		$('#showdiff').click();
		return false;
	}
});

// Change Rev in Diff Viewer select dropdown
$('#diffviewerControld select').change( function () {
	var revID2Load = $(this).val();
	if (revID2Load == '0') {
		var revContentLoad = $("#txtContents").val(); // shoe last saved 
	} else {
		$("#txtTemps").val(revJson[revID2Load]);
		var revContentLoad = $("#txtTemps").val();
	}
	if ($(this).parent().index() == 0) codeLeft = revContentLoad;
	else codeRight = revContentLoad;
	codeMain = dv.editor().getValue();
	buildDiffUI();
});	

// Back to Main editor from DIFF UI
$('#backEditBTN').click( function () {
	$('#editBlock').slideDown('slow');
	$('#diffBlock').slideUp('slow');
	myCode.setValue(dv.editor().getValue());
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
	codeMain = dv.editor().getValue();
	buildDiffUI();
	return false;
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
	codeMain = dv.editor().getValue();
	buildDiffUI();
	return false;
});

// ---------------------------------------------------------------------------
// initCMToolbar(editor, toolbar)
// Wires up a .cm-toolbar element to a CodeMirror instance.
// Safe to call multiple times on the same page for different editor instances.
// ---------------------------------------------------------------------------
function initCMToolbar(editor, toolbar) {
	var $tb = $(toolbar);
	var fontSizeKey = 'ezCMFontSize';

	function setFontSize(size) {
		$(editor.getWrapperElement()).css('font-size', size + 'px');
		editor.refresh();
		$tb.find('.cm-size-label').text(size + 'px');
		localStorage.setItem(fontSizeKey, size);
	}

	// Restore saved font size on load
	var saved = localStorage.getItem(fontSizeKey);
	if (saved) setFontSize(parseInt(saved, 10));

	$tb.find('.cm-fontsize-menu a').on('click', function () {
		setFontSize(parseInt($(this).data('size'), 10));
		return false;
	});

	$tb.find('.cm-btn-find').on('click',    function () { editor.execCommand('findPersistent'); return false; });
	$tb.find('.cm-btn-replace').on('click', function () { editor.execCommand('replace');         return false; });
	$tb.find('.cm-btn-goto').on('click',    function () { editor.execCommand('jumpToLine');       return false; });

	// Fold all blocks at brace-depth >= minDepth, deepest first.
	// Level 1 = 1 level visible (fold depth >= 1), Level 2 = 2 visible, etc.
	// Fold All = minDepth 0 (collapse everything).
	function foldToLevel(minDepth) {
		editor.operation(function () {
			for (var i = editor.firstLine(); i <= editor.lastLine(); i++)
				editor.foldCode({line: i, ch: 0}, null, "unfold");

			var maxD = 0, d = 0;
			for (var i = editor.firstLine(); i <= editor.lastLine(); i++) {
				var line = editor.getLine(i) || '';
				for (var c = 0; c < line.length; c++) {
					if      (line[c] === '{') { d++; if (d > maxD) maxD = d; }
					else if (line[c] === '}' && d > 0) d--;
				}
			}

			for (var target = maxD - 1; target >= minDepth; target--) {
				var depth = 0;
				for (var i = editor.firstLine(); i <= editor.lastLine(); i++) {
					var line = editor.getLine(i) || '';
					for (var c = 0; c < line.length; c++) {
						if (line[c] === '{') {
							if (depth === target)
								editor.foldCode({line: i, ch: c + 1}, null, "fold");
							depth++;
						} else if (line[c] === '}' && depth > 0) {
							depth--;
						}
					}
				}
			}
		});
	}

	$tb.find('.cm-fold-menu a').on('click', function () {
		var val = $(this).data('fold');
		if (val === 'none') {
			editor.operation(function () {
				for (var i = editor.firstLine(); i <= editor.lastLine(); i++)
					editor.foldCode({line: i, ch: 0}, null, "unfold");
			});
		} else {
			foldToLevel(parseInt(val, 10));
		}
		return false;
	});
}