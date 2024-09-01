<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Macros Class 
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

class ezMacros extends ezCMS {

	public $filename = "macro.php";
	public $homeclass = '';
	public $deletebtn = '';
	public $content = '';
	public $treehtml = '';

	
	// Consturct the class
	public function __construct () {
	
		// call parent constuctor
		parent::__construct();
		
		// Create macros folder if not present
		if ( !is_dir('../macros') ) $this->setupMacrosDir();
		
		// Check if file to display is set
		if (isset($_GET['show'])) $this->filename = $_GET['show'];
		if ($this->filename=="macro.php") {
			$this->homeclass = 'label label-info';
		} else {
			$this->deletebtn = '<a href="?delfile='.$this->filename.'" class="btn btn-danger conf-del">Delete</a>';
		}
		
		// Check if file is to be deleted
		if (isset($_GET['delfile'])) $this->deleteFile();
		
		// Purge Revision
		if (isset($_GET['purgeRev'])) $this->delRevision();
		
		// Check if include file is present
		/**/
		if (!file_exists('../macros/'.$this->filename)) {
			header("Location: ?flg=yell");
			exit;
		}

		
		// get the contents of the include file
		$this->content = htmlspecialchars(file_get_contents('../macros/'.$this->filename));
		
		// Update if Posted
		if ($_SERVER['REQUEST_METHOD'] == 'POST') $this->update();
		
		//Build the HTML Treeview
		$this->buildTree();
				
		// Get the Revisions
		$this->getRevisions();

	}

	// Function to create Macros folder and copy files if not present
	private function setupMacrosDir() {
		mkdir('../macros');
		copy("root_files/macros/.htaccess", '../macros/.htaccess');
		foreach(glob("root_files/macros/*.php") as $file) 
			copy($file, '../macros/'.basename($file));
	}
	
	// Function to Delete the Macro Revision
	private function delRevision() {

		$show = '';
		if ($this->filename != "macro.php") 
			$show = '&show='.$this->filename;	

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
		foreach (glob("../macros/*.php") as $entry) {
			
			$entry = basename($entry);
			if ($entry != 'macro.php') {
				$myclass = ($this->filename == $entry) ? 'label label-info' : '';
				$this->treehtml .= '<li><i class="icon-play"></i> <a href="macros.php?show='.$entry.'" class="'.$myclass.'">'.$entry.'</a></li>';
			}
		}
		$this->treehtml .= '</ul>';
	}
	
	// Function to fetch the revisions
	private function getRevisions() {
	
		$show = '';
		if ($this->filename != "macro.php") 
			$show = '&show='.$this->filename;	
		
		foreach ($this->query("SELECT git_files.*, users.username
				FROM users LEFT JOIN git_files ON users.id = git_files.createdby
				WHERE git_files.fullpath = 'macros/".$this->filename."'
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
	
	// Function to Delete the Macro
	private function deleteFile() {
	
		$filename = $_REQUEST['delfile'];
		
		// Check permissions
		if (!$this->usr['editlayout']) {
			header("Location: ?flg=noperms&show=$filename");
			exit;
		}
		
		// Default layout cannot be deleted and file must begin with 'layout.' and end with '.php'
		if (substr($filename,-4)!='.php') {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}

		// Check if layout is writeable
		if (!is_writable("../macros/$filename")) {
			header("Location: ?flg=unwriteable&show=$filename");
			exit;
		}		
		
		// Delete the file
		if (unlink("../macros/$filename")) {
			header("Location: ?flg=deleted");
			exit;
		}
		// Failed to delete the file	
		header("Location: ?flg=delfailed&show=$show");
		exit;	
	}
	
	// Function to Update the Macro
	private function update() {
	
		// Check all the variables are posted
		if ( (!isset($_POST['Submit'])) || (!isset($_POST['txtContents'])) || (!isset($_POST["txtName"])) ) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request.');
		}
		
		$filename = $_POST["txtName"];
		$contents = $_POST["txtContents"];
		
		// Check permissions
		if (!$this->usr['editlayout']) {
			header("Location: ?flg=noperms&show=$filename");
			exit;
		}

		// Layout file must end with '.php'
		if (substr($filename,-4)!='.php') {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}

		// If file is missing then it is a copy 
		if (file_exists("../macros/$filename")) {
		
			// Check if writeable
			if (!is_writable("../macros/$filename")) {
				$this->flg = 'unwriteable';
				$this->filename = $filename;
				$this->content = htmlspecialchars($contents);
				return;
			}
			
			// Check if nothing has changed		
			$original = file_get_contents("../macros/$filename");
			if ($original == $contents) {
				header("Location: ?flg=nochange&show=$filename");
				exit;
			}
	
			// Create a revision
			$data = ['content' => $original, 
					'fullpath' => "macros/$filename",
					'revmsg' => $_POST['revmsg'],
					'createdby' => $this->usr['id']];
			if ( !$this->add('git_files', $data) ) {
				header("Location: ?flg=revfailed&show=$filename");
				exit;
			}
			
		}
		
		// Save the file
		if (file_put_contents("../macros/$filename", $contents ) !== false) {
			header("Location: ?flg=saved&show=$filename");
			exit;
		}
		
		// Failed to update 
		$this->flg = 'failed';
		$this->filename = $filename;
		$this->content = htmlspecialchars($contents);
	}
	
}
?>