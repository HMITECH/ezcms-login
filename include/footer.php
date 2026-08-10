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
  	    <a href="../sitemap.xml" target="_blank">Sitemap</a></div>
      <div class="span3"> ezCMS Version:<strong>7</strong></div>
    </div>
  </div>
</div>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/bs-typeahead-compat.js"></script>
<script src="js/jquery.treeview/jquery.treeview.js"></script>
<script src="js/pass-strength.js"></script>
<script>(function($) {
"use strict";
// Bootstrap 5 tooltips (opt-in, no jQuery plugin)
document.querySelectorAll('.tooltipme2').forEach(function (el) { new bootstrap.Tooltip(el); });
// Confirm Delete Action
$('.conf-del').click( function () {
	return confirm('Confirm Delete Action ?');
});
// expand/shrink the edit block — animated collapse, remembered across reloads
(function () {
	var KEY = 'ezcmsSidebarCollapsed';
	var $eb = $('#editBlock');
	function setState(collapsed, animate) {
		if (!$eb.length) return;
		if (!animate) $eb.addClass('no-anim');           // apply instantly on load
		$eb.toggleClass('sidebar-collapsed', collapsed);
		$('#toggleEditSize i')
			.toggleClass('icon-chevron-right', collapsed)
			.toggleClass('icon-chevron-left', !collapsed);
		if (!animate) { void $eb[0].offsetHeight; $eb.removeClass('no-anim'); }
	}
	// restore the saved state on load, without animating
	setState(localStorage.getItem(KEY) === '1', false);
	$('#toggleEditSize').click( function () {
		var collapsed = !$eb.hasClass('sidebar-collapsed');
		setState(collapsed, true);
		localStorage.setItem(KEY, collapsed ? '1' : '0');
		// let CodeMirror re-measure once the pane finishes resizing
		setTimeout(function () { window.dispatchEvent(new Event('resize')); }, 300);
		return false;
	});
})();
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
var _csrfToken = '<?php echo $_SESSION["CSRF_TOKEN"] ?? ""; ?>';
$.ajaxSetup({
	beforeSend: function(xhr, settings) {
		if (settings.type !== 'POST') return;
		var tok = 'csrf_token=' + encodeURIComponent(_csrfToken);
		if (typeof settings.data === 'string') {
			if (settings.data.indexOf('csrf_token') === -1)
				settings.data += (settings.data ? '&' : '') + tok;
		} else {
			if (!settings.data) settings.data = {};
			if (!settings.data.csrf_token) settings.data.csrf_token = _csrfToken;
		}
	}
});
})(jQuery);</script>