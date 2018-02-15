<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Main Class 
 * 
 */

// **************** DATABASE ****************
require_once ("../cms.class.php"); // PDO Class for database access

// Class to handle post data
class ezCMS extends db {
 
	public $flg = ''; 	// Set the error message flag to none
	public $msg = ''; 	// Message to disaply if any
	public $usr; 		// Logged in user record

	// Stores Revision Details
	public $revs = array('log' => '', 'opt' => '', 'cnt' => 1, 'jsn' => array());
	
	// Consturct the class
	public function __construct ( $loginRequired = true ) {
	
		// call parent constuctor
		parent::__construct();
		
		// Start SESSION if not started 
		if (session_status() !== PHP_SESSION_ACTIVE) session_start(); 
		
		// Set SESSION ADMIN Login Flag to false if not set
		if (!isset($_SESSION['LOGGEDIN'])) $_SESSION['LOGGEDIN'] = false;
		
		// Redirect the user if NOT logged in
		if ((!$_SESSION['LOGGEDIN']) && ($loginRequired) ) { 
			$_SESSION['AFTERLOGINPAGE'] = $_SERVER['REQUEST_URI'];
			header("Location: index.php?flg=expired"); 
			exit; 
		}
		
		// Fetch the Logged in users details if login is required
		if ($loginRequired) { 
			$this->usr = $this->query('SELECT * FROM `users` WHERE `id` = '.
				$_SESSION['EZUSERID'].' LIMIT 1')->fetch(PDO::FETCH_ASSOC); // get the user details
			$_SESSION['MANAGEFILES'] = $this->usr['editpage'];
		}
		
		// Check if user is requesting bgColor
		if (isset($_GET['getCMScolor'])) 
			if (isset($this->usr['cmscolor']))
				 die($this->usr['cmscolor']);
			else die('');
			
		// Check if user is savng BG Color
		if (isset($_GET['cmsBgColor'])) 
			if (isset($this->usr['id']))
				 die( $this->edit( 'users', $this->usr['id'], array('cmscolor' => $_GET['cmsBgColor']) ) );
			else die('usr not set');			
		
		// Change editor type
		if (isset($_GET['etype'])) $this->chgEditor();
		
		// Change Code Mirror Theme
		if (isset($_GET['theme'])) $this->chgEditorTheme();
		
		// init revision vars
		$this->revs = array('log' => '', 'opt' => '', 'cnt' => 1, 'jsn' => array());
		
		// Check if Message Flag is set
		if (isset($_GET["flg"])) $this->flg = $_GET["flg"];
		
		// Load message for standard flags
		$this->getStdFlgMessage();
		
	}
	
	// this function will set the formatted html to display
	public function setMsgHTML ($class, $caption, $subcaption ) {
		$this->msg = '<div class="alert alert-'.$class.'">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>'.$caption.'</strong><br>'.$subcaption.'</div>';
	}
	
	// Add to Database table and returns new ID, false if failed
	protected function add($table, $data) {
		/*Uncomment to debug 
		die("INSERT INTO $table (`".
			implode("`,`", array_keys ($data))."`) VALUES ('".
			implode("','", array_values($data))."')");	
		*/ 
		$stmt = $this->prepare("INSERT INTO $table (`".
			implode("`,`", array_keys($data))."`) VALUES (".
			implode(',', array_fill(0, count($data), '?')).")");
		if ($stmt->execute(array_values($data))) {
			$newid = $this->lastInsertId();			
			$this->query("OPTIMIZE TABLE $table");
			return $newid;
		} 
		return false;
	}
	
	// Edit Database table row
	protected function edit($table, $id, $data) {
		$stmt = $this->prepare("UPDATE $table SET ".$this->arrayToPDOstr($data)." WHERE id = ? ");
		$data[] = $id;
		if ($stmt->execute(array_values($data))) {
			$this->query("OPTIMIZE TABLE $table");
			return true;
		} 
		return false;
	}
	
	// Delete from Database table
	protected function delete($t, $id) {
		$stmt = $this->prepare("DELETE FROM $t where id = ?");
		if ($stmt->execute(array($id))) {
			$this->query("OPTIMIZE TABLE $t");
			return true;
		}
		return false;
	}
	
	// Fetch a value from a table on single condition like id
	protected function chkTableForVal($table, $chkFld, $retFld, $val) {
		$r = new stdClass();	
		$stmt = $this->prepare("SELECT `$retFld` FROM `$table` WHERE `$chkFld` = ? LIMIT 1");
		if ($stmt->execute(array($val))) {
			if ($stmt->rowCount()) {
				$row = $stmt->fetch();
				return $row[$retFld];
			} else return false;
		} else die('Error: SQL FAILED');
	}
	
	// Fetch POST data and place into array
	protected  function fetchPOSTData($f, &$d) { 
		foreach($f as $k) {
			if (isset($_POST[$k])) {
				$d[$k] = trim($_POST[$k]); 
			} else {
				header('HTTP/1.1 400 BAD REQUEST');
				die('BAD REQUEST');
			}
		}
	}

	// Fetch POST checkbox data and place into array
	protected  function fetchPOSTCheck($f, &$d) { 
		foreach($f as $k) $d[$k] = (isset($_POST[$k])) ? 1 : 0;
	}

	// Change editor type
	private function chgEditor() {
		$editor  = intval($_GET['etype']);
		if ( ($editor<0) || ($editor>3)  ) die('Invalid Editor');
		$_SESSION['EDITORTYPE']=$editor;
		// Save to database
		$this->query("UPDATE `users` SET `editor` = '$editor' WHERE id = ".$this->usr['id']);
	}

	// CHange Code Mirror Theme
	private function chgEditorTheme() {
		$theme = $_GET['theme'];
		if ( ($theme!='default') && (!file_exists("codemirror/theme/$theme.css")) )
			die('<h1>Missing theme, please install it first.</h1>');
		$_SESSION['CMTHEME'] = $theme;
		// Save to database
		$this->query("UPDATE `users` SET `cmtheme` = '$theme' WHERE id = ".$this->usr['id']);
	}

	// Converts a php array into a PDO string (INTERNAL)
	private function arrayToPDOstr($a) { 
		$t = array();
		foreach (array_keys($a) as $n) $t[] = "`$n` = ?"; 
		return implode(', ', $t); 
	}

	// Function to Set the Display Message
	private function getStdFlgMessage() {

		// Set the HTML to display for this flag
		switch ($this->flg) {
			case "failed":
				$this->setMsgHTML('error','SAVE FAILED','An error occurred and the File was NOT saved.');
				break;
			case "saved":
				$this->setMsgHTML('success','SAVED SUCCESSFULLY','You have successfully saved the File.');
				break;
			case "revfailed":
				$this->setMsgHTML('error','REVISION FAILED','An error occurred and the REVISION was NOT created.');
				break;
			case "delfailed":
				$this->setMsgHTML('error','DELETE FAILED','An error occurred and the File was NOT deleted.');
				break;
			case "deleted":
				$this->setMsgHTML('success','DELETED','You have successfully deleted the File.');
				break;
			case "revdeleted":
				$this->setMsgHTML('success','REVISION DELETED','You have successfully deleted the Revision.');
				break;				
			case "revdelfailed":
				$this->setMsgHTML('error','DELETE FAILED','An error occurred and the Revision was NOT deleted.');
				break;			
			case "unwriteable":
				$this->setMsgHTML('error','NOT WRITEABLE','The File is NOT writeable.');
				break;
			case "yell":
				$this->setMsgHTML('warn','NOT FOUND','The File does not exist.');
				break;				
			case "nochange":
				$this->setMsgHTML('warn','NO CHANGE','Nothing has changed to save.');
				break;
			case "noperms":
				$this->setMsgHTML('info','PERMISSION DENIED','You do not have permissions for this action.');
				break;
		}
	}

}
?>