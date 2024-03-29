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
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
	<div class="container-fluid">
	  <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> 
		<span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> 
	  </button>
	  <a class="brand" href="../"><small>ezCMS</small></a>
	  <div class="nav-collapse collapse">
		<ul class="nav" id="top-bar">
		  <li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-th-large"></i> Template <b class="caret"></b></a>
			  <ul class="dropdown-menu">
				<li><a href="setting.php"><i class="icon-th-list"></i> Defaults Blocks</a></li>
				<li class="divider"></li>
				<li><a href="controllers.php"><i class="icon-play"></i> URL Router</a></li>
				<li><a href="redirects.php"><i class="icon-retweet"></i> 404 Redirects</a></li>
				<li class="divider"></li>
				<li><a href="layouts.php"><i class="icon-list-alt"></i> PHP Layouts</a></li>
				<li><a href="includes.php"><i class="icon-share-alt"></i> PHP Includes</a></li>
				<li class="divider"></li>
				<li><a href="styles.php"><i class="icon-pencil"></i> CSS Stylesheets</a></li>
				<li><a href="scripts.php"><i class="icon-align-left"></i> JS Javascripts</a></li>
				<li class="divider"></li>
				<li><a href="files.php"><i class="icon-folder-open"></i> File Manager</a></li>
			  </ul>
		  </li>
		  <li><a href="pages.php"><i class="icon-file"></i> Pages</a></li>
		  <li><a href="find.php"><i class="icon-search"></i> Find</a></li>
		  <li><a href="users.php"><i class="icon-user"></i> Users</a></li>
		  <li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i> Macros <b class="caret"></b></a>
			  <ul class="dropdown-menu">
				<li><a href="macros.php"><i class="icon-pencil"></i> Manage</a></li>
				<li class="divider"></li>
				<li><a href="macro.php"><i class="icon-play"></i> Execute</a></li>
			  </ul>
		  </li>
		  <li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-edit"></i> CMS <b class="caret"></b></a>
			  <div id="divbgcolor" class="dropdown-menu">
				<blockquote>
				  <p><i class="icon-tint"></i> Background Color</p>
				</blockquote>
				<div><input id="txtbgcolor" type="color"></div>
				<hr>
				<blockquote>
				  <p><i class="icon-edit"></i> ezCMS Theme</p>
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
		
		<ul class="nav pull-right">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-share"></i> Welcome
					<?php echo $cms->usr['username']; ?> <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="update.php"><i class="icon-download"></i> Update ezCMS</a></li>
					<li class="divider"></li>
					<li><a href="profile.php"><i class="icon-comment"></i> Change Password</a></li>
					<li class="divider"></li>
					<li><a href="scripts/logout.php"><i class="icon-off"></i> Logout</a></li>
				</ul>
			</li>
		</ul>
		
	  </div>
	</div>
  </div>
</div>
