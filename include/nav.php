<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 * * Version 2.010413 Dated 20/March/2013 
 * Rev: 04-Octr-2016 (4.161005) * HMI Technologies Mumbai (2016-17)
 *
 *Include: Displays the navigation bar
 * 
 */ 
?>
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
	<div class="container-fluid">
	  <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> 
		<span class="icon-bar"></span> 
		<span class="icon-bar"></span> 
		<span class="icon-bar"></span> 
	  </button>
	  <a class="brand" href="../"><small>ezCMS: <?php echo $_SERVER['HTTP_HOST']; ?></small></a>
	  <div class="nav-collapse collapse">
		<ul class="nav" id="top-bar">
		  <li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-th-large"></i> Template <b class="caret"></b></a>
			  <ul class="dropdown-menu">
				<li><a href="setting.php"><i class="icon-th-list"></i> Defaults Settings</a></li>
				<li><a href="controllers.php"><i class="icon-play"></i> URL Router</a></li>
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
		  
<!-- 		  <li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-th-large"></i> Shop <b class="caret"></b></a>
			  <ul class="dropdown-menu">
				<li><a href="setting.php"><i class="icon-th-list"></i> Defaults Settings</a></li>
				<li><a href="controllers.php"><i class="icon-play"></i> URL Router</a></li>
				<li class="divider"></li>
				<li><a href="layouts.php"><i class="icon-list-alt"></i> PHP Layouts</a></li>
			  </ul>
		  </li>	 -->
		  
		  <li><a href="find.php"><i class="icon-search"></i> Find</a></li>
		  <li><a href="users.php"><i class="icon-user"></i> Users</a></li>
		  <li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-edit"></i> CMS <b class="caret"></b></a>
			  <div id="divbgcolor" class="dropdown-menu" style="padding:10px;">
<!-- 			  	<p><a href=""><i class="icon-share-alt"></i> Upgrade</a></p>
			  	<p><a href=""><i class="icon-share-alt"></i> Users</a></p>
			  	<p><a href=""><i class="icon-share-alt"></i> Search</a></p>			 -->  
				<blockquote>
				  <p><i class="icon-tint"></i> Background Color</p>
				  <small>Change background color</small>
				</blockquote>
				<div><input id="txtbgcolor" type="color"></div>
				<hr>
				<?php if ($_SESSION['EDITORTYPE'] == 3) { ?>
				<blockquote>
				  <p><i class="icon-edit"></i> Code Mirror Theme</p>
				  <small>Change Code Mirror Theme</small>
				</blockquote>
				<div>
				  <select id="slCmTheme">
					<option selected>default</option>
					<option>3024-day</option>
					<option>3024-night</option>
					<option>abcdef</option>
					<option>ambiance</option>
					<option>base16-dark</option>
					<option>base16-light</option>
					<option>bespin</option>
					<option>blackboard</option>
					<option>cobalt</option>
					<option>colorforth</option>
					<option>dracula</option>
					<option>eclipse</option>
					<option>elegant</option>
					<option>erlang-dark</option>
					<option>hopscotch</option>
					<option>icecoder</option>
					<option>isotope</option>
					<option>lesser-dark</option>
					<option>liquibyte</option>
					<option>material</option>
					<option>mbo</option>
					<option>mdn-like</option>
					<option>midnight</option>
					<option>monokai</option>
					<option>neat</option>
					<option>neo</option>
					<option>night</option>
					<option>paraiso-dark</option>
					<option>paraiso-light</option>
					<option>pastel-on-dark</option>
					<option>railscasts</option>
					<option>rubyblue</option>
					<option>seti</option>
					<option>solarized dark</option>
					<option>solarized light</option>
					<option>the-matrix</option>
					<option>tomorrow-night-bright</option>
					<option>tomorrow-night-eighties</option>
					<option>ttcn</option>
					<option>twilight</option>
					<option>vibrant-ink</option>
					<option>xq-dark</option>
					<option>xq-light</option>
					<option>yeti</option>
					<option>zenburn</option>
				  </select>
				</div>
			  <?php } ?>
			  </div>
		  </li>
		</ul>
		
		<ul class="nav pull-right">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-share"></i>
					Welcome <?php echo $cms->usr['username']; ?> <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="update.php"><i class="icon-download"></i> Update ezCMS</a></li>
					<li class="divider"></li>
					<li><a href="profile.php"><i class="icon-comment"></i> Change Password</a></li>
					<li class="divider"></li>
					<li class="nav-header">Select Editor</li>
 					<li><a href="?etype=3"><i class="icon-edit"></i> Code Mirror</a></li> 
					<li><a href="?etype=0"><i class="icon-calendar"></i> CK Editor</a></li>
					<li><a href="?etype=1"><i class="icon-folder-close"></i> Edit Area</a></li>
					<li><a href="?etype=2"><i class="icon-hdd"></i> Text Area</a></li>
					<li class="divider"></li>
					<li><a href="scripts/logout.php"><i class="icon-off"></i> Logout</a></li>
				</ul>
			</li>
		</ul>
		
	  </div>
	</div>
  </div>
</div>
