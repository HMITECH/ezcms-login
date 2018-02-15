<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * Version 2.010413 Dated 20/March/2013
 * Rev: 04-Oct-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 * $Header: /cygdrive/c/cvs/repo/xampp/htdocs/hmi/ezsite/login/users.php,v 1.2 2017-12-02 09:33:28 a Exp $
 * View: Displays the users in the site
 */

// **************** ezCMS USERS CLASS ****************
require_once ("class/users.class.php");

// **************** ezCMS USERS HANDLE ****************
$cms = new ezUsers();

?><!DOCTYPE html><html lang="en"><head>

	<title>Users : ezCMS Admin</title>
	<?php include('include/head.php'); ?>

</head><body>

<div id="wrap">
	<?php include('include/nav.php'); ?>
	<div class="container">
	  <div class="row-fluid">
		<div class="span3 white-boxed"><?php echo $cms->treehtml; ?></div>
		<div class="span9 white-boxed">
			<form id="frmUser" action="" method="post" enctype="multipart/form-data" class="form-horizontal" autocomplete="off">
				<div class="navbar"><div class="navbar-inner">
					<?php echo $cms->barBtns; ?>
					<a id="showrevs" href="#" class="btn btn-secondary">Revisions <sup><?php echo $cms->revs['cnt']; ?></sup></a>
				</div></div>

				<?php echo $cms->msg; ?>

				<div id="revBlock">
				  <table class="table table-striped"><thead>
					<tr><th>#</th><th>Revised</th><th>Resource</th><th>Revision Message</th><th>Date &amp; Time</th></tr>
				  </thead><tbody><?php echo $cms->revs['log']; ?></tbody></table>
				</div>

				<div class="row">
					<div class="span4">
						<label for="inputName">User Name</label>
						<input type="text" name="username" id="username" data-toggle="tooltip"
							placeholder="Enter the full name" autocomplete="off"
							title="Enter the full name of the user here."
							value="<?php echo $cms->thisUser['username']; ?>"
							data-placement="top" minlength="2" class="input-block-level tooltipme2" required>
					</div>
					<div class="span4">
						<label for="inputEmail">Email Address</label>
						<input type="email" name="email" id="email" data-toggle="tooltip"
							placeholder="Enter the full email address"
							title="Enter the full email address of the user here."
							value="<?php echo $cms->thisUser['email']; ?>"  autocomplete="off"
							data-placement="top" class="input-block-level tooltipme2" required>
					</div>
					<div class="span4">
						<label for="txtpsswd">Password</label>
						<input type="password" name="passwd" id="passwd" data-toggle="tooltip"
							placeholder="<?php echo ($cms->id=='new') ? 'Enter the password' : 'Leave blank to keep unchanged' ?>"
							title="<?php echo ($cms->id=='new') ? 'Enter the password here' : 'Enter a new password or leave blank to keep unchanged' ?>"
							data-placement="top" minlength="8"  autocomplete="off"
							class="input-block-level tooltipme2" <?php  if ($cms->id=='new') echo 'required'; ?>>
					</div>
				</div>

				<h4 class="well">USER PERMISSIONS</h4>

				<div class="row">
					<div class="span4">
						<label class="checkbox">
							<input name="active" type="checkbox" value="checkbox"
								<?php echo $cms->thisUser['activeCheck']; ?>>
							Active</label><?php echo $cms->thisUser['activeMsg']; ?><hr>
						<label class="checkbox">
							<input name="editpage" type="checkbox" value="checkbox"
								<?php echo $cms->thisUser['editpageCheck']; ?>>
							Manage Pages</label><?php echo $cms->thisUser['editpageMsg']; ?><br><br>
						<label class="checkbox">
							<input name="delpage" type="checkbox" value="checkbox"
								<?php echo $cms->thisUser['delpageCheck']; ?>>
							Delete Pages</label><?php echo $cms->thisUser['delpageMsg']; ?><hr>
					</div>
					<div class="span4">
						<label class="checkbox">
							<input name="edituser" type="checkbox" value="checkbox"
								<?php echo $cms->thisUser['edituserCheck']; ?>>
							Manage Users</label><?php echo $cms->thisUser['edituserMsg']; ?><br><br>
						<label class="checkbox">
							<input name="deluser" type="checkbox" value="checkbox"
								<?php echo $cms->thisUser['deluserCheck']; ?>>
							Delete Users</label><?php echo $cms->thisUser['deluserMsg']; ?><hr>
						<label class="checkbox">
							<input name="editsettings" type="checkbox" value="checkbox"
								<?php echo $cms->thisUser['editsettingsCheck']; ?>>
							Manage Defaults</label><?php echo $cms->thisUser['editsettingsMsg']; ?><br><br>
						<label class="checkbox">
							<input name="editcont" type="checkbox" value="checkbox"
								<?php echo $cms->thisUser['editcontCheck']; ?>>
							Manage Router</label><?php echo $cms->thisUser['editcontMsg']; ?><hr>
					</div>
					<div class="span4">
						<label class="checkbox">
							<input name="editlayout" type="checkbox" value="checkbox"
								<?php echo $cms->thisUser['editlayoutCheck']; ?>>
							Manage Layouts</label><?php echo $cms->thisUser['editlayoutMsg']; ?><br><br>
						<label class="checkbox">
							<input name="editcss" type="checkbox" id="ckeditcss" value="checkbox"
								<?php echo $cms->thisUser['editcssCheck']; ?>>
							Manage Styles</label><?php echo $cms->thisUser['editcssMsg']; ?><br><br>
						<label class="checkbox">
							<input name="editjs" type="checkbox" id="ckeditjs" value="checkbox"
								<?php echo $cms->thisUser['editjsCheck']; ?>>
							Manage Javascripts</label><?php echo $cms->thisUser['editjsMsg']; ?><hr>
					</div>
					<?php echo $cms->createdText; ?>
				</div><!-- / row -->
			</form>
		</div>
	  </div>
	</div>
	<br><br>
</div><!-- /wrap  -->

<?php include('include/footer.php'); ?>
<script type="text/javascript">
	$("#top-bar li").removeClass('active');
	$("#top-bar li:eq(11)").addClass('active');
</script>
</body>
</html>
