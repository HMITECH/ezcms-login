<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * Version 2.010413 Dated 20/March/2013
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 * $Header: /cygdrive/c/cvs/repo/xampp/htdocs/hmi/ezsite/login/index.php,v 1.2 2017-12-02 09:33:28 a Exp $
 * View: Login page to ezCMS (index.php)
 */

// **************** ezCMS CLASS ****************
require_once ("class/ezcms.class.php"); // CMS Class for database access
$cms = new ezCMS(false); // create new instance of CMS Class with loginRequired = false

// Redirect the user if already logged in
if ($_SESSION['LOGGEDIN'] == true) {
	header("Location: pages.php");
	exit;
}

// Check if userid is set in the request
$userid = ""; // Reset Login
if (isset($_GET["userid"])) $userid = $_GET["userid"];

// If userid is not set check session for it.
if ( ($userid == '') && (isset($_SESSION['userid'])) ) $userid = $_SESSION['userid'];

// Set the HTML to display for this flag
switch ($cms->flg) {
	case "failed":
		$cms->setMsgHTML('error','Login Failed !','Incorrect email or password');
		break;
	case "expired":
		$cms->setMsgHTML('warning','Session Expired !','Your session has expired');
		break;
	case "logout":
		$cms->setMsgHTML('success','Logged Out !','You have successfully logged out');
		break;
	case "inactive":
		$cms->setMsgHTML('info','Account Inactive !','Your status is NOT Active');
		break;
}
?><!DOCTYPE html><html lang="en"><head>

	<title>Login : ezCMS Admin</title>
	<?php include('include/head.php'); ?>

</head><body>

<div id="wrap">
	<div class="navbar navbar-inverse navbar-fixed-top"><div class="navbar-inner">
	  <a class="brand" href="/">ezCMS : <?php echo $_SERVER['HTTP_HOST']; ?></a>
	  <div class="pull-right" style="color: #FFFFFF;margin: 10px 10px 2px 2px;">Your IP <strong>
		<?php echo $_SERVER['REMOTE_ADDR']; ?></strong> is Logged</div>
	  <div class="clearfix"></div>
	</div></div>
	<div class="container">
		<form id="frm-login" class="form-signin" method="post" action="scripts/login.php">
			<h3 class="form-signin-heading"><img src="../site-assets/hmi-logo.png" ><br>Please sign in</h3>
			<?php echo $cms->msg; ?>
			<input type="text" id="txtemail" name="userid" data-toggle="tooltip"
				class="input-block-level tooltipme2" data-placement="top"
				title="Email address"
				value="<?php echo $userid; ?>"
				placeholder="Email address">
			<input type="password" id="txtpass" name="passwd" data-toggle="tooltip"
				class="input-block-level tooltipme2" data-placement="top"
				title="Enter your password here." placeholder="Password">
			<button class="btn btn-large btn-inverse" type="submit">Sign in</button>
			<p class="pull-right">
				<a id="lnk-restpass" href="#" class="tooltipme2"
					data-toggle="tooltip" data-placement="top" style="display:none;"
					title="Password Lost, recover your password here.">Lost your password?</a><br>
				<a href="../" class="tooltipme2" data-toggle="tooltip" data-placement="top"
					title="Are you lost? Go back to the main site.">&lt;&lt; Back to Site</a>
			</p>
			<p class="clearfix"></p>
		</form>
	</div>
</div>
<?php include('include/footer.php'); ?>

</body></html>
