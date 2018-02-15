<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Layouts Class 
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

class ezLayouts extends ezCMS {

	public $filename = "layout.php";
	public $homeclass = '';
	public $deletebtn = '';
	public $content = '';
	public $treehtml = '';
	// Stores Pages usage Details
	public $usage = array('log' => '', 'cnt' => 0);
	
	// Consturct the class
	public function __construct () {
	
		// call parent constuctor
		parent::__construct();
		
		// Check if file to display is set
		if (isset($_GET['show'])) $this->filename = 'layout.'.$_GET['show'];
		if ($this->filename=="layout.php") {
			$this->homeclass = 'label label-info';
		} else {
			$this->deletebtn = '<a href="?delfile='.$this->filename.'" class="btn btn-danger conf-del">Delete</a>';
		}
		
		// Check if file is to be deleted
		if (isset($_GET['delfile'])) $this->deleteFile();
		
		// Purge Revision
		if (isset($_GET['purgeRev'])) $this->delRevision();
		
		// Check if layout file is present
		if (!file_exists('../'.$this->filename)) {
			header("Location: ?flg=yell");
			exit;
		}
		
		// get the contents of the layout file
		$this->content = htmlspecialchars(file_get_contents('../'.$this->filename));
		
		// Update if Posted
		if ($_SERVER['REQUEST_METHOD'] == 'POST') $this->update();
		
		//Build the HTML Treeview
		$this->buildTree();
		
		// Get the Usage of this layout in pages
		$this->getPageUse();		
		
		// Get the Revisions
		$this->getRevisions();

	}
	
	// Function to Update the Defaults Settings
	private function delRevision() {

		$show = '';
		if ($this->filename != "layout.php")
			$show = '&show='.substr($this->filename, 7 , strlen($this->filename)-7);

		// Check permissions
		if (!$this->usr['editlayout']) {
			header("Location: ?flg=noperms$show");
			exit;
		}
		
		// Get the revision ID to delete
		$revID = intval($_GET['purgeRev']);
		
		// Delete the revision
		if ( $this->delete('git_files',$revID) ) {
			header("Location: ?flg=revdeleted$show");
			exit;
		}
		
		header("Location: ?flg=revdelfailed$show");
		exit;
	
	}
	
	// Function to Build Treeview HTML
	private function buildTree() {
		$this->treehtml = '<ul>';
		foreach (glob("../layout.*.php") as $entry) {
			$entry = substr($entry, 10, strlen($entry)-10);
			$myclass = ($this->filename == 'layout.'.$entry) ? 'label label-info' : '';
			$this->treehtml .= '<li><i class="icon-list-alt"></i> <a href="layouts.php?show='.
				$entry.'" class="'.$myclass.'">'.$entry.'</a></li>';
		}
		$this->treehtml .= '</ul>';
	}
	
	// Function to fetch the revisions
	private function getRevisions() {
	
		$show = '';
		if ($this->filename != "layout.php")
			$show = '&show='.substr($this->filename, 7 , strlen($this->filename)-7);	
		
		foreach ($this->query("SELECT git_files.*, users.username
				FROM users LEFT JOIN git_files ON users.id = git_files.createdby
				WHERE git_files.fullpath = '".$this->filename."'
				ORDER BY git_files.id DESC") as $entry) {

			$this->revs['opt'] .= '<option value="'.$entry['id'].'">#'.
				$this->revs['cnt'].' '.$entry['createdon'].' ('.$entry['username'].')</option>';

			$this->revs['log'] .= '<tr>
				<td>'.$this->revs['cnt'].'</td>
				<td>'.$entry['username'].'</td>
				<td>'.$entry['revmsg'].'</td>	
				<td>'.$entry['createdon'].'</td>
			  	<td data-rev-id="'.$entry['id'].'">
				<a href="#">Fetch</a> &nbsp;|&nbsp; 
				<a href="#">Diff</a> &nbsp;|&nbsp;
				<a href="?purgeRev='.$entry['id'].$show.'" class="conf-del">Purge</a>	
				</td></tr>';

			$this->revs['jsn'][$entry['id']] = $entry['content'];

			$this->revs['cnt']++;
		}
		$this->revs['cnt']--;
		
		if ($this->revs['log'] == '') 
			$this->revs['log'] = '<tr><td colspan="4">There are no revisions.</td></tr>';	
	}

	// Function to fetch the pages this layout is used in
	private function getPageUse() {

		foreach ($this->query("SELECT `id`, `pagename`, `url` FROM `pages`
				WHERE `layout` = '".$this->filename."' ORDER BY id") as $entry) {
			$this->usage['log'] .= '<tr><td>'.$entry['id'].'</td>
				<td><a href="pages.php?id='.$entry['id'].'" target="_blank">'.$entry['pagename'].'</a></td>	
				<td><a href="'.$entry['url'].'" target="_blank">'.$entry['url'].'</a></td></tr>';
			$this->usage['cnt']++;
		}
		
		if ($this->usage['log'] == '') 
			$this->usage['log'] = '<tr><td colspan="3">There are no pages using this layout.</td></tr>';	
	}
	
	// Function to Delete the Layout
	private function deleteFile() {
	
		$filename = $_REQUEST['delfile'];
		$show = substr($filename, 7 , strlen($filename)-7);
		
		// Check permissions
		if (!$this->usr['editlayout']) {
			die('MA');
			header("Location: ?flg=noperms&show=$show");
			exit;
		}
		
		// Default layout cannot be deleted and file must begin with 'layout.' and end with '.php'
		if (($filename=='layout.php') || (substr($filename,0,7)!='layout.') || (substr($filename,-4)!='.php') ) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}

		// Check if layout is writeable
		if (!is_writable("../$filename")) {
			header("Location: ?flg=unwriteable&show=$show");
			exit;	
		}		
		
		// Delete the file
		if (unlink("../$filename")) {
			header("Location: ?flg=deleted");
			exit;
		}
		// Failed to delete the file	
		header("Location: ?flg=delfailed&show=$show");
		exit;	
	}
	
	// Function to Update the Layout
	private function update() {
	
		// Check all the variables are posted
		if ( (!isset($_POST['Submit'])) || (!isset($_POST['txtContents'])) || (!isset($_POST["txtName"])) ) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}
		
		$filename = $_POST["txtName"];
		$contents = $_POST["txtContents"];
		$show = substr($filename, 7 , strlen($filename)-7);
		
		
		// Check permissions
		if (!$this->usr['editlayout']) {
			header("Location: ?flg=noperms&show=$show");
			exit;
		}

		// Layout file must begin with 'layout.' and end with '.php'
		if ((substr($filename,0,7)!='layout.') || (substr($filename,-4)!='.php') ) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}

		// If file is missing then it is a copy 
		if (file_exists("../$filename")) {
		
			// Check if writeable
			if (!is_writable("../$filename")) {
				$this->flg = 'unwriteable';
				$this->filename = $filename;
				$this->content = htmlspecialchars($contents);
				return;
			}
			
			// Check if nothing has changed		
			$original = file_get_contents("../$filename");
			if ($original == $contents) {
				header("Location: ?flg=nochange&show=$show");
				exit;
			}
	
			// Create a revision
			$data = array (	'content' => $original, 
							'fullpath' => $filename,
							'revmsg' => $_POST['revmsg'],
							'createdby' => $this->usr['id']);
			if ( !$this->add('git_files', $data) ) {
				header("Location: ?flg=revfailed&show=$show");
				exit;
			}			
			
		}
		
		// Save the layout file
		if (file_put_contents("../$filename", $contents ) !== false) {
			header("Location: ?flg=saved&show=$show");
			exit;
		}
		
		// Failed to update layout
		$this->flg = 'failed';
		$this->filename = $filename;
		$this->content = htmlspecialchars($contents);

	}
	
}
?>