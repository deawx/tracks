<?php
require_once("Util.php");
require_once("db.php");


define ("FEET_PER_METER", 3.2808399);
define ("PIC_FEED", "https://picasaweb.google.com/data/feed/base/user/104513162711957846602/albumid/5722183446880050193?alt=rss&kind=photo&hl=en_US");
define ("SECRET", "p1ss0ff");
define ("INI_FILE", "/var/www/tracks.ini");

if (!file_exists(INI_FILE)) {
	echo "INI file (" . INI_FILE . ") does not exist!";
	exit;
}

$iniOptions = parse_ini_file(INI_FILE, true);
if ($iniOptions === false) {
	echo "Failed to parse INI file (" . INI_FILE . ")!";
	exit;
}

if (!isset($iniOptions["mysql"])) {
	echo "INI file (" . INI_FILE . ") missing section '[mysql]'.";
	exit;
}

foreach ($iniOptions["mysql"] as $option => $value) {
	switch ($option) {
		case "server":
			define("MYSQL_SERVER", $value);
			break;
		case "database":
			define("MYSQL_DBASE", $value);
			break;
		case "user":
			define("MYSQL_USER", $value);
			break;
		case "password":
			define("MYSQL_PASSWORD", $value);
			break;
		default:
			echo "Unknown key/value ($option/$value) pair specified in ini file (" . INI_FILE .").";
			exit;
	}
}

foreach ($iniOptions["gdal"] as $option => $value) {
	switch ($option) {
		case "ogr2ogr":
			define ("OGR2OGR" , $value);
			break;
		default:
			echo "Unknown key/value ($option/$value) pair specified in ini file (" . INI_FILE .").";
			exit;
	}
}

if (!defined("MYSQL_SERVER")) {
	echo "mysql server is not defined in the ini file (" . INI_FILE . ").";
	exit;
}

if (!defined("MYSQL_DBASE")) {
	echo "mysql database is not defined in the ini file (" . INI_FILE . ").";
	exit;
}

if (!defined("MYSQL_USER")) {
	echo "mysql user is not defined in the ini file (" . INI_FILE . ").";
	exit;
}

if (!defined("MYSQL_PASSWORD")) {
	echo "mysql password is not defined in the ini file (" . INI_FILE . ").";
	exit;
}

if (!defined("OGR2OGR")) {
	echo "ogr2ogr path is not defined in the ini file (" . INI_FILE . ").";
	exit;
}

$analytics = "<script type=\"text/javascript\" src=\"/analytics.js\"></script>";
$google_api_id = "AIzaSyDOW5DPTHvc5b8aO0zttUeo3IwVqOKVE0g";
if (defined("CREATE_DB")) {
	$db = new db(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, null);
} else {
	$db = new db(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DBASE);
}
$db->debug(false);

function array_dump($array) {
	echo "<pre>" . print_r($array, true) . "</pre>";
}
?>
