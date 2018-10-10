<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * HMI Technologies Mumbai - SEP 2018
 *
 */
class db extends PDO {

	public $useRedis = false;
	public $redis;

	public function __construct() {
		// read config file 
		$config = @include("config.php");
		if (!$config) {
			if (!file_exists('install.php')) die('FATAL : Config and installer missing.');
			header("Location: install.php", true, 307); // Make Temp redirect
			exit; 
		}
		try {
			parent::__construct( 'mysql:host='.$config['dbHost'].';dbname='.
				$config['dbName'], $config['dbUser'], $config['dbPass'] );
			$this->exec("SET names utf8");
			/* SET TIME ZONE IF Defined*/
			if (isset($config['dbTime'])) $this->exec("SET time_zone='".$config['dbTime']."'");
		} catch (PDOException $e) {
			/** MySQL Connection error message */
			header('HTTP/1.0 500 Internal Server Error');
			die("<h1>Connection to Database failed.</h1><p>Check config.php file</p>");
		}
		if (isset($config['useRedis'])) 
			if ($config['useRedis']) try {
			    $this->redis = new Redis();
			    $this->redis->connect('localhost', 6379);
			    $this->useRedis = $config['dbName'];
			} catch (Exception $ex) {}
	}

	// get the site data
	public function getSiteData() {
		if ($this->useRedis) {
			$redKey = $this->useRedis."-site";
			if (!$this->redis->exists($redKey))
				$this->redis->setex($redKey, (int) 3600*12*30, json_encode($this->query('SELECT * FROM `site` ORDER BY `id` DESC LIMIT 1')->fetch(PDO::FETCH_ASSOC)));
			return json_decode($this->redis->get($redKey), true);
		} else return $this->query('SELECT * FROM `site` ORDER BY `id` DESC LIMIT 1')->fetch(PDO::FETCH_ASSOC);
	}

	// get the page data
	public function getPageData($uri) {
		if ($this->useRedis) {
			$redKey = $this->useRedis."-page-".$uri;
			if (!$this->redis->exists($redKey)) {
				$page = $this->getPageDatabase($uri);
				if ( ($page['id'] == 2) || (!$page["published"]) ) return $page;
				else $this->redis->setex($redKey, (int) 3600*12*10, json_encode($page));
			}
			return json_decode($this->redis->get($redKey), true);
		} else return $this->getPageDatabase($uri);
	}

	// get a page from the database
	private function getPageDatabase($uri) {
		$stmt = $this->prepare('SELECT * FROM `pages` WHERE `url` = ? ORDER BY `id` DESC LIMIT 1');
		$stmt->execute( array($uri) );
		// Check if page is found in database.
		if ($stmt->rowCount()) {
			$page = $stmt->fetch(PDO::FETCH_ASSOC);
			if (!$page["published"]) {
				 // Check if Admin is logged in - unpublished pages are visible to ADMIN.
				if (session_status() !== PHP_SESSION_ACTIVE) session_start();
				if (!isset($_SESSION['LOGGEDIN'])) $_SESSION['LOGGEDIN'] = false;
				if (!$_SESSION['LOGGEDIN']) $page = $this->get404Page($uri);

			}
		} else $page = $this->get404Page($uri);
		// build canonical URL
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? "https://" : "http://";
		$page['canonical'] = $protocol.$_SERVER['HTTP_HOST'].$page["url"];
		return $page;
	}

	// server 404 page
	private function get404Page($uri) {
		// Set 404 header when severing page not found
		Header("HTTP/1.0 404 Not Found");
		// check in redirects table
		$stmt = $this->prepare('SELECT `id`, `desurl` FROM `redirects` WHERE `srcurl` = ? AND `enabled`=1 ORDER BY `id` DESC LIMIT 1');
		$stmt->execute( array($uri) );
		// Check if page is found in database.
		if ($stmt->rowCount()) {
			// Redirect is found in Database
			$redirect = $stmt->fetch(PDO::FETCH_ASSOC);
			$this->query('UPDATE `redirects` SET `actioncount` = (`actioncount`+1) WHERE id='.$redirect['id']);
			// do the redirecting
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ".$redirect['desurl']);
			exit;
		} else {
			// add to log404
			$add404 = $this->prepare("INSERT INTO `log404` (`url`,`refer`,`ip`,`useragent`) VALUES (?,?,?,?)");
			$refer = '';
			if (isset($_SERVER['HTTP_REFERER']))  $refer = $_SERVER['HTTP_REFERER'];
			$add404->execute( array($uri, $refer, $_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT']));
		}
		if ($this->useRedis) {
			$redKey = $this->useRedis."-404page";
			if (!$this->redis->exists($redKey)) 
				$this->redis->setex($redKey, (int) 3600*12*30, json_encode($this->query('SELECT * FROM `pages` WHERE `id` = 2')->fetch(PDO::FETCH_ASSOC)));
			return json_decode($this->redis->get($redKey), true);
		} else {
			return $this->query('SELECT * FROM `pages` WHERE `id` = 2')->fetch(PDO::FETCH_ASSOC);
		}
	}

}?>