<?php
class db {
	private $_db;
	private $debug = false;
	private $eol;

	public function __construct($server, $user, $password, $dbase = null, $debug = false) {
		switch (php_sapi_name()) {
			case "cli": 
				$this->eol = "\n";
				break;
			default:
				$this->eol = "<br>";
				break;
		}
		if ($debug) {
			$this->debug(true);
		}
		if (is_null($dbase) || $dbase === false) {
			$dbase = "";
		}
		$this->_db = new mysqli($server, $user, $password, $dbase);
		if ($this->_db->connect_errno) {
			throw new Exception ("Failed to connect to database: " . $this->_db->connect_error);
		}
		$this->_debug("Connected to dbase server", true);
		return true;
	}

	public function debug($mode = null) {
		if ($mode === true) {
			$this->debug = true;
		}

		if ($mode === false) {
			$this->debug = false;
		}

		return $this->debug;
	}
	
	private function _debug($str, $eol = false) {
		if ($this->debug === true) {
			echo $str;
			if ($eol) {
				echo $this->eol;
			}
		}
	}

	public function query($rawQuerys) {
		if (!is_array($rawQuerys)) {
			$querys[] = $rawQuerys;
		} else {
			$querys = $rawQuerys;
		}
		$queryStr = "";
		foreach ($querys as $count => $query) {
			$queryStr .= "$query;";
		}
		//$queryStr = $this->_db->real_escape_string($queryStr);
		$this->_debug("QUERY: $queryStr");
		
		if (!$this->_db->multi_query($queryStr)) {
			$this->_debug(" FAIL", true);
			throw new Exception("FAILED: " . $this->_db->error);
		}
		$this->_debug(" SUCCESS", true);
		
		do {
			$res[] = $this->_db->use_result()->fetch_assoc();
		} while ($this->_db->next_result());
		return array("rowCount" => count($res), "rows" => $res);
	}

	public function setDB($dbase) {
		return $this->_db->select_db($dbase);
	}
}
?>
