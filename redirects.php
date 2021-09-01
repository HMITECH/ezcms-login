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
	.table { table-layout: fixed; }
	textarea { height: auto; }
	td.title, td.keywords, td.description { text-transform: capitalize;	}
	</style>

</head><body>

<div id="wrap">
	<?php include('include/nav.php'); ?>
	<div class="container">
		<div class="white-boxed">
			<div class="navbar"><div class="navbar-inner">
				<form id="frmFilter" class="navbar-search pull-left">
				  <input type="text" class="search-query input-xxlarge" placeholder="Search">
				</form>
			</div></div>
			<form id="frmAddRedirect" class="form-inline">
			  <input id="srcuri" name="srcuri" type="text" class="input-xxlarge" placeholder="Target URI">
			  <input id="desuri" name="desuri" type="text" class="input-xxlarge" placeholder="Destination URI">
			  <button type="submit" class="btn btn-primary">Add New</button>
			</form>
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
		</div>
	</div>
	<br><br>
</div><!-- /wrap  -->
<a href="" target="_blank"></a>
<?php include('include/footer.php'); ?>
<script>

var ezRedirect = {

	init: function () {
		$('#frmAddRedirect').submit(function(e) {
			e.preventDefaults;
			$.post( 'redirects.php?addRedirect', $(this).serialize(), function(data) {
				if (data=='0') ezRedirect.loadData();
				else alert('Error: '+ data);
			}).fail( function() { 
				alert('Failed: The request failed.'); 
			});
			return false;
		});
		$('#resultsTable').on("click", ".delredirectLnk", function(e) {
			e.preventDefaults;
			if (confirm("Are sure you want to delete?") != true) return false;
			$.post( 'redirects.php?delRedirect', { id: $(this).data('id') }, function(data) {
				if (data=='0') ezRedirect.loadData();
				else alert('Error: '+ data);
			}).fail( function() { 
				alert('Failed: The request failed.'); 
			});	
			return false;
		});
		$('#resultsTable').on("click", ".addRedirect", function(e) {
			e.preventDefaults;
			var srcNew = $(this).closest('tr').find('td.srcurl').text();
			$('#srcuri').val(srcNew).focus();
			return false;
		});
		$('#resultsTable').on("click", ".togenabled", function() {
			var that = this;
			$(this).hide();
			$.post( 'redirects.php?togenabled', { id: $(this).data('id') }, function(data) {
				if (data!='0') alert('Error: '+ data);
				$(that).show();
			}).fail( function() { 
				alert('Failed: The request failed.'); 
				$(that).show();
				return false;
			});						
		});
		$('#resultsTable').on("click", ".del404Log", function(e) {
			e.preventDefaults;
			if (confirm("Are sure you want to purge?") != true) return false;
			var del404 = $(this).closest('tr').find('td.srcurl').text();
			$.post( 'redirects.php?del404log', { url: del404 }, function(data) {
				if (data=='0') ezRedirect.loadData();
				else alert('Error: '+ data);
			}).fail( function() { 
				alert('Failed: The request failed.'); 
			});	
			return false;
		});
		$('#frmFilter').submit(function(e) {
			e.preventDefaults;
			ezRedirect.applySearch();
			return false;
		});
		ezRedirect.loadData();
	},

	applySearch: function () {
		var str = $('#frmFilter input').val().trim();
		$('#resultsTable tbody tr').show();
		if (str.length < 2) return false; 
		$('#resultsTable tbody tr').each(function () {
			var thisSrc = $(this).find('td.srcurl').text();
			var thisDes = $(this).find('td.desurl').text();
			if ( (thisSrc.indexOf(str) === -1) && (thisDes.indexOf(str) === -1) )
				$(this).hide();
		});
	},

	loadData: function () {
        $.getJSON( 'redirects.php?getall', function(data) {
            if (!data.status) {
                alert('Error: '+ data.msg);
                return false;
            }
			var rowsMAP = {};
			$('#resultsTable tbody').empty();
			for(var k in data.rows) {
				var srcLink = '<a href="'+data.rows[k].srcurl+'" target="_blank">'+data.rows[k].srcurl+'</a>',
					desLink = '<a href="'+data.rows[k].desurl+'" target="_blank">'+data.rows[k].desurl+'</a>',
					isEnabled = '';
				if (data.rows[k].enabled == '1') isEnabled = 'checked';
				var row = 	'<td><input data-id="'+data.rows[k].id+'" class="togenabled" type="checkbox" '+isEnabled+' /></td>'+
							'<td class="srcurl">'+srcLink+'</td>'+
							'<td class="desurl">'+desLink+'</td>'+
							'<td>'+data.rows[k].actioncount+'</td>'+
							'<td class="cnt404">0</td>'+
							'<td><a href="#" data-id="'+data.rows[k].id+'" class="delredirectLnk">DELETE</a></td>';
				$('<tr></tr>').html(row).appendTo('#resultsTable tbody');
				rowsMAP[data.rows[k].srcurl] = k;
			}
			for(var k in data.r404) {
				if ( data.r404[k].url in rowsMAP)  {
					var redirectROW = rowsMAP[data.r404[k].url];
					$('#resultsTable tbody tr').eq(redirectROW).find('td.cnt404').text(data.r404[k].cnt404);
					continue;
				}
				var row = 	'<td></td>'+'<td class="srcurl">'+data.r404[k].url+'</td>'+
							'<td><a href="#" class="addRedirect btn btn-danger btn-mini">ADD REDIRECT</a></td>'+
							'<td></td>'+'<td>'+data.r404[k].cnt404+'</td>'+
							'<td><a href="#" class="del404Log">PURGE</a></td>';
				$('<tr></tr>').html(row).appendTo('#resultsTable tbody');
			}
        }).fail(function( jqXHR, textStatus ) {
          alert( "Request failed: " + textStatus );
        });
	}

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
