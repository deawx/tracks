<?php
class db {
	private $_db;
	private $debug = false;
	private $eol;

	public function __construct($server, $user, $password, $dbase = null, $debug = false) {
		$this->eol = Util::eol();
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
		
		$res = null;		
		do {
			if ($result = $this->_db->store_result()) {
				while ($row = $result->fetch_assoc()) {
					$res[] = $row;
				}
				$result->free();
			} 
		} while ($this->_db->more_results() && $this->_db->next_result());
		if (is_array($res)) {
			return array("rowCount" => count($res), "rows" => $res);
		}
		return array("rowCount" => 0, "rows" => null);
	}

	public function setDB($dbase) {
		return $this->_db->select_db($dbase);
	}
	
	public function escapeString($str) {
		return $this->_db->real_escape_string($str);
	}
	
	public function __destruct() {
		$this->_db->close();
	}
}
?>
