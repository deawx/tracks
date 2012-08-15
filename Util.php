<?php
class Util {
	static function eol() {
		switch (php_sapi_name()) {
			case "cli": 
				$eol = "\n";
				break;
			default:
				$eol = "<br>";
				break;
		}
		return $eol;
	}
}
?>