<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Include: Displays the footer
 * 
 */
?><div class="clearfix"></div>
<div id="footer">
  <div class="container">
    <div class="row-fluid">
      <div class="span3"><a target="_blank" href="https://www.hmi-tech.net/">
      	&copy; HMI Technologies</a></div>
      <div class="span6">
  	    <a href="../sitemap.xml" target="_blank">SITEMAP</a></div>
      <div class="span3"> ezCMS Version:<strong>5.8</strong></div>
    </div>
  </div>
</div>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.treeview/jquery.treeview.js"></script>
<script src="js/pass-strength.js"></script>
<script>(function($) {
"use strict";
$('.tooltipme2').tooltip();
// Confirm Delete Action
$('.conf-del').click( function () {
	return confirm('Confirm Delete Action ?');
});
// expand srink edit block size
$('#toggleEditSize').click( function () {
	var btnIcon = $(this).find('i');
	if (btnIcon.hasClass('icon-chevron-left')) {
		btnIcon.removeClass('icon-chevron-left').addClass('icon-chevron-right');
		$('#editBlock > div').eq(0).hide()
		$('#editBlock > div').eq(1).removeClass('span9')
	} else {
		btnIcon.removeClass('icon-chevron-right').addClass('icon-chevron-left');
		$('#editBlock > div').eq(0).show()
		$('#editBlock > div').eq(1).addClass('span9')
	}
	return false;
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