<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * View: Displays the UI for Executing Macros
 * 
 */

// **************** ezCMS USERS CLASS ****************
require_once ("class/macro.class.php");

// **************** ezCMS USERS HANDLE ****************
$cms = new ezMacro();

?><!DOCTYPE html><html lang="en"><head>

	<title>Execute Macro : ezCMS Admin</title>
	<?php include('include/head.php'); ?>

</head><body>
<div id="wrap">
	<?php include('include/nav.php'); ?>
	<div class="container">
	  <div id="editBlock" class="row-fluid">
		<div class="span3">
		  <div class="white-boxed"><form id="frmfind"  method="post" action="#">
			<div class="navbar"><div class="navbar-inner">
				<input type="submit" name="find" class="btn btn-primary pull-left" value="Find Pages to execute Macro">
			</div></div>
			<div class="control-group">
				<label class="control-label">Page URL</label>
				<div class="controls">
					<input name="findurl" id="findurl" type="text" class="input-block-level"
						 placeholder="enter page url to find pages" required></input>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Find Pages by:</label>
				<div class="controls">
					<select name="findby" class="input-block-level">
						<option value="exact">Exact URL Match</option>
						<option value="begins">URL Begins With</option>
						<option value="ends">URL Ends With</option>
						<option value="contains">URL Contains</option>
						<option value="children">Child Pages</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="checkbox">
					<input name="incunpub" type="checkbox" value="checkbox">
					Include unpublised pages</label>
			</div>
			<div class="control-group">
				<label class="checkbox">
					<input id="incsidebar" type="checkbox" value="checkbox">
					Include Aside 1 (sidebarcontent)</label>
			</div>
			<div class="control-group">
				<label class="checkbox">
					<input id="incsiderbar" type="checkbox" value="checkbox">
					Include Aside 2 (siderbarcontent)</label>
			</div>
		  </form></div><br>
		</div>
		<div class="span9 white-boxed">
			<div id="exenavbar" class="navbar"><div class="navbar-inner">
				<button id="toggleEditSize" class="btn pull-left"><i class="icon-chevron-left"></i></button>
				<ul class="nav pull-left">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i>
							<strong>Select Macro</strong> <b class="caret"></b></a>
						<ul id="macro-select" class="dropdown-menu">
							<?php echo $cms->macrolist; ?>
						</ul>
					</li>
				</ul>
				<button id="runmacro" class="btn btn-success pull-right hide">Execute Macro</button>
				<a id="editmacro" href="#" target="_blank" class="btn btn-warning pull-right hide">Edit Macro</a>
			</div></div>
			<div id="progressbox" class="alert alert-block alert-info text-center hide">
				<h4>Macro `<i></i>` is running for Page 
					<span class="runrow"></span> of <span class="totrow"></span></h4>
				<i>Processing pagename pageurl here</i>
				<div class="progress progress-striped active">
					<div class="bar"></div>
				</div>
				<button id="stopexe" class="btn btn-danger" type="button">
					Stop Macro Execution</button>
			</div>
			<ul id="pager" class="pager hide">
				<li class="previous">
					<a href="#" class="first"><i class="icon-fast-backward"></i> 
						First Page</a>
				</li>
				<li class="previous">
					<a href="#" class="prev"><i class="icon-step-backward"></i> 
						Previous Page</a>
				</li>
				<li class="pagesbox"><span>Page 
					<input type="number" value="1" min="1" id="currpage" 
						class="span3"> of 
					<input type="number" value="1325" id="numbpages" 
						class="span3" disabled>
					<button id="checkfull" class="btn btn-small" 
						type="button">CHECK ALL</button>
				</span></li>
				<li class="next">
					<a href="#" class="last">Last Page 
						<i class="icon-fast-forward"></i></a>
				</li>				
				<li class="next">
					<a href="#" class="next">Next Page 
						<i class="icon-step-forward"></i></a>
				</li>
			</ul>
			<table id="resultsTable" class="table table-striped">
				<thead><tr>
					<th width="10%"><input id="checkall" type="checkbox"> NO</th>
					<th width="25%">PAGE</th>
					<th width="25%">URL</th>
					<th width="30%">MACRO LOG</th>
					<th width="10%">PUBLISHED</th>
				</tr></thead>
				<tbody>
					<tr><td colspan="5">Find Pages to execute the macro on.</td></tr>
				</tbody>
			</table>
		</div>
	  </div>
	</div>
	<br><br>
</div><!-- /wrap  -->
<?php include('include/footer.php'); ?>
<script src="js/macro.js"></script>
</body>
</html>
