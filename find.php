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
	  <div id="editBlock" class="row">
		<div class="col-md-3">
		  <div class="white-boxed"><form id="frmfind"  method="post" action="#">
			<div class="toolbar-bar">
				<input type="submit" name="find" class="btn btn-primary" value="Find All">
				<ul class="nav">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-flag"></i>
							WHERE <b class="caret"></b></a>
						<ul id="findinDD" class="dropdown-menu">
							<li class="active"><a data-loc="page" href="#"><i class="bi bi-file-earmark"></i> Pages</a></li>
							<li class="divider"></li>
							<li><a data-loc="php" href="#"><i class="bi bi-file-text"></i> PHP Layouts</a></li>
							<li><a data-loc="css" href="#"><i class="bi bi-pencil"></i> CSS Stylesheets</a></li>
							<li><a data-loc="js" href="#"><i class="bi bi-file-code"></i> JS Javascripts</a></li>
							<li><a data-loc="inc" href="#"><i class="bi bi-share"></i> PHP Includes</a></li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="mb-3">
				<label class="form-label">FIND TEXT</label>
				<div class="controls">
					<textarea name="find" id="txtfind" rows="5"
						placeholder="Enter the text or code to find"
						class="form-control" required minlength="3"></textarea></div>
				<div class="mb-3">
					<label class="form-label">REPLACE WITH</label>
					<div class="controls">
						<textarea name="replace" id="txtreplace" rows="5"
							placeholder="Enter the text or code to replace"
							class="form-control"></textarea></div>
				</div>
			</div>
			<input type="hidden" name="findinTxt" id="findinTxt" />
		  </form></div><br>
		</div>
		<div class="col-md-9 white-boxed">
			<div id="exenavbar" class="toolbar-bar">
				<button id="toggleEditSize" class="btn float-start"><i class="bi bi-chevron-left"></i></button>
				<a class="brand" onclick="return false" href=""><small id="findinlbl"></small></a>
				<button id="replace" class="btn btn-success float-end d-none">
					Replace Checked</button>
			</div>
			<div id="progressbox" class="alert alert-info text-center hide">
				<h4>REPLACE is running for Page 
					<span class="runrow"></span> of <span class="totrow"></span></h4>
				<i>Processing pagename pageurl here</i>
				<div class="progress">
					<div class="progress-bar progress-bar-striped progress-bar-animated"></div>
				</div>
				<button id="stopexe" class="btn btn-danger" type="button">
					Stop Replace Execution</button>
			</div>
			<ul id="pager" class="pagination hide">
				<li class="page-item">
					<a href="#" class="page-link first"><i class="bi bi-skip-start-fill"></i>
						First Page</a>
				</li>
				<li class="page-item">
					<a href="#" class="page-link prev"><i class="bi bi-skip-backward"></i>
						Previous Page</a>
				</li>
				<li class="page-item pagesbox"><span>Page
					<input type="number" value="1" min="1" id="currpage"
						class="col-md-3"> of
					<input type="number" value="1325" id="numbpages"
						class="col-md-3" disabled>
					<button id="checkfull" class="btn btn-sm"
						type="button">CHECK ALL</button>
				</span></li>
				<li class="page-item">
					<a href="#" class="page-link last">Last Page
						<i class="bi bi-skip-end-fill"></i></a>
				</li>
				<li class="page-item">
					<a href="#" class="page-link next">Next Page
						<i class="bi bi-skip-forward"></i></a>
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
