<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Find and Replace Class 
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

class ezFind extends ezCMS {

	// Consturct the class
	public function __construct () {
	
		// call parent constuctor
		parent::__construct();
		
		// Handle post request
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$r = new stdClass();
			$r->success = true;
			$findin = $_POST['findinTxt'];
			if ($findin == 'page') 
				$r->results = $this->findPages();
			if ($findin == 'php' ) 
				$r->results = $this->findFiles ('layout.php', '../layout.', '*.php'); //findLayouts();
			if ($findin == 'css' ) 
				$r->results = $this->findFiles ('style.css', '../site-assets/css/', '*.css'); //findCSS();
			if ($findin == 'js'  ) 
				$r->results = $this->findFiles ('main.js', '../site-assets/js/', '*.js'); //findJS();
			die(json_encode($r));
		}

	}

	private function findFiles ($mainFile, $path, $type) {	
		$results = array();
		$content = file_get_contents("../$mainFile"); 
		if (strpos($content, $_POST['find']) !== false)
			array_push($results, array('name' => $mainFile));
		$pathLen = strlen($path);
		foreach (glob($path.$type) as $entry) {
			$content = file_get_contents($entry);
			if (strpos($content, $_POST['find']) !== false)
				array_push($results, array('name' => substr($entry, $pathLen , strlen($entry)-$pathLen)));
		}
		return $results;
	}
	
	private function findPages () {	
		return array_merge(
			$this->findPagesBlk('title'),
			$this->findPagesBlk('maincontent'),
			$this->findPagesBlk('headercontent'),
			$this->findPagesBlk('footercontent'),
			$this->findPagesBlk('sidecontent'),
			$this->findPagesBlk('sidercontent'),
			$this->findPagesBlk('head'),
			$this->findPagesBlk('description'),
			$this->findPagesBlk('keywords'));
	}
	
	private function findPagesBlk ( $fld ) {
		$stmt = $this->prepare(
			"SELECT `id` , `pagename` as `name`, '$fld' as `block`, `url`, `published` FROM `pages` 
			WHERE `$fld` LIKE CONCAT ('%' , :findstr , '%')" );
		$stmt->bindParam(':findstr', $_POST['find'], PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

}
?>