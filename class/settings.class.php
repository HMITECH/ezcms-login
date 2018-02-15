<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Set Defaults Class 
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

// Handles Default Setting in ezCMS
class ezSettings extends ezCMS {
	
	// Stores Default Setting data from database
	public $site;
	
	// Consturct the class
	public function __construct () {
	
		// call parent constuctor
		parent::__construct();
		
		// fetch the data
		$this->site = $this
			->query('SELECT * FROM `site` ORDER BY `id` DESC LIMIT 1')
			->fetch(PDO::FETCH_ASSOC);
				
		// Update if POSTED here
		if ($_SERVER['REQUEST_METHOD'] == 'POST') $this->update();
		
		// Purge Revision
		if (isset($_GET['purgeRev'])) $this->delRevision();

		// process variable for html display
		$this->setPageVariables();
		
		// Get the Revisions
		$this->getRevisions();

	}

	// Function to Setup page variable and checkboxes
	private function setPageVariables() {	
		$this->site['headercontent'] = htmlspecialchars($this->site["headercontent"]);
		$this->site['sidecontent'] = htmlspecialchars($this->site["sidecontent"]);
		$this->site['sidercontent'] = htmlspecialchars($this->site["sidercontent"]);		
		$this->site['footercontent'] = htmlspecialchars($this->site["footercontent"]);		
	}

	// Function to Update the Defaults Settings
	private function delRevision() {

		// Check permissions
		if (!$this->usr['editsettings']) {
			header("Location: ?flg=noperms");
			exit;
		}
		
		// Get the revision ID to delete
		$revID = intval($_GET['purgeRev']);
		
		// Validations - cannot delete current record.
		if ($this->site['id'] == $revID) {
			header("Location: ?flg=invalid");
			exit;		
		}
		
		// Delete the revision
		if ( $this->delete('site',$revID) ) {
			header("Location: ?flg=revdeleted");
			exit;
		}
		
		header("Location: ?flg=revdelfailed");
		exit;		
	
	}
	
	// Function to fetch the revisions
	private function getRevisions() {
		
		foreach ($this->query("SELECT site.*, users.username
				FROM site LEFT JOIN users ON site.createdby = users.id
				WHERE site.id <> ".$this->site['id']." ORDER BY site.id DESC") as $entry) {
					
			$this->revs['opt'] .= '<option value="'.$entry['id'].'">#'.
				$entry['id'].' '.$entry['createdon'].' ('.$entry['username'].')</option>';
				
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
				
			$this->revs['jsn'][$entry['id']] = array( 
				'header' =>  $entry['headercontent'] , 
				'side1' =>  $entry['sidecontent'] ,
				'side2' =>  $entry['sidercontent'] ,
				'footer' =>  $entry['footercontent'] );

			$this->revs['cnt']++;
		}
		$this->revs['cnt']--;
		
		if ($this->revs['log'] == '') 
			$this->revs['log'] = '<tr><td colspan="4">There are no revisions.</td></tr>';	
	}
	
	// Function to Update the Defaults Settings
	private function update() {

		// Check permissions
		if (!$this->usr['editsettings']) {
			header("Location: ?flg=noperms");
			exit;
		}
		
		// array to hold the data
		$data = array();
		
		// get the required post varables 
		$this->fetchPOSTData(array(
			'headercontent',
			'sidecontent', 
			'sidercontent', 
			'footercontent'), $data);
		$data['createdby'] = $_SESSION['EZUSERID'];
		
		// Test if nothing has changed 
		if (($data['headercontent'] == $this->site['headercontent']) && 
			($data['sidecontent'  ] == $this->site['sidecontent'  ]) && 
			($data['sidercontent' ] == $this->site['sidercontent' ]) && 
			($data['footercontent'] == $this->site['footercontent']) ){
				header("Location: ?flg=nochange");
				exit;		
		}
		
		// Save to database
		if ( $this->add('site',$data) ) {
			// Save the rev message to the last records
			$this->edit('site', $this->site['id'], array('revmsg' => $_POST['revmsg']));
			header("Location: ?flg=saved");
			exit;
		}
		
		header("Location: ?flg=failed");
		exit;

	}

}
?>