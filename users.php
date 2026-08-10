<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * View: Displays the users in the site
 * 
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
			<?php echo $cms->csrfField(); ?>
				<div class="navbar"><div class="navbar-inner"><?php echo $cms->barBtns; ?></div></div>

				<?php echo $cms->msg; ?>

				<div id="revBlock">
				  <table class="table table-striped"><thead>
					<tr><th>#</th><th>Revised</th><th>Resource</th><th>Revision Message</th><th>Date &amp; Time</th></tr>
				  </thead><tbody id="revBody"><tr><td colspan="5" class="text-muted">Loading revisions …</td></tr></tbody></table>
				  <div id="revPager"></div>
				</div>

				<div class="row">
					<div class="span4">
						<label for="inputName">User Name</label>
						<input type="text" name="username" id="username" data-bs-toggle="tooltip"
							placeholder="Enter the full name" autocomplete="off"
							title="Enter the full name of the user here."
							value="<?php echo $cms->thisUser['username']; ?>"
							data-bs-placement="top" minlength="2" class="input-block-level tooltipme2" required>
					</div>
					<div class="span4">
						<label for="inputEmail">Email Address</label>
						<input type="email" name="email" id="email" data-bs-toggle="tooltip"
							placeholder="Enter the full email address"
							title="Enter the full email address of the user here."
							value="<?php echo $cms->thisUser['email']; ?>"  autocomplete="off"
							data-bs-placement="top" class="input-block-level tooltipme2" required>
					</div>
					<div class="span4">
						<label for="txtpsswd">Password</label>
						<input type="password" name="passwd" id="passwd" data-bs-toggle="tooltip"
							placeholder="<?php echo ($cms->id=='new') ? 'Enter the password' : 'Leave blank to keep unchanged' ?>"
							title="<?php echo ($cms->id=='new') ? 'Enter the password here' : 'Enter a new password or leave blank to keep unchanged' ?>"
							data-bs-placement="top" minlength="8"  autocomplete="off"
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
							Manage Layouts &amp; Macros</label><?php echo $cms->thisUser['editlayoutMsg']; ?><br><br>
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
<script>
	$("#top-bar li").removeClass('active');
	$("#top-bar > li:eq(3)").addClass('active');

	// ---- Lazy revision loader (read-only activity log): same pager as the
	//      other pages, but no Fetch/Diff — rows arrive pre-built. ----
	var ezRevs = {
		page:1, pages:1, count:0, per:10,
		url: function (extra) { return location.pathname + (location.search ? location.search + '&' : '?') + extra; },
		load: function (page) {
			$('#revBody').css('opacity', .4);
			$.getJSON(ezRevs.url('ajaxRevs&page=' + (page || 1)), function (d) {
				if (!d || !d.status) { $('#revBody').css('opacity', 1); return; }
				ezRevs.page = d.page; ezRevs.pages = d.pages; ezRevs.count = d.count;
				$('#revcount').text(d.count);
				var html = '';
				if (!d.rows.length) html = '<tr><td colspan="5">There are no revisions.</td></tr>';
				d.rows.forEach(function (cells) { html += '<tr>' + cells + '</tr>'; });
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
	$(function () {
		ezRevs.load(1);
		$('#revPager').on('click', 'a[data-page]', function (e) {
			e.preventDefault();
			var pg = parseInt($(this).data('page'), 10);
			if (pg >= 1 && pg <= ezRevs.pages) ezRevs.load(pg);
		});
	});
</script>
</body>
</html>
