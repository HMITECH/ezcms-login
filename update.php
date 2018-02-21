<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * Version 2.010413 Dated 20/March/2013
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 * $Header: /cygdrive/c/cvs/repo/xampp/htdocs/hmi/ezsite/login/profile.php,v 1.2 2017-12-02 09:33:28 a Exp $
 * View: Displays the users in the site
 */
 // **************** ezCMS USERS CLASS ****************
require_once ("class/ezcms.class.php");

// **************** ezCMS USERS HANDLE ****************
$cms = new ezCMS();

// Execute the pull and return the results for ajax.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$r = new stdClass();
	if ( is_dir('.git') ) {
		$r->success = true;
		$r->msg = shell_exec('git pull');
	} else {
		$r->success = false;
		$r->msg = 'Update repo is missing. Clone https://github.com/HMITECH/ezcms-login';		
	}
	die(json_encode($r));
}


?><!DOCTYPE html><html lang="en"><head>

	<title>Update : ezCMS Admin</title>
	<?php include('include/head.php'); ?>
	<style>
		#output { background:#000; color:#00FF00; }
		#frmUpdate { position:relative; }
		#frmOverlay {
			display:none;
			position: absolute;
			height: 100%;
			width: 100%;
			top: 0;
			left: 0;
			background: #2c5d9066;
			z-index: 1;
			background-image: url(img/ajax-loader.gif);
			background-repeat: no-repeat;
			background-position: center;
		}
	</style>

</head><body>

<div id="wrap">
	<?php include('include/nav.php'); ?>
	<div class="container">
		<div class="container-fluid">
		  <div class="row-fluid">
			<div class="span2"></div>
			<div class="span8 white-boxed">
				<blockquote><p>Update ezCMS</p><small>You must have git installed for this.</small></blockquote>
				<form id="frmUpdate"><div id="frmOverlay"></div>
					<textarea id="output" disabled><?php echo '['.dirname(__FILE__).'] # git pull_'?></textarea>
					<input type="submit" name="Submit" class="btn btn-primary" value="Update ezCMS">
				</form>
			</div>
			<div class="span2"></div>
		  </div>
		</div>
	</div>
	<br><br>
</div><!-- /wrap  -->

<?php include('include/footer.php'); ?>
<script>
	var shtxt = $('#output').val();
	$("#frmUpdate").submit(function (e) {
	 	e.preventDefaults;
		$('#frmOverlay').slideDown();
		$.post( 'update.php', {execute:true}, function(data) {
				if (data.success) {
					$('#output').val(shtxt + '\n' + data.msg);
				} else alert('Error: '+ data.msg);
				$('#frmOverlay').slideUp();
		}, 'json').fail( function() { 
			$('#frmOverlay').slideUp( 300, function () {
				alert('Failed: The request failed.'); 
			});
		});
		return false;
	});
</script>
<script>
	 $("#top-bar li").removeClass('active');
</script>
</body></html>
