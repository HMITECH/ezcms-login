<?php
/*
 * Code written by mo.ahmed@hmi-tech.net
 *
 * HMI Technologies Mumbai - DEC 2017
 *
 */
class db extends PDO {

	public function __construct() {

		// read config file 
		$config = @include("config.php");
		
		if (!$config) {
			die('FATAL : Config and installer missing.');
		}

		try {

			parent::__construct( 'mysql:host='.
				$config['dbHost'].';dbname='.
				$config['dbName'],
				$config['dbUser'],
				$config['dbPass'] );

			$this->exec("SET names utf8");

			/* SET TIME ZONE IF Defined*/
			if (isset($config['dbTime'])) $this->exec("SET time_zone='".$config['dbTime']."'");

		} catch (PDOException $e) {

			/** MySQL Connection error message */
			header('HTTP/1.0 500 Internal Server Error');
			die("<h1>Connection to Database failed.</h1><p>Check config.php file</p>");

		}
	}

}?>