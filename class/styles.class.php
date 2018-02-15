<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Styles Class 
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

class ezStyles extends ezCMS {

	public $filename = "../style.css";
	public $homeclass = '';
	public $deletebtn = '';
	public $content = '';
	public $treehtml = '';
	public $siteFolder; // Folder from which site is running
	
	// Consturct the class
	public function __construct () {
	
		// call parent constuctor
		parent::__construct();
		
		// Get the folder fro which site is running
		$this->siteFolder =  substr(htmlspecialchars($_SERVER["PHP_SELF"]), 0, -17);
		
		// Check if file to display is set
		if (isset($_GET['show'])) $this->filename = $_GET['show'];
		if ($this->filename != "../style.css") {
			$this->filename = "../site-assets/css/".$this->filename;
			$this->deletebtn = '<a href="?delfile='.$this->filename.'" class="btn btn-danger conf-del">Delete</a>';			
		} else {
			$this->homeclass = 'label label-info';
		}
		
		// Check if file is to be deleted
		if (isset($_GET['delfile'])) $this->deleteFile();
		
		// Purge Revision
		if (isset($_GET['purgeRev'])) $this->delRevision();
		
		// Check if layout file is present
		if (!file_exists($this->filename)) {
			header("Location: ?flg=yell");
			exit;
		}
		
		// get the contents of the controller file (index.php)
		$this->content = htmlspecialchars(file_get_contents($this->filename));
		
		// Update if Posted
		if ($_SERVER['REQUEST_METHOD'] == 'POST') $this->update();

		//Build the HTML Treeview
		$this->buildTree();
		
		// Get the Revisions
		$this->getRevisions();

	}
	
	// Function to Update the Defaults Settings
	private function delRevision() {

		$show = '';
		if ($this->filename != "../style.css")
			$show = '&show='.substr($this->filename, 19 , strlen($this->filename)-19);

		// Check permissions
		if (!$this->usr['editcss']) {
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
	
	// Function to fetch the revisions
	private function getRevisions() {
	
		$show = '';
		if ($this->filename != "../style.css")
			$show = '&show='.substr($this->filename, 19 , strlen($this->filename)-19);
	
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
				<a href="?purgeRev='.$entry['id'].$show.'" class="conf-del">Purge</a></td></tr>';

			$this->revs['jsn'][$entry['id']] = $entry['content'];

			$this->revs['cnt']++;
		}
		$this->revs['cnt']--;
		
		if ($this->revs['log'] == '') 
			$this->revs['log'] = '<tr><td colspan="4">There are no revisions.</td></tr>';	
	}
	
	// Function to Build Treeview HTML
	private function buildTree() {
		$this->treehtml = '<ul>';
		foreach (glob("../site-assets/css/*.css") as $entry) {
			$myclass = ($this->filename == $entry) ? 'label label-info' : '';
			$entry = substr($entry, 19, strlen($entry)-19);
			$this->treehtml .= '<li><i class="icon-tint"></i> <a href="styles.php?show='.
				$entry.'" class="'.$myclass.'">'.$entry.'</a></li>';
		}
		$this->treehtml .= '</ul>';		
	}
	
	// Function to Delete the JavasStylesheet file
	private function deleteFile() {
	
		$filename = $_REQUEST['delfile'];
		$show = substr($filename, 19 , strlen($filename)-19);
		
		// Check permissions
		if (!$this->usr['editcss']) {
			header("Location: ?flg=noperms&show=$show");
			exit;
		}
		
		// Default stylesheet cannot be deleted and file must end with '.js'
		if (($filename=='../styles.css') || (substr($filename,-4)!='.css') ) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}

		// Check if stylesheet is writeable
		if (!is_writable($filename)) {
			header("Location: ?flg=unwriteable&show=$show");
			exit;	
		}		
		
		// Delete the file
		if (unlink($filename)) {
			header("Location: ?flg=deleted");
			exit;
		}
		// Failed to delete the file	
		header("Location: ?flg=delfailed&show=$show");
		exit;	
	}

	
	// Function to Update the stylesheet files
	private function update() {
	
		// Check all the variables are posted
		if ( (!isset($_POST['Submit'])) || (!isset($_POST['txtContents'])) || (!isset($_POST["txtName"])) ) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}		
	
		$filename = $_POST["txtName"];
		$contents = $_POST["txtContents"];
		$show = '';
		if ($filename != "../style.css") $show = '&show='.substr($filename, 19 , strlen($filename)-19);

		// Check permissions
		if (!$this->usr['editcss']) {
			header("Location: ?flg=noperms$show");
			exit;
		}
	
		// CSS file must end with '.css'
		if (substr($filename,-4)!='.css') {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}

		// If file is missing then it is a copy 
		if (file_exists($filename)) {

			// Check if writeable
			if (!is_writable($filename)) {
				$this->flg = 'unwriteable';
				$this->filename = $filename;
				$this->content = htmlspecialchars($contents);
				return;
			}
			
			// Check if nothing has changed		
			$original = file_get_contents($filename);
			if ($original == $contents) {
				header("Location: ?flg=nochange$show");
				exit;
			}
	
			// Create a revision
			$data = array (	'content' => $original, 
							'fullpath' => $filename, 
							'revmsg' => $_POST['revmsg'],
							'createdby' => $this->usr['id']);
			if ( !$this->add('git_files', $data) ) {
				header("Location: ?flg=revfailed$show");
				exit;
			}

		}
		
		// Save the file
		if (file_put_contents($filename, $contents ) !== false) {
			header("Location: ?flg=saved$show");
			exit;
		}
		
		// Failed to update
		$this->flg = 'failed';
		$this->filename = $filename;
		$this->content = htmlspecialchars($contents);
		
	}
	
}
?>