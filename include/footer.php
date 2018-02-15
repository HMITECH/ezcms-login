<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Include: Displays the footer
 * 
 */
// Fetch the site stats
$stats = $cms->query('SELECT COUNT(DISTINCT `url`) as `ispublished` from `pages` where `published`=1')->fetch(PDO::FETCH_ASSOC);
?>
<div class="clearfix"></div>
<div id="footer">
  <div class="container">
    <div class="row-fluid" style=" ">
      <div class="span3"><a target="_blank" href="http://www.hmi-tech.net/">&copy; HMI Technologies</a> 
	  </div>
      <div class="span6"> 
  	    <a href="../sitemap.xml" target="_blank"><strong><?php echo $stats['ispublished']; ?></strong> published page(s)</a>		  
	  </div>
      <div class="span3"> ezCMS Version:<strong>5.0</strong> </div>
    </div>
  </div>
</div>

<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.treeview/jquery.treeview.js"></script>
<script src="js/pass-strength.js"></script>
<script type="text/javascript">(function($) {

"use strict";

$('.tooltipme2').tooltip();

// Confirm Delete Action
$('.conf-del').click( function () {
	return confirm('Confirm Delete Action ?');
});

// Open the treeview to selected item
var tSelc = $('#left-tree a.label-info').closest('li');
while ( tSelc.length ) {
	tSelc.addClass('open');
	tSelc = tSelc.parent().closest('li');
}
// Create treeview out of Left side UL 
$("#left-tree").treeview({
	collapsed: true,
	animated: "medium",
	unique: true
});

// Show or  the revisions block
$('#showrevs').click(function () {
	$('#revBlock').slideToggle();
	return false;
});

// Stop propagation of drop down events
$('#SaveAsDDM').click(function (e) {
	e.stopPropagation();
});	

// Change code mirror theme
$('#divCmTheme, #divbgcolor').click(function (e) {
	e.stopPropagation();
});

// Code Mirror Theme Change
$('#slCmTheme')
	.val('<?php if (isset($_SESSION["CMTHEME"])) echo $_SESSION["CMTHEME"]; ?>')
	.change(function (e) {
		location.href = "?theme="+$(this).val();
});

// CMS Background color
$('#txtbgcolor')
	.val(localStorage.getItem("cmsBgColor"))
	.change(function () {
		$('body').css('background-color', $(this).val());
		localStorage.setItem("cmsBgColor", $(this).val());
		$.get( '', {cmsBgColor: $(this).val()});	
});
if ( localStorage.getItem("cmsBgColor") )
	$('body').css('background-color',localStorage.getItem("cmsBgColor"));
else {
	// fetch bg color from ajax
	$.get( '?getCMScolor', function (data) {
		localStorage.setItem("cmsBgColor", data);
		$('body').css('background-color',localStorage.getItem("cmsBgColor"));
	});
}
})(jQuery);</script>