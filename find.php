<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * View: Displays the UI for find and replace
 * 
 */

// **************** ezCMS USERS CLASS ****************
require_once ("class/find.class.php");

// **************** ezCMS USERS HANDLE ****************
$cms = new ezFind();

?><!DOCTYPE html><html lang="en"><head>

	<title>Find Replace : ezCMS Admin</title>
	<?php include('include/head.php'); ?>
	<style>
		#frmfind textarea { height: auto; }
		#resultsTable .replaceOnelnk { color: red; }
		#resultsTable .title, #resultsTable .keywords, 
		#resultsTable .description { text-transform: capitalize; }
	</style>

</head><body>
<div id="wrap">
	<?php include('include/nav.php'); ?>
	<div class="container">
	  <div id="editBlock" class="row-fluid">
		<div class="span3">
		  <div class="white-boxed"><form id="frmfind"  method="post" action="#">
			<div class="navbar"><div class="navbar-inner">
				<input type="submit" name="find" class="btn btn-primary pull-left" value="Find All">
				<ul class="nav pull-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-flag"></i>
							WHERE <b class="caret"></b></a>
						<ul id="findinDD" class="dropdown-menu">
							<li class="active"><a data-loc="page" href="#"><i class="icon-file"></i> Pages</a></li>
							<li class="divider"></li>
							<li><a data-loc="php" href="#"><i class="icon-list-alt"></i> PHP Layouts</a></li>
							<li><a data-loc="css" href="#"><i class="icon-pencil"></i> CSS Stylesheets</a></li>
							<li><a data-loc="js" href="#"><i class="icon-align-left"></i> JS Javascripts</a></li>
							<li><a data-loc="inc" href="#"><i class="icon-share-alt"></i> PHP Includes</a></li>
						</ul>
					</li>
				</ul>
			</div></div>
			<div class="control-group">
				<label class="control-label">FIND TEXT</label>
				<div class="controls">
					<textarea name="find" id="txtfind" rows="5"
						placeholder="Enter the text or code to find"
						class="input-block-level" required minlength="3"></textarea></div>
				<div class="control-group">
					<label class="control-label">REPLACE WITH</label>
					<div class="controls">
						<textarea name="replace" id="txtreplace" rows="5"
							placeholder="Enter the text or code to replace"
							class="input-block-level"></textarea></div>
				</div>
			</div>
			<input type="hidden" name="findinTxt" id="findinTxt" />
		  </form></div><br>
		</div>
		<div class="span9 white-boxed">
			<div id="exenavbar" class="navbar"><div class="navbar-inner">
				<button id="toggleEditSize" class="btn pull-left"><i class="icon-chevron-left"></i></button>
				<a class="brand" onclick="return false" href=""><small id="findinlbl"></small></a>
				<button id="replace" class="btn btn-success pull-right hide">
					Replace Checked</button>
			</div></div>
			<div id="progressbox" class="alert alert-block alert-info text-center hide">
				<h4>REPLACE is running for Page 
					<span class="runrow"></span> of <span class="totrow"></span></h4>
				<i>Processing pagename pageurl here</i>
				<div class="progress progress-striped active">
					<div class="bar"></div>
				</div>
				<button id="stopexe" class="btn btn-danger" type="button">
					Stop Replace Execution</button>
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
			<table id="resultsTable" class="table table-striped"><thead>
			<tr><th>Search Results</th></tr></thead><tbody>
			<tr><td>No Results</td></tr></tbody></table>
		</div>
	  </div>
	</div>
	<br><br>
</div><!-- /wrap  -->
<?php include('include/footer.php'); ?>
<script src="js/find.js"></script>
</body>
</html>
