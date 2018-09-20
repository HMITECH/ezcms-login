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
		
			// Check if find all 
			if (isset($_GET['fetchall'])) $this->findALL();
			
			// Check if replace one
			if (isset($_GET['replaceone'])) $this->replaceOne();

		}
	}
	
	private function replaceOne () {

		$r = new stdClass();
		$r->success = true;	

		$in = $_POST['findinTxt'];
		if (isset($_GET['file'])) $file = $_GET['file'];

		if ($in == 'page')  {
		
			$id = $_GET['id'];
			$block = $_GET['block'];

			// finc item ... if not found ... error
			$stmt = $this->prepare("SELECT `id` FROM `pages` WHERE `$block` LIKE CONCAT ('%', ?, '%') AND `id` = ?" );
			$stmt->execute(array($_POST['find'], $id));
			if (!$stmt->rowCount()) {
				$r->success = false;
				$r->msg = "Find text not found!";
				die(json_encode($r));			
			}
	
			// Create a revision			
			if (!$this->query("INSERT INTO `git_pages` ( 
				  `page_id`, `pagename`, `title`, `keywords`, `description`, `maincontent`,
				  `useheader` , `headercontent` , `usefooter` , `footercontent` , `useside` ,
				  `sidecontent` , `published` , `parentid` , `url` , `revmsg`,
				  `sidercontent` , `usesider` ,`head` , `notes`, `layout` , `nositemap` , `createdby` )
				SELECT 
				  `id` AS page_id, `pagename`, `title`, `keywords`, `description`, `maincontent`,
				  `useheader` , `headercontent` , `usefooter` , `footercontent` ,
				  `useside` , `sidecontent` , `published` , `parentid` , `url` , 'Find and Replace',
				  `sidercontent` , `usesider` ,`head` , `notes`, `layout` , `nositemap` , 
				  '".$this->usr['id']."' as `createdby`  FROM `pages` WHERE `id` = '$id'")) {

				$r->success = false;
				$r->msg = "Failed to create revision";
				die(json_encode($r));
			}

			// do replace
			$stmt = $this->prepare("UPDATE `pages` SET `$block` = REPLACE (`$block`, ?, ?) WHERE id = ?" );
			if (!$stmt->execute(array($_POST['find'], $_POST['replace'], $id))) $r->success = false;

		} else if ($in == 'php')  {
			if ($file != 'layout.php') $file = "layout.$file";
			$this->replaceInFiles( "../$file", $file, $r );
		} else if ($in == 'js')  {
			if ($file != 'main.js') $file = "site-assets/js/$file";
			$this->replaceInFiles( "../$file", "../$file", $r );
		} else if ($in == 'css')  {
			if ($file != 'style.css') $file = "site-assets/css/$file";
			$this->replaceInFiles( "../$file", "../$file", $r );
		} else {
			$r->success = false;
			$r->msg = "File Type not found!";
		}

		die(json_encode($r));	
	}
	
	private function replaceInFiles( $file, $fullpath, &$r ) {

			// check if item is present in it
			$content = file_get_contents($file); 
			if (strpos($content, $_POST['find']) === false) {
				$r->success = false;
				$r->msg = "Find text not found!";
				die(json_encode($r));			
			}

			// Create a revision
			$data = array (	'content' => $content, 
							'fullpath' =>  $fullpath,
							'revmsg' => 'Find and Replace',
							'createdby' => $this->usr['id']);
			if ( !$this->add('git_files', $data) ) {
				$r->success = false;
				$r->msg = "Failed to create Revision!";
				die(json_encode($r));
			}

			// do replace action
			$content = str_replace($_POST['find'], $_POST['replace'], $content);
			if (file_put_contents($file, $content ) === false) {
				$r->success = false;
				$r->msg = "File save failed!";
			}
	}
	
	private function findALL () {
		$r = new stdClass();
		$r->success = true;
		$in = $_POST['findinTxt'];
		if ($in == 'page') $r->results = $this->findPages();
		if ($in == 'php' ) $r->results = $this->findFiles ('layout.php', '../layout.'         , '*.php'); 
		if ($in == 'css' ) $r->results = $this->findFiles ('style.css' , '../site-assets/css/', '*.css');
		if ($in == 'js'  ) $r->results = $this->findFiles ('main.js'   , '../site-assets/js/' , '*.js'); 
		die(json_encode($r));
	}

	private function findFiles ($mainFile, $path, $type) {	
		$results = array();
		$content = file_get_contents("../$mainFile"); 
		if (strpos($content, $_POST['find']) !== false)
			array_push($results, array('name' => $mainFile, 'inroot' => 1));
		$pathLen = strlen($path);
		foreach (glob($path.$type) as $entry) {
			$content = file_get_contents($entry);
			if (strpos($content, $_POST['find']) !== false)
				array_push($results, array('name' => substr($entry, $pathLen , strlen($entry)-$pathLen), 'inroot' => 0 ));
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