<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Controller Class 
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

class ezController extends ezCMS {

	//  Stores the content of the file (index.php)
	public $content = '';
	
	// Consturct the class
	public function __construct () {
	
		// call parent constuctor
		parent::__construct();
		
		// get the contents of the controller file (index.php)
		$this->content = htmlspecialchars(file_get_contents("../index.php"));
		
		// Update the Controller of Posted
		if ($_SERVER['REQUEST_METHOD'] == 'POST') $this->update();
		
		// Purge Revision
		if (isset($_GET['purgeRev'])) $this->delRevision();
		
		// Get the Revisions
		$this->getRevisions();

	}
	
	// Function to Update the Defaults Settings
	private function delRevision() {

		// Check permissions
		if (!$this->usr['editcont']) {
			header("Location: ?flg=noperms");
			exit;
		}
		
		// Get the revision ID to delete
		$revID = intval($_GET['purgeRev']);
		
		// Delete the revision
		if ( $this->delete('git_files',$revID) ) {
			header("Location: ?flg=revdeleted");
			exit;
		}
		
		header("Location: ?flg=revdelfailed");
		exit;
	
	}
	
	// Function to fetch the revisions
	private function getRevisions() {
	
		foreach ($this->query("SELECT git_files.*, users.username 
				FROM users LEFT JOIN git_files ON users.id = git_files.createdby
				WHERE git_files.fullpath = 'index.php'
				ORDER BY git_files.id DESC") as $entry) {
	
			$this->revs['opt'] .= '<option value="'.$entry['id'].'">#'.
				$this->revs['cnt'].' '.$entry['createdon'].' ('.$entry['username'].')</option>';
			
			$this->revs['log'] .= '<tr>
				<td>'.$entry['id'].'</td>
				<td>'.$entry['username'].'</td>
				<td>'.$entry['revmsg'].'</td>
				<td>'.$entry['createdon'].'</td>
			  	<td data-rev-id="'.$entry['id'].'">
				<a href="#">Fetch</a> &nbsp;|&nbsp; 
				<a href="#">Diff</a> &nbsp;|&nbsp;
				<a href="?purgeRev='.$entry['id'].'" class="conf-del">Purge</a>	
				</td></tr>';

			$this->revs['jsn'][$entry['id']] = $entry['content'];

			$this->revs['cnt']++;
		}
		$this->revs['cnt']--;
		
		if ($this->revs['log'] == '') 
			$this->revs['log'] = '<tr><td colspan="4">There are no revisions.</td></tr>';	
	}
	
	// Function to Update the Controller
	private function update() {
	
		// Check all the variables are posted
		if ( (!isset($_POST['Submit'])) || (!isset($_POST['txtContents'])) ) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}

		// Check permissions
		if (!$this->usr['editcont']) {
			header("Location: ?flg=noperms");
			exit;
		}
		
		// Fetch the contents
		$contents = $_POST["txtContents"];
		
		// Check if controller is writeable
		if (!is_writable('../index.php')) {
			$this->flg = 'unwriteable';
			$this->content = htmlspecialchars($contents);
			return;
		}

		// Check if nothing has changed		
		$original = file_get_contents("../index.php");
		if ($original == $contents) {
			header("Location: ?flg=nochange");
			exit;
		}
		
		// Create a revision
		$data = array (	'content' => $original, 
						'fullpath' => 'index.php', 
						'revmsg' => $_POST['revmsg'],
						'createdby' => $this->usr['id']);
		if ( !$this->add('git_files', $data) ) {
			header("Location: ?flg=revfailed");
			exit;
		}
		
		if (file_put_contents('../index.php', $contents )) {
			header("Location: ?flg=saved");
			exit;
		}

		$this->flg = 'failed';
		$this->content = htmlspecialchars($contents);
		
	}
	
}
?>