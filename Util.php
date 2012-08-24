<?php
class Util {
		const feetPerMeter = 3.2808399;

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

	static function meters2feet($meters) {
		return $meters * self::feetPerMeter;
	}

	static function seconds2HumanTime($secs) {
		return array ("hours" => intval($secs / 3660), "minutes" => intval(($secs / 60) % 60), "seconds" => intval($secs % 60));
	}
}
?>
