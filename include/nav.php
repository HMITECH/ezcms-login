<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Include: Displays the navigation bar (Bootstrap 5)
 *
 */
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
	<a class="navbar-brand" href="../"><small>ezCMS</small></a>
	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav"
		aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="topNav">
	  <ul class="navbar-nav me-auto" id="top-bar">
		<li class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false"><i class="icon-th-large"></i> Template</a>
			<ul class="dropdown-menu">
				<li><a class="dropdown-item" href="setting.php"><i class="icon-th-list"></i> Defaults Blocks</a></li>
				<li><hr class="dropdown-divider"></li>
				<li><a class="dropdown-item" href="controllers.php"><i class="icon-play"></i> URL Router</a></li>
				<li><a class="dropdown-item" href="redirects.php"><i class="icon-retweet"></i> 404 Redirects</a></li>
				<li><hr class="dropdown-divider"></li>
				<li><a class="dropdown-item" href="layouts.php"><i class="icon-list-alt"></i> PHP Layouts</a></li>
				<li><a class="dropdown-item" href="includes.php"><i class="icon-share-alt"></i> PHP Includes</a></li>
				<li><hr class="dropdown-divider"></li>
				<li><a class="dropdown-item" href="styles.php"><i class="icon-pencil"></i> CSS Stylesheets</a></li>
				<li><a class="dropdown-item" href="scripts.php"><i class="icon-align-left"></i> JS Javascripts</a></li>
				<li><hr class="dropdown-divider"></li>
				<li><a class="dropdown-item" href="files.php"><i class="icon-folder-open"></i> File Manager</a></li>
			</ul>
		</li>
		<li class="nav-item"><a class="nav-link" href="pages.php"><i class="icon-file"></i> Pages</a></li>
		<li class="nav-item"><a class="nav-link" href="find.php"><i class="icon-search"></i> Find</a></li>
		<li class="nav-item"><a class="nav-link" href="users.php"><i class="icon-user"></i> Users</a></li>
		<li class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false"><i class="icon-cog"></i> Macros</a>
			<ul class="dropdown-menu">
				<li><a class="dropdown-item" href="macros.php"><i class="icon-pencil"></i> Manage</a></li>
				<li><hr class="dropdown-divider"></li>
				<li><a class="dropdown-item" href="macro.php"><i class="icon-play"></i> Execute</a></li>
			</ul>
		</li>
		<li class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false"><i class="icon-edit"></i> CMS</a>
			<div id="divbgcolor" class="dropdown-menu p-3" style="min-width:220px;">
				<blockquote class="mb-2">
				  <p class="mb-1"><i class="icon-tint"></i> Background Color</p>
				</blockquote>
				<div><input id="txtbgcolor" type="color"></div>
				<hr>
				<blockquote class="mb-2">
				  <p class="mb-1"><i class="icon-edit"></i> ezCMS Theme</p>
				</blockquote>
				<div>
				  <select id="slCmTheme" class="form-select form-select-sm">
					<option selected>default</option><option>3024-day</option><option>3024-night</option>
					<option>abcdef</option><option>base16-dark</option><option>base16-light</option>
					<option>bespin</option><option>blackboard</option><option>cobalt</option>
					<option>colorforth</option><option>dracula</option><option>eclipse</option>
					<option>elegant</option><option>erlang-dark</option><option>hopscotch</option>
					<option>icecoder</option><option>isotope</option><option>lesser-dark</option>
					<option>liquibyte</option><option>material</option><option>mbo</option>
					<option>mdn-like</option><option>midnight</option><option>monokai</option>
					<option>neat</option><option>neo</option><option>night</option>
					<option>paraiso-dark</option><option>paraiso-light</option><option>pastel-on-dark</option>
					<option>railscasts</option><option>rubyblue</option><option>seti</option>
					<option>solarized dark</option><option>solarized light</option><option>the-matrix</option>
					<option>tomorrow-night-bright</option><option>tomorrow-night-eighties</option><option>ttcn</option>
					<option>twilight</option><option>vibrant-ink</option><option>xq-dark</option>
					<option>xq-light</option><option>yeti</option><option>zenburn</option>
				  </select>
				</div>
			</div>
		</li>
	  </ul>

	  <ul class="navbar-nav ms-auto">
		<li class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false"><i class="icon-share"></i> Welcome
				<?php echo $cms->usr['username']; ?></a>
			<ul class="dropdown-menu dropdown-menu-end">
				<li><a class="dropdown-item" href="update.php"><i class="icon-download"></i> Update ezCMS</a></li>
				<li><hr class="dropdown-divider"></li>
				<li><a class="dropdown-item" href="profile.php"><i class="icon-comment"></i> Change Password</a></li>
				<li><hr class="dropdown-divider"></li>
				<li><a class="dropdown-item" href="scripts/logout.php"><i class="icon-off"></i> Logout</a></li>
			</ul>
		</li>
	  </ul>
	</div>
  </div>
</nav>
