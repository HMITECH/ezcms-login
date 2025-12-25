<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Macro Execution Class 
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

class ezMacro extends ezCMS {

	public $macrolist = '';

	private $logFile = '';

	// Consturct the class
	public function __construct () {
		// call parent constuctor
		parent::__construct();
		// Handle post request
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if (isset($_GET['fetchall'])) $this->findPages();
			if (isset($_GET['execute'])) $this->runMacro();
			die('invalid request');
		}
		//Build the MacroList
		$this->buildMacroList();
	}

	private function runMacro () {

		$this->fetchPOSTData(['id','macro','side1','side2'],$data);
		$data['id'] = intval($data['id']);
		$data['side1'] = intval($data['side1']);
		$data['side2'] = intval($data['side2']);
		$macro = '../macros/'.$data['macro'];

		// macro file must be present
		if (!file_exists($macro)) die('Macro file not found! ');

		$page = $this->query("SELECT `id`, `maincontent`, `url`,
			`sidecontent`, `sidercontent` FROM `pages` 
			WHERE `id`= ".$data['id'])->fetch(PDO::FETCH_ASSOC);
		// page must be present
		if (!$page['id']) die('page not found with id='.$data['id']);

		include ( "include/simple-html-dom.php" );

		$this->mlog = [];
		if ($page['maincontent']) 
			$page['maincontent'] = $this->doMacro($page['maincontent'], $macro);
		else 
			$this->log('Content is empty, skipping','important');
		
		if ($data['side1']) {
			if ($page['sidecontent'])
				$page['sidecontent'] = $this->doMacro($page['sidecontent'], $macro);
			else 
				$this->log('Aside 1 is empty, skipping','important');
		}
		if ($data['side2']) {
			if ($page['sidercontent'])
				$page['sidercontent'] = $this->doMacro($page['sidercontent'], $macro);
			else
				$this->log('Aside 2 is empty, skipping','important');
		}

		// Create a revision			
		if (!$this->pageRevision($page['id'], "Macro Action: ".$data['macro'])) 
			die(json_encode(['success'=>false, 'msg'=>'Failed to create revision']));

		// save the page
		unset($page['id']);
		if (!$this->edit('pages', $data['id'], $page))
			die(json_encode(['success'=>false, 'msg'=>'Failed to update page']));

		// busrt redis cache
		if ($this->useRedis) {
			$redKey = $this->useRedis."-page-".$page['url'];
			if ($data['id'] == 2) $redKey = $this->useRedis."-404page";
			$this->redis->del($redKey);
		}

		// save log to file
		if ($this->logFile) $this->saveLog($data, $page);
		die(json_encode(['success'=>true, 'log'=>$this->mlog]));
	}

	private function saveLog($data, $page) {

	    if (!$this->logFile || empty($this->mlog)) return;

	    $logDir = "../site-assets/logs/macro/";
	    $file   = $logDir . basename($this->logFile);

	    if (!is_dir($logDir)) {
	        mkdir($logDir, 0755, true);
	    }

	    // Prepare values
	    $pageId   = $data['id'];
	    $pageUrl  = $page['url'] ?? '';
	    $macro    = basename($data['macro']);
	    $user     = $this->usr['username'] ?? 'system';
	    $time     = date('Y-m-d H:i:s');

	    // Combine all messages into one field (newline separated)
	    $messages = [];
	    foreach ($this->mlog as $entry) {
	        $messages[] = $entry['msg'];
	    }
	    $msg = implode("\n", $messages);

	    // CSV header (only if file does not exist)
	    if (!file_exists($file)) {
	        $header = [
	            'Page ID',
	            'Page URL',
	            'MSG',
	            'Macro',
	            'User',
	            'Timestamp'
	        ];
	        file_put_contents(
	            $file,
	            '"' . implode('","', $header) . '"' . "\n",
	            FILE_APPEND
	        );
	    }

	    // Data row
	    $row = [
	        $pageId,
	        $pageUrl,
	        $msg,
	        $macro,
	        $user,
	        $time
	    ];

	    file_put_contents(
	        $file,
	        '"' . implode('","', array_map('addslashes', $row)) . '"' . "\n",
	        FILE_APPEND
	    );
	}

	private function doMacro($content, $macro) {
		$html = str_get_html($content, true, false, DEFAULT_TARGET_CHARSET, false);
		try {
			include ( "../macros/$macro" );
			return @$html->innertext;
		} catch(ParseError $e) {
			die(json_encode(['success'=>false, 'msg'=>$e->getMessage().' line no: '.$e->getLine()]));
		}
	}

	private function log($msg, $label) {
		array_push($this->mlog,['msg'=>$msg,'label'=>$label]);
	}

	private function buildMacroList() {
		foreach(glob("../macros/*.php") as $file) {
			$file = basename($file);
			if ($file != 'macro.php')
				$this->macrolist .= '<li><a href="#"><i class="icon-play"></i> '.
					$file.'</a></li>';
		}
	}
	
	private function findPages () {
		$r = new stdClass();
		$r->success = true;
		$by = $_POST['findby'];
		$sURL = $_POST['findurl'];
		$sql = "SELECT `id` , `pagename`, `url`, `published` FROM `pages` WHERE ";
		if (!isset($_POST['incunpub'])) $sql .= '`published`=1 AND ';
		if ($by == 'children') {
			$stmt = $this->prepare( "SELECT `id` FROM `pages` WHERE `url` = ?" );
			$stmt->execute([$sURL]);
			$parent = $stmt->fetch(PDO::FETCH_ASSOC);
			if (!$parent['id']) 
				die(json_encode(['success'=>false, 
					'msg'=>'Page with this URL was not found']));
				// die('Page with this URL was not found!!');
			$r->results = [];
			$this->findChildPages($parent['id'], $r->results, "$sql `parentid`= ");
			die(json_encode($r));
		}
		$sql .= "`url` ";
		if ($by == 'exact') $sql .= "= ?";
		elseif ($by == 'begins') $sql .= "LIKE CONCAT (?, '%')";
		elseif ($by == 'ends') $sql .= "LIKE CONCAT ('%', ?)";
		elseif ($by == 'contains') $sql .= "LIKE CONCAT ('%', ?, '%')";
		else die('findby error');
		$stmt = $this->prepare( $sql );
		$stmt->execute([$sURL]);
		$r->results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		die(json_encode($r));
	}
	
	private function findChildPages ($id, &$results, $sql) {
		$children = $this->query("SELECT `id` 
			FROM `pages` WHERE `parentid`= $id")->fetchAll(PDO::FETCH_ASSOC);
		if (count($children)) {
			$results = array_merge($results,
				$this->query($sql.$id)->fetchAll(PDO::FETCH_ASSOC));
			foreach ($children as $child)
				$this->findChildPages($child['id'], $results, $sql);
		}
	}

}
?>