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

// ---- Lazy revision loader (shared by every file editor). Fetches the count,
//      diff options and a page of the log after load; content on demand. ----
var revJson = {};   // per-revision content cache
var ezRevs = {
	page: 1, pages: 1, count: 0, per: 10,
	url: function (extra) { return location.pathname + (location.search ? location.search + '&' : '?') + extra; },
	load: function (page) {
		$('#revBody').css('opacity', .4);   // keep rows in place, just dim → smooth
		$.getJSON(ezRevs.url('ajaxRevs&page=' + (page || 1)), function (d) {
			if (!d || !d.status) { $('#revBody').css('opacity', 1); return; }
			ezRevs.page = d.page; ezRevs.pages = d.pages; ezRevs.count = d.count;
			$('#revcount').text(d.count);
			if (d.opts) {
				var o = '<option value="0">Current (Last Saved)</option>';
				d.opts.forEach(function (x) { o += '<option value="' + x.id + '">' + x.label + '</option>'; });
				$('.revSel').html(o);
			}
			var showQS = (location.search.match(/[?&](show=[^&]*)/) || [])[1];
			var html = '';
			if (!d.rows.length) html = '<tr><td colspan="5">There are no revisions.</td></tr>';
			d.rows.forEach(function (r) {
				var purge = '?purgeRev=' + r.id + (showQS ? '&' + showQS : '');
				html += '<tr><td>' + r.id + '</td><td>' + r.user + '</td><td>' + $('<i>').text(r.msg || '').html() +
					'</td><td>' + r.date + '</td><td data-rev-id="' + r.id + '">' +
					'<a href="#">Fetch</a> &nbsp;|&nbsp; <a href="#">Diff</a> &nbsp;|&nbsp; ' +
					'<a href="' + purge + '" class="conf-del">Purge</a></td></tr>';
			});
			$('#revBody').html(html).css('opacity', 1);
			ezRevs.pager(d.rows.length);
		}).fail(function () { $('#revBody').html('<tr><td colspan="5" class="text-danger">Failed to load revisions.</td></tr>').css('opacity', 1); });
	},
	pager: function (shown) {
		var p = ezRevs.page, n = ezRevs.pages, per = ezRevs.per, total = ezRevs.count;
		var start = total ? (p - 1) * per + 1 : 0, end = (p - 1) * per + shown, h = '';
		if (n > 1) {
			var item = function (pg, label, dis, act) {
				return '<li class="page-item' + (dis ? ' disabled' : '') + (act ? ' active' : '') +
					'"><a class="page-link" href="#" data-page="' + pg + '">' + label + '</a></li>';
			};
			h += '<ul class="pagination pagination-sm">' + item(p - 1, '«', p === 1);
			var from = Math.max(1, p - 2), to = Math.min(n, p + 2);
			if (from > 1) h += item(1, '1', false, false);
			if (from > 2) h += '<li class="page-item disabled"><span class="page-link">…</span></li>';
			for (var i = from; i <= to; i++) h += item(i, i, false, i === p);
			if (to < n - 1) h += '<li class="page-item disabled"><span class="page-link">…</span></li>';
			if (to < n) h += item(n, n, false, false);
			h += item(p + 1, '»', p === n) + '</ul>';
		}
		h += '<span class="rcount">' + (total ? ('Showing ' + start + '–' + end + ' of ' + total) : 'No revisions') + '</span>';
		$('#revPager').html(h);
	}
};
// fetch a revision's content (cached) then run the callback
function ensureRev(id, cb) {
	if (id in revJson) { cb(revJson[id]); return; }
	$.getJSON(ezRevs.url('ajaxRevContent&id=' + id), function (d) {
		if (!d || !d.status) { alert('Could not load revision ' + id); return; }
		revJson[id] = d.content;
		cb(revJson[id]);
	}).fail(function () { alert('Could not load revision ' + id); });
}
$(function () {
	ezRevs.load(1);
	$('#revPager').on('click', 'a[data-page]', function (e) {
		e.preventDefault();
		var pg = parseInt($(this).data('page'), 10);
		if (pg >= 1 && pg <= ezRevs.pages) ezRevs.load(pg);
	});
});
	
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

// Click on Fetch or DIFF in the revision log (rows are added dynamically, so
// delegate; the revision's content is fetched on demand via ensureRev)
$('#revBody').on('click', 'a', function () {
	var action = $(this).text(), loadID = $(this).parent().data('rev-id');
	if (action == 'Fetch') {
		ensureRev(loadID, function (content) { myCode.setValue(content); });
		return false;
	} else if (action == 'Diff') {
		ensureRev(loadID, function (content) {
			$("#txtTemps").val(content);
			codeRight = $("#txtTemps").val();
			$('#diffviewerControld td:last-child select').val(loadID);
			$('#showdiff').click();
		});
		return false;
	}
});

// Change Rev in Diff Viewer select dropdown
$('#diffviewerControld select').change( function () {
	var revID2Load = $(this).val(), sel = $(this);
	function apply(revContentLoad) {
		if (sel.parent().index() == 0) codeLeft = revContentLoad;
		else codeRight = revContentLoad;
		codeMain = dv.editor().getValue();
		buildDiffUI();
	}
	if (revID2Load == '0') apply($("#txtContents").val());  // last saved
	else ensureRev(revID2Load, function (content) { apply(content); });
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