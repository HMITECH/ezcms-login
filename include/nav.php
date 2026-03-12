<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Include: Displays the navigation bar
 *
 */
?>
<nav class="navbar navbar-dark bg-dark fixed-top navbar-expand-md">
  <div class="container-fluid">
	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
	  <span class="navbar-toggler-icon"></span>
	</button>
	<a class="navbar-brand" href="../"><small>ezCMS</small></a>
	<div class="collapse navbar-collapse" id="navbarMain">
	  <ul class="navbar-nav" id="top-bar">
		<li class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-grid"></i> Template</a>
			<ul class="dropdown-menu">
			  <li><a class="dropdown-item" href="setting.php"><i class="bi bi-list-ul"></i> Defaults Blocks</a></li>
			  <li><hr class="dropdown-divider"></li>
			  <li><a class="dropdown-item" href="controllers.php"><i class="bi bi-play-fill"></i> URL Router</a></li>
			  <li><a class="dropdown-item" href="redirects.php"><i class="bi bi-arrow-repeat"></i> 404 Redirects</a></li>
			  <li><hr class="dropdown-divider"></li>
			  <li><a class="dropdown-item" href="layouts.php"><i class="bi bi-file-text"></i> PHP Layouts</a></li>
			  <li><a class="dropdown-item" href="includes.php"><i class="bi bi-share"></i> PHP Includes</a></li>
			  <li><hr class="dropdown-divider"></li>
			  <li><a class="dropdown-item" href="styles.php"><i class="bi bi-pencil"></i> CSS Stylesheets</a></li>
			  <li><a class="dropdown-item" href="scripts.php"><i class="bi bi-file-code"></i> JS Javascripts</a></li>
			  <li><hr class="dropdown-divider"></li>
			  <li><a class="dropdown-item" href="files.php"><i class="bi bi-folder2-open"></i> File Manager</a></li>
			</ul>
		</li>
		<li class="nav-item"><a class="nav-link" href="pages.php"><i class="bi bi-file-earmark"></i> Pages</a></li>
		<li class="nav-item"><a class="nav-link" href="find.php"><i class="bi bi-search"></i> Find</a></li>
		<li class="nav-item"><a class="nav-link" href="users.php"><i class="bi bi-person"></i> Users</a></li>
		<li class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-gear"></i> Macros</a>
			<ul class="dropdown-menu">
			  <li><a class="dropdown-item" href="macros.php"><i class="bi bi-pencil"></i> Manage</a></li>
			  <li><hr class="dropdown-divider"></li>
			  <li><a class="dropdown-item" href="macro.php"><i class="bi bi-play-fill"></i> Execute</a></li>
			</ul>
		</li>
		<li class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-sliders"></i> CMS</a>
			<div id="divbgcolor" class="dropdown-menu" style="padding:10px; min-width:220px;">
			  <blockquote>
				<p><i class="bi bi-palette"></i> Background Color</p>
			  </blockquote>
			  <div><input id="txtbgcolor" type="color"></div>
			  <hr>
			  <blockquote>
				<p><i class="bi bi-sliders"></i> ezCMS Theme</p>
			  </blockquote>
			  <div>
				<select id="slCmTheme">
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
			<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-door-open"></i> Welcome
				<?php echo $cms->usr['username']; ?></a>
			<ul class="dropdown-menu dropdown-menu-end">
			  <li><a class="dropdown-item" href="update.php"><i class="bi bi-download"></i> Update ezCMS</a></li>
			  <li><hr class="dropdown-divider"></li>
			  <li><a class="dropdown-item" href="profile.php"><i class="bi bi-key"></i> Change Password</a></li>
			  <li><hr class="dropdown-divider"></li>
			  <li><a class="dropdown-item" href="scripts/logout.php"><i class="bi bi-power"></i> Logout</a></li>
			</ul>
		</li>
	  </ul>

	</div>
  </div>
</nav>
