<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Redirect Class 
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

class ezRedirect extends ezCMS {

	// Consturct the class
	public function __construct () {
		// call parent constuctor
		parent::__construct();
		if (isset($_GET['getall'])) $this->getJson();
		// Handle post request
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if (isset($_GET['addRedirect'])) $this->addRedirect();
			if (isset($_GET['delRedirect'])) $this->delRedirect();
			if (isset($_GET['togenabled' ])) $this->toggleRedirect();
			if (isset($_GET['del404log'  ])) $this->del404Log();
		}
	}

	private function getJson() {
		$r = new stdClass();
		$r->status = true;
		$r->rows = $this->query("SELECT * FROM `redirects` ORDER BY `id` DESC")->fetchAll(PDO::FETCH_ASSOC);
		$r->r404 = $this->query("SELECT log404.url, count(0) as cnt404 FROM log404 GROUP BY log404.url")->fetchAll(PDO::FETCH_ASSOC);
		die(json_encode($r));
	}
	
	private function addRedirect () {
		if (isset($_POST['srcuri'])) $srcurl = trim($_POST['srcuri']); else die('Source URL not set.');
		if (isset($_POST['desuri'])) $desurl = trim($_POST['desuri']); else die('Destination URL not set.');
		// validate 
		if (strlen($srcurl)<2) die('Source URL must be at least 2 chars.');
		if (strlen($desurl)<2) die('Destination URL must be at least 2 chars.');
		if (substr($srcurl,0,1)<>'/') die('Source URL must begin with /');
		$stmtCHK = $this->prepare("SELECT `id` FROM `redirects` WHERE `srcurl` = ? ");
		$stmtCHK->execute(array($srcurl));
		if ($stmtCHK->rowCount()>0) die('This URL is already redirected.');
		// Add here 
		$stmt = $this->prepare("INSERT INTO `redirects` (`srcurl`,`desurl`,`createdby`) VALUES (?,?,?)");
		if ($stmt->execute(array($srcurl, $desurl, $this->usr['id'])))
			die('0');
		die('Failed to add redirect!');
	}
	
	private function del404Log() {
		if (isset($_POST['url'])) $url = trim($_POST['url']); else die('Delete ID not set.');
		$stmt = $this->prepare("DELETE FROM `log404` WHERE `url` = ? ");
		if ($stmt->execute(array($url))) die('0');
		die('Failed to del 404 log.');
	}	
	
	private function toggleRedirect() {
		if (isset($_POST['id'])) $id = intval($_POST['id']); else die('Delete ID not set.');
		if ($this->query("UPDATE `redirects` SET `enabled`= IF(`enabled`=1, 0, 1) WHERE `id`=$id")) die('0');
		die('Failed to toggle redirect.');
	}

	private function delRedirect () {
		if (isset($_POST['id'])) $id = intval($_POST['id']); else die('Delete ID not set.');
		if ($this->query("DELETE FROM `redirects` WHERE `id`=$id")) die('0');
		die('Failed to delete redirect.');
	}

}
?>