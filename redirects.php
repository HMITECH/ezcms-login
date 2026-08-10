<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * View: Displays the UI for redirects
 *
 */

// **************** ezCMS USERS CLASS ****************
require_once ("class/redirect.class.php");

// **************** ezCMS USERS HANDLE ****************
$cms = new ezRedirect();

?><!DOCTYPE html><html lang="en"><head>

	<title>Redirects : ezCMS Admin</title>
	<?php include('include/head.php'); ?>
	<style>
	#resultsTable { table-layout: fixed; }
	#resultsTable td { word-break: break-word; }
	/* toolbar */
	#redirectToolbar { display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:12px; }
	#redirectToolbar .search-wrap { margin-left:auto; }
	#searchBox { width:300px; max-width:100%; }
	/* colored, collapsible "add redirect" box */
	.redirect-add-box {
		background:#eef6fc; border:1px solid #bcdff1; border-radius:6px;
		padding:14px 16px; margin-bottom:14px;
	}
	.redirect-add-box .help { margin:0 0 10px; color:#2c6a8f; font-size:13px; }
	.redirect-add-box .help code { background:#dcecf7; color:#1b4f6b; padding:0 4px; border-radius:3px; }
	.redirect-add-box form { display:flex; gap:8px; align-items:stretch; flex-wrap:wrap; }
	.redirect-add-box .form-control { flex:1 1 240px; }
	.redirect-add-box .btn { flex:0 0 auto; }
	/* pager */
	#redirectPager { display:flex; justify-content:space-between; align-items:center; margin-top:12px; flex-wrap:wrap; gap:8px; }
	#redirectPager .pagination { margin:0; }
	#redirectPager .rcount { color:#777; font-size:13px; }
	</style>

</head><body>

<div id="wrap">
	<?php include('include/nav.php'); ?>
	<div class="container">
		<div class="white-boxed">

			<div id="redirectToolbar">
				<a class="btn btn-success" data-bs-toggle="collapse" href="#addRedirectPanel" role="button" aria-expanded="false">
					<i class="icon-plus"></i> Add 404 Redirect</a>
				<div class="search-wrap">
					<input type="text" id="searchBox" class="form-control" placeholder="Search source or target URL ...">
				</div>
			</div>

			<div class="collapse" id="addRedirectPanel">
				<div class="redirect-add-box">
					<p class="help"><strong>Redirect a 404 (not-found) URL to a new page.</strong>
						When a visitor or search engine hits a removed/renamed path, send them (301) to the
						right place. Enter the old path (must start with <code>/</code>) and its destination.</p>
					<form id="frmAddRedirect">
						<input id="srcuri" name="srcuri" type="text" class="form-control" placeholder="Source URI — e.g. /old-page">
						<input id="desuri" name="desuri" type="text" class="form-control" placeholder="Destination URI — e.g. /new-page">
						<button type="submit" class="btn btn-primary">Add New</button>
					</form>
				</div>
			</div>

			<table id="resultsTable" class="table table-striped">
			<thead><tr>
				<th width="5%">ENABLED</th>
				<th width="35%">SOURCE URL</th>
				<th width="35%">TARGET URL</th>
				<th width="8%">301 COUNT</th>
				<th width="8%">404 COUNT</th>
				<th width="9%">ACTION</th>
			</tr></thead>
			<tbody><tr><td colspan="6">Loading ... please wait</td></tr></tbody></table>

			<div id="redirectPager"></div>
		</div>
	</div>
	<br><br>
</div><!-- /wrap  -->
<?php include('include/footer.php'); ?>
<script>

var ezRedirect = {

	perPage: 20,
	page: 1,
	all: [],        // combined redirect + 404-log rows
	filtered: [],   // current (searched) subset

	init: function () {
		$('#frmAddRedirect').submit(function(e) {
			e.preventDefault();
			$.post( 'redirects.php?addRedirect', $(this).serialize(), function(data) {
				if (data=='0') { $('#srcuri,#desuri').val(''); ezRedirect.loadData(); }
				else alert('Error: '+ data);
			}).fail( function() { alert('Failed: The request failed.'); });
			return false;
		});
		$('#resultsTable').on("click", ".delredirectLnk", function(e) {
			e.preventDefault();
			if (confirm("Are sure you want to delete?") != true) return false;
			$.post( 'redirects.php?delRedirect', { id: $(this).data('id') }, function(data) {
				if (data=='0') ezRedirect.loadData();
				else alert('Error: '+ data);
			}).fail( function() { alert('Failed: The request failed.'); });
			return false;
		});
		$('#resultsTable').on("click", ".addRedirect", function(e) {
			e.preventDefault();
			var srcNew = $(this).closest('tr').find('td.srcurl').text();
			// make sure the (collapsed) add form is visible, then prefill the source
			var el = document.getElementById('addRedirectPanel');
			bootstrap.Collapse.getOrCreateInstance(el).show();
			$('#srcuri').val(srcNew).focus();
			return false;
		});
		$('#resultsTable').on("click", ".togenabled", function() {
			var that = this;
			$(this).hide();
			$.post( 'redirects.php?togenabled', { id: $(this).data('id') }, function(data) {
				if (data!='0') alert('Error: '+ data);
				$(that).show();
			}).fail( function() { alert('Failed: The request failed.'); $(that).show(); return false; });
		});
		$('#resultsTable').on("click", ".del404Log", function(e) {
			e.preventDefault();
			if (confirm("Are sure you want to purge?") != true) return false;
			var del404 = $(this).closest('tr').find('td.srcurl').text();
			$.post( 'redirects.php?del404log', { url: del404 }, function(data) {
				if (data=='0') ezRedirect.loadData();
				else alert('Error: '+ data);
			}).fail( function() { alert('Failed: The request failed.'); });
			return false;
		});
		// live search across the whole dataset (not just the current page)
		$('#searchBox').on('keyup', function () { ezRedirect.applySearch(); });
		// pager clicks
		$('#redirectPager').on('click', 'a[data-page]', function (e) {
			e.preventDefault();
			var pg = parseInt($(this).data('page'), 10);
			if (pg >= 1 && pg <= ezRedirect.pageCount()) { ezRedirect.page = pg; ezRedirect.render(); }
		});
		ezRedirect.loadData();
	},

	applySearch: function () {
		var str = $('#searchBox').val().trim().toLowerCase();
		ezRedirect.filtered = (str.length < 1) ? ezRedirect.all :
			ezRedirect.all.filter(function (r) {
				return (r.src && r.src.toLowerCase().indexOf(str) !== -1) ||
				       (r.des && r.des.toLowerCase().indexOf(str) !== -1);
			});
		ezRedirect.page = 1;
		ezRedirect.render();
	},

	pageCount: function () {
		return Math.max(1, Math.ceil(ezRedirect.filtered.length / ezRedirect.perPage));
	},

	loadData: function () {
		$.getJSON( 'redirects.php?getall', function(data) {
			if (!data.status) { alert('Error: '+ data.msg); return false; }
			var rows = [], map = {};
			for (var k in data.rows) {
				var r = data.rows[k];
				rows.push({ type:'redirect', id:r.id, src:r.srcurl, des:r.desurl,
					enabled:(r.enabled=='1'), cnt301:r.actioncount, cnt404:0 });
				map[r.srcurl] = rows.length - 1;
			}
			for (var j in data.r404) {
				var l = data.r404[j];
				if (l.url in map) { rows[map[l.url]].cnt404 = l.cnt404; continue; }
				rows.push({ type:'404', src:l.url, cnt404:l.cnt404 });
			}
			ezRedirect.all = rows;
			ezRedirect.applySearch();   // seeds filtered + renders (respects any search text)
		}).fail(function( jqXHR, textStatus ) {
			alert( "Request failed: " + textStatus );
		});
	},

	render: function () {
		var per = ezRedirect.perPage,
			pg  = Math.min(ezRedirect.page, ezRedirect.pageCount()),
			list = ezRedirect.filtered,
			start = (pg - 1) * per,
			slice = list.slice(start, start + per),
			html = '';
		ezRedirect.page = pg;
		if (!slice.length) {
			html = '<tr><td colspan="6" class="text-center text-muted" style="padding:18px">No redirects or 404s found.</td></tr>';
		} else {
			slice.forEach(function (r) { html += ezRedirect.rowHtml(r); });
		}
		$('#resultsTable tbody').html(html);
		ezRedirect.renderPager(start, slice.length, list.length);
	},

	rowHtml: function (r) {
		if (r.type === 'redirect') {
			var s = '<a href="'+r.src+'" target="_blank">'+r.src+'</a>',
				d = '<a href="'+r.des+'" target="_blank">'+r.des+'</a>';
			return '<tr>'+
				'<td><input data-id="'+r.id+'" class="togenabled" type="checkbox" '+(r.enabled?'checked':'')+' /></td>'+
				'<td class="srcurl">'+s+'</td>'+
				'<td class="desurl">'+d+'</td>'+
				'<td>'+r.cnt301+'</td>'+
				'<td class="cnt404">'+r.cnt404+'</td>'+
				'<td><a href="#" data-id="'+r.id+'" class="delredirectLnk">DELETE</a></td></tr>';
		}
		return '<tr>'+
			'<td></td>'+
			'<td class="srcurl">'+r.src+'</td>'+
			'<td><a href="#" class="addRedirect btn btn-danger btn-mini">ADD REDIRECT</a></td>'+
			'<td></td>'+
			'<td>'+r.cnt404+'</td>'+
			'<td><a href="#" class="del404Log">PURGE</a></td></tr>';
	},

	renderPager: function (start, shown, total) {
		var pages = ezRedirect.pageCount(), pg = ezRedirect.page;
		var info = total ? ('Showing '+(start+1)+'–'+(start+shown)+' of '+total) : '';
		var h = '<span class="rcount">'+info+'</span>';
		if (pages > 1) {
			h += '<ul class="pagination pagination-sm">';
			h += ezRedirect.pgItem(pg-1, '«', pg===1);
			// windowed page numbers
			var win = 2, from = Math.max(1, pg-win), to = Math.min(pages, pg+win);
			if (from > 1) { h += ezRedirect.pgItem(1, '1', false, pg===1); if (from > 2) h += ezRedirect.pgGap(); }
			for (var i = from; i <= to; i++) h += ezRedirect.pgItem(i, String(i), false, i===pg);
			if (to < pages) { if (to < pages-1) h += ezRedirect.pgGap(); h += ezRedirect.pgItem(pages, String(pages), false, pg===pages); }
			h += ezRedirect.pgItem(pg+1, '»', pg===pages);
			h += '</ul>';
		}
		$('#redirectPager').html(h);
	},

	pgItem: function (page, label, disabled, active) {
		var cls = 'page-item' + (disabled ? ' disabled' : '') + (active ? ' active' : '');
		return '<li class="'+cls+'"><a class="page-link" href="#" data-page="'+page+'">'+label+'</a></li>';
	},
	pgGap: function () { return '<li class="page-item disabled"><span class="page-link">…</span></li>'; }

}
ezRedirect.init();
</script>
<script>
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(0)").addClass('active');
	$("#top-bar li:eq(0) ul li:eq(3)").addClass('active');
</script>
</body>
</html>
