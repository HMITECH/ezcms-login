<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Users Class 
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

class ezUsers extends ezCMS {

	public $id = 1;
	public $treehtml = '';
	public $barBtns = '';
	public $createdText = '';
	public $thisUser;

	// Consturct the class
	public function __construct () {
	
		// call parent constuctor
		parent::__construct();
		
		// Check if user to display is set
		if (isset($_GET['id'])) $this->id = $_GET['id'];
		
		// Update the user if Posted
		if ($_SERVER['REQUEST_METHOD'] == 'POST') $this->update();
		
		// Check if delete ID is set
		if (isset($_GET['delid'])) $this->deleteUser();
		
		if ($this->id != 'new' ) {
			$this->thisUser = $this->query('SELECT * FROM `users` WHERE `id` = '.intval($this->id).' LIMIT 1')
				->fetch(PDO::FETCH_ASSOC); // get the selected user details
			
			// check if user is present.
			if (!isset($this->thisUser['id'])) {
				header("Location: ?flg=yell");
				exit;
			}
			
			$this->barBtns = '<input type="submit" name="Submit" class="btn btn-primary" value="Save Changes">
				 <a href="?id=new" class="btn btn-info">New User</a>';
				
			if ($this->id <> 1) $this->barBtns .=  
				' <a href="?delid=' . $this->id .'" class="btn btn-danger conf-del">Delete</a>';
				
			// Get Created on String
			$this->createdText = '<div class="clearfix"></div><p><em>Created on '.
				$this->thisUser['createdon'].'</em></p>';
				
			// Get the Revisions
			$this->getRevisions();			

		} else {
			// Set empty default values for new user
			$this->thisUser = array( 'username' => '', 'email' => '@',
				'active' => '1', 'editpage' => '1', 'delpage' => '',
				'edituser' => '', 'deluser' => '', 'editsettings' => '1',
				'editcont' => '', 'editlayout' => '', 'editcss' => '1', 'editjs' => '1');
			$this->barBtns = '<input type="submit" name="Submit" class="btn btn-primary" value="Add New">';
		}
		
		// Setup the checkboxes to display
		$this->setupCheckboxes();
		
		//Build the HTML Treeview
		$this->buildTree();
	
		// Get the Message to display if any
		$this->getMessage();
		$this->msg = str_replace('File','User',$this->msg);

	}
	
	// Function to fetch the revisions
	private function getRevisions() {
	
		$results = $this->query(" (SELECT `page_id` as `id`, 1 as `type`, `url` as `det`, `revmsg`, `createdon` 
								FROM `git_pages` WHERE `createdby` = ".intval($this->id)." )
							UNION (SELECT `id`, 2 as `type`, `fullpath` as `det`, `revmsg`,`createdon` 
								FROM `git_files` WHERE `createdby` = ".intval($this->id).")
							UNION (SELECT `id`, 3 as `type`, '' as `det`, `revmsg`, `createdon` 
								FROM `site` WHERE `createdby` = ".intval($this->id).")
							ORDER BY `createdon` DESC")->fetchAll(PDO::FETCH_ASSOC);
		
		foreach ($results as $entry) {
		
			if ($entry['type']==1) {
				$type = '<a href="pages.php?id='.$entry['id'].'"><i class="icon-file"></i> Page</a>';
			} else if ($entry['type']==2) {
				$ext = substr($entry['det'], -3);
				if ($entry['det'] == 'index.php' ) {
					$type = '<a href="controllers.php"><i class="icon-play"></i> URL Router</a>';
				} else if ($ext == '.js' ) {
					if ($entry['det']=='../main.js') $type = '<a href="scripts.php">';
					else $type = '<a href="scripts.php?show='.substr($entry['det'], 18 , strlen($entry['det'])-18).'">';
					$type .= '<i class="icon-align-left"></i> JS Javascripts</a>';
				} else if ($ext == 'css' ) {
					if ($entry['det']=='../style.css') $type = '<a href="styles.php">';
					else $type = '<a href="styles.php?show='.substr($entry['det'], 19 , strlen($entry['det'])-19).'">';
					$type .= '<i class="icon-pencil"></i> CSS Stylesheets</a>';
				} else if ($ext == 'php' ) {
					if ($entry['det']=='layouts.php') $type = '<a href="layouts.php">';
					else $type = '<a href="layouts.php?show='.substr($entry['det'], 7 , strlen($entry['det'])-7).'">';
					$type .= '<i class="icon-list-alt"></i> PHP Layouts</a>';
				}
			} else if ($entry['type']==3) {
				$type = '<a href="setting.php"><i class="icon-th-list"></i> Defaults Settings</a>';
			}

			$this->revs['log'] .= '<tr>
				<td>'.$this->revs['cnt'].'</td>
				<td>'.$type.'</td>
				<td>'.$entry['det'].'</td>
				<td>'.$entry['revmsg'].'</td>
				<td>'.$entry['createdon'].'</td></tr>';

			$this->revs['cnt']++;
		}
		$this->revs['cnt']--;

		if ($this->revs['log'] == '') 
			$this->revs['log'] = '<tr><td colspan="4">There are no revisions.</td></tr>';
			
	}
	
	// this function will set the options to diaplay check boxes
	private function setOptions($itm, $msgOn, $mgsOff) {
		$this->thisUser[$itm.'Check'] = '';
		$this->thisUser[$itm.'Msg'] = '<span class="label label-important">'.$mgsOff.'</span>';
		if ($this->thisUser[$itm]) {
			$this->thisUser[$itm.'Check'] = 'checked';
			$this->thisUser[$itm.'Msg'] = '<span class="label label-info">'.$msgOn.'</span>';
		}
	}
	
	// Function to setup the checkboxes
	private function setupCheckboxes() {
		$this->setOptions('active', 'User is Active.', 'Inactive user cannot login.');
		$this->setOptions('editpage', 'Page management available.', 'Page management blocked.');
		$this->setOptions('delpage', 'Page delete available.', 'Page delete blocked.');
		$this->setOptions('edituser', 'User can manage other users.', 'User cannot manage other users.');
		$this->setOptions('deluser', 'User can delete other users.', 'User cannot delete other users.');
		$this->setOptions('editsettings', 'Template Settings management available.', 'Template Settings management blocked.');
		$this->setOptions('editcont', 'Template Controller management available.', 'Template Controller management blocked.');
		$this->setOptions('editlayout', 'Template Layout management available.', 'Template Layout management blocked.');
		$this->setOptions('editcss', 'Stylesheet management available.', 'Stylesheet management blocked.');
		$this->setOptions('editjs', 'Javascript management available.', 'Javascript management blocked.');
	}

	// Function to Build Treeview HTML
	private function buildTree() {
		$this->treehtml = '<ul id="left-tree">';
		foreach ($this->query("SELECT `id`, `username`, `active` FROM `users` ORDER BY id;") as $entry) {
			$myclass = ($entry["id"] == $this->id) ? 'label label-info' : '';
			if ($entry["id"] == 1) {
				$this->treehtml .= '<li class="open"><i class="icon-globe"></i> <a href="?id='.
					$entry['id'].'" class="'.$myclass.'">'.$entry["username"].'</a><ul>';
			} else {
				$active = ($entry["active"] != 1) ? ' <i class="icon-ban-circle" title="User is not active, cannot login"></i>' : '';
				$this->treehtml .= '<li><i class="icon-user"></i> <a href="?id='.
					$entry['id'].'" class="'.$myclass.'">'.$entry["username"].$active.'</a></li>';
			}
			
		}
		$this->treehtml .= '</ul></li></ul>';		
	}
	
	private function deleteUser() {
	
		// Check permissions
		if (!$this->usr['deluser']) {
			header("Location: ?flg=noperms");
			exit;
		}
		
		$id = $_GET['delid']; 
		// cannot delete home page or 404 page
		if ($id==1) die('Cannot delete root user.');
		
		// Delete the User
		if ( $this->delete('users',$id) ) {
			header("Location: ?flg=deleted");
			exit;
		}
		
		// Failed to update
		$this->flg = 'failed';

	}
	
	// Function to Update the Controller
	private function update() {
	
		// Check permissions
		if (!$this->usr['edituser']) {
			header("Location: ?flg=noperms");
			exit;
		}
		
		// array to hold the data
		$data = array();
		
		// get the required post varables 
		$this->fetchPOSTData(array('username','passwd','email'), $data);
		if (!$data['passwd']) unset($data['passwd']);
		else $data['passwd'] = hash('sha512',$data['passwd']); // encrypt the password
			
		// get the required post checkboxes 
		$this->fetchPOSTCheck( array('active','editpage','delpage','edituser','deluser',
			'editsettings','editcont','editlayout','editcss','editjs'), $data);
			
		// Common Validtions 
		if (strlen(trim($data['username'])) < 2) die('User Name must min 2 chars!');
		if (strlen(trim($data['email'])) < 5) die('User email must min 5 chars!');
		if (isset($data['passwd'])) 
			if (strlen(trim($data['passwd'])) < 8) 
				die('New User password must be 8 in length.');
		// email address should not be duplicated.
		$dupCheckID = $this->chkTableForVal('users', 'email', 'id', $data['email']);
		
		if ($this->id == 'new') {
			// add new
			
			// password must set for new users
			if (!isset($data['passwd'])) die('New user password must be set.');
			
			// email address should not be duplicated.
			if ($dupCheckID) {
				$this->flg = 'emailduplicate';
				$this->thisUser = $data;
				$this->setupCheckboxes();
				return;
			}
			
			$newID = $this->add( 'users' , $data);
			if ($newID) {
				header("Location: ?id=".$newID."&flg=saved");	// added
				exit; 
			} 
			
		} else {
			// update
			
			// email address should not be duplicated.
			if (($dupCheckID) && ($dupCheckID != $this->id)) {
				$this->flg = 'emailduplicate';
				$this->thisUser = $data;
				$this->setupCheckboxes();
				return;
			}
			
			if ($this->edit( 'users' , $this->id , $data )) {
				header("Location: ?id=".$this->id."&flg=saved");	// added
				exit; 
			}		
		}
		$this->flg = 'failed';
		$this->thisUser = $data;
		$this->setupCheckboxes();		
	}
	
	// Function to Set the Display Message
	private function getMessage() {

		// Set the HTML to display for this flag
		switch ($this->flg) {
			case "emailduplicate":
				$this->setMsgHTML('error','SAVE FAILED','This email is already in use by another user.');
				break;
		}

	}

}
?>