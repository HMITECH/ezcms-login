<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * HMI Technologies Mumbai
 *
 * POST Handler Service: Client POST end point.
 *
 */

class ezForms {
	
	private $emFROM = 'Admin';
	private $emADDR = '';

	// Consturct the class
	public function __construct () {
		
		// form must be post here ...
		if ($_SERVER['REQUEST_METHOD']!='POST') $this->fail();
		
		// HTTP Referer must be set ...
		if (!isset($_SERVER["HTTP_REFERER"])) $this->fail();
		
		$form = $_SERVER["HTTP_REFERER"];
	}
		
	/*
	 * Validate the form data posted
	 */		
	private function validate() {
	
			return false;
	}
	
	/*
	 * Send an email 
	 */	
	private function sendemail($to, $sub, $msg) {
		
		// create a boundary for the email. 
		$boundary = uniqid('np');
		
		$headers  = "MIME-Version: 1.0\n";
		//$headers .= "Content-type: text/html; charset=utf-8\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "Return-Path: ".$this->emADDR."\n";
		$headers .= 'From: "'.$this->emFROM.'" <'.$this->emADDR.'>' . "\r\n" .
					'Reply-To: "'.$this->emFROM.'" <'.$this->emADDR.'>' . "\r\n";
		return @mail($to, $sub,$msg, $headers,'-f'.$this->emADDR);
	}
	
	/*
	 * Update Database with form signup data 
	 */	
	private function update() {
		
		// get database handle
		global $dbh;
		
		
		return false;
	}
	
	/*
	 * FAIL : 400 BAD REQUEST
	 */	
	private function fail() {
		header('HTTP/1.1 400 BAD REQUEST');
		die('400 BAD REQUEST');
	}	
	
}	

$forms = new ezForms;

?>