<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * View: Displays the UI for find and replace
 * 
 */

// **************** ezCMS USERS CLASS ****************
require_once ("class/find.class.php");

// **************** ezCMS USERS HANDLE ****************
$cms = new ezFind();

?><!DOCTYPE html><html lang="en"><head>

	<title>Find Replace : ezCMS Admin</title>
	<?php include('include/head.php'); ?>
	<style>
	textarea { height: auto; }
	#frmreplace { display:none }
	.row-fluid > .span9 {min-height: 240px;}
	.icon-ok { background-color: green; }
	.icon-remove { background-color: red; }
	.replaceOnelnk { color: red; }
	td.title, td.keywords, td.description { text-transform: capitalize;	}
	</style>

</head><body>

<div id="wrap">
	<?php include('include/nav.php'); ?>
	<div class="container">
	  <div class="row-fluid">
		<div class="span3">
		
		  <div class="white-boxed"><form id="frmfind"  method="post" action="#">
			<div class="navbar"><div class="navbar-inner">
				<input type="submit" name="find" class="btn btn-primary pull-left" value="Find All">
				<ul class="nav pull-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-flag"></i>
							WHERE <b class="caret"></b></a>
						<ul id="findinDD" class="dropdown-menu">
							<li class="active"><a data-loc="page" href="#"><i class="icon-file"></i> Pages</a></li>
							<li class="divider"></li>
							<li><a data-loc="php" href="#"><i class="icon-list-alt"></i> PHP Layouts</a></li>
							<li><a data-loc="css" href="#"><i class="icon-pencil"></i> CSS Stylesheets</a></li>
							<li><a data-loc="js" href="#"><i class="icon-align-left"></i> JS Javascripts</a></li>
							<li><a data-loc="inc" href="#"><i class="icon-share-alt"></i> PHP Includes</a></li>
						</ul>
					</li>
				</ul>
			</div></div>

			<div class="control-group">
				<label class="control-label">FIND TEXT</label>
				<div class="controls">
					<textarea name="find" id="txtfind" rows="5"
						placeholder="Enter the text or code to find"
						class="input-block-level" required minlength="3"></textarea></div>
				<div class="control-group">
					<label class="control-label">REPLACE WITH</label>
					<div class="controls">
						<textarea name="replace" id="txtreplace" rows="5"
							placeholder="Enter the text or code to replace"
							class="input-block-level"></textarea></div>
				</div>
			</div>
			<input type="hidden" name="findinTxt" id="findinTxt" />
		  </form></div><br>
			
		</div>
		<div class="span9 white-boxed">
			<div class="navbar"><div class="navbar-inner">
				<a href="#" id="repall" class="btn btn-danger pull-left hide">Replace All</a>
				<a class="brand" onclick="return false" href=""><small id="findinlbl"></small></a>
				<ul class="nav pull-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-filter"></i>
							FILTER <b class="caret"></b></a>
						<ul id="filterDD" class="dropdown-menu">
							<li><a href="#"><i id="ckpub" class="icon-ok"></i> Not Published</a></li>
							<li><a href="#"><i id="cktitle" class="icon-ok"></i> Title Tag</a></li>
							<li><a href="#"><i id="ckcontent" class="icon-ok"></i> Content</a></li>
							<li><a href="#"><i id="ckheader" class="icon-ok"></i> Header</a></li>
							<li><a href="#"><i id="ckfooter" class="icon-ok"></i> Footer</a></li>
							<li><a href="#"><i id="ckaside1" class="icon-ok"></i> Aside #1</a></li>
							<li><a href="#"><i id="ckaside2" class="icon-ok"></i> Aside #2</a></li>
							<li><a href="#"><i id="ckhead" class="icon-ok"></i> Page Head</a></li>
							<li><a href="#"><i id="ckdesc" class="icon-ok"></i> Description</a></li>
							<li><a href="#"><i id="ckkeyw" class="icon-ok"></i> Keywords</a></li>
						</ul>
					</li>
				</ul>
			</div></div>
			<table id="resultsTable" class="table table-striped"><thead>
			<tr><th>Search Results</th></tr></thead><tbody>
			<tr><td>No Results</td></tr></tbody></table>
		</div>
	  </div>
	</div>
	<br><br>
</div><!-- /wrap  -->

<?php include('include/footer.php'); ?>
<script>
var applyFilters = function () {
	$('#resultsTable tr').show();
	if ($('#cktitle').hasClass('icon-remove')) 
		$('#resultsTable td.title').closest('tr').hide();
	if ($('#ckcontent').hasClass('icon-remove')) 
		$('#resultsTable td.maincontent').closest('tr').hide();
	if ($('#ckheader').hasClass('icon-remove')) 
		$('#resultsTable td.headercontent').closest('tr').hide();
	if ($('#ckfooter').hasClass('icon-remove')) 
		$('#resultsTable td.footercontent').closest('tr').hide();
	if ($('#ckaside1').hasClass('icon-remove')) 
		$('#resultsTable td.sidecontent').closest('tr').hide();
	if ($('#ckaside2').hasClass('icon-remove')) 
		$('#resultsTable td.sidercontent').closest('tr').hide();
	if ($('#ckhead').hasClass('icon-remove')) 
		$('#resultsTable td.head ').closest('tr').hide();
	if ($('#ckdesc').hasClass('icon-remove')) 
		$('#resultsTable td.description').closest('tr').hide();
	if ($('#ckkeyw').hasClass('icon-remove')) 
		$('#resultsTable td.keywords').closest('tr').hide();
	if ($('#ckpub').hasClass('icon-remove')) 
		$('#resultsTable tbody i.icon-remove').closest('tr').hide();
}

$('#resultsTable').on("click", ".replaceOnelnk", function() {

	// ajax to the server
	var param, that = this;
	
	if ($('#findinTxt').val() == 'page') 
		params =  '&id='+$(this).data('id')+'&block='+$(this).data('block');
	else params =  '&file='+$(this).data('lnk');
	
	$.post( 'find.php?replaceone'+params, $('#frmfind').serialize(), 
		function(data) {
			if (data.success) {
				$(that).after('<label class="label label-success">REPLACED</label>');
				$(that).closest('tr').css('text-decoration','line-through');
				$(that).remove();
			} else alert('Error: '+ data.msg);
	}, 'json').fail( function() { 
		alert('Failed: The request failed.'); 
	});	
	
	return false;
});
$('#repall').click(function (e) {
	e.preventDefaults;
	
	$.post( 'find.php?replaceall', $('#frmfind').serialize(), 
		function(data) {
			if (data.success) {
				
			} else alert('Error: '+ data.msg);
	}, 'json').fail( function() { 
		alert('Failed: The request failed.'); 
	});	
	
	return false;
});
$('#findinDD a').click(function (e) {
	e.preventDefaults;
	$('#findinlbl').html('WHERE : ' + $(this).html());
	$('#findinTxt').val($(this).data('loc'));
	$('#findinDD li').removeClass('active');
	$(this).parent().addClass('active');
	if ($(this).data('loc') == 'page')
		$('#filterDD').parent().parent().show();
	else 
		$('#filterDD').parent().parent().hide();
	//return false;
}).eq(0).click();
$('#filterDD a').click(function (e) {
	e.preventDefaults;
	var thisIcon = $(this).find('i');
	if ( $(thisIcon).hasClass('icon-ok') )
		$(thisIcon).removeClass('icon-ok').addClass('icon-remove');
	else 
		$(thisIcon).removeClass('icon-remove').addClass('icon-ok');
	applyFilters();
	return false;
});

$('#frmfind').submit(function (e) {
	
	e.preventDefaults;
	
	// ajax to the server
	$.post( 'find.php?fetchall', $( this ).serialize(), 
		function(data) {
			if (data.success) {
				var row, lnk, blockCap, pagehash, findinTxt = $('#findinTxt').val();
				var headerRow = '<th>NAME</th><th>PUBLISHED</th><th>BLOCK</th><th>ACTION</th>';
				if (findinTxt != 'page') headerRow = '<th>NAME</th><th>ACTION</th>';

				$('#resultsTable tbody').empty();
				$('#resultsTable thead tr').empty().html(headerRow);
			
				for(var k in data.results) {
					row = '<td>'+data.results[k].name+'</td>';
					if (findinTxt != 'page') {
						if ($('#findinTxt').val()=='php') {
							lnk = 'layouts.php?show='+data.results[k].name;
							if (data.results[k].name == 'layout.php' ) lnk = 'layouts.php';
						} else if ($('#findinTxt').val()=='css') {
							lnk = 'styles.php?show='+data.results[k].name;
							if (data.results[k].inroot == 1 ) lnk = 'styles.php';
						} else if ($('#findinTxt').val()=='js') {
							lnk = 'scripts.php?show='+data.results[k].name;
							if (data.results[k].inroot == 1 ) lnk = 'scripts.php';
						} else if ($('#findinTxt').val()=='inc') {
							lnk = 'includes.php?show='+data.results[k].name;
						}
						row += '<td><a target="_blank" href="'+lnk+'">EDIT</a> '+
								'<a href="#" class="replaceOnelnk" data-lnk="'+data.results[k].name+'">| REPLACE</a></td>';		
					} else {
						if (data.results[k].published == '1') 
							row += '<td><i class="icon-ok icon-white"></i></td>';
						else 
							row += '<td><i class="icon-remove icon-white"></i></td>';
						blockCap = data.results[k].block;
						pagehash = '';
						if (blockCap == 'maincontent' ) {
							pagehash = '#content';
							blockCap = 'Content';
						} else if (blockCap == 'headercontent' ) {
							pagehash = '#header';
							blockCap = 'Header';
						} else if (blockCap == 'footercontent' ) {
							pagehash = '#footers';
							blockCap = 'Footer';
						} else if (blockCap == 'head' ) {
							pagehash = '#head';
							blockCap = 'Page Head';
						} else if (blockCap == 'sidecontent' ) {
							pagehash = '#sidebar';
							blockCap = 'Aside #1';
						} else if (blockCap == 'sidercontent') {
							pagehash = '#siderbar';
							blockCap = 'Aside #2';
						}
						row += '<td class="'+data.results[k].block+'">'+blockCap+'</td>';
						row += '<td><a target="_blank" href="'+data.results[k].url+'">VIEW</a> | '+
								'<a target="_blank" href="pages.php?id='+data.results[k].id+pagehash+'">EDIT</a>' +
								' | <a href="#" data-id="'+data.results[k].id+'" data-block="'+data.results[k].block+
								'" class="replaceOnelnk">REPLACE</a></td>';
					}
					$('<tr></tr>').html(row).appendTo('#resultsTable tbody');
				}

				if ($('#resultsTable tbody').html() == '') 
					$('#resultsTable tbody').html('<tr><td colspan="4">Nothing found</td></tr>');
				applyFilters();
				
			} else alert('Error: '+ data.msg);
	}, 'json').fail( function() { 
		alert('Failed: The request failed.'); 
	});
	
	return false;
	
});
</script>
<script>
	$("#top-bar li").removeClass('active');
	$("#top-bar > li:eq(2)").addClass('active');
</script>
</body>
</html>
