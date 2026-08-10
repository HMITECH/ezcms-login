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

		// Revisions are loaded lazily by AJAX after the page renders (a site can
		// have thousands of them and they're rarely opened) — these endpoints die.
		if (isset($_GET['ajaxRevs']))       $this->ajaxRevs();
		if (isset($_GET['ajaxRevContent'])) $this->ajaxRevContent();

		// Update if POSTED here
		if ($_SERVER['REQUEST_METHOD'] == 'POST') $this->update();

		// Purge Revision
		if (isset($_GET['purgeRev'])) $this->delRevision();

		// process variable for html display
		$this->setPageVariables();

	}

	// Function to Setup page variable and checkboxes
	private function setPageVariables() {	
		$this->site['headercontent'] = $this->site['headercontent'] ? 
		    htmlspecialchars($this->site["headercontent"]) : '';
		$this->site['sidecontent'] = $this->site['sidecontent'] ? 
		    htmlspecialchars($this->site["sidecontent"]) : '';
		$this->site['sidercontent'] = $this->site['sidercontent'] ? 
		    htmlspecialchars($this->site["sidercontent"]) : '';
		$this->site['footercontent'] = $this->site['footercontent'] ? 
		    htmlspecialchars($this->site["footercontent"]) : '';  		
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
	// AJAX: recent revision metadata + total count + select options (no content)
	private function ajaxRevs() {
		$per  = 10;   // the log shows only the most recent few
		$page = max(1, (int)($_GET['page'] ?? 1));
		$cur  = (int)$this->site['id'];

		$total  = (int)$this->query("SELECT COUNT(*) FROM `site` WHERE `id` <> $cur")->fetchColumn();
		$pages  = max(1, (int)ceil($total / $per));
		$offset = ($page - 1) * $per;

		$rows = [];
		foreach ($this->query("SELECT site.id, site.revmsg, site.createdon, users.username
				FROM site LEFT JOIN users ON site.createdby = users.id
				WHERE site.id <> $cur ORDER BY site.id DESC LIMIT $per OFFSET $offset") as $e) {
			$rows[] = ['id'=>$e['id'], 'user'=>$e['username'], 'msg'=>$e['revmsg'], 'date'=>$e['createdon']];
		}

		// dropdown options (id/date/user only) — sent once, with the first page
		$opts = null;
		if ($page === 1) {
			$opts = [];
			foreach ($this->query("SELECT site.id, site.createdon, users.username
					FROM site LEFT JOIN users ON site.createdby = users.id
					WHERE site.id <> $cur ORDER BY site.id DESC") as $e) {
				$opts[] = ['id'=>$e['id'], 'label'=>'#'.$e['id'].' '.$e['createdon'].' ('.$e['username'].')'];
			}
		}

		die(json_encode(['status'=>true, 'count'=>$total, 'page'=>$page, 'pages'=>$pages,
			'rows'=>$rows, 'opts'=>$opts]));
	}

	// AJAX: a single revision's content blocks (for Fetch / Diff)
	private function ajaxRevContent() {
		$id  = (int)($_GET['id'] ?? 0);
		$row = $this->query("SELECT headercontent, sidecontent, sidercontent, footercontent
				FROM `site` WHERE `id` = $id LIMIT 1")->fetch(PDO::FETCH_ASSOC);
		if (!$row) die(json_encode(['status'=>false]));
		die(json_encode(['status'=>true,
			'header'=>$row['headercontent'], 'side1'=>$row['sidecontent'],
			'side2' =>$row['sidercontent'], 'footer'=>$row['footercontent']]));
	}
	
	// Function to Update the Defaults Settings
	private function update() {

		// Check permissions
		if (!$this->usr['editsettings']) {
			header("Location: ?flg=noperms");
			exit;
		}
		
		// array to hold the data
		$data = [];
		
		// get the required post varables 
		$this->fetchPOSTData([
			'headercontent',
			'sidecontent', 
			'sidercontent', 
			'footercontent'], $data);
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
			$this->edit('site', $this->site['id'], ['revmsg' => $_POST['revmsg']]);
			if ($this->useRedis) $this->redis->del($this->useRedis."-site");
			header("Location: ?flg=saved");
			exit;
		}
		
		header("Location: ?flg=failed");
		exit;

	}

}
?>
