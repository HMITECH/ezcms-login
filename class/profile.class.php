<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Class: ezCMS User password Class 
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

class ezProfile extends ezCMS {

	// Consturct the class
	public function __construct () {

		// call parent constuctor
		parent::__construct();
				
		// Update the Controller of Posted
		if ($_SERVER['REQUEST_METHOD'] == 'POST') $this->update();

	}
	
	// this function will check and update the password
	private function update() {
			
		// check all the variables are posted
		if ( (!isset($_POST['txtcpass'])) || (!isset($_POST['txtnpass'])) || (!isset($_POST['txtrpass'])) ) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}
		
		// Get the POST data
		$curpass = trim($_POST['txtcpass']); 
		$newpass = trim($_POST['txtnpass']);
		$reppass = trim($_POST['txtrpass']);
		
		// check password match
		if ($newpass != $reppass) {
			$this->setMsgHTML('error','Password Mismatch!',
				'The new password and repeat password do not match.');
			return;
		} 
		
		// check password len
		if (strlen($newpass)<1) {
			$this->setMsgHTML('error','Password Too Short!',
				'The new password must be more than 8 characters in length.');
			return;
		}
		
		// Prepare SQL to fetch user's record from database
		$id = $this->usr['id'];
		$stmt = $this->prepare("SELECT `id` FROM `users` WHERE `id` = $id AND (`passwd` = SHA2( ? , 512 ) or `passwd` = ?) LIMIT 1");
		$stmt->execute( array($curpass, $curpass) );

		// Check if User Record is present and returned from the database
		if ($stmt->rowCount()) {
		
			// update the password  here
			$stmt = $this->prepare("UPDATE `users` SET `passwd` = SHA2( ? , 512 ) WHERE `id` = $id ");
			if ($stmt->execute( array($newpass) ) ) {
				// Database update done
				$this->setMsgHTML('success','New Password Saved!',
					'You have successfully changed your password.');
			} else {
				// Database update failed
				$this->setMsgHTML('error','Update Failed!',
					'Failed to update your password.');
			}

		} else {
				// Database update failed
				$this->setMsgHTML('error','Current Password Mismatch!',
					'Your current password is incorrect.');
		}
	}
}
?>
